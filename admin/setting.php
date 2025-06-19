<?php
// File: admin/setting.php
require 'session.php';
require '../includes/connect.php';
include '../common/header.php';
include '../common/navbar.php';

// Load settings
$settings = $conn->query("SELECT * FROM settings LIMIT 1")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $site_name = $_POST['site_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $meta_title = $_POST['meta_title'] ?? '';
  $meta_description = $_POST['meta_description'] ?? '';
  $meta_keywords = $_POST['meta_keywords'] ?? '';

  $target_dir = '../assets/img/';

  // Handle logo upload
  if (!empty($_FILES['logo']['name'])) {
    $file_name = time() . '_' . basename($_FILES['logo']['name']);
    $target_file = $target_dir . $file_name;
    move_uploaded_file($_FILES['logo']['tmp_name'], $target_file);
    $logo_path = 'assets/img/' . $file_name;
  } else {
    $logo_path = $settings['logo'] ?? '';
  }

  // Handle favicon upload with validation
  $allowed_types = ['image/png', 'image/x-icon', 'image/vnd.microsoft.icon'];
  $max_size = 102400; // 100KB

  if (!empty($_FILES['favicon']['name'])) {
    if (in_array($_FILES['favicon']['type'], $allowed_types) && $_FILES['favicon']['size'] <= $max_size) {
      $fav_name = time() . '_' . basename($_FILES['favicon']['name']);
      $fav_target = $target_dir . $fav_name;
      move_uploaded_file($_FILES['favicon']['tmp_name'], $fav_target);
      $favicon_path = 'assets/img/' . $fav_name;
    } else {
      echo '<div class="alert alert-danger">Favicon must be a PNG or ICO file and under 100KB.</div>';
      $favicon_path = $settings['favicon'] ?? '';
    }
  } else {
    $favicon_path = $settings['favicon'] ?? '';
  }

  $stmt = $conn->prepare("REPLACE INTO settings (id, site_name, email, phone, address, logo, favicon, meta_title, meta_description, meta_keywords) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $id = 1;
  $stmt->bind_param("isssssssss", $id, $site_name, $email, $phone, $address, $logo_path, $favicon_path, $meta_title, $meta_description, $meta_keywords);
  $stmt->execute();
  echo '<div class="alert alert-success">Settings updated!</div>';
  $settings = [
    'site_name' => $site_name,
    'email' => $email,
    'phone' => $phone,
    'address' => $address,
    'logo' => $logo_path,
    'favicon' => $favicon_path,
    'meta_title' => $meta_title,
    'meta_description' => $meta_description,
    'meta_keywords' => $meta_keywords
  ];
}
?>
<button class="sidebar-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">👤</button>
<div class="dashboard-container">
    <?php include '../common/sidebar-admin.php'; ?>

    <div class="dashboard-main">
    <h3>Website Settings</h3>
     <form method="post" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Website Name</label>
          <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" value="<?= htmlspecialchars($settings['email'] ?? '') ?>" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" value="<?= htmlspecialchars($settings['phone'] ?? '') ?>" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Address</label>
          <input type="text" name="address" value="<?= htmlspecialchars($settings['address'] ?? '') ?>" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Website Logo</label>
          <input type="file" name="logo" class="form-control">
          <?php if (!empty($settings['logo'])): ?>
            <img src="../<?= $settings['logo'] ?>" alt="Logo" class="img-fluid mt-2 border rounded p-1" style="max-height: 80px;">
          <?php endif; ?>
        </div>

        <div class="col-md-6">
          <label class="form-label">Favicon (32x32 recommended, PNG/ICO, max 100KB)</label>
          <input type="file" name="favicon" class="form-control">
          <?php if (!empty($settings['favicon'])): ?>
            <img src="../<?= $settings['favicon'] ?>" alt="Favicon" class="img-thumbnail mt-2" style="height: 32px; width: 32px;">
          <?php endif; ?>
        </div>

        <div class="col-md-6">
          <label class="form-label">Meta Title</label>
          <input type="text" name="meta_title" value="<?= htmlspecialchars($settings['meta_title'] ?? '') ?>" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Meta Description</label>
          <input type="text" name="meta_description" value="<?= htmlspecialchars($settings['meta_description'] ?? '') ?>" class="form-control">
        </div>

        <div class="col-md-12">
          <label class="form-label">Meta Keywords</label>
          <textarea name="meta_keywords" class="form-control" rows="2"><?= htmlspecialchars($settings['meta_keywords'] ?? '') ?></textarea>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
</div>

<?php include '../common/footer.php'; ?>
