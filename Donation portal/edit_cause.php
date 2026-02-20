<?php
require 'db.php';
if($_SESSION['username'] !== 'admin') die("Access denied");

$id = $_GET['id'];

if($_SERVER['REQUEST_METHOD']=='POST'){
    $title = $_POST['title'];
    $desc  = $_POST['description'];
    $target = $_POST['target'];

    $stmt = $conn->prepare(
        "UPDATE causes SET title=?, description=?, target_amount=? WHERE id=?"
    );
    $stmt->bind_param("ssii", $title, $desc, $target, $id);
    $stmt->execute();

    header("Location: admin.php");
}

$cause = $conn->query("SELECT * FROM causes WHERE id=$id")->fetch_assoc();
?>
<form method="POST">
<input name="title" value="<?= e($cause['title']) ?>" required>
<textarea name="description"><?= e($cause['description']) ?></textarea>
<input name="target" value="<?= $cause['target_amount'] ?>" required>
<button class="btn">Update</button>
</form>
