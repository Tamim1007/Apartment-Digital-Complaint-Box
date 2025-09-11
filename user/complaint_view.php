<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('user');

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM complaints WHERE id=? AND user_id=?");
$stmt->execute([$id, current_user()['id']]);
$c = $stmt->fetch();
if (!$c) { include __DIR__ . '/../includes/header.php'; echo '<section class="card">Not found.</section>'; include __DIR__ . '/../includes/footer.php'; exit; }

// Add comment
if (isset($_POST['add_comment'])) {
    $content = trim($_POST['content'] ?? '');
    if ($content) {
        $stmt = $pdo->prepare("INSERT INTO comments (complaint_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$id, current_user()['id'], $content]);
    }
    header("Location: complaint_view.php?id=$id");
    exit;
}

$comments = $pdo->prepare("SELECT cm.*, u.name FROM comments cm JOIN users u ON u.id=cm.user_id WHERE complaint_id=? ORDER BY cm.created_at ASC");
$comments->execute([$id]);
$comments = $comments->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<section class="grid">
  <div class="card">
    <h2><?= e($c['title']) ?></h2>
    <p class="muted">Status: <span class="status <?= e($c['status']) ?>"><?= e($c['status']) ?></span> • <?= e($c['created_at']) ?></p>
    <p><?= nl2br(e($c['description'])) ?></p>
  </div>
  <div class="card">
    <h3>Comments</h3>
    <?php if (!$comments): ?><div class="empty">No comments yet.</div><?php endif; ?>
    <?php foreach ($comments as $cm): ?>
      <div style="border-bottom:1px solid var(--border); padding:8px 0;">
        <strong><?= e($cm['name']) ?></strong>
        <span class="muted"> • <?= e($cm['created_at']) ?></span>
        <p><?= nl2br(e($cm['content'])) ?></p>
      </div>
    <?php endforeach; ?>
    <form method="post" style="margin-top:12px">
      <label>Add Comment</label>
      <textarea name="content" class="input" required></textarea>
      <button class="btn primary" name="add_comment">Post Comment</button>
    </form>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
