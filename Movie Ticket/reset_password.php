<?php
// reset_password.php  — protect this file (or run from CLI)
require 'db.php'; // your connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $new_plain = $_POST['new_password'] ?? '';

    if (!$username || !$new_plain) {
        echo "Provide username and new_password";
        exit;
    }

    $new_hash = password_hash($new_plain, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_hash, $username);
    if ($stmt->execute()) {
        echo "Password reset for user $username";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit;
}
?>

<!-- Simple form (delete after use or protect behind auth) -->
<form method="post">
  Username: <input name="username"><br>
  New password: <input name="new_password" type="password"><br>
  <button type="submit">Reset</button>
</form>
