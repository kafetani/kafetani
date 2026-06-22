<?php include '../includes/header.php'; ?>
<div class="admin-layout" style="display:grid;grid-template-columns:240px 1fr;min-height:100vh;">
    <?php include '../includes/admin_sidebar.php'; ?>

    <main style="padding:3rem;background:var(--cream);">
        <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <h1 style="font-family:var(--ff-display);font-size:2.2rem;color:var(--brown);">Manajemen Petani</h1>
            <a href="?action=add" class="add-btn"
               style="text-decoration:none;padding:.8rem 1.5rem;width:auto;background:var(--green);color:white;border-radius:2px;font-size:0.9rem;">
               + Tambah Petani
            </a>
        </header>

        <?php if ($success): ?>
            <div style="background:#edf7ee;color:#2d5016;padding:1rem;margin-bottom:1.5rem;border:1px solid #d4e8d5;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div style="background:#fcebea;color:#c0392b;padding:1rem;margin-bottom:1.5rem;border:1px solid #f5d1cf;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <table style="width:100%;background:#fff;border-collapse:collapse;border:1px solid var(--border);">
            <thead style="background:var(--cream2);text-align:left;">
                <tr>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Avatar</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Nama</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Lokasi</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Kontak</th>
                    <th style="padding:1rem;font-size:.85rem;border-bottom:1px solid var(--border);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($farmers)): ?>
                    <tr>
                        <td colspan="5" style="padding:2rem;text-align:center;color:var(--text-light);">
                            Belum ada data petani.
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($farmers as $f): ?>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:1rem;">
                        <div style="width:40px;height:40px;border-radius:50%;background:var(--cream2);display:flex;align-items:center;justify-content:center;overflow:hidden;">
                            <?php if ($f['avatar']): ?>
                                <img src="../assets/img/farmers/<?= htmlspecialchars($f['avatar']) ?>"
                                     style="width:100%;height:100%;object-fit:cover;">
                            <?php else: ?>👨‍🌾<?php endif; ?>
                        </div>
                    </td>
                    <td style="padding:1rem;font-weight:500;"><?= htmlspecialchars($f['name']) ?></td>
                    <td style="padding:1rem;font-size:.85rem;"><?= htmlspecialchars($f['location']) ?></td>
                    <td style="padding:1rem;font-size:.85rem;"><?= htmlspecialchars($f['contact']) ?></td>
                    <td style="padding:1rem;">
                        <a href="?action=edit&id=<?= $f['id'] ?>"
                           style="color:var(--green);font-size:.8rem;text-decoration:none;margin-right:.8rem;">Edit</a>
                        <a href="?action=delete&id=<?= $f['id'] ?>"
                           style="color:#c0392b;font-size:.8rem;text-decoration:none;"
                           onclick="return confirm('Hapus data petani ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
<?php include '../includes/admin_footer.php'; ?>
