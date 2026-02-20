<?php
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    die("Access denied");
}

$causes = $conn->query("SELECT * FROM causes");
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<h2 style="text-align:center;">Admin Dashboard</h2>

<form method="POST" action="add_cause.php" enctype="multipart/form-data">
<h3>Add New Cause</h3>
<a href="edit_cause.php?id=<?= $row['id'] ?>" class="donate-btn">Edit</a>
<a href="admin_donations.php">View Donations</a>
<input name="title" placeholder="Title" required>
<textarea name="description" placeholder="Description" required></textarea>
<input type="number" name="target" placeholder="Target Amount" required>
<input type="file" name="image" required>
<button class="btn">Add Cause</button>
</form>

<hr>

<h3 style="text-align:center;">All Causes</h3>
<div class="donation-container">
<?php while($row = $causes->fetch_assoc()): ?>
<div class="card">
<img src="images/<?= e($row['image']) ?>">
<h3><?= e($row['title']) ?></h3>
<p><?= e($row['description']) ?></p>
<p>Target: <?= $row['target_amount'] ?></p>
<a class="donate-btn" href="delete_cause.php?id=<?= $row['id'] ?>" style="background:red">Delete</a>
</div>
<?php endwhile; ?>
</div>

</body>
</html>
