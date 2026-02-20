<?php
require 'db.php';

if ($_SESSION['username'] !== 'admin') die("Access denied");

$title = $_POST['title'];
$desc  = $_POST['description'];
$target = $_POST['target'];

$image = $_FILES['image']['name'];
$tmp = $_FILES['image']['tmp_name'];

move_uploaded_file($tmp, "images/$image");

$stmt = $conn->prepare(
    "INSERT INTO causes (title, description, target_amount, image)
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssis", $title, $desc, $target, $image);
$stmt->execute();

header("Location: admin.php");
