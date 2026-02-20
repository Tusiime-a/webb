<?php
session_start();
require 'db.php'; // connect to database

$err = '';

// when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username']);
    $p = $_POST['password'];

    // check if username exists
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $u);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res && password_verify($p, $res['password'])) {
        $_SESSION['user_id'] = $res['id'];
        $_SESSION['username'] = $u;
        header('Location: index.php');
        exit;
    } else {
        $err = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
        .error {
            color: #ff5555;
            margin-bottom: 10px;
        }
        a { color: #58a6ff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>🎬 Movie Ticket Login</h2>

    <?php if(!empty($err)) echo "<p class='error'>$err</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
</body>
</html>
