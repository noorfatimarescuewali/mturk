<?php
require 'db.php';

// fetch featured tasks (latest 6)
$stmt = $pdo->query("SELECT t.*, u.name as requester FROM tasks t JOIN users u ON u.id = t.requester_id WHERE t.status IN ('open') ORDER BY t.id DESC LIMIT 6");
$featured = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>MiniTurk — Microtask Marketplace</title>
<style>
/* internal CSS - crisp & modern */
:root{--accent:#4a90e2;--muted:#6b7280}
*{box-sizing:border-box;font-family:Inter,system-ui,Arial;margin:0}
body{background:linear-gradient(180deg,#f6fbff,#ffffff);color:#111;padding:24px}
.container{max-width:1000px;margin:0 auto}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
.brand{font-weight:700;color:var(--accent);font-size:22px}
.nav a{margin-left:12px;text-decoration:none;color:var(--muted)}
.card{background:#fff;border-radius:12px;padding:18px;box-shadow:0 6px 18px rgba(20,30,70,0.06);margin-bottom:14px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;margin-top:12px}
.task{padding:12px;border-radius:8px;border:1px solid #eef2f7}
.small{color:var(--muted);font-size:13px}
.btn{display:inline-block;padding:8px 12px;border-radius:8px;background:var(--accent);color:white;text-decoration:none}
.footer{margin-top:20px;text-align:center;color:var(--muted);font-size:13px}
.badge{background:#eef7ff;color:var(--accent);padding:6px;border-radius:8px;font-weight:600}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <div class="brand">MiniTurk</div>
    <div class="nav">
      <?php if(isLoggedIn()): ?>
        <span class="small">Hi, <?=htmlspecialchars(currentUser()['name'])?></span>
        <a href="marketplace.php">Marketplace</a>
        <?php if(currentUser()['role']=='worker'): ?>
          <a href="worker_dashboard.php">Worker Dashboard</a>
        <?php else: ?>
          <a href="requester_dashboard.php">Requester Dashboard</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Sign up</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <h2>Earn money doing small tasks — fast & flexible.</h2>
    <p class="small">Sign up as a <strong>worker</strong> to complete tasks or as a <strong>requester</strong> to post tasks and get them done.</p>
    <div style="margin-top:12px">
      <a href="marketplace.php" class="btn">Browse Tasks</a>
      <a href="register.php" class="btn" onclick="redirectToRegister(event)" style="background:#22c55e">Join & Earn</a>
    </div>
  </div>

  <h3 style="margin-top:14px">Featured tasks</h3>
  <div class="grid">
    <?php foreach($featured as $t): ?>
      <div class="card task">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <strong><?=htmlspecialchars($t['title'])?></strong>
          <div class="badge"><?=htmlspecialchars($t['payment'])?> PKR</div>
        </div>
        <div class="small" style="margin:8px 0"><?=htmlspecialchars($t['category'])?> • by <?=htmlspecialchars($t['requester'])?></div>
        <p class="small"><?=htmlspecialchars(substr($t['description'],0,120))?>...</p>
        <div style="margin-top:8px">
          <a href="task.php?id=<?= $t['id'] ?>" class="btn">View</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="footer card">
    <div>Built with ♥ for microutaskers — Drop files in your Apache/PHP folder and run the SQL provided.</div>
  </div>
</div>

<script>
function redirectToRegister(e){
  e.preventDefault();
  // JS redirect to register
  window.location.href = 'register.php';
}
</script>
</body>
</html>
