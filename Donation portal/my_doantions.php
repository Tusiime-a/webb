<?php
require 'db.php';
$stmt = $conn->prepare("
SELECT causes.title, donations.amount, donations.donated_at
FROM donations
JOIN causes ON causes.id = donations.cause_id
WHERE donations.user_id=?
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>
<h2 style="text-align:center;">My Donations</h2>
<?php while($row = $res->fetch_assoc()): ?>
<p style="text-align:center;">
<?= e($row['title']) ?> — <?= $row['amount'] ?> — <?= $row['donated_at'] ?>
</p>
<?php endwhile; ?>
</body>
</html>
