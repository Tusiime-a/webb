<?php
require 'db.php';
$causes = $conn->query("SELECT * FROM causes");
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
<h1>Kirabo Charity Organization</h1>
<nav>
<?php if(isset($_SESSION['user_id'])): ?>
<a href="my_donations.php">My Donations</a>
<a href="logout.php">Logout</a>
<?php else: ?>
<a href="login.php">Login</a>
<?php endif; ?>
<?php if(isset($_SESSION['username']) && $_SESSION['username']=='admin'): ?>
<a href="admin.php">Admin</a>
<?php endif; ?>

</nav>
</header>

<div class="donation-container">
<?php while($row = $causes->fetch_assoc()): ?>
<div class="card">
<button onclick="document.body.classList.toggle('dark')">🌙</button>
<img src="images/<?= e($row['image']) ?>">
<h3><?= e($row['title']) ?></h3>
<p><?= e($row['description']) ?></p>
<p><strong>Target:</strong> <?= $row['target_amount'] ?></p>
<a class="donate-btn" href="donate.php?id=<?= $row['id'] ?>">Donate</a>
</div>
<?php endwhile; ?>
</div>

</body>
</html>


