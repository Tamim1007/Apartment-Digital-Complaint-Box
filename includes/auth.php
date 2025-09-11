<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/functions.php';

// Default admin code can be overridden via environment variable ADMIN_CODE
$ADMIN_CODE = getenv('ADMIN_CODE') ?: 'ADMIN123';

function current_user() {
    return $_SESSION['user'] ?? null;
}

function require_login($role = null) {
    if (!isset($_SESSION['user'])) {
        redirect('/apartment-digital-complaint-box/index.php');
    }
    if ($role && $_SESSION['user']['role'] !== $role) {
        redirect('/apartment-digital-complaint-box/dashboard.php');
    }
}

function login($user) {
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role']
    ];
}

function logout() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    redirect('/apartment-digital-complaint-box/index.php');
}
