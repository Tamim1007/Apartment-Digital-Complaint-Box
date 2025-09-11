<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$ADMIN_CODE = getenv('ADMIN_CODE') ?: 'BRAINLESS';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $admin_code = $_POST['admin_code'] ?? '';

    if ($role === 'admin' && $admin_code !== $ADMIN_CODE) {
        $error = 'Invalid admin registration code.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $hash, $role]);
            $id = $pdo->lastInsertId();
            $user = ['id'=>$id,'name'=>$name,'email'=>$email,'role'=>$role];
            login($user);
            redirect('/apartment-digital-complaint-box/dashboard.php');
        } catch (PDOException $e) {
            $error = 'Registration failed: email may already be in use.';
        }
    }
}
include __DIR__ . '/includes/header.php';
?>
<section class="card">
  <h2>Create your account</h2>
  <?php if ($error): ?><p style="color:#ff8aa5"><?= e($error) ?></p><?php endif; ?>
  <form method="post">
    <div class="row">
      <div>
        <label>Name</label>
        <input class="input" type="text" name="name" required>
      </div>
      <div>
        <label>Email</label>
        <input class="input" type="email" name="email" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label>Password</label>
        <input class="input" type="password" name="password" required>
      </div>
      <div>
        <label>Role</label>
        <select name="role" class="input">
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>
    </div>
    <div id="admin_code_wrap" style="display:none">
      <label>Admin Registration Code</label>
      <input class="input" type="text" name="admin_code" placeholder="Enter admin code">
      <p class="muted">Tip: Default code is ********* </p>
    </div>
    <div style="margin-top:12px">
      <button class="btn primary" type="submit">Register</button>
      <a class="btn ghost" href="index.php">Back to Login</a>
    </div>
  </form>
</section>
<script>
const roleSel = document.querySelector('select[name=role]');
const wrap = document.getElementById('admin_code_wrap');
function toggle(){ wrap.style.display = roleSel.value==='admin' ? 'block' : 'none'; }
roleSel.addEventListener('change', toggle); toggle();
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
