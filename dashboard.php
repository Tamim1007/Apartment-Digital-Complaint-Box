<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();
$user = current_user();
if ($user['role'] === 'admin') {
    redirect('/apartment-digital-complaint-box/admin/dashboard.php');
} else {
    redirect('/apartment-digital-complaint-box/user/complaints.php');
}
