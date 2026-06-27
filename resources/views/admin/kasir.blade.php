@extends('layouts.admin')
@section('title', 'Kasir POS')

@push('styles')
<style>
.kasir-layout{display:grid;grid-template-columns:1fr 360px;gap:1.5rem;height:calc(100vh - 120px)}
/* Menu panel */
.menu-panel{display:flex;flex-direction:column;overflow:hidden}
.menu-cats{display:flex;gap:0;border-bottom:1px solid var(--border);margin-bottom:1rem;overflow-x:auto;flex-shrink:0}
.menu-cat-btn{padding:.6rem 1rem;font-size:.8rem;background:none;border:none;border-bottom:2px solid transparent;cursor:pointer;color:var(--text-mid);white-space:nowrap;font-family:var(--ff-body);transition:all .2s}
.menu-cat-btn.active,.menu-cat-btn:hover{color:var(--green);border-bottom-color:var(--green)}
.menu-search{position:relative;margin-bottom:1rem;flex-shrink:0}
.menu-search input{width:100%;padding:.6rem .8rem .6rem 2.2rem;border:1px solid var(--border);font-family:var(--ff-body);font-size:.88rem;background:var(--cream);outline:none;color:var(--text)}
.menu-search input:focus{border-color:var(--green)}
.menu-search-icon{position:absolute;left:.7rem;top:50%;transform:translateY(-50%);color:var(--text-light);font-size:.9rem}
.menu-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:.8rem;overflow-y:auto;padding-right:.3rem}
.menu-item{background:#fff;border:1px solid var(--border);cursor:pointer;transition:all .18s;position:relative;overflow:hidden}
.menu-item:hover{border-color:var(--green);transform:translateY(-1px);box-shadow:0 4px 12px rgba(45,80,22,.1)}
.menu-item-img{height:90px;background:var(--cream2);overflow:hidden}
.menu-item-img img{width:100%;height:100%;object-fit:cover}
.menu-item-body{padding:.6rem .7rem}
.menu-item-name{font-size:.82rem;font-weight:500;color:var(--brown);margin-bottom:.15rem;line-height:1.3}
.menu-item-price{font-size:.78rem;color:var(--green);font-weight:500}
.menu-item-stok{font-size:.68rem;color:var(--text-light)}
.menu-item.no-stok{opacity:.4;pointer-events:none}
.add-ripple{position:absolute;inset:0;background:rgba(45,80,22,.12);animation:ripple .25s ease;pointer-events:none}
@keyframes ripple{from{opacity:1}to{opacity:0}}
/* Order panel */
.order-panel{background:#fff;border:1px solid var(--border);display:flex;flex-direction:column;height:100%}
.order-top{padding:1.1rem 1.2rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center}
.order-top h3{font-family:var(--ff-display);font-size:1.3rem;font-weight:300;color:var(--brown);margin:0}
.order-items{flex:1;overflow-y:auto;padding:.8rem 1.2rem}
.order-empty{text-align:center;padding:2.5rem 1rem;color:var(--text-light)}
.order-empty-icon{font-size:2.5rem;margin-bottom:.5rem}
.order-item{display:flex;align-items:center;gap:.8rem;padding:.65rem 0;border-bottom:.5px solid var(--border)}
.order-item-name{flex:1;font-size:.85rem;font-weight:500;color:var(--brown)}
.order-item-price{font-size:.78rem;color:var(--text-mid)}
.order-qty{display:flex;align-items:center;gap:.35rem}
.qty-btn{width:22px;height:22px;border:1px solid var(--border);background:var(--cream);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.85rem;color:var(--text-mid);transition:all .15s;font-family:var(--ff-body)}
.qty-btn:hover{background:var(--green);color:#fff;border-color:var(--green)}
.qty-num{font-size:.85rem;font-weight:500;min-width:20px;text-align:center}
.remove-item{background:none;border:none;color:var(--text-light);cursor:pointer;font-size:.8rem;margin-left:.3rem;transition:color .2s}
.remove-item:hover{color:#c0392b}
/* Order bottom */
.order-bottom{padding:1rem 1.2rem;border-top:1px solid var(--border)}
.order-meta{margin-bottom:.8rem}
.order-meta label{display:block;font-size:.78rem;color:var(--text-mid);margin-bottom:.3rem}
.order-meta select,.order-meta input{
  width:100%;padding:.5rem .7rem;border:1px solid var(--border);
  font-family:var(--ff-body);font-size:.85rem;background:var(--cream);
  outline:none;color:var(--text);margin-bottom:.5rem;
}
.order-meta select:focus,.order-meta input:focus{border-color:var(--green)}
.order-subtotal{display:flex;justify-content:space-between;font-size:.83rem;color:var(--text-mid);margin-bottom:.4rem}
.order-total{display:flex;justify-content:space-between;font-size:1rem;font-weight:500;color:var(--brown);margin:.8rem 0 1rem}
.place-order-btn{width:100%;background:var(--green);color:#fff;border:none;padding:.8rem;font-family:var(--ff-body);font-size:.9rem;font-weight:500;cursor:pointer;transition:background .2s;letter-spacing:.03em}
.place-order-btn:hover{background:var(--green2)}
.place-order-btn:disabled{background:var(--text-light);cursor:not-allowed}
.clear-btn{width:100%;background:none;border:1px solid var(--border);padding:.5rem;font-family:var(--ff-body);font-size:.8rem;cursor:pointer;color:var(--text-mid);margin-top:.5rem;transition:all .2s}
.clear-btn:hover{border-color:#c0392b;color:#c0392b}
/* Receipt modal */
.modal-bg{display:none;position:fixed;inset:0;background:rgba(42,31,18,.55);z-index:200;align-items:center;justify-content:center}
.modal-bg.open{display:flex}
.receipt-box{background:#fff;padding:2rem;width:100%;max-width:380px;font-family:var(--ff-body)}
.receipt-header{text-align:center;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px dashed var(--border)}
.receipt-header h3{font-family:var(--ff-display);font-size:1.5rem;font-weight:300;color:var(--brown);margin-bottom:.2rem}
.receipt-header p{font-size:.78rem;color:var(--text-light)}
.receipt-item-row{display:flex;justify-content:space-between;font-size:.82rem;padding:.3rem 0;color:var(--text)}
.receipt-divider{border:none;border-top:1px dashed var(--border);margin:.8rem 0}
.receipt-total-row{display:flex;justify-content:space-between;font-size:.95rem;font-weight:500;color:var(--brown);padding:.4rem 0}
.receipt-thank{text-align:center;margin-top:1.2rem;font-size:.8rem;color:var(--text-light)}
.receipt-actions{display:flex;gap:.5rem;margin-top:1.5rem}
.receipt-actions button{flex:1;padding:.7rem;font-family:var(--ff-body);font-size:.85rem;cursor:pointer;border:none}
.btn-print{background:var(--green);color:#fff}
.btn-print:hover{background:var(--green2)}
.btn-close-receipt{background:var(--cream);border:1px solid var(--border)!important;color:var(--text-mid)}
</style>
@endpush

@section('content')

{{-- Flash receipt dari redirect --}}
@if(session('success_order'))
  @php $so = session('success_order'); @endphp
  <div class="modal-bg open" id="receipt-modal">
    <div class="receipt-box">
      <div class="receipt-header">
        <div style="font-size:1.8rem;margin-bottom:.3rem;">🧾</div>
        <h3>Pesanan #{{ $so['id'] }}</h3>
        <p>{{ $so['customer_name'] }} &mdash;
          {{ $so['order_type'] === 'dine-in' ? 'Makan di Tempat' : 'Take Away' }}
        </p>
      </div>
      @foreach($so['items'] as $item)
        <div class="receipt-item-row">
          <span>{{ $item['product']->nama_produk }} ×{{ $item['qty'] }}</span>
          <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
        </div>
      @endforeach
      <hr class="receipt-divider">
      <div class="receipt-total-row">
        <span>Total</span>
        <span>Rp {{ number_format($so['total'], 0, ',', '.') }}</span>
      </div>
      <div class="receipt-thank">Terima kasih! Pesanan sedang diproses. ☕</div>
      <div class="receipt-actions">
        <button class="btn-print" onclick="window.print()">🖨 Cetak</button>
        <button class="btn-close-receipt" onclick="closeReceipt()">Tutup</button>
      </div>
    </div>
  </div>
@endif

<div class="page-header" style="margin-bottom:1rem;">
  <h1>Kasir POS</h1>
  <span style="font-size:.82rem;color:var(--text-mid)">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
</div>

<div class="kasir-layout">

  {{-- LEFT: Menu --}}
  <div class="menu-panel">
    {{-- Search --}}
    <div class="menu-search">
      <span class="menu-search-icon">🔍</span>
      <input type="text" id="menu-search" placeholder="Cari menu..." oninput="filterMenu()">
    </div>
    {{-- Kategori tabs --}}
    <div class="menu-cats">
      <button class="menu-cat-btn active" data-cat="Semua" onclick="setCat(this)">Semua</button>
      @foreach($categories as $cat)
        <button class="menu-cat-btn" data-cat="{{ $cat }}" onclick="setCat(this)">{{ $cat }}</button>
      @endforeach
    </div>
    {{-- Grid produk --}}
    <div class="menu-grid" id="menu-grid">
      @foreach($products as $prod)
        <div class="menu-item {{ $prod->stok < 1 ? 'no-stok' : '' }}"
             data-cat="{{ $prod->category->name ?? '' }}"
             data-name="{{ strtolower($prod->nama_produk) }}"
             onclick="addItem({{ $prod->id_product }}, '{{ addslashes($prod->nama_produk) }}', {{ $prod->harga }}, '{{ $prod->gambar }}')">
          <div class="menu-item-img">
            @if($prod->gambar)
              <img src="{{ asset('products/' . $prod->gambar) }}" alt="{{ $prod->nama_produk }}" loading="lazy">
            @else
              <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:2rem;">☕</div>
            @endif
          </div>
          <div class="menu-item-body">
            <div class="menu-item-name">{{ $prod->nama_produk }}</div>
            <div class="menu-item-price">Rp {{ number_format($prod->harga, 0, ',', '.') }}</div>
            <div class="menu-item-stok">Stok: {{ $prod->stok }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- RIGHT: Order panel --}}
  <div class="order-panel">
    <div class="order-top">
      <h3>Pesanan</h3>
      <span id="item-count" style="font-size:.8rem;color:var(--text-light)">0 item</span>
    </div>

    <div class="order-items" id="order-items">
      <div class="order-empty" id="order-empty">
        <div class="order-empty-icon">🍽</div>
        <p>Pilih menu dari daftar</p>
      </div>
    </div>

    <div class="order-bottom">
      <div class="order-meta">
        <label>Tipe Pesanan</label>
        <select id="order-type" name="order_type">
          <option value="dine-in">🪑 Makan di Tempat</option>
          <option value="pickup">🥡 Take Away</option>
        </select>
        <label>Nama Pelanggan (opsional)</label>
        <input type="text" id="customer-name" placeholder="cth. Meja 3 / Budi">
      </div>
      <div class="order-subtotal"><span>Subtotal</span><span id="subtotal-val">Rp 0</span></div>
      <div class="order-total"><span>Total</span><span id="total-val">Rp 0</span></div>
      <form method="POST" action="{{ route('admin.kasir.order') }}" id="kasir-form">
        @csrf
        <input type="hidden" name="items" id="f-items">
        <input type="hidden" name="order_type" id="f-order-type">
        <input type="hidden" name="customer_name" id="f-customer-name">
        <button type="submit" class="place-order-btn" id="place-btn" disabled>
          Buat Pesanan →
        </button>
      </form>
      <button class="clear-btn" onclick="clearOrder()">🗑 Kosongkan</button>
    </div>
  </div>
</div>

{{-- Receipt modal placeholder (diisi via session flash di atas) --}}
<div class="modal-bg" id="receipt-modal" style="display:none;"></div>
@endsection

@push('scripts')
<script>
let cart = [];
let activeCat = 'Semua';

function addItem(id, name, price, img) {
  const existing = cart.find(i => i.id === id);
  if (existing) { existing.qty++; }
  else { cart.push({ id, name, price, img, qty: 1 }); }
  renderOrder();
  // ripple
  event.currentTarget.insertAdjacentHTML('beforeend', '<div class="add-ripple"></div>');
  setTimeout(() => { event.currentTarget.querySelector('.add-ripple')?.remove(); }, 280);
}

function changeQty(id, delta) {
  const item = cart.find(i => i.id === id);
  if (!item) return;
  item.qty += delta;
  if (item.qty <= 0) cart = cart.filter(i => i.id !== id);
  renderOrder();
}

function removeItem(id) {
  cart = cart.filter(i => i.id !== id);
  renderOrder();
}

function clearOrder() {
  if (cart.length && !confirm('Kosongkan pesanan?')) return;
  cart = [];
  renderOrder();
}

function renderOrder() {
  const container = document.getElementById('order-items');
  const emptyEl   = document.getElementById('order-empty');
  const countEl   = document.getElementById('item-count');
  const subtotalEl = document.getElementById('subtotal-val');
  const totalEl   = document.getElementById('total-val');
  const placeBtn  = document.getElementById('place-btn');

  // Hapus item lama
  container.querySelectorAll('.order-item').forEach(el => el.remove());

  let subtotal = 0;
  cart.forEach(item => {
    subtotal += item.price * item.qty;
    const div = document.createElement('div');
    div.className = 'order-item';
    div.innerHTML = `
      <div class="order-item-name">
        ${item.name}
        <div class="order-item-price">Rp ${fmt(item.price)}</div>
      </div>
      <div class="order-qty">
        <button class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
        <span class="qty-num">${item.qty}</span>
        <button class="qty-btn" onclick="changeQty(${item.id}, +1)">+</button>
        <button class="remove-item" onclick="removeItem(${item.id})" title="Hapus">✕</button>
      </div>`;
    container.appendChild(div);
  });

  const total = subtotal;
  const totalItems = cart.reduce((s, i) => s + i.qty, 0);

  emptyEl.style.display  = cart.length ? 'none' : 'block';
  countEl.textContent    = totalItems + ' item';
  subtotalEl.textContent = 'Rp ' + fmt(subtotal);
  totalEl.textContent    = 'Rp ' + fmt(total);
  placeBtn.disabled      = cart.length === 0;
}

function fmt(n) {
  return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Form submit
document.getElementById('kasir-form').addEventListener('submit', function(e) {
  document.getElementById('f-items').value         = JSON.stringify(cart.map(i => ({ id: i.id, qty: i.qty })));
  document.getElementById('f-order-type').value    = document.getElementById('order-type').value;
  document.getElementById('f-customer-name').value = document.getElementById('customer-name').value;
});

// Filter kategori
function setCat(btn) {
  activeCat = btn.dataset.cat;
  document.querySelectorAll('.menu-cat-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  filterMenu();
}

function filterMenu() {
  const q = document.getElementById('menu-search').value.toLowerCase();
  document.querySelectorAll('.menu-item:not(.no-stok)').forEach(el => {
    const matchCat  = activeCat === 'Semua' || el.dataset.cat === activeCat;
    const matchName = !q || el.dataset.name.includes(q);
    el.style.display = matchCat && matchName ? '' : 'none';
  });
}

function closeReceipt() {
  document.getElementById('receipt-modal').classList.remove('open');
  document.getElementById('receipt-modal').style.display = 'none';
}

renderOrder();
</script>
@endpush
