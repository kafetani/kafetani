<?php include '../includes/header.php'; ?>
<style>
:root{--cream:#F7F3EC;--cream2:#EFE8D9;--brown:#3B2A1A;--green:#2D5016;--green2:#4A7C23;--amber:#C8883A;--text:#2A1F12;--text-mid:#7A6550;--text-light:#A9967E;--border:#D9CEBC;--ff:'DM Sans',sans-serif;--ff-d:'Cormorant Garamond',serif}
.admin-layout{display:grid;grid-template-columns:240px 1fr;min-height:100vh}
.main-content{padding:2rem;background:var(--cream);font-family:var(--ff)}
.page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem}
.page-header h1{font-family:var(--ff-d);font-size:2rem;font-weight:300;color:var(--brown);margin:0}
.btn-primary{background:var(--green);color:#fff;border:none;padding:.6rem 1.2rem;font-family:var(--ff);font-size:.85rem;cursor:pointer;transition:background .2s}
.btn-primary:hover{background:var(--green2)}
.btn-edit{background:none;border:1px solid var(--green);color:var(--green);padding:.3rem .75rem;font-family:var(--ff);font-size:.78rem;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-block}
.btn-edit:hover{background:var(--green);color:#fff}
.btn-danger{background:none;border:1px solid #c0392b;color:#c0392b;padding:.3rem .75rem;font-family:var(--ff);font-size:.78rem;cursor:pointer;transition:all .2s}
.btn-danger:hover{background:#c0392b;color:#fff}
.filter-bar{display:flex;gap:.5rem;margin-bottom:1.2rem;flex-wrap:wrap}
.filter-btn{background:none;border:1px solid var(--border);padding:.35rem .9rem;font-family:var(--ff);font-size:.8rem;cursor:pointer;color:var(--text-mid);transition:all .18s;text-decoration:none;display:inline-block}
.filter-btn:hover{border-color:var(--green);color:var(--green)}
.filter-btn.active{background:var(--green);border-color:var(--green);color:#fff}
.product-table{width:100%;border-collapse:collapse;background:#fff}
.product-table th{padding:.85rem 1rem;text-align:left;font-size:.8rem;font-weight:500;color:var(--text-mid);border-bottom:1px solid var(--border);white-space:nowrap}
.product-table td{padding:.8rem 1rem;border-bottom:.5px solid var(--border);font-size:.85rem;color:var(--text);vertical-align:middle}
.product-table tr:hover td{background:var(--cream)}
.prod-img{width:52px;height:38px;object-fit:cover;border-radius:2px;display:block}
.prod-img-ph{width:52px;height:38px;background:var(--cream2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:var(--text-light);border-radius:2px}
.type-cafe{font-size:.7rem;padding:.2rem .55rem;border-radius:20px;background:#E6F1FB;color:#185FA5}
.type-market{font-size:.7rem;padding:.2rem .55rem;border-radius:20px;background:#FAEEDA;color:#854F0B}
.stok-low{color:#c0392b;font-weight:500}
.actions{display:flex;gap:.4rem;align-items:center}
.alert{padding:.75rem 1rem;margin-bottom:1rem;font-size:.85rem;border-left:3px solid}
.alert-ok{background:#EAF3DE;border-color:var(--green);color:#27500A}
.alert-err{background:#FCEBEB;border-color:#c0392b;color:#7b2d1e}
.empty-row td{text-align:center;padding:3rem;color:var(--text-light)}
/* Modal */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(42,31,18,.5);z-index:100;padding:2rem 1rem;overflow-y:auto}
.modal-overlay.open{display:flex;align-items:flex-start;justify-content:center}
.modal-box{background:#fff;width:100%;max-width:520px;border-top:3px solid var(--green);padding:1.75rem;margin:auto}
.modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem}
.modal-title{font-family:var(--ff-d);font-size:1.5rem;font-weight:300;color:var(--brown);margin:0}
.modal-close{background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--text-mid);line-height:1;padding:0}
.modal-close:hover{color:var(--text)}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:.9rem}
.form-grid .full{grid-column:1/-1}
.form-group{display:flex;flex-direction:column;gap:.3rem}
.form-group label{font-size:.8rem;font-weight:500;color:var(--text-mid)}
.form-group input,.form-group select,.form-group textarea{border:1px solid var(--border);padding:.45rem .65rem;font-family:var(--ff);font-size:.85rem;color:var(--text);background:var(--cream);outline:none;transition:border-color .15s;width:100%;box-sizing:border-box}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--green)}
.form-group textarea{min-height:68px;resize:vertical}
.img-preview{margin-top:.4rem}
.img-preview img{width:72px;height:52px;object-fit:cover;border-radius:2px;border:1px solid var(--border)}
.form-actions{display:flex;gap:.6rem;justify-content:flex-end;margin-top:1.25rem;padding-top:1rem;border-top:.5px solid var(--border)}
.btn-cancel{background:none;border:1px solid var(--border);color:var(--text-mid);padding:.6rem 1.2rem;font-family:var(--ff);font-size:.85rem;cursor:pointer}
.btn-cancel:hover{border-color:var(--text-mid)}
</style>

<div class="admin-layout">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="main-content">

        <div class="page-header">
            <h1>Manajemen Produk</h1>
            <button class="btn-primary" onclick="openModal()">+ Produk Baru</button>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-ok"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-err"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Filter tipe -->
        <div class="filter-bar">
            <a class="filter-btn <?= $ft==='all'    ? 'active' : '' ?>" href="?type=all">Semua (<?= count($products) ?>)</a>
            <a class="filter-btn <?= $ft==='cafe'   ? 'active' : '' ?>" href="?type=cafe">Menu Kafe</a>
            <a class="filter-btn <?= $ft==='market' ? 'active' : '' ?>" href="?type=market">Marketplace Petani</a>
        </div>

        <!-- Tabel produk -->
        <table class="product-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr class="empty-row"><td colspan="7">Belum ada produk. Klik "+ Produk Baru" untuk menambahkan.</td></tr>
                <?php endif; ?>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <?php if ($p['gambar']): ?>
                            <img class="prod-img"
                                 src="../assets/img/products/<?= htmlspecialchars($p['gambar']) ?>"
                                 alt="<?= htmlspecialchars($p['nama_produk']) ?>"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="prod-img-ph" style="display:none">&#9749;</div>
                        <?php else: ?>
                            <div class="prod-img-ph">&#9749;</div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($p['cat_name'] ?? '—') ?></td>
                    <td><span class="<?= $p['type']==='cafe' ? 'type-cafe' : 'type-market' ?>"><?= $p['type']==='cafe' ? 'Kafe' : 'Market' ?></span></td>
                    <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                    <td class="<?= $p['stok'] <= 5 ? 'stok-low' : '' ?>"><?= (int)$p['stok'] ?></td>
                    <td>
                        <div class="actions">
                            <button class="btn-edit" onclick='openEditModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)'>Edit</button>
                            <a href="?hapus=<?= $p['id_product'] ?>&type=<?= $ft ?>"
                               onclick="return confirm('Hapus produk ini?')">
                                <button class="btn-danger">Hapus</button>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>

<!-- Modal Tambah / Edit Produk -->
<div class="modal-overlay" id="modal-overlay" onclick="if(event.target===this)closeModal()">
  <div class="modal-box">
    <div class="modal-header">
      <h2 class="modal-title" id="modal-title">Tambah Produk Baru</h2>
      <button class="modal-close" onclick="closeModal()" aria-label="Tutup">&times;</button>
    </div>
    <form method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" name="action"      value="save">
      <input type="hidden" name="id"          id="f-id"    value="">
      <input type="hidden" name="gambar_lama" id="f-glama" value="">

      <div class="form-grid">
        <div class="form-group full">
          <label>Nama Produk *</label>
          <input type="text" name="nama_produk" id="f-nama" placeholder="Kopi Susu Gula Aren" required maxlength="100">
        </div>

        <div class="form-group">
          <label>Kategori</label>
          <select name="category_id" id="f-cat">
            <option value="">— Pilih —</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label>Tipe</label>
          <select name="type" id="f-type" onchange="togglePetani(this.value)">
            <option value="cafe">Menu Kafe</option>
            <option value="market">Marketplace Petani</option>
          </select>
        </div>

        <div class="form-group">
          <label>Harga (Rp) *</label>
          <input type="number" name="harga" id="f-harga" placeholder="28000" min="0" required>
        </div>

        <div class="form-group">
          <label>Stok</label>
          <input type="number" name="stok" id="f-stok" placeholder="0" min="0" value="0">
        </div>

        <div class="form-group full" id="petani-row" style="display:none">
          <label>Nama Petani / Asal</label>
          <input type="text" name="petani" id="f-petani" placeholder="Pak Budi - Gayo, Aceh" maxlength="100">
        </div>

        <div class="form-group full">
          <label>Deskripsi</label>
          <textarea name="deskripsi" id="f-desk" placeholder="Deskripsi singkat..."></textarea>
        </div>

        <div class="form-group full">
          <label>Gambar Produk (JPG / PNG / WEBP)</label>
          <input type="file" name="gambar" id="f-file" accept="image/jpeg,image/png,image/webp">
          <div class="img-preview" id="img-preview"></div>
        </div>
      </div>

      <div class="form-actions">
        <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn-primary">Simpan Produk</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(){
  document.getElementById('modal-title').textContent='Tambah Produk Baru';
  ['f-id','f-glama','f-nama','f-harga','f-desk','f-petani'].forEach(id=>document.getElementById(id).value='');
  document.getElementById('f-stok').value='0';
  document.getElementById('f-cat').value='';
  document.getElementById('f-type').value='cafe';
  document.getElementById('img-preview').innerHTML='';
  togglePetani('cafe');
  document.getElementById('modal-overlay').classList.add('open');
}

function openEditModal(p){
  document.getElementById('modal-title').textContent='Edit Produk';
  document.getElementById('f-id').value      = p.id_product;
  document.getElementById('f-glama').value   = p.gambar||'';
  document.getElementById('f-nama').value    = p.nama_produk;
  document.getElementById('f-harga').value   = p.harga;
  document.getElementById('f-stok').value    = p.stok;
  document.getElementById('f-desk').value    = p.deskripsi||'';
  document.getElementById('f-petani').value  = p.petani||'';
  document.getElementById('f-cat').value     = p.category_id||'';
  document.getElementById('f-type').value    = p.type;
  document.getElementById('img-preview').innerHTML = p.gambar
    ? '<img src="../assets/img/products/'+p.gambar+'" alt="Preview">'
    : '';
  togglePetani(p.type);
  document.getElementById('modal-overlay').classList.add('open');
}

function closeModal(){ document.getElementById('modal-overlay').classList.remove('open'); }

function togglePetani(type){
  document.getElementById('petani-row').style.display = type==='market' ? '' : 'none';
}

document.getElementById('f-file').addEventListener('change',function(){
  const prev=document.getElementById('img-preview');
  if(this.files&&this.files[0]){
    prev.innerHTML='<img src="'+URL.createObjectURL(this.files[0])+'" alt="Preview">';
  }
});
</script>

<?php include '../includes/admin_footer.php'; ?>
