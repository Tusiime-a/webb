<?php
session_start();
require 'db.php'; // database connection

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username']);
    $p = $_POST['password'];

    // Check if username already exists
    $check = $conn->prepare("SELECT id FROM users WHERE username=? LIMIT 1");
    $check->bind_param("s", $u);
    $check->execute();
    $exists = $check->get_result()->fetch_assoc();

    if ($exists) {
        $msg = 'Username already taken!';
    } else {
        // Hash the password before saving
        $hash = password_hash($p, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $u, $hash);

        if ($stmt->execute()) {
            $msg = 'Account created successfully! <a href="login.php">Login now</a>.';
        } else {
            $msg = 'Error creating account.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #0d1117;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        h2 { margin-bottom: 20px; }
        form {
            background: #161b22;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #222;
            display: flex;
            flex-direction: column;
            width: 280px;
        }
        input {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            border: none;
            outline: none;
        }
        button {
            background: #238636;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #2ea043;
        }
        .msg {
            margin-bottom: 10px;
            color: #ff5555;
        }
        a { color: #58a6ff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>🎟️ Create Account</h2>

    <?php if(!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Choose a username" required>
        <input type="password" name="password" placeholder="Create a password" required>
        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
