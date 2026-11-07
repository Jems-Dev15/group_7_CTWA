
<?php
require_once __DIR__ . '/config.php';

if (isset($_POST['register'])) {
    $u = trim($_POST['username']);
    $p = trim($_POST['password']);
    $c = trim($_POST['confirm']);

    if (empty($u) || empty($p) || empty($c)) {
        $error = "All fields required.";
    } elseif ($p != $c) {
        $error = "Passwords do not match!";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $u);
        $stmt->execute();
        $chk = $stmt->get_result();

        if ($chk->num_rows > 0) {
            $error = "Username already exists!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $u, $p);

            if ($stmt->execute()) {
                $success = "Account Created! <a href='login.php'>Login now</a>";
            } else {
                $error = "Unable to create account.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Create Account | SmartLog</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #0d47a1;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

.box {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    width: 100%;
    max-width: 390px;
}

/* LOGO AND BRAND */
.header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 25px;
}

.header img {
    display: block;
    width: 95px;
    height: 85px;
    object-fit: contain;
    flex-shrink: 0;
}

.header-text {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.brand-name {
    font-size: 25px;
    font-weight: 800;
    color: #111;
    letter-spacing: -1px;
}

.brand-name span {
    color: #00b377;
}

.header-text h2 {
    color: #0d47a1;
    font-size: 16px;
    font-weight: 600;
}

.header-text small {
    font-size: 10px;
    color: #777;
    letter-spacing: 1.2px;
}

/* INPUT FIELDS */
input {
    width: 100%;
    padding: 12px;
    margin: 7px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    outline: none;
}

input:focus {
    border-color: #0d47a1;
}

/* BUTTON */
button {
    width: 100%;
    padding: 12px;
    background: #0d47a1;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
    transition: 0.3s;
}

button:hover {
    background: #083579;
}

/* MESSAGES */
.error {
    background: #ffcdd2;
    color: #b71c1c;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    margin: 10px 0;
    font-size: 14px;
}

.success {
    background: #c8e6c9;
    color: #2e7d32;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    margin: 10px 0;
    font-size: 14px;
}

.success a {
    color: #1b5e20;
    font-weight: bold;
}

/* LOGIN LINK */
.link {
    text-align: center;
    margin-top: 18px;
    font-size: 14px;
}

.link a {
    color: #0d47a1;
    text-decoration: none;
    font-weight: bold;
}

.link a:hover {
    text-decoration: underline;
}

/* MOBILE RESPONSIVE */
@media (max-width: 400px) {
    .box {
        padding: 22px;
    }

    .header {
        gap: 8px;
    }

    .header img {
        width: 70px;
        height: 70px;
    }

    .brand-name {
        font-size: 21px;
    }

    .header-text h2 {
        font-size: 14px;
    }

    .header-text small {
        font-size: 9px;
    }
}
</style>
</head>

<body>

<div class="box">

    <!-- LOGO AND SMARTLOG BRAND -->
    <div class="header">

        <img src="/sample/attendance.png" alt="Attendance System Logo">

        <div class="header-text">
            <div class="brand-name">
                Smart<span>Log</span>
            </div>

            <h2>Create Account</h2>

            <small>ATTENDANCE SYSTEM</small>
        </div>

    </div>

    <!-- ERROR / SUCCESS MESSAGE -->
    <?php
    if (isset($error)) {
        echo "<div class='error'>$error</div>";
    }

    if (isset($success)) {
        echo "<div class='success'>$success</div>";
    }
    ?>

    <!-- SIGNUP FORM -->
    <form method="POST">

        <input type="text" name="username" placeholder="Username" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="password" name="confirm" placeholder="Confirm Password" required>

        <button type="submit" name="register">
            CREATE ACCOUNT
        </button>

    </form>

    <!-- LOGIN LINK -->
    <div class="link">
        Already have an account?
        <a href="login.php">Login</a>
    </div>

</div>

</body>
</html>
