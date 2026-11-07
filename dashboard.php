<?php require_once __DIR__ . '/config.php'; if(!isset($_SESSION['user'])){ header("Location: login.php"); exit(); } $user = $_SESSION['user']; $msg = ""; if(isset($_POST['add'])){ $n = $conn->real_escape_string($_POST['name']); $sid = $conn->real_escape_string($_POST['stud_id']); $course = $conn->real_escape_string($_POST['course']); $date = $_POST['date']; $status = $_POST['status']; $time = $_POST['time_in']; if($time && $status == "Present" && strtotime($time) > strtotime("08:00:00")) { $status = "Late"; } $conn->query("INSERT INTO attendance (name,stud_id,course,date,status,time_in,created_by) VALUES ('$n','$sid','$course','$date','$status','$time','$user')"); $msg = "<div class='toast-alert' id='alertBox'>Record added successfully</div>"; } if(isset($_GET['del'])){ $id = intval($_GET['del']); $conn->query("DELETE FROM attendance WHERE id=$id AND created_by='$user'"); header("Location: dashboard.php"); exit(); } if(isset($_POST['update'])){ $id = intval($_POST['id']); $n = $conn->real_escape_string($_POST['name']); $sid = $conn->real_escape_string($_POST['stud_id']); $course = $conn->real_escape_string($_POST['course']); $date = $_POST['date']; $status = $_POST['status']; $time = $_POST['time_in']; $conn->query("UPDATE attendance SET name='$n',stud_id='$sid',course='$course',date='$date',status='$status',time_in='$time' WHERE id=$id AND created_by='$user'"); header("Location: dashboard.php"); exit(); } $edit = null; if(isset($_GET['edit'])){ $id = intval($_GET['edit']); $edit = $conn->query("SELECT * FROM attendance WHERE id=$id AND created_by='$user'")->fetch_assoc(); } $total = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE created_by='$user'")->fetch_assoc()['c'] ?? 0; $present = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE status='Present' AND created_by='$user'")->fetch_assoc()['c'] ?? 0; $absent = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE status='Absent' AND created_by='$user'")->fetch_assoc()['c'] ?? 0; $late = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE status='Late' AND created_by='$user'")->fetch_assoc()['c'] ?? 0; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Maya-Style Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root { --maya-green: #00D68F; --maya-green-hover: #00b377; --bg-dark: #000000; --surface-card: #1C1C1E; --surface-input: #2C2C2E; --text-main: #FFFFFF; --text-muted: #8E8E93; --danger: #FF453A; --warning: #FF9F0A; --info: #0A84FF; }
* { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
body { background: var(--bg-dark); color: var(--text-main); padding-bottom: 60px; }
.nav { padding: 20px 5%; display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; }
.nav-profile { display: flex; align-items: center; gap: 12px; }
.avatar { width: 40px; height: 40px; background: var(--maya-green); color: #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem; }
.nav-greeting { font-size: 1.1rem; font-weight: 600; }
.logout-btn { background: var(--surface-input); color: var(--text-main); padding: 8px 16px; border-radius: 20px; text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: background 0.2s; }
.logout-btn:hover { background: #3a3a3c; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
.balance-section { text-align: center; margin: 30px 0 40px 0; }
.balance-label { color: var(--text-muted); font-size: 0.9rem; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
.balance-amount { font-size: 3.5rem; font-weight: 700; line-height: 1; margin-bottom: 20px; }
.quick-stats { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; }
.q-stat { background: var(--surface-card); padding: 10px 20px; border-radius: 20px; font-size: 0.9rem; font-weight: 500; display: flex; align-items: center; gap: 8px; }
.dot { width: 10px; height: 10px; border-radius: 50%; }
.dot.present { background: var(--maya-green); }
.dot.absent { background: var(--danger); }
.dot.late { background: var(--warning); }
.card { background: var(--surface-card); padding: 24px; border-radius: 24px; margin-bottom: 30px; }
.card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.card h3 { font-size: 1.2rem; font-weight: 600; }
.grid-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 20px; }
input, select { width: 100%; padding: 14px 16px; border-radius: 16px; border: 2px solid transparent; background: var(--surface-input); color: var(--text-main); font-size: 0.95rem; transition: all 0.2s; }
::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
input:focus, select:focus { outline: none; border-color: var(--maya-green); background: #222224; }
.btn-primary { width: 100%; padding: 16px; border: none; border-radius: 30px; color: var(--bg-dark); font-weight: 600; font-size: 1.05rem; background: var(--maya-green); cursor: pointer; transition: transform 0.1s, background 0.2s; }
.btn-primary:hover { background: var(--maya-green-hover); }
.btn-primary:active { transform: scale(0.98); }
.section-title { font-size: 1.1rem; font-weight: 600; margin: 40px 0 15px 0; padding: 0 5px; }
.search-bar { background: var(--surface-input); padding: 12px 20px; border-radius: 20px; width: 100%; max-width: 350px; margin-bottom: 15px; border: none; }
.table-responsive { overflow-x: auto; }
table { width: 100%; border-collapse: separate; border-spacing: 0 10px; min-width: 700px; }
th { color: var(--text-muted); font-weight: 500; font-size: 0.85rem; text-align: left; padding: 0 20px; text-transform: uppercase; }
td { background: var(--surface-card); padding: 16px 20px; vertical-align: middle; }
tr td:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; }
tr td:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; }
.record-title { font-weight: 600; font-size: 1rem; color: var(--text-main); }
.record-sub { font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }
.badge { padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
.Present { background: rgba(0, 214, 143, 0.15); color: var(--maya-green); }
.Absent { background: rgba(255, 69, 58, 0.15); color: var(--danger); }
.Late { background: rgba(255, 159, 10, 0.15); color: var(--warning); }
.Excused { background: rgba(10, 132, 255, 0.15); color: var(--info); }
.action-links a { text-decoration: none; font-size: 0.85rem; font-weight: 500; padding: 8px 16px; border-radius: 20px; background: var(--surface-input); transition: all 0.2s; display: inline-block; }
.edit-link { color: var(--text-main); }
.edit-link:hover { background: #3a3a3c; }
.del-link { color: var(--danger); margin-left: 5px; }
.del-link:hover { background: rgba(255, 69, 58, 0.15); }
.toast-alert { position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); background: var(--maya-green); color: var(--bg-dark); padding: 12px 24px; border-radius: 30px; font-weight: 600; font-size: 0.95rem; box-shadow: 0 4px 15px rgba(0, 214, 143, 0.3); z-index: 1000; animation: slideUp 0.3s ease forwards; }
@keyframes slideUp { from { opacity: 0; bottom: 0; } to { opacity: 1; bottom: 30px; } }
</style>
</head>
<body>
<nav class="nav">
<div class="nav-profile">
<div class="avatar"><?= strtoupper(substr($user, 0, 1)) ?></div>
<div class="nav-greeting">Hi, <?= htmlspecialchars($user) ?>!</div>
</div>
<a href="logout.php" class="logout-btn">Log out</a>
</nav>
<div class="container">
<div class="balance-section">
<div class="balance-label">Total Records</div>
<div class="balance-amount"><?= $total ?></div>
<div class="quick-stats">
<div class="q-stat"><div class="dot present"></div> <?= $present ?> Present</div>
<div class="q-stat"><div class="dot absent"></div> <?= $absent ?> Absent</div>
<div class="q-stat"><div class="dot late"></div> <?= $late ?> Late</div>
</div>
</div>
<?= $msg ?>
<div class="card">
<div class="card-header">
<h3><?= $edit ? "Update Record Details" : "Add New Record" ?></h3>
<?php if($edit) echo "<a href='dashboard.php' style='color:var(--maya-green); text-decoration:none; font-size:0.9rem; font-weight: 500;'>Cancel</a>"; ?>
</div>
<form method="POST">
<?php if($edit) echo "<input type='hidden' name='id' value='{$edit['id']}'>"; ?>
<div class="grid-form">
<input name="name" placeholder="Student Name" value="<?= $edit['name'] ?? '' ?>" required>
<input name="stud_id" placeholder="Student ID Number" value="<?= $edit['stud_id'] ?? '' ?>" required>
<input name="course" placeholder="Course & Section" value="<?= $edit['course'] ?? '' ?>">
<input type="date" name="date" value="<?= $edit['date'] ?? date('Y-m-d') ?>" required>
<select name="status">
<option <?= isset($edit) && $edit['status']=='Present' ? 'selected' : '' ?>>Present</option>
<option <?= isset($edit) && $edit['status']=='Absent' ? 'selected' : '' ?>>Absent</option>
<option <?= isset($edit) && $edit['status']=='Late' ? 'selected' : '' ?>>Late</option>
<option <?= isset($edit) && $edit['status']=='Excused' ? 'selected' : '' ?>>Excused</option>
</select>
<input type="time" name="time_in" value="<?= $edit['time_in'] ?? '' ?>">
</div>
<button class="btn-primary" name="<?= $edit ? 'update' : 'add' ?>">
<?= $edit ? 'Save Changes' : 'Confirm Record' ?>
</button>
</form>
</div>
<div style="display: flex; justify-content: space-between; align-items: flex-end;">
<div class="section-title">Recent Activity</div>
<input type="text" id="searchInput" class="search-bar" placeholder="Search records..." onkeyup="filterTable()">
</div>
<div class="table-responsive">
<table id="attendanceTable">
<thead>
<tr>
<th>Profile</th>
<th>Course</th>
<th>Timestamp</th>
<th>Status</th>
<th style="text-align: right;">Actions</th>
</tr>
</thead>
<tbody>
<?php $r = $conn->query("SELECT * FROM attendance WHERE created_by='$user' ORDER BY id DESC"); while($row = $r->fetch_assoc()){ $formatted_time = $row['time_in'] ? date("h:i A", strtotime($row['time_in'])) : 'No time recorded'; $formatted_date = date("M d, Y", strtotime($row['date'])); echo "<tr> <td> <div class='record-title'>{$row['name']}</div> <div class='record-sub'>ID: {$row['stud_id']}</div> </td> <td style='color: var(--text-main); font-weight: 500;'>{$row['course']}</td> <td> <div class='record-title'>{$formatted_date}</div> <div class='record-sub'>{$formatted_time}</div> </td> <td><span class='badge {$row['status']}'>{$row['status']}</span></td> <td class='action-links' style='text-align: right;'> <a href='?edit={$row['id']}' class='edit-link'>Edit</a> <a href='?del={$row['id']}' class='del-link' onclick='return confirm(\"Delete this record?\")'>Delete</a> </td> </tr>"; } if($r->num_rows == 0) { echo "<tr><td colspan='5' style='text-align:center; padding: 40px; color:var(--text-muted); border-radius: 16px;'>No activity yet.</td></tr>"; } ?>
</tbody>
</table>
</div>
</div>
<script>
function filterTable() {
const input = document.getElementById("searchInput");
const filter = input.value.toLowerCase();
const table = document.getElementById("attendanceTable");
const tr = table.getElementsByTagName("tr");
for (let i = 1; i < tr.length; i++) {
let tdName = tr[i].getElementsByTagName("td")[0];
if (tdName) {
let txtValue = tdName.textContent || tdName.innerText;
if (txtValue.toLowerCase().indexOf(filter) > -1) {
tr[i].style.display = "";
} else {
tr[i].style.display = "none";
}
}
}
}
setTimeout(() => {
const alertBox = document.getElementById('alertBox');
if(alertBox) {
alertBox.style.transition = "opacity 0.3s ease, bottom 0.3s ease";
alertBox.style.opacity = "0";
alertBox.style.bottom = "0px";
setTimeout(() => alertBox.remove(), 300);
}
}, 3000);
</script>
</body>
</html>