<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');

// Update status
if (isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE complaints SET status=? WHERE id=?");
    $stmt->execute([$status, $id]);
}

// Add comment (admin)
if (isset($_POST['add_comment'])) {
    $id = (int)$_POST['id'];
    $content = trim($_POST['content'] ?? '');
    if ($content) {
        $stmt = $pdo->prepare("INSERT INTO comments (complaint_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$id, current_user()['id'], $content]);
    }
}

$view = isset($_GET['view']) ? (int)$_GET['view'] : null;

include __DIR__ . '/../includes/header.php';

if ($view) {
    $stmt = $pdo->prepare("SELECT c.*, u.name as user_name FROM complaints c JOIN users u ON u.id=c.user_id WHERE c.id=?");
    $stmt->execute([$view]);
    $c = $stmt->fetch();
    $comments = $pdo->prepare("SELECT cm.*, u.name FROM comments cm JOIN users u ON u.id=cm.user_id WHERE complaint_id=? ORDER BY cm.created_at ASC");
    $comments->execute([$view]);
    $comments = $comments->fetchAll();
    if (!$c) { echo '<section class="card">Not found.</section>'; include __DIR__ . '/../includes/footer.php'; exit; }
    ?>
    <section class="grid">
      <div class="card">
        <h2><?= e($c['title']) ?></h2>
        <p class="muted">By <?= e($c['user_name']) ?> • <?= e($c['created_at']) ?></p>
        <p><?= nl2br(e($c['description'])) ?></p>
        <form method="post" class="actions">
          <input type="hidden" name="id" value="<?= e($c['id']) ?>">
          <select name="status" class="input" style="max-width:200px">
            <?php foreach (['open','in_progress','closed'] as $s): ?>
              <option value="<?= $s ?>" <?php if ($c['status']===$s) echo 'selected';?>><?= $s ?></option>
            <?php endforeach; ?>
          </select>
          <button class="btn primary" name="update_status">Update Status</button>
        </form>
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
          <input type="hidden" name="id" value="<?= e($c['id']) ?>">
          <label>Add Comment</label>
          <textarea name="content" class="input" required></textarea>
          <button class="btn primary" name="add_comment">Post Comment</button>
        </form>
      </div>
    </section>
    <?php
} else {
    $rows = $pdo->query("SELECT c.*, u.name as user_name FROM complaints c JOIN users u ON u.id=c.user_id ORDER BY c.created_at DESC")->fetchAll();
    ?>
    <section class="card">
      <h2>All Complaints</h2>
      <?php if (!$rows): ?><div class="empty">No complaints found.</div><?php else: ?>
      <table class="table">
        <tr><th>Title</th><th>User</th><th>Status</th><th>Created</th><th></th></tr>
        <?php foreach ($rows as $c): ?>
          <tr>
            <td><?= e($c['title']) ?></td>
            <td><?= e($c['user_name']) ?></td>
            <td><span class="status <?= e($c['status']) ?>"><?= e($c['status']) ?></span></td>
            <td><?= e($c['created_at']) ?></td>
            <td><a class="btn" href="?view=<?= e($c['id']) ?>">View</a></td>
          </tr>
        <?php endforeach; ?>
      </table>
      <?php endif; ?>
    </section>
    <?php
}

include __DIR__ . '/../includes/footer.php';
