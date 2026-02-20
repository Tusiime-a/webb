<?php
session_start();
require 'db.php'; // database connection

// Fetch movies from database
$result = $conn->query("SELECT * FROM movies ORDER BY show_time ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🎬 Classic Cinemax</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* General Styles */
        body {
            background: #0d1117;
            color: white;
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

        /* Movie Grid */
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 25px;
            padding: 80px;
        }

        .movie-card {
            background: #161b22;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px #222;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }

        .movie-card:hover {
            transform: scale(1.03);
        }

        .movie-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .movie-info {
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .movie-info h3 {
            margin: 0 0 10px;
            font-size: 1.2em;
        }

        .movie-info p {
            margin: 5px 0;
            font-size: 0.9em;
        }

        .movie-info button {
            margin-top: auto;
            background: #238636;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .movie-info button:hover {
            background: #2ea043;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #888;
            background: #161b22;
            margin-top: 20px;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .movie-grid {
                grid-template-columns: 1fr;
                padding: 10px;
            }
            .movie-card img {
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>🎬 Classic Cinemax</h1>
        <nav>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="my_bookings.php">My Bookings</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Sign Up</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="movie-grid">
        <?php while($movie = $result->fetch_assoc()): ?>
            <div class="movie-card">
                <img src="images/<?php echo !empty($movie['poster']) ? htmlspecialchars($movie['poster']) : 'default.jpg'; ?>" 
                     alt="<?php echo htmlspecialchars($movie['title']); ?>">
                <div class="movie-info">
                    <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                    <p>Showtime: <?php echo date('d M Y H:i', strtotime($movie['show_time'])); ?></p>
                    <p>Price: UGX <?php echo number_format($movie['price'], 0); ?></p>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <form action="book.php" method="GET">
                            <input type="hidden" name="id" value="<?php echo $movie['id']; ?>">
                            <button type="submit">Book Now</button>
                        </form>
                    <?php else: ?>
                        <p style="color:#888; font-size:0.85em;">Login to book</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <footer>
        &copy; <?php echo date('Y'); ?> Classic Cinemax
    </footer>
</body>
</html>
