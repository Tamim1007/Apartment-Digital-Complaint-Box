<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('user');

// Create complaint
if (isset($_POST['create'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    if ($title && $description) {
        $stmt = $pdo->prepare("INSERT INTO complaints (user_id, title, description, status, created_at) VALUES (?, ?, ?, 'open', NOW())");
        $stmt->execute([current_user()['id'], $title, $description]);
    }
    header("Location: complaints.php");
    exit;
}

$rows = $pdo->prepare("SELECT * FROM complaints WHERE user_id=? ORDER BY created_at DESC");
$rows->execute([current_user()['id']]);
$rows = $rows->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<section class="grid">
  <div class="card">
    <h2>New Complaint</h2>
    <form method="post">
      <label>Title</label>
      <input class="input" type="text" name="title" required>
      <label>Description</label>
      <textarea class="input" name="description" required></textarea>
      <button class="btn primary" name="create">Submit</button>
    </form>
  </div>

  <div class="card">
    <h2>My Complaints</h2>
    <?php if (!$rows): ?><div class="empty">You haven't submitted any complaints.</div><?php else: ?>
      <table class="table">
        <tr><th>Title</th><th>Status</th><th>Created</th><th></th></tr>
        <?php foreach ($rows as $c): ?>
        <tr>
          <td><?= e($c['title']) ?></td>
          <td><span class="status <?= e($c['status']) ?>"><?= e($c['status']) ?></span></td>
          <td><?= e($c['created_at']) ?></td>
          <td><a class="btn" href="complaint_view.php?id=<?= e($c['id']) ?>">Open</a></td>
        </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
