<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once '../config/koneksi.php';

// Auth: izinkan admin dan kasir
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'kasir'])) {
    header('Location: ../auth/login.php');
    exit;
}

$success_order = null;
$error         = '';

// ── Handle submit pesanan offline ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'place_order') {
    $items         = json_decode($_POST['items'] ?? '[]', true);
    $order_type    = in_array($_POST['order_type'] ?? '', ['dine-in','pickup']) ? $_POST['order_type'] : 'dine-in';
    $customer_name = trim($_POST['customer_name'] ?? '') ?: 'Tamu';

    if (empty($items) || !$conn) {
        $error = 'Pesanan kosong atau koneksi database gagal.';
    } else {
        mysqli_begin_transaction($conn);
        try {
            // Ambil harga asli dari DB (anti-manipulasi harga dari klien)
            $ids        = array_map('intval', array_column($items, 'id'));
            $ph         = implode(',', $ids);
            $res        = mysqli_query($conn, "SELECT id_product, nama_produk, harga, stok FROM product WHERE id_product IN ($ph) AND type='cafe'");
            $db_prods   = [];
            while ($row = mysqli_fetch_assoc($res)) {
                $db_prods[$row['id_product']] = $row;
            }

            $total      = 0;
            $line_items = [];
            foreach ($items as $item) {
                $pid = (int)$item['id'];
                $qty = max(1, (int)$item['qty']);
                if (!isset($db_prods[$pid])) continue;
                $harga      = (int)$db_prods[$pid]['harga'];
                $subtotal   = $harga * $qty;
                $total     += $subtotal;
                $line_items[] = [
                    'id'       => $pid,
                    'qty'      => $qty,
                    'harga'    => $harga,
                    'subtotal' => $subtotal,
                    'nama'     => $db_prods[$pid]['nama_produk'],
                ];
            }

            if (empty($line_items)) throw new Exception('Tidak ada produk kafe valid dalam pesanan.');

            // Insert order
            $uid  = (int)$_SESSION['user_id'];
            $stmt = mysqli_prepare($conn,
                "INSERT INTO orders (user_id, total, type, source, customer_name, status) VALUES (?, ?, 'cafe', 'offline', ?, 'processing')"
            );
            mysqli_stmt_bind_param($stmt, 'iis', $uid, $total, $customer_name);
            mysqli_stmt_execute($stmt);
            $order_id = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);

            if (!$order_id) throw new Exception('Gagal membuat pesanan.');

            // Insert order items & kurangi stok
            $stmt2 = mysqli_prepare($conn,
                "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)"
            );
            foreach ($line_items as $li) {
                mysqli_stmt_bind_param($stmt2, 'iiiii',
                    $order_id, $li['id'], $li['qty'], $li['harga'], $li['subtotal']
                );
                mysqli_stmt_execute($stmt2);
                mysqli_query($conn,
                    "UPDATE product SET stok = GREATEST(0, stok - {$li['qty']}) WHERE id_product = {$li['id']}"
                );
            }
            mysqli_stmt_close($stmt2);

            mysqli_commit($conn);
            $success_order = [
                'id'            => $order_id,
                'customer_name' => $customer_name,
                'order_type'    => $order_type,
                'items'         => $line_items,
                'total'         => $total,
            ];
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = 'Gagal menyimpan pesanan: ' . $e->getMessage();
        }
    }
}

// Ambil produk kafe dari DB
$products   = [];
$categories = [];
$res = mysqli_query($conn,
    "SELECT p.*, c.name AS cat_name
     FROM product p
     LEFT JOIN categories c ON p.category_id = c.id
     WHERE p.type = 'cafe' AND p.stok > 0
     ORDER BY p.category_id, p.nama_produk"
);
while ($row = mysqli_fetch_assoc($res)) {
    $products[] = $row;
    $cat = $row['cat_name'] ?: 'Lainnya';
    if (!in_array($cat, $categories)) $categories[] = $cat;
}

$kasir_nama = $_SESSION['nama'] ?? 'Kasir';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kafetani — Kasir POS</title>
<link rel="icon" type="image/svg+xml" href="/kafetani/assets/img/favicon.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --cream:#F7F3EC;--cream2:#EFE8D9;--brown:#3B2A1A;--brown2:#6B4C30;
  --green:#2D5016;--green2:#4A7C23;--amber:#C8883A;--amber-light:#F5ECD8;
  --text:#2A1F12;--text-mid:#7A6550;--text-light:#A9967E;--border:#D9CEBC;
  --ff-display:'Cormorant Garamond',serif;--ff-body:'DM Sans',sans-serif;
}
html,body{height:100%;font-family:var(--ff-body);background:var(--cream);color:var(--text);font-size:15px}

/* ── TOPBAR ── */
.topbar{height:52px;background:var(--brown);display:flex;align-items:center;justify-content:space-between;padding:0 1.5rem;position:fixed;top:0;left:0;right:0;z-index:50}
.topbar-brand{font-family:var(--ff-display);color:#fff;font-size:1.3rem;font-weight:300;display:flex;align-items:center;gap:.6rem}
.topbar-badge{background:var(--amber);color:#fff;font-size:.65rem;font-weight:500;padding:.15rem .55rem;letter-spacing:.06em}
.topbar-right{display:flex;align-items:center;gap:1rem;font-size:.8rem;color:rgba(255,255,255,.7)}
.topbar-right a{color:rgba(255,255,255,.7);text-decoration:none;transition:color .2s}
.topbar-right a:hover{color:#fff}
.topbar-sep{opacity:.3}

/* ── LAYOUT ── */
.pos-wrap{display:grid;grid-template-columns:1fr 360px;height:calc(100vh - 52px);margin-top:52px;overflow:hidden}

/* ── LEFT: Menu ── */
.menu-panel{background:var(--cream);overflow-y:auto;display:flex;flex-direction:column}
.cat-tabs{display:flex;gap:.4rem;padding:1rem 1.2rem .6rem;border-bottom:1px solid var(--border);flex-wrap:wrap;position:sticky;top:0;background:var(--cream);z-index:10}
.cat-tab{background:none;border:1px solid var(--border);padding:.35rem .9rem;font-family:var(--ff-body);font-size:.78rem;cursor:pointer;color:var(--text-mid);transition:all .18s;letter-spacing:.03em}
.cat-tab:hover{border-color:var(--green);color:var(--green)}
.cat-tab.active{background:var(--green);border-color:var(--green);color:#fff}
.products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(148px,1fr));gap:.9rem;padding:1rem 1.2rem}
.prod-card{background:#fff;border:1px solid var(--border);cursor:pointer;transition:all .18s;position:relative;overflow:hidden;user-select:none}
.prod-card:hover{border-color:var(--green2);box-shadow:0 2px 12px rgba(45,80,22,.12)}
.prod-card:active{transform:scale(.97)}
.prod-img{width:100%;aspect-ratio:4/3;object-fit:cover;display:block}
.prod-img-placeholder{width:100%;aspect-ratio:4/3;background:var(--cream2);display:flex;align-items:center;justify-content:center;font-size:2rem;color:var(--text-light)}
.prod-body{padding:.7rem}
.prod-name{font-size:.82rem;font-weight:500;line-height:1.35;margin-bottom:.25rem;color:var(--text)}
.prod-price{font-size:.9rem;font-weight:500;color:var(--green)}
.prod-add-hint{position:absolute;bottom:.5rem;right:.5rem;background:var(--green);color:#fff;width:26px;height:26px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;border-radius:50%;opacity:0;transition:opacity .18s}
.prod-card:hover .prod-add-hint{opacity:1}

/* ── RIGHT: Cart ── */
.cart-panel{background:#fff;border-left:1px solid var(--border);display:flex;flex-direction:column;overflow:hidden}
.cart-header{padding:1rem 1.2rem .8rem;border-bottom:1px solid var(--border);flex-shrink:0}
.cart-header h2{font-family:var(--ff-display);font-size:1.4rem;font-weight:300;color:var(--brown)}
.cart-items{flex:1;overflow-y:auto;padding:.5rem 1.2rem}
.cart-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:var(--text-light);gap:.6rem;font-size:.9rem}
.cart-item{display:flex;align-items:center;gap:.6rem;padding:.65rem 0;border-bottom:1px solid var(--border)}
.cart-item-name{flex:1;font-size:.83rem;line-height:1.3;color:var(--text)}
.cart-item-price{font-size:.8rem;color:var(--text-mid);white-space:nowrap}
.qty-controls{display:flex;align-items:center;gap:.3rem;flex-shrink:0}
.qty-btn{width:26px;height:26px;border:1px solid var(--border);background:var(--cream);font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text-mid);transition:all .15s;line-height:1}
.qty-btn:hover{background:var(--green);color:#fff;border-color:var(--green)}
.qty-num{min-width:20px;text-align:center;font-size:.85rem;font-weight:500}
.cart-remove{background:none;border:none;color:var(--text-light);cursor:pointer;font-size:.9rem;padding:.1rem;transition:color .15s;flex-shrink:0}
.cart-remove:hover{color:#c0392b}

/* ── Cart footer ── */
.cart-footer{border-top:1px solid var(--border);padding:1rem 1.2rem;flex-shrink:0;display:flex;flex-direction:column;gap:.75rem}
.meta-row{display:flex;gap:.5rem;align-items:center;margin-bottom:.3rem}
.meta-label{font-size:.78rem;color:var(--text-mid);min-width:52px}
.meta-input{flex:1;border:1px solid var(--border);padding:.38rem .6rem;font-family:var(--ff-body);font-size:.82rem;color:var(--text);background:var(--cream);outline:none;transition:border-color .15s}
.meta-input:focus{border-color:var(--green)}
.otype-btns{display:flex;gap:.4rem;flex:1}
.otype-btn{flex:1;border:1px solid var(--border);background:var(--cream);padding:.38rem;font-family:var(--ff-body);font-size:.78rem;cursor:pointer;color:var(--text-mid);transition:all .15s;text-align:center}
.otype-btn.active{background:var(--green);border-color:var(--green);color:#fff}
.total-row{display:flex;justify-content:space-between;align-items:center}
.total-label{font-size:.85rem;color:var(--text-mid)}
.total-amount{font-size:1.4rem;font-weight:500;color:var(--brown);font-family:var(--ff-display)}
.btn-order{width:100%;background:var(--green);color:#fff;border:none;padding:.85rem;font-family:var(--ff-body);font-size:.95rem;font-weight:500;cursor:pointer;letter-spacing:.04em;transition:background .2s}
.btn-order:hover:not(:disabled){background:var(--green2)}
.btn-order:disabled{background:var(--text-light);cursor:not-allowed}
.btn-clear{width:100%;background:none;border:1px solid var(--border);color:var(--text-mid);padding:.5rem;font-family:var(--ff-body);font-size:.8rem;cursor:pointer;transition:all .2s}
.btn-clear:hover{border-color:#c0392b;color:#c0392b}

/* ── Error banner ── */
.error-banner{background:#fdf0ee;border:1px solid #e8bdb6;color:#7b2d1e;padding:.75rem 1.2rem;font-size:.85rem;margin:.6rem 1.2rem;display:none}
.error-banner.show{display:block}

/* ── Success Modal ── */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(42,31,18,.55);z-index:200;align-items:center;justify-content:center}
.modal-overlay.show{display:flex}
.modal-box{background:#fff;padding:2rem;max-width:420px;width:92%;border-top:3px solid var(--green)}
.modal-icon{font-size:2.5rem;text-align:center;margin-bottom:.8rem}
.modal-title{font-family:var(--ff-display);font-size:1.8rem;font-weight:300;color:var(--green);text-align:center;margin-bottom:.3rem}
.modal-sub{font-size:.85rem;color:var(--text-mid);text-align:center;margin-bottom:1.2rem}
.receipt{background:var(--cream);padding:1rem;font-size:.8rem;line-height:1.8}
.receipt-row{display:flex;justify-content:space-between}
.receipt-row.total{border-top:1px dashed var(--border);margin-top:.3rem;padding-top:.4rem;font-weight:500;font-size:.88rem}
.modal-actions{display:flex;gap:.6rem;margin-top:1.2rem}
.btn-print{flex:1;background:none;border:1px solid var(--green);color:var(--green);padding:.7rem;font-family:var(--ff-body);font-size:.85rem;cursor:pointer;transition:all .2s}
.btn-print:hover{background:var(--green);color:#fff}
.btn-next{flex:1;background:var(--green);color:#fff;border:none;padding:.7rem;font-family:var(--ff-body);font-size:.85rem;cursor:pointer;transition:background .2s}
.btn-next:hover{background:var(--green2)}

/* ── Hidden form ── */
#pos-form{display:none}

@media print{
  .topbar,.cat-tabs,.btn-clear,.btn-order,.modal-actions,.cart-panel .cart-footer{display:none!important}
  .pos-wrap{display:none!important}
  .modal-overlay{display:block!important;position:static;background:none}
  .modal-box{box-shadow:none;border:none;max-width:100%;padding:0}
}
</style>
</head>
<body>

<header class="topbar">
  <div class="topbar-brand">
    Kafetani <span class="topbar-badge">KASIR POS</span>
  </div>
  <div class="topbar-right">
    <span>&#128100; <?= htmlspecialchars($kasir_nama) ?></span>
    <span class="topbar-sep">|</span>
    <?php if ($_SESSION['role'] === 'admin'): ?>
      <a href="dashboard.php">&#8592; Admin</a>
      <span class="topbar-sep">|</span>
    <?php endif; ?>
    <a href="../auth/logout.php">Logout</a>
  </div>
</header>

<div class="pos-wrap">

  <!-- LEFT: Menu Grid -->
  <div class="menu-panel">
    <div class="cat-tabs">
      <button class="cat-tab active" onclick="filterCat(this,'')">Semua</button>
      <?php foreach ($categories as $cat): ?>
        <button class="cat-tab" onclick="filterCat(this,'<?= htmlspecialchars($cat, ENT_QUOTES) ?>')"><?= htmlspecialchars($cat) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="products-grid" id="products-grid">
      <?php if (empty($products)): ?>
        <p style="color:var(--text-light);padding:2rem;grid-column:1/-1;text-align:center;">Belum ada produk kafe tersedia.</p>
      <?php endif; ?>

      <?php foreach ($products as $p): ?>
      <div class="prod-card"
           data-id="<?= $p['id_product'] ?>"
           data-name="<?= htmlspecialchars($p['nama_produk'], ENT_QUOTES) ?>"
           data-price="<?= (int)$p['harga'] ?>"
           data-cat="<?= htmlspecialchars($p['cat_name'] ?? 'Lainnya', ENT_QUOTES) ?>"
           onclick="addToCart(this)">
        <?php if ($p['gambar']): ?>
          <img class="prod-img"
               src="/kafetani/assets/img/products/<?= htmlspecialchars($p['gambar']) ?>"
               alt="<?= htmlspecialchars($p['nama_produk']) ?>"
               loading="lazy"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
          <div class="prod-img-placeholder" style="display:none">&#9749;</div>
        <?php else: ?>
          <div class="prod-img-placeholder">&#9749;</div>
        <?php endif; ?>
        <div class="prod-body">
          <div class="prod-name"><?= htmlspecialchars($p['nama_produk']) ?></div>
          <div class="prod-price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
        </div>
        <div class="prod-add-hint">+</div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- RIGHT: Cart Panel -->
  <div class="cart-panel">
    <div class="cart-header">
      <h2>Pesanan Baru</h2>
    </div>

    <div class="cart-items" id="cart-items">
      <div class="cart-empty" id="cart-empty">
        <div style="font-size:2.2rem">&#128203;</div>
        <div>Pilih menu di sebelah kiri</div>
      </div>
    </div>

    <div class="error-banner" id="error-banner"></div>

    <div class="cart-footer">
      <div>
        <div class="meta-row">
          <span class="meta-label">Nama</span>
          <input type="text" class="meta-input" id="customer-name" placeholder="Nama tamu / meja" maxlength="60">
        </div>
        <div class="meta-row">
          <span class="meta-label">Tipe</span>
          <div class="otype-btns">
            <button class="otype-btn active" id="btn-dinein" onclick="setOrderType('dine-in')">Dine In</button>
            <button class="otype-btn" id="btn-pickup" onclick="setOrderType('pickup')">Takeaway</button>
          </div>
        </div>
      </div>

      <div class="total-row">
        <span class="total-label">Total</span>
        <span class="total-amount" id="total-display">Rp 0</span>
      </div>

      <button class="btn-order" id="btn-order" onclick="submitOrder()" disabled>Proses Pesanan &rarr;</button>
      <button class="btn-clear" onclick="clearCart()">Bersihkan Pesanan</button>
    </div>
  </div>
</div>

<!-- Hidden form POST -->
<form id="pos-form" method="POST" action="">
  <input type="hidden" name="action" value="place_order">
  <input type="hidden" name="items" id="form-items">
  <input type="hidden" name="order_type" id="form-order-type" value="dine-in">
  <input type="hidden" name="customer_name" id="form-customer-name">
</form>

<!-- Success Modal -->
<div class="modal-overlay <?= $success_order ? 'show' : '' ?>" id="success-modal">
  <div class="modal-box">
    <div class="modal-icon">&#9989;</div>
    <div class="modal-title">Pesanan Masuk!</div>
    <?php if ($success_order): ?>
      <div class="modal-sub">
        #<?= $success_order['id'] ?> &nbsp;&middot;&nbsp;
        <?= htmlspecialchars($success_order['customer_name']) ?> &nbsp;&middot;&nbsp;
        <?= $success_order['order_type'] === 'dine-in' ? 'Dine In' : 'Takeaway' ?>
      </div>
      <div class="receipt">
        <?php foreach ($success_order['items'] as $li): ?>
          <div class="receipt-row">
            <span><?= $li['qty'] ?>x <?= htmlspecialchars($li['nama']) ?></span>
            <span>Rp <?= number_format($li['subtotal'], 0, ',', '.') ?></span>
          </div>
        <?php endforeach; ?>
        <div class="receipt-row total">
          <span>TOTAL</span>
          <span>Rp <?= number_format($success_order['total'], 0, ',', '.') ?></span>
        </div>
      </div>
    <?php else: ?>
      <div class="modal-sub" id="modal-sub-js">—</div>
      <div class="receipt" id="modal-receipt-js"></div>
    <?php endif; ?>
    <div class="modal-actions">
      <button class="btn-print" onclick="window.print()">&#128424; Cetak Struk</button>
      <button class="btn-next" onclick="closeModal()">Pesanan Baru &rarr;</button>
    </div>
  </div>
</div>

<script>
const cart = {};
let orderType = 'dine-in';

function filterCat(btn, cat) {
  document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.prod-card').forEach(card => {
    card.style.display = (!cat || card.dataset.cat === cat) ? '' : 'none';
  });
}

function addToCart(card) {
  const id    = card.dataset.id;
  const name  = card.dataset.name;
  const price = parseInt(card.dataset.price);
  cart[id] = cart[id] ? { ...cart[id], qty: cart[id].qty + 1 } : { name, price, qty: 1 };
  renderCart();
}

function renderCart() {
  const container = document.getElementById('cart-items');
  const empty     = document.getElementById('cart-empty');
  const keys      = Object.keys(cart);
  let total = 0, html = '';

  container.querySelectorAll('.cart-item').forEach(n => n.remove());

  if (keys.length) {
    empty.style.display = 'none';
    keys.forEach(id => {
      const { name, price, qty } = cart[id];
      const sub = price * qty;
      total += sub;
      html += `<div class="cart-item">
        <div class="cart-item-name">${esc(name)}</div>
        <div class="qty-controls">
          <button class="qty-btn" onclick="changeQty('${id}',-1)">&#8722;</button>
          <span class="qty-num">${qty}</span>
          <button class="qty-btn" onclick="changeQty('${id}',1)">+</button>
        </div>
        <div class="cart-item-price">Rp ${fmt(sub)}</div>
        <button class="cart-remove" onclick="removeItem('${id}')" title="Hapus">&#10005;</button>
      </div>`;
    });
    container.insertAdjacentHTML('beforeend', html);
  } else {
    empty.style.display = '';
  }

  document.getElementById('total-display').textContent = 'Rp ' + fmt(total);
  document.getElementById('btn-order').disabled = keys.length === 0;
}

function changeQty(id, d) {
  if (!cart[id]) return;
  cart[id].qty = Math.max(1, cart[id].qty + d);
  renderCart();
}

function removeItem(id) { delete cart[id]; renderCart(); }
function clearCart() { Object.keys(cart).forEach(k => delete cart[k]); renderCart(); }

function setOrderType(type) {
  orderType = type;
  document.getElementById('btn-dinein').classList.toggle('active', type === 'dine-in');
  document.getElementById('btn-pickup').classList.toggle('active', type === 'pickup');
}

function submitOrder() {
  const keys = Object.keys(cart);
  if (!keys.length) return;
  document.getElementById('form-items').value        = JSON.stringify(keys.map(id => ({ id, qty: cart[id].qty })));
  document.getElementById('form-order-type').value   = orderType;
  document.getElementById('form-customer-name').value = document.getElementById('customer-name').value.trim() || 'Tamu';
  document.getElementById('pos-form').submit();
}

function closeModal() {
  document.getElementById('success-modal').classList.remove('show');
  clearCart();
  document.getElementById('customer-name').value = '';
}

function fmt(n) { return n.toLocaleString('id-ID'); }
function esc(s) { return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

<?php if ($error): ?>
const eb = document.getElementById('error-banner');
eb.textContent = <?= json_encode($error) ?>;
eb.classList.add('show');
<?php endif; ?>
</script>
</body>
</html>
