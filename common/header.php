<?php
// common/header.php

require_once '../includes/connect.php';
$site = $conn->query("SELECT * FROM settings LIMIT 1")->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($site['meta_title'] ?? $site['site_name'] ?? 'SkillzenNepal') ?></title>
  <?php if (!empty($site['favicon'])): ?>
    <link rel="icon" href="/<?= $site['favicon'] ?>" type="image/png">
  <?php endif; ?>

  <meta name="description" content="<?= htmlspecialchars($site['meta_description'] ?? '') ?>">
  <meta name="keywords" content="<?= htmlspecialchars($site['meta_keywords'] ?? '') ?>">

  <!-- Social sharing Open Graph (optional) -->
  <meta property="og:title" content="<?= htmlspecialchars($site['meta_title'] ?? '') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($site['meta_description'] ?? '') ?>">
  <meta property="og:image" content="/<?= $site['logo'] ?? '' ?>">
  <meta property="og:type" content="website">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>