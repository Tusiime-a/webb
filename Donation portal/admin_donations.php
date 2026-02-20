<?php
require 'db.php';
if($_SESSION['username'] !== 'admin') die("Access denied");

$res = $conn->query("
SELECT users.username, causes.title, donations.amount, donations.donated_at
FROM donations
JOIN users ON users.id = donations.user_id
JOIN causes ON causes.id = donations.cause_id
ORDER BY donations.donated_at DESC
");
?>
<h2>Donation Reports</h2>
<?php while($row = $res->fetch_assoc()): ?>
<p>
<?= e($row['username']) ?> donated <?= $row['amount'] ?>
to <?= e($row['title']) ?> (<?= $row['donated_at'] ?>)
</p>
<?php endwhile; ?>
