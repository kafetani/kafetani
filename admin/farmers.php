<?php
include '../config/koneksi.php';
$current_page = 'farmers';
require_once '../includes/auth_check.php';
checkAdmin();

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

// Handle Delete
if ($action == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM farmers WHERE id = ?");
    if ($stmt->execute([$_GET['id']])) {
        $success = "Data petani berhasil dihapus!";
    } else {
        $error = "Gagal menghapus data petani.";
    }
    $action = 'list';
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($action == 'add' || $action == 'edit')) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];
    $bio = $_POST['bio'];
    $id = $_POST['id'] ?? null;

    // Handle Avatar Upload
    $avatar_path = $_POST['existing_avatar'] ?? null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $target_dir = "../assets/img/farmers/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $file_ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_ext;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_dir . $new_filename)) {
            $avatar_path = $new_filename;
        }
    }

    if ($action == 'add') {
        $stmt = $pdo->prepare("INSERT INTO farmers (name, location, contact, bio, avatar) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $location, $contact, $bio, $avatar_path])) {
            $success = "Data petani berhasil ditambahkan!";
            $action = 'list';
        }
    } else {
        $stmt = $pdo->prepare("UPDATE farmers SET name=?, location=?, contact=?, bio=?, avatar=? WHERE id=?");
        if ($stmt->execute([$name, $location, $contact, $bio, $avatar_path, $id])) {
            $success = "Data petani berhasil diupdate!";
            $action = 'list';
        }
    }
}

$farmers = $pdo->query("SELECT * FROM farmers ORDER BY created_at DESC")->fetchAll();

include '../includes/header.php';
?>
<div class="admin-layout" style="display:grid;grid-template-columns:240px 1fr;min-height:100vh;">
    <?php include '../includes/admin_sidebar.php'; ?>

    <main style="padding:3rem;background:var(--cream);">
        <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <h1 style="font-family:var(--ff-display);font-size:2.2rem;color:var(--brown);">Manajemen Petani</h1>
            <?php if($action == 'list'): ?>
                <a href="?action=add" class="add-btn" style="text-decoration:none;padding:.8rem 1.5rem;width:auto;">+ Tambah Petani</a>
            <?php endif; ?>
        </header>

        <?php if($success): ?><div class="alert alert-success" style="background:#edf7ee;color:#2d5016;padding:1rem;margin-bottom:1.5rem;border:1px solid #d4e8d5;"><?= $success ?></div><?php endif; ?>
        <?php if($error): ?><div class="alert alert-error" style="background:#fcebea;color:#c0392b;padding:1rem;margin-bottom:1.5rem;border:1px solid #f5d1cf;"><?= $error ?></div><?php endif; ?>

        <?php if($action == 'list'): ?>
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
                    <?php if(empty($farmers)): ?>
                    <tr><td colspan="5" style="padding:2rem;text-align:center;color:var(--text-light);">Belum ada data petani.</td></tr>
                    <?php endif; ?>
                    <?php foreach($farmers as $f): ?>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:1rem;">
                            <div style="width:40px;height:40px;border-radius:50%;background:var(--cream2);display:flex;align-items:center;justify-content:center;overflow:hidden;">
                                <?php if($f['avatar']): ?>
                                    <img src="../assets/img/farmers/<?= $f['avatar'] ?>" style="width:100%;height:100%;object-fit:cover;">
                                <?php else: ?>
                                    👨‍🌾
                                <?php endif; ?>
                            </div>
                        </td>
                        <td style="padding:1rem;font-weight:500;"><?= $f['name'] ?></td>
                        <td style="padding:1rem;font-size:.85rem;"><?= $f['location'] ?></td>
                        <td style="padding:1rem;font-size:.85rem;"><?= $f['contact'] ?></td>
                        <td style="padding:1rem;">
                            <a href="?action=edit&id=<?= $f['id'] ?>" style="color:var(--green);font-size:.8rem;text-decoration:none;margin-right:.8rem;">Edit</a>
                            <a href="?action=delete&id=<?= $f['id'] ?>" style="color:#c0392b;font-size:.8rem;text-decoration:none;" onclick="return confirm('Hapus data petani ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <?php
            $f = ['name'=>'','location'=>'','contact'=>'','bio'=>'','avatar'=>''];
            if($action == 'edit' && isset($_GET['id'])) {
                $f_stmt = $pdo->prepare("SELECT * FROM farmers WHERE id = ?");
                $f_stmt->execute([$_GET['id']]);
                $f = $f_stmt->fetch();
            }
            ?>
            <form method="POST" enctype="multipart/form-data" style="background:#fff;padding:2rem;border:1px solid var(--border);max-width:800px;">
                <input type="hidden" name="id" value="<?= $_GET['id'] ?? '' ?>">
                <input type="hidden" name="existing_avatar" value="<?= $f['avatar'] ?>">
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
                    <div>
                        <div class="form-group"><label>Nama Lengkap Petani</label><input type="text" name="name" value="<?= $f['name'] ?>" required></div>
                        <div class="form-group"><label>Lokasi (Kota/Daerah)</label><input type="text" name="location" value="<?= $f['location'] ?>" required></div>
                        <div class="form-group"><label>Kontak (WhatsApp/Telp)</label><input type="text" name="contact" value="<?= $f['contact'] ?>" required></div>
                    </div>
                    <div>
                         <div class="form-group"><label>Bio Singkat</label><textarea name="bio" style="width:100%;height:100px;border:1px solid var(--border);padding:.7rem;font-family:var(--ff-body);"><?= $f['bio'] ?></textarea></div>
                         <div class="form-group"><label>Foto Avatar</label><input type="file" name="avatar" accept="image/*"></div>
                    </div>
                </div>
                <div style="margin-top:2rem;">
                    <button type="submit" class="auth-btn" style="width:auto;padding:1rem 3rem;">Simpan Data Petani</button>
                    <a href="farmers.php" style="margin-left:1.5rem;color:var(--text-mid);text-decoration:none;font-size:.9rem;">Batal</a>
                </div>
            </form>
        <?php endif; ?>
    </main>
</div>
<?php include '../includes/footer.php'; ?>
