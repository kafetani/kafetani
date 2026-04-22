<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
checkAdmin();

$success = '';
$error = '';

// Handle Checkout Process
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'checkout') {
    $cart = json_decode($_POST['cart_data'], true);
    if (!empty($cart)) {
        try {
            $pdo->beginTransaction();
            $total = 0;
            foreach ($cart as $item) {
                // Ensure price logic matches DB for security. In POS, admin can be trusted but better to recalculate.
                // However, Kasir interface often sends exact current prices.
                $total += $item['price'] * $item['qty'];
            }
            
            // Assume guest user or admin's own ID
            // Here we use admin's ID
            $user_id = $_SESSION['user_id'] ?? 1;

            $stmt = $pdo->prepare("INSERT INTO orders (user_id, status, total, type) VALUES (?, 'completed', ?, 'kasir')");
            $stmt->execute([$user_id, $total]);
            $order_id = $pdo->lastInsertId();

            $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmtStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

            foreach ($cart as $item) {
                $stmtItem->execute([$order_id, $item['id'], $item['qty'], $item['price']]);
                // Decrease stock
                $stmtStock->execute([$item['qty'], $item['id']]);
            }

            $pdo->commit();
            $success = "Pesanan kasir berhasil diproses! Order ID: #" . $order_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        $error = "Keranjang kosong.";
    }
}

// Fetch all products for Kasir grid
$products = $pdo->query("SELECT id, name, price, stock, unit, image_url FROM products WHERE stock > 0 ORDER BY name ASC")->fetchAll();

include '../includes/header.php';
?>
<div class="admin-layout" style="display:grid;grid-template-columns:240px 1fr;min-height:100vh;">
    <aside style="background:var(--brown);color:#fff;padding:2rem;">
        <h2 style="font-family:var(--ff-display);font-size:1.5rem;margin-bottom:1rem;">Kafetani Admin</h2>
        <?php include '../includes/admin_sidebar.php'; ?>
    </aside>

    <main style="display:grid;grid-template-columns:1fr 350px;background:var(--cream);">
        <!-- Left: Product Grid -->
        <div style="padding:2rem;">
            <header style="margin-bottom:2rem;">
                <h1 style="font-family:var(--ff-display);font-size:2.2rem;color:var(--brown);">Kasir (POS)</h1>
                <p style="color:var(--text-mid);font-size:.9rem;">Pilih produk untuk ditambahkan ke keranjang.</p>
            </header>

            <?php if ($success): ?>
                <div style="background:#edf7ee;color:#2d5016;padding:1rem;margin-bottom:1.5rem;border:1px solid #d4e8d5;"><?= $success ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div style="background:#fdf2f2;color:#b22b2b;padding:1rem;margin-bottom:1.5rem;border:1px solid #f8d7da;"><?= $error ?></div>
            <?php endif; ?>

            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));gap:1rem;">
                <?php foreach ($products as $p): ?>
                <div style="background:#fff;border:1px solid var(--border);border-radius:4px;padding:1rem;cursor:pointer;display:flex;flex-direction:column;justify-content:space-between;" onclick="addToCart(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['name'])) ?>', <?= $p['price'] ?>, <?= $p['stock'] ?>)">
                    <div>
                        <div style="font-size:.8rem;color:var(--orange);font-weight:600;margin-bottom:.3rem;">Sisa: <?= $p['stock'] ?></div>
                        <h3 style="font-family:var(--ff-body);font-size:.9rem;color:var(--brown);line-height:1.3;margin-bottom:.5rem;"><?= $p['name'] ?></h3>
                    </div>
                    <div style="font-family:var(--ff-display);font-size:1.1rem;color:var(--green);font-weight:500;">Rp <?= number_format($p['price'], 0, ',', '.') ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Right: Cart & Checkout -->
        <div style="background:#fff;border-left:1px solid var(--border);display:flex;flex-direction:column;height:100vh;position:sticky;top:0;">
            <div style="padding:1.5rem;border-bottom:1px solid var(--border);background:var(--cream2);">
                <h2 style="font-family:var(--ff-display);font-size:1.3rem;color:var(--brown);">Keranjang Kasir</h2>
            </div>
            
            <div id="cart-items" style="flex:1;overflow-y:auto;padding:1.5rem;display:flex;flex-direction:column;gap:1rem;">
                <div style="text-align:center;color:var(--text-light);font-size:.9rem;margin-top:2rem;">Belum ada produk di keranjang.</div>
            </div>

            <div style="padding:1.5rem;border-top:1px solid var(--border);background:var(--cream2);">
                <div style="display:flex;justify-content:space-between;margin-bottom:1rem;font-size:1.2rem;">
                    <span style="color:var(--text-mid);">Total</span>
                    <strong style="font-family:var(--ff-display);color:var(--green);" id="cart-total">Rp 0</strong>
                </div>
                
                <form method="POST" id="checkout-form">
                    <input type="hidden" name="action" value="checkout">
                    <input type="hidden" name="cart_data" id="cart-data-input" value="[]">
                    <button type="button" onclick="processCheckout()" class="btn-primary" style="width:100%;padding:1rem;font-size:1rem;text-align:center;border:none;background:var(--green);color:#fff;border-radius:4px;cursor:pointer;">Proses Pesanan</button>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
let cart = [];

function updateCartUI() {
    const container = document.getElementById('cart-items');
    const totalEl = document.getElementById('cart-total');
    let total = 0;
    
    container.innerHTML = '';
    
    if (cart.length === 0) {
        container.innerHTML = '<div style="text-align:center;color:var(--text-light);font-size:.9rem;margin-top:2rem;">Belum ada produk di keranjang.</div>';
        totalEl.innerText = 'Rp 0';
        return;
    }

    cart.forEach((item, index) => {
        total += item.price * item.qty;
        
        let div = document.createElement('div');
        div.style.cssText = 'display:flex;justify-content:space-between;align-items:center;background:#fff;border:1px solid var(--border);padding:.8rem;border-radius:4px;';
        
        div.innerHTML = `
            <div style="flex:1;">
                <div style="font-size:.85rem;color:var(--brown);font-weight:600;margin-bottom:.2rem;">${item.name}</div>
                <div style="font-size:.8rem;color:var(--green);">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</div>
            </div>
            <div style="display:flex;align-items:center;gap:.5rem;">
                <button type="button" onclick="changeQty(${index}, -1)" style="width:24px;height:24px;border:1px solid var(--border);background:var(--cream2);border-radius:2px;cursor:pointer;display:flex;align-items:center;justify-content:center;">-</button>
                <span style="font-size:.9rem;width:20px;text-align:center;">${item.qty}</span>
                <button type="button" onclick="changeQty(${index}, 1)" style="width:24px;height:24px;border:1px solid var(--border);background:var(--cream2);border-radius:2px;cursor:pointer;display:flex;align-items:center;justify-content:center;">+</button>
            </div>
        `;
        container.appendChild(div);
    });

    totalEl.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}

function addToCart(id, name, price, maxStock) {
    let existing = cart.find(i => i.id === id);
    if (existing) {
        if (existing.qty < maxStock) {
            existing.qty++;
        } else {
            alert('Stok tidak mencukupi untuk ' + name);
        }
    } else {
        if (maxStock > 0) {
            cart.push({ id, name, price, qty: 1, maxStock });
        } else {
            alert('Stok habis untuk ' + name);
        }
    }
    updateCartUI();
}

function changeQty(index, dir) {
    let item = cart[index];
    let newQty = item.qty + dir;
    
    if (newQty <= 0) {
        cart.splice(index, 1);
    } else if (newQty > item.maxStock) {
        alert('Stok maksimum tercapai');
    } else {
        item.qty = newQty;
    }
    updateCartUI();
}

function processCheckout() {
    if (cart.length === 0) {
        alert('Keranjang belanja kosong!');
        return;
    }
    if (confirm('Proses pesanan ini?')) {
        document.getElementById('cart-data-input').value = JSON.stringify(cart);
        document.getElementById('checkout-form').submit();
    }
}
</script>

<?php include '../includes/footer.php'; ?>
