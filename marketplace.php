<?php
require 'db.php';
$cat = $_GET['category'] ?? '';
$q = trim($_GET['q'] ?? '');

// build query
$sql = "SELECT t.*, u.name as requester FROM tasks t JOIN users u ON u.id = t.requester_id WHERE t.status='open' ";
$params = [];
if($cat){
  $sql .= " AND t.category = ? ";
  $params[] = $cat;
}
if($q){
  $sql .= " AND (t.title LIKE ? OR t.description LIKE ?)";
  $params[] = "%$q%";
  $params[] = "%$q%";
}
$sql .= " ORDER BY t.id DESC LIMIT 80";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// categories quick list
$cats = ['Data Entry','Survey','Transcription','Image Tagging','Research','Other'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Marketplace — MiniTurk</title>
<style>
body{font-family:Inter,Arial;background:#f7fbff;padding:26px}
.wrap{max-width:1100px;margin:0 auto}
.header{display:flex;justify-content:space-between;align-items:center}
.searchbox{display:flex;gap:8px;margin-top:14px}
input,select{padding:10px;border-radius:10px;border:1px solid #e6eef7}
.btn{padding:10px 12px;border-radius:10px;background:#0b63b8;color:white;border:none}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px;margin-top:18px}
.card{background:white;padding:14px;border-radius:10px;box-shadow:0 8px 20px rgba(18,30,60,0.04)}
.small{color:#64748b;font-size:13px}
.badge{background:#eef7ff;color:#0b63b8;padding:6px;border-radius:8px;font-weight:700}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h2>Task Marketplace</h2>
    <div>
      <?php if(isLoggedIn()): ?>
        <span class="small">Hi, <?=htmlspecialchars(currentUser()['name'])?></span>
        <a href="index.php" class="small" style="margin-left:8px">Home</a>
      <?php else: ?>
        <a href="login.php">Login</a> • <a href="register.php">Sign up</a>
      <?php endif; ?>
    </div>
  </div>

  <form method="get" class="searchbox" style="margin-top:12px">
    <input type="text" name="q" placeholder="Search tasks..." value="<?=htmlspecialchars($q)?>">
    <select name="category">
      <option value="">All categories</option>
      <?php foreach($cats as $c): ?>
        <option value="<?=htmlspecialchars($c)?>" <?= $c===$cat?'selected':'';?>><?=htmlspecialchars($c)?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn" type="submit">Search</button>
    <?php if(isLoggedIn() && currentUser()['role']=='requester'): ?>
      <button type="button" class="btn" style="background:#22c55e;margin-left:10px" onclick="window.location.href='post_task.php'">Post Task</button>
    <?php endif; ?>
  </form>

  <div class="grid">
    <?php if(!$tasks): ?>
      <div class="card">No tasks found.</div>
    <?php endif; ?>
    <?php foreach($tasks as $t): ?>
      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <strong><?=htmlspecialchars($t['title'])?></strong>
          <div class="badge"><?=htmlspecialchars($t['payment'])?> PKR</div>
        </div>
        <div class="small" style="margin-top:8px"><?=htmlspecialchars($t['category'])?> • by <?=htmlspecialchars($t['requester'])?></div>
        <p class="small" style="margin-top:10px"><?=htmlspecialchars(substr($t['description'],0,160))?>...</p>
        <div style="margin-top:12px">
          <a href="task.php?id=<?= $t['id'] ?>" class="btn">View & Apply</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>
</body>
</html>
