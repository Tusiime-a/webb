<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check POST data
if (!isset($_POST['movie_id'], $_POST['seats']) || empty($_POST['seats'])) {
    exit('No seats selected.');
}

$movie_id = (int)$_POST['movie_id'];
$seats = $_POST['seats']; // array of seat strings
$seats_str = implode(',', $seats);

// Get price per seat
$stmt = $conn->prepare("SELECT title, price FROM movies WHERE id=? LIMIT 1");
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
if (!$movie) exit('Movie not found.');

$total_price = count($seats) * $movie['price'];

// Insert booking
$stmt = $conn->prepare("INSERT INTO bookings (user_id, movie_id, seats, total_price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iisd", $_SESSION['user_id'], $movie_id, $seats_str, $total_price);
$success = $stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Booking Confirmation</title>
<style>
    body {
        background: #0d1117;
        color: #fff;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    header {
        background: #161b22;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 5px #222;
    }
    header h1 {
        margin: 0;
        color: #58a6ff;
    }
    nav {
        margin-top: 10px;
    }
    nav a {
        color: #fff;
        text-decoration: none;
        margin: 0 10px;
        font-weight: bold;
    }
    nav a:hover {
        color: #58a6ff;
    }
    main {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background: #161b22;
        border-radius: 10px;
        box-shadow: 0 0 10px #222;
        text-align: center;
    }
    h2 {
        color: #58a6ff;
        margin-top: 0;
    }
    p {
        margin: 10px 0;
    }
    a.button {
        display: inline-block;
        margin: 10px;
        padding: 10px 15px;
        background: #238636;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
    }
    a.button:hover {
        background: #2ea043;
    }
</style>
</head>
<body>
<header>
    <h1>🎬 Movie Booking</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
<?php if($success): ?>
    <h2>Booking Confirmed!</h2>
    <p><strong>Movie:</strong> <?= htmlspecialchars($movie['title']) ?></p>
    <p><strong>Seats:</strong> <?= htmlspecialchars($seats_str) ?></p>
    <p><strong>Total Price:</strong> UGX <?= number_format($total_price,0) ?></p>
    <a href="my_bookings.php" class="button">Go to My Bookings</a>
    <a href="index.php" class="button">Back to Movies</a>
<?php else: ?>
    <h2>Booking Failed!</h2>
    <p>Error: <?= htmlspecialchars($stmt->error) ?></p>
    <a href="index.php" class="button">Back to Movies</a>
<?php endif; ?>
</main>
</body>
</html>
