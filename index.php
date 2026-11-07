<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SmartLog | Attendance System</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root { --maya-green: #00D68F; --maya-green-hover: #00b377; --bg-dark: #000000; --surface-card: #1C1C1E; --surface-input: #2C2C2E; --text-main: #FFFFFF; --text-muted: #8E8E93; }
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
body { background: var(--bg-dark); color: var(--text-main); overflow-x: hidden; -webkit-font-smoothing: antialiased; }
.nav { padding: 24px 5%; display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; position: relative; z-index: 10; }
.logo { font-weight: 700; font-size: 1.25rem; display: flex; align-items: center; gap: 12px; letter-spacing: -0.5px; text-decoration: none; color: var(--text-main); }
.logo-img { height: 45px; width: auto; object-fit: contain; }
.marquee-container { width: 100%; overflow: hidden; padding: 17px 0; background: rgba(0, 214, 143, 0.07); border-top: 1px solid rgba(0, 214, 143, 0.2); border-bottom: 1px solid rgba(0, 214, 143, 0.2); display: flex; }
.marquee-track { display: flex; width: max-content; flex-shrink: 0; animation: marquee 25s linear infinite; }
.marquee-container:hover .marquee-track { animation-play-state: paused; }
.marquee-content { display: flex; align-items: center; gap: 45px; padding-right: 45px; white-space: nowrap; }
.marquee-content span { font-size: 0.85rem; font-weight: 600; letter-spacing: 2px; color: var(--text-main); text-transform: uppercase; }
.marquee-content .highlight { color: var(--maya-green); }
.marquee-content .dot { color: var(--maya-green); font-size: 1.3rem; }
@keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.hero { padding: 100px 20px 90px; text-align: center; max-width: 900px; margin: 0 auto; position: relative; overflow: hidden; }
.hero::before { content: ''; position: absolute; top: 45%; left: 50%; transform: translate(-50%, -50%); width: 350px; height: 350px; background: rgba(0, 214, 143, 0.15); filter: blur(110px); z-index: -1; border-radius: 50%; }
.hero h1 { font-size: 3.8rem; font-weight: 800; line-height: 1.1; margin-bottom: 24px; letter-spacing: -1.5px; }
.hero h1 span { color: var(--maya-green); }
.hero p { font-size: 1.1rem; color: var(--text-muted); line-height: 1.8; max-width: 650px; margin: 0 auto 40px; }
.cta { background: var(--maya-green); color: var(--bg-dark); padding: 10px 30px 10px 14px; border-radius: 40px; text-decoration: none; font-weight: 600; font-size: 1.1rem; display: inline-flex; align-items: center; justify-content: center; gap: 12px; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 10px 25px rgba(0, 214, 143, 0.2); }
.cta img { width: 45px; height: 45px; object-fit: contain; border-radius: 50%; }
.cta:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0, 214, 143, 0.3); background: var(--maya-green-hover); }
.cta:active { transform: translateY(0) scale(0.98); }
.bottom-marquee { width: 100%; overflow: hidden; padding: 25px 0; background: var(--surface-card); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); }
.bottom-track { display: flex; width: max-content; animation: marquee 35s linear infinite; }
.bottom-content { display: flex; align-items: center; gap: 60px; padding-right: 60px; white-space: nowrap; }
.bottom-content span { font-size: 1.4rem; font-weight: 800; color: rgba(255,255,255,0.12); letter-spacing: 3px; }
.bottom-content .green { color: var(--maya-green); }
.cards { max-width: 1200px; margin: 60px auto 80px; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; padding: 0 5%; }
.card { background: var(--surface-card); padding: 32px; border-radius: 24px; transition: transform 0.3s ease, background 0.3s; border: 1px solid rgba(255,255,255,0.05); }
.card:hover { transform: translateY(-8px); background: #222224; border-color: rgba(0, 214, 143, 0.3); }
.icon-box { background: rgba(0, 214, 143, 0.1); color: var(--maya-green); width: 54px; height: 54px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 24px; }
.card h3 { font-size: 1.25rem; font-weight: 600; margin-bottom: 12px; }
.card p { font-size: 0.95rem; color: var(--text-muted); line-height: 1.7; }
footer { text-align: center; padding: 25px 20px; color: var(--text-muted); font-size: 0.8rem; border-top: 1px solid rgba(255,255,255,0.05); }
footer span { color: var(--maya-green); }
@media(max-width: 768px) { .nav { padding: 18px 20px; } .logo { font-size: 1.1rem; } .logo-img { height: 38px; } .hero { padding: 85px 20px 75px; } .hero h1 { font-size: 2.7rem; } .hero p { font-size: 0.95rem; padding: 0 5px; } .marquee-content span { font-size: 0.75rem; } .marquee-content { gap: 30px; padding-right: 30px; } .cards { grid-template-columns: 1fr; gap: 16px; margin-top: 40px; } .bottom-content span { font-size: 1.1rem; } }
@media(max-width: 400px) { .hero h1 { font-size: 2.2rem; } .hero p { font-size: 0.9rem; } .cta { font-size: 1rem; padding: 8px 24px 8px 12px; } .cta img { width: 38px; height: 38px; } .logo { font-size: 1rem; } }
@media (prefers-reduced-motion: reduce) { .marquee-track, .bottom-track { animation: none; } }
</style>
</head>
<body>
<div class="nav">
<a href="index.php" class="logo">
<img src="attendance.png" alt="Attendance System Logo" class="logo-img">
</a>
</div>
<div class="marquee-container">
<div class="marquee-track">
<div class="marquee-content">
<span class="highlight">SMARTLOG ATTENDANCE SYSTEM</span>
<span class="dot">✦</span>
<span>FAST AND SECURE TRACKING</span>
<span class="dot">✦</span>
<span class="highlight">REAL-TIME STUDENT MONITORING</span>
<span class="dot">✦</span>
<span>SIMPLE AND EFFICIENT MANAGEMENT</span>
<span class="dot">✦</span>
</div>
<div class="marquee-content" aria-hidden="true">
<span class="highlight">SMARTLOG ATTENDANCE SYSTEM</span>
<span class="dot">✦</span>
<span>FAST AND SECURE TRACKING</span>
<span class="dot">✦</span>
<span class="highlight">REAL-TIME STUDENT MONITORING</span>
<span class="dot">✦</span>
<span>SIMPLE AND EFFICIENT MANAGEMENT</span>
<span class="dot">✦</span>
</div>
</div>
</div>
<div class="hero">
<h1>Seamless<br><span>Attendance</span> System</h1>
<p>A fast, secure, and modern platform for tracking student attendance. Built with complete CRUD capabilities, real-time validations, and automatic status detection.</p>
<a href="login.php" class="cta">
<img src="attendance.png" alt="Attendance Logo">
<span>Get Started Now</span>
</a>
</div>
<div class="bottom-marquee">
<div class="bottom-track">
<div class="bottom-content">
<span>SMARTLOG</span>
<span class="green">ATTENDANCE</span>
<span>MONITORING</span>
<span class="green">EFFICIENCY</span>
<span>ACCURACY</span>
<span class="green">SECURITY</span>
</div>
<div class="bottom-content" aria-hidden="true">
<span>SMARTLOG</span>
<span class="green">ATTENDANCE</span>
<span>MONITORING</span>
<span class="green">EFFICIENCY</span>
<span>ACCURACY</span>
<span class="green">SECURITY</span>
</div>
</div>
</div>
<div class="cards">
<div class="card">
<div class="icon-box">✓</div>
<h3>Easy Attendance</h3>
<p>Quickly record and manage student attendance with a simple and user-friendly interface.</p>
</div>
<div class="card">
<div class="icon-box">◷</div>
<h3>Real-Time Monitoring</h3>
<p>Monitor student attendance records and check attendance status efficiently.</p>
</div>
<div class="card">
<div class="icon-box">▤</div>
<h3>Student Management</h3>
<p>Organize student information, update records, and manage attendance data in one place.</p>
</div>
</div>
<footer>© <?php echo date("Y"); ?> <span>SmartLog</span> Attendance System. All Rights Reserved.</footer>
</body>
</html>