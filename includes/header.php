<?php
// includes/header.php
require_once __DIR__ . '/auth.php';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Apartment Digital Complaint Box</title>
<link rel="stylesheet" href="/apartment-digital-complaint-box/assets/css/style.css">
<script defer src="/apartment-digital-complaint-box/assets/js/app.js"></script>
</head>
<body>
<header class="site-header">
  <div class="container between">
    <h1><a href="/apartment-digital-complaint-box/dashboard.php">🏢 Complaint Box</a></h1>
    <nav>
      <?php if ($user): ?>
        <span class="muted">Signed in as <?= e($user['name']) ?> (<?= e($user['role']) ?>)</span>
        <?php if ($user['role'] === 'user'): ?>
          <a href="/apartment-digital-complaint-box/user/complaints.php">My Complaints</a>
          <a href="/apartment-digital-complaint-box/user/announcements.php">Announcements</a>
        <?php else: ?>
          <a href="/apartment-digital-complaint-box/admin/complaints.php">All Complaints</a>
          <a href="/apartment-digital-complaint-box/admin/announcements.php">Manage Announcements</a>
        <?php endif; ?>
        <a class="danger" href="/apartment-digital-complaint-box/logout.php">Logout</a>
      <?php else: ?>
        <a href="/apartment-digital-complaint-box/index.php">Login</a>
        <a href="/apartment-digital-complaint-box/register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
