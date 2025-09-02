<?php
require 'db.php';
$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if(!$email || !$password) $err = "Enter email & password.";
    else {
        $s = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $s->execute([$email]);
        $u = $s->fetch(PDO::FETCH_ASSOC);
        if(!$u || !password_verify($password, $u['password'])){
            $err = "Invalid credentials.";
        } else {
            $_SESSION['user'] = ['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role'],'balance'=>$u['balance']];
            echo "<script>window.location.href='index.php';</script>";
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login — MiniTurk</title>
<style>
body{background:#f2f8ff;font-family:Inter,Arial;padding:32px}
.box{max-width:420px;margin:40px auto;background:white;padding:20px;border-radius:12px;box-shadow:0 8px 30px rgba(20,30,70,0.06)}
input{width:100%;padding:10px;border-radius:8px;border:1px solid #e6eef7;margin-top:8px}
.btn{margin-top:12px;padding:10px;border-radius:8px;background:#0b63b8;color:white;border:none;cursor:pointer}
.small{font-size:13px;color:#666;margin-top:8px}
.error{color:#b00020}
</style>
</head>
<body>
<div class="box">
  <h2>Login</h2>
  <?php if($err): ?><div class="error"><?=$err?></div><?php endif; ?>
  <form method="post">
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button class="btn" type="submit">Login</button>
  </form>
  <div class="small">No account? <a href="register.php">Register</a></div>
</div>
</body>
</html>
