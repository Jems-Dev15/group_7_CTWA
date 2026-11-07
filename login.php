<?php require_once __DIR__ . '/config.php'; $errL=""; $errR=""; $okR=""; if(isset($_POST['login'])){ $u=$conn->real_escape_string($_POST['username']); $p=$conn->real_escape_string($_POST['password']); $res=$conn->query("SELECT * FROM users WHERE username='$u' AND password='$p'"); if($res && $res->num_rows>0){ $_SESSION['user']=$u; header("Location: dashboard.php"); exit(); } else { $errL="Invalid username or password!"; } } if(isset($_POST['register'])){ $u=trim($conn->real_escape_string($_POST['new_username'])); $p=trim($conn->real_escape_string($_POST['new_password'])); $c=trim($conn->real_escape_string($_POST['confirm'])); if($p!==$c){ $errR="Passwords do not match!"; } else { $chk=$conn->query("SELECT id FROM users WHERE username='$u'"); if($chk->num_rows>0){ $errR="Username already exists!"; } else { $conn->query("INSERT INTO users (username,password) VALUES ('$u','$p')"); $okR="Account created! Click LOGIN tab now."; } } } ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SmartLog | Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root { --maya-green: #00D68F; --maya-green-hover: #00b377; --bg-dark: #000000; --surface-card: #1C1C1E; --surface-input: #2C2C2E; --text-main: #FFFFFF; --text-muted: #8E8E93; --danger: #FF453A; }
* { box-sizing: border-box; font-family: 'Poppins', sans-serif; margin: 0; padding: 0; }
body { background: var(--bg-dark); display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; color: var(--text-main); -webkit-font-smoothing: antialiased; }
.box { background: var(--surface-card); width: 100%; max-width: 920px; border-radius: 30px; display: grid; grid-template-columns: 1fr 1fr; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.05); }
.brand { position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 22px; padding: 40px 32px; background: var(--bg-dark); overflow: hidden; }
.brand::before { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 280px; height: 280px; background: rgba(0, 214, 143, 0.15); filter: blur(90px); border-radius: 50%; pointer-events: none; }
.brand-logo { position: relative; width: 100%; max-width: 340px; aspect-ratio: 3 / 2; object-fit: contain; border-radius: 24px; display: block; }
.brand-name { position: relative; font-size: 1.75rem; font-weight: 800; letter-spacing: -0.5px; }
.brand-name span { color: var(--maya-green); }
.brand-text { position: relative; font-size: 0.9rem; color: var(--text-muted); line-height: 1.6; max-width: 280px; }
.panel { padding: 40px 36px; display: flex; flex-direction: column; justify-content: center; }
.tabs { display: flex; background: var(--surface-input); border-radius: 20px; padding: 6px; margin-bottom: 25px; }
.tab { flex: 1; padding: 12px; border: none; background: transparent; color: var(--text-muted); border-radius: 16px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; }
.tab.active { background: var(--maya-green); color: var(--bg-dark); box-shadow: 0 4px 10px rgba(0, 214, 143, 0.2); }
.tab:focus-visible, button.action-btn:focus-visible, a.home:focus-visible { outline: 2px solid var(--maya-green); outline-offset: 2px; }
.form { display: none; animation: fadeIn 0.3s ease; }
.form.active { display: block; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
h3 { font-size: 1.5rem; font-weight: 600; margin-bottom: 20px; text-align: center; }
input { width: 100%; padding: 16px 20px; margin-bottom: 16px; border-radius: 16px; border: 2px solid transparent; background: var(--surface-input); color: var(--text-main); font-size: 0.95rem; transition: all 0.2s; }
input::placeholder { color: var(--text-muted); }
input:focus { outline: none; border-color: var(--maya-green); background: #222224; }
button.action-btn { width: 100%; padding: 16px; margin-top: 10px; border: none; border-radius: 30px; color: var(--bg-dark); font-weight: 600; font-size: 1.05rem; background: var(--maya-green); cursor: pointer; transition: transform 0.1s, background 0.2s; }
button.action-btn:hover { background: var(--maya-green-hover); }
button.action-btn:active { transform: scale(0.98); }
.msg { padding: 14px; border-radius: 16px; text-align: center; font-size: 0.9rem; font-weight: 500; margin-bottom: 20px; }
.err { background: rgba(255, 69, 58, 0.15); color: var(--danger); border: 1px solid rgba(255, 69, 58, 0.2); }
.ok { background: rgba(0, 214, 143, 0.15); color: var(--maya-green); border: 1px solid rgba(0, 214, 143, 0.2); }
a.home { color: var(--text-muted); text-decoration: none; font-size: 0.9rem; display: block; text-align: center; margin-top: 25px; font-weight: 500; transition: color 0.2s; }
a.home:hover { color: var(--text-main); }
@media (max-width: 760px) { .box { grid-template-columns: 1fr; max-width: 440px; } .brand { display: none; } .panel { padding: 24px; } }
@media (prefers-reduced-motion: reduce) { .form { animation: none; } }
</style>
</head>
<body>
<div class="box">
<div class="brand">
<img src="attendance.png" alt="Attendance System Logo" class="brand-logo">
<div class="brand-name"><span></span></div>
<p class="brand-text">Track student attendance fast, securely, and in real time.</p>
</div>
<div class="panel">
<div class="tabs">
<button class="tab active" id="t1" onclick="show('login')">Login</button>
<button class="tab" id="t2" onclick="show('reg')">Register</button>
</div>
<div class="form active" id="f1">
<h3>Welcome Back</h3>
<?php if($errL) echo "<div class='msg err'>$errL</div>"; ?>
<form method="POST">
<input name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button name="login" class="action-btn">Log In</button>
</form>
<a class="home" href="index.php">← Back to Home</a>
</div>
<div class="form" id="f2">
<h3>Create Account</h3>
<?php if($errR) echo "<div class='msg err'>$errR</div>"; if($okR) echo "<div class='msg ok'>$okR</div>"; ?>
<form method="POST">
<input name="new_username" placeholder="Choose a Username" required>
<input type="password" name="new_password" placeholder="Password" required>
<input type="password" name="confirm" placeholder="Confirm Password" required>
<button name="register" class="action-btn">Sign Up</button>
</form>
<a class="home" href="index.php">← Back to Home</a>
</div>
</div>
</div>
<script>
function show(x) {
document.getElementById('f1').classList.remove('active');
document.getElementById('f2').classList.remove('active');
document.getElementById('t1').classList.remove('active');
document.getElementById('t2').classList.remove('active');
if (x == 'login') {
document.getElementById('f1').classList.add('active');
document.getElementById('t1').classList.add('active');
} else {
document.getElementById('f2').classList.add('active');
document.getElementById('t2').classList.add('active');
}
}
<?php if($errR || $okR) echo "show('reg');"; ?>
</script>
</body>
</html>