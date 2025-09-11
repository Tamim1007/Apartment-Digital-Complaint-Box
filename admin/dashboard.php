<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');

$totalComplaints = $pdo->query("SELECT COUNT(*) c FROM complaints")->fetch()['c'];
$open = $pdo->query("SELECT COUNT(*) c FROM complaints WHERE status='open'")->fetch()['c'];
$prog = $pdo->query("SELECT COUNT(*) c FROM complaints WHERE status='in_progress'")->fetch()['c'];
$closed = $pdo->query("SELECT COUNT(*) c FROM complaints WHERE status='closed'")->fetch()['c'];
$latest = $pdo->query("SELECT c.*, u.name as user_name FROM complaints c JOIN users u ON u.id=c.user_id ORDER BY c.created_at DESC LIMIT 5")->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<section class="grid">
  <div class="grid grid-2">
    <div class="card"><h3>Total Complaints</h3><p style="font-size:28px"><?= e($totalComplaints) ?></p></div>
    <div class="card"><h3>Open</h3><p style="font-size:28px"><?= e($open) ?></p></div>
    <div class="card"><h3>In Progress</h3><p style="font-size:28px"><?= e($prog) ?></p></div>
    <div class="card"><h3>Closed</h3><p style="font-size:28px"><?= e($closed) ?></p></div>
  </div>
  <div class="card">
    <h3>Latest Complaints</h3>
    <?php if (!$latest): ?>
      <div class="empty">No complaints yet.</div>
    <?php else: ?>
    <table class="table">
      <tr><th>Title</th><th>User</th><th>Status</th><th>Created</th><th></th></tr>
      <?php foreach ($latest as $c): ?>
        <tr>
          <td><?= e($c['title']) ?></td>
          <td><?= e($c['user_name']) ?></td>
          <td><span class="status <?= e($c['status']) ?>"><?= e($c['status']) ?></span></td>
          <td><?= e($c['created_at']) ?></td>
          <td><a class="btn" href="complaints.php?view=<?= e($c['id']) ?>">Open</a></td>
        </tr>
      <?php endforeach; ?>
    </table>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
