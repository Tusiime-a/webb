<?php
require 'db.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$cause_id = $_GET['id'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $amount = $_POST['amount'];
    $stmt = $conn->prepare("INSERT INTO donations (user_id, cause_id, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $_SESSION['user_id'], $cause_id, $amount);
    $stmt->execute();
    header("Location: my_donations.php");
	$method = $_POST['method'];
$stmt = $conn->prepare(
"INSERT INTO donations (user_id, cause_id, amount, method) VALUES (?,?,?,?)"
);
$stmt->bind_param("iiis", $_SESSION['user_id'], $cause_id, $amount, $method);

}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>
<form method="POST">
<h2>Donate</h2>
<input type="number" name="amount" placeholder="Amount" required>
<button class="btn">Donate</button>
<select name="method" required>
  <option value="MTN">MTN Mobile Money</option>
  <option value="Airtel">Airtel Money</option>
</select>

</form>
</body>
</html>
