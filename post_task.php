<?php
require 'db.php';
if(!isLoggedIn() || currentUser()['role'] !== 'requester'){
    echo "<script>alert('Only requesters can post tasks.');window.location.href='index.php';</script>";
    exit;
}

$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? 'Other');
    $payment = (int)$_POST['payment'];
    $deadline = $_POST['deadline'] ?? null;

    if(!$title || !$desc || !$payment){
        $err = "Please fill title, description, and payment.";
    } else {
        $ins = $pdo->prepare("INSERT INTO tasks (requester_id,title,description,category,payment,deadline,status) VALUES (?,?,?,?,?,?, 'open')");
        $ins->execute([currentUser()['id'],$title,$desc,$category,$payment,$deadline]);
        echo "<script>window.location.href='requester_dashboard.php';</script>";
        exit;
    }
}
?>
<!doctype html>
<html><head>
<meta charset="utf-8"><title>Post Task</title>
<style>
body{font-family:Inter,Arial;background:#f7fbff;padding:30px}
.box{max-width:760px;margin:30px auto;background:#fff;padding:18px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.06)}
input,textarea,select{width:100%;padding:10px;border-radius:10px;border:1px solid #e6eef7;margin-top:8px}
.btn{padding:10px;border-radius:9px;background:#0b63b8;color:white;border:none;margin-top:10px}
.small{color:#6b7280}
.error{color:#b00020}
</style>
</head>
<body>
<div class="box">
  <h2>Post a Task</h2>
  <?php if($err): ?><div class="error"><?=htmlspecialchars($err)?></div><?php endif; ?>
  <form method="post">
    <label>Title</label>
    <input name="title" required>
    <label>Category</label>
    <select name="category">
      <option>Data Entry</option>
      <option>Survey</option>
      <option>Transcription</option>
      <option>Image Tagging</option>
      <option>Research</option>
      <option>Other</option>
    </select>
    <label>Description</label>
    <textarea name="description" rows="6" required></textarea>
    <div style="display:flex;gap:12px">
      <div style="flex:1">
        <label>Payment (PKR)</label>
        <input name="payment" type="number" required min="10">
      </div>
      <div style="flex:1">
        <label>Deadline</label>
        <input name="deadline" type="date">
      </div>
    </div>
    <button class="btn" type="submit">Post Task</button>
  </form>
</div>
</body>
</html>
