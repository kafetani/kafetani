<?php include '../includes/header.php'; ?>
<div class="admin-layout" style="display:grid;grid-template-columns:240px 1fr;min-height:100vh;">
    <?php include '../includes/admin_sidebar.php'; ?>

    <main style="padding:3rem;background:var(--cream);">
        <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <h1 style="font-family:var(--ff-display);font-size:2.2rem;color:var(--brown);">
                <?= $action === 'add' ? 'Tambah Petani Baru' : 'Edit Data Petani' ?>
            </h1>
        </header>

        <form method="POST"
              enctype="multipart/form-data"
              action="?action=<?= htmlspecialchars($action) ?><?= isset($f['id']) && $f['id'] ? '&id=' . (int)$f['id'] : '' ?>"
              style="background:#fff;padding:2rem;border:1px solid var(--border);max-width:800px;">

            <input type="hidden" name="id"              value="<?= htmlspecialchars($f['id']     ?? '') ?>">
            <input type="hidden" name="existing_avatar" value="<?= htmlspecialchars($f['avatar'] ?? '') ?>">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
                <div>
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label style="display:block;margin-bottom:0.5rem;font-size:0.9rem;">Nama Lengkap Petani</label>
                        <input type="text" name="name"
                               value="<?= htmlspecialchars($f['name'] ?? '') ?>"
                               required
                               style="width:100%;padding:0.7rem;border:1px solid var(--border);">
                    </div>
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label style="display:block;margin-bottom:0.5rem;font-size:0.9rem;">Lokasi (Kota/Daerah)</label>
                        <input type="text" name="location"
                               value="<?= htmlspecialchars($f['location'] ?? '') ?>"
                               required
                               style="width:100%;padding:0.7rem;border:1px solid var(--border);">
                    </div>
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label style="display:block;margin-bottom:0.5rem;font-size:0.9rem;">Kontak (WhatsApp/Telp)</label>
                        <input type="text" name="contact"
                               value="<?= htmlspecialchars($f['contact'] ?? '') ?>"
                               required
                               style="width:100%;padding:0.7rem;border:1px solid var(--border);">
                    </div>
                </div>
                <div>
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label style="display:block;margin-bottom:0.5rem;font-size:0.9rem;">Bio Singkat</label>
                        <textarea name="bio"
                                  style="width:100%;height:100px;border:1px solid var(--border);padding:.7rem;font-family:var(--ff-body);"><?= htmlspecialchars($f['bio'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label style="display:block;margin-bottom:0.5rem;font-size:0.9rem;">Foto Avatar</label>
                        <?php if (!empty($f['avatar'])): ?>
                            <img src="../assets/img/farmers/<?= htmlspecialchars($f['avatar']) ?>"
                                 style="width:50px;height:50px;object-fit:cover;border-radius:50%;margin-bottom:10px;display:block;">
                        <?php endif; ?>
                        <input type="file" name="avatar" accept="image/*"
                               style="width:100%;padding:0.5rem;border:1px solid var(--border);">
                    </div>
                </div>
            </div>

            <div style="margin-top:2rem;">
                <button type="submit" class="auth-btn"
                        style="width:auto;padding:1rem 3rem;background:var(--green);color:white;border:none;cursor:pointer;">
                    Simpan Data Petani
                </button>
                <a href="farmers.php" style="margin-left:1.5rem;color:var(--text-mid);text-decoration:none;font-size:.9rem;">Batal</a>
            </div>
        </form>
    </main>
</div>
<?php include '../includes/admin_footer.php'; ?>
