<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'tusiime_db'; // or tusiime_db depending on what you’re using

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die('DB connect error: '.$conn->connect_error);

function e($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
