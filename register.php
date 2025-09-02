<?php
require 'db.php';
$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] === 'requester' ? 'requester' : 'worker';

    if(!$name || !$email || !$password){
        $err = "All fields are required.";
    } else {
        // check exists
        $s = $pdo->prepare("SELECT id FROM users WHERE email=?");
        $s->execute([$email]);
        if($s->fetch()){
            $err = "Email already registered.";
        } else {
            $pw = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare("INSERT INTO users (name,email,password,role,balance) VALUES (?,?,?,?,0)");
            $ins->execute([$name,$email,$pw,$role]);
            // login and redirect via JS
            $_SESSION['user'] = ['id'=>$pdo->lastInsertId(),'name'=>$name,'email'=>$email,'role'=>$role,'balance'=>0];
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
<title>Register — MiniTurk</title>
<style>
body{font-family:Inter,Arial;background:#f7fbff;padding:30px}
.box{max-width:420px;margin:40px auto;background:#fff;padding:20px;border-radius:12px;box-shadow:0 8px 30px rgba(20,30,70,0.06)}
h2{color:#0b63b8;margin-bottom:6px}
label{display:block;margin-top:10px;font-size:14px;color:#333}
input[type=text],input[type=email],input[type=password],select{width:100%;padding:10px;border-radius:8px;border:1px solid #e6eef7;margin-top:6px}
.row{display:flex;gap:8px}
.btn{margin-top:12px;padding:10px;border-radius:8px;background:#0b63b8;color:white;border:none;cursor:pointer}
.error{color:#b00020;margin-top:8px}
.note{font-size:13px;color:#666;margin-top:8px}
.link{color:#0b63b8;text-decoration:none}
</style>
</head>
<body>
<div class="box">
  <h2>Create account</h2>
  <?php if($err): ?><div class="error"><?=htmlspecialchars($err)?></div><?php endif; ?>
  <form method="post" id="frm">
    <label>Name</label>
    <input type="text" name="name" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <label>Account type</label>
    <select name="role">
      <option value="worker">Worker (complete tasks)</option>
      <option value="requester">Requester (post tasks)</option>
    </select>
    <button class="btn" type="submit">Sign up</button>
  </form>
  <div class="note">Already have an account? <a href="login.php" class="link">Login</a></div>
</div>

<script>
// no JS required except fallback link
document.getElementById('frm').addEventListener('submit', function(){
  // allow normal post
});
</script>
</body>
</html>
