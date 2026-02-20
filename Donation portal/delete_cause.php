<?php
require 'db.php';

if ($_SESSION['username'] !== 'admin') die("Access denied");

$id = $_GET['id'];
$conn->query("DELETE FROM causes WHERE id=$id");

header("Location: admin.php");
