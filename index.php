<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        login($user);
        redirect('/apartment-digital-complaint-box/dashboard.php');
    } else {
        $error = 'Invalid credentials.';
    }
}
include __DIR__ . '/includes/header.php';
?>
<div class="grid grid-2">
  <section class="card">
    <h2>Welcome back</h2>
    <p class="muted">Log in to manage complaints and announcements.</p>
    <?php if ($error): ?><p style="color:#ff8aa5"><?= e($error) ?></p><?php endif; ?>
    <form method="post">
      <label>Email</label>
      <input class="input" type="email" name="email" required>
      <label>Password</label>
      <input class="input" type="password" name="password" required>
      <div style="margin-top:12px">
        <button class="btn primary" type="submit">Login</button>
        <a class="btn ghost" href="register.php">Need an account?</a>
      </div>
    </form>
  </section>
  <section class="card">
    <h2>About</h2>
    <p>This system lets residents submit complaints and interact with admins digitally. Admins can manage complaints and post announcements.</p>
    <ul>
      <li>Role-based access (User/Admin)</li>
      <li>Commenting on complaints</li>
      <li>Announcements board</li>
    </ul>
  </section>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
