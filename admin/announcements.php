<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('admin');

// Create / Update
if (isset($_POST['save'])) {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    if ($id) {
        $stmt = $pdo->prepare("UPDATE announcements SET title=?, body=?, updated_at=NOW() WHERE id=?");
        $stmt->execute([$title, $body, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO announcements (admin_id, title, body, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([current_user()['id'], $title, $body]);
    }
}
// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id=?");
    $stmt->execute([$id]);
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id=?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

$rows = $pdo->query("SELECT a.*, u.name as admin_name FROM announcements a JOIN users u ON u.id=a.admin_id ORDER BY a.created_at DESC")->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<section class="grid">
  <div class="card">
    <h2><?= $edit ? 'Edit' : 'New' ?> Announcement</h2>
    <form method="post">
      <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">
      <label>Title</label>
      <input class="input" type="text" name="title" required value="<?= e($edit['title'] ?? '') ?>">
      <label>Body</label>
      <textarea class="input" name="body" required><?= e($edit['body'] ?? '') ?></textarea>
      <button class="btn primary" name="save">Save</button>
      <?php if ($edit): ?>
        <a class="btn" href="announcements.php">Cancel</a>
      <?php endif; ?>
    </form>
  </div>

  <div class="card">
    <h2>All Announcements</h2>
    <?php if (!$rows): ?><div class="empty">No announcements.</div><?php else: ?>
      <table class="table">
        <tr><th>Title</th><th>By</th><th>Created</th><th>Updated</th><th></th></tr>
        <?php foreach ($rows as $a): ?>
          <tr>
            <td><?= e($a['title']) ?></td>
            <td><?= e($a['admin_name']) ?></td>
            <td><?= e($a['created_at']) ?></td>
            <td><?= e($a['updated_at']) ?></td>
            <td class="actions">
              <a class="btn" href="?edit=<?= e($a['id']) ?>">Edit</a>
              <a class="btn danger" data-confirm="Delete this announcement?" href="?delete=<?= e($a['id']) ?>">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
