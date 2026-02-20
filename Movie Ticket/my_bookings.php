<?php
session_start();
require 'db.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch bookings for the logged-in user
$stmt = $conn->prepare("
    SELECT b.*, m.title, m.show_time 
    FROM bookings b 
    JOIN movies m ON b.movie_id = m.id 
    WHERE b.user_id = ? 
    ORDER BY b.booked_at DESC
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$bookings = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings</title>
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
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background: #161b22;
        border-radius: 10px;
        box-shadow: 0 0 10px #222;
    }
    h2 {
        color: #58a6ff;
        margin-top: 0;
        text-align: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #333;
    }
    th {
        background: #0d1117;
    }
    tr:nth-child(even) {
        background: #1b222b;
    }
    tr:hover {
        background: #2a2f38;
    }
    a.button {
        display: inline-block;
        margin-top: 15px;
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
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <h2>My Bookings</h2>
    <?php if($bookings->num_rows > 0): ?>
    <table>
        <tr>
            <th>Movie</th>
            <th>Showtime</th>
            <th>Seats</th>
            <th>Total (UGX)</th>
            <th>Booked At</th>
        </tr>
        <?php while($b = $bookings->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($b['title']) ?></td>
            <td><?= date('d M Y H:i', strtotime($b['show_time'])) ?></td>
            <td><?= htmlspecialchars($b['seats']) ?></td>
            <td><?= number_format($b['total_price'],0) ?></td>
            <td><?= date('d M Y H:i', strtotime($b['booked_at'])) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p style="text-align:center; margin-top:20px;">You have no bookings yet.</p>
    <?php endif; ?>

    <div style="text-align:center;">
        <a href="index.php" class="button">Back to Movies</a>
    </div>
</main>
</body>
</html>
