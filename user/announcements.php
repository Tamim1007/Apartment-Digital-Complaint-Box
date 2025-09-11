<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('user');

$rows = $pdo->query("SELECT a.*, u.name as admin_name FROM announcements a JOIN users u ON u.id=a.admin_id ORDER BY a.created_at DESC")->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<section class="card">
  <h2>Announcements</h2>
  <?php if (!$rows): ?><div class="empty">No announcements yet.</div><?php else: ?>
    <?php foreach ($rows as $a): ?>
      <article style="border-bottom:1px solid var(--border); padding:10px 0;">
        <h3><?= e($a['title']) ?></h3>
        <p class="muted">By <?= e($a['admin_name']) ?> • <?= e($a['created_at']) ?></p>
        <p><?= nl2br(e($a['body'])) ?></p>
      </article>
    <?php endforeach; ?>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
