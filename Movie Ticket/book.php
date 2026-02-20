<?php
session_start();
require 'db.php'; // database connection

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get movie ID from GET
if (!isset($_GET['id'])) exit('No movie selected');
$movie_id = (int)$_GET['id'];

// Fetch movie from database
$stmt = $conn->prepare("SELECT * FROM movies WHERE id=? LIMIT 1");
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
if (!$movie) exit('Movie not found');

// Fetch already booked seats
$bk = $conn->prepare("SELECT seats FROM bookings WHERE movie_id=?");
$bk->bind_param("i",$movie_id);
$bk->execute();
$r = $bk->get_result();
$booked = [];
while($row = $r->fetch_assoc()){
    $s = explode(',', $row['seats']);
    $booked = array_merge($booked, $s);
}
$booked = array_unique($booked);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book: <?= htmlspecialchars($movie['title']) ?></title>
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
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background: #161b22;
        border-radius: 10px;
        box-shadow: 0 0 10px #222;
    }
    h2 {
        margin-top: 0;
        color: #58a6ff;
    }
    p {
        margin: 10px 0;
    }
    .seat-grid div {
        display: flex;
        margin-bottom: 5px;
    }
    .seat-grid label {
        margin: 4px;
        cursor: pointer;
    }
    .seat-grid input[type=checkbox] {
        margin-right: 5px;
        cursor: pointer;
    }
    input[type=checkbox]:disabled + span {
        opacity: 0.4;
        cursor: not-allowed;
    }
    button {
        background: #238636;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 15px;
    }
    button:hover {
        background: #2ea043;
    }
    a.back {
        display: inline-block;
        margin-top: 15px;
        color: #58a6ff;
        text-decoration: none;
    }
    a.back:hover {
        text-decoration: underline;
    }
    @media(max-width:600px){
        .seat-grid div {
            flex-wrap: wrap;
        }
        main {
            margin: 10px;
            padding: 15px;
        }
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
    <h2>Book: <?= htmlspecialchars($movie['title']) ?></h2>
    <p>Showtime: <?= htmlspecialchars($movie['show_time']) ?></p>
    <p>Price per seat: UGX <?= number_format($movie['price'],0) ?></p>

    <form method="post" action="process_booking.php">
        <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">

        <p>Select seats:</p>
        <div class="seat-grid">
        <?php
        $rows = range('A','F'); $cols = range(1,8);
        foreach($rows as $r){
            echo '<div>';
            foreach($cols as $c){
                $seat = $r.$c;
                $disabled = in_array($seat,$booked) ? 'disabled' : '';
                echo "<label>
                        <input type='checkbox' name='seats[]' value='{$seat}' {$disabled}>
                        <span>{$seat}</span>
                      </label>";
            }
            echo '</div>';
        }
        ?>
        </div>

        <button>Confirm Booking</button>
    </form>
    <a href="index.php" class="back">← Back to Movies</a>
</main>
</body>
</html>
