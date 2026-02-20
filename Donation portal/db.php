<?php
session_start();

$conn = new mysqli("localhost", "root", "", "donation_db");
if ($conn->connect_error) {
    die("Database connection failed");
}

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
