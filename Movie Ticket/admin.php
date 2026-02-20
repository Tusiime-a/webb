<?php
require 'db.php';
$err = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $title = $_POST['title']; $genre = $_POST['genre'];
  $duration = (int)$_POST['duration']; $show_time = $_POST['show_time'];
  $price = (float)$_POST['price'];
  $stmt = $conn->prepare("INSERT INTO movies (title,genre,duration,show_time,price) VALUES (?,?,?,?,?)");
  $stmt->bind_param("ssisd",$title,$genre,$duration,$show_time,$price);
  if ($stmt->execute()) $msg = 'Movie added';
  else $err = 'Error';
}
$movies = $conn->query("SELECT * FROM movies ORDER BY id DESC");
?>
<!doctype html><h2>Admin - Add Movie</h2>
<?= !empty($msg) ? '<div>'.e($msg).'</div>' : '' ?>
<form method="post">
  Title: <input name="title"><br>
  Genre: <input name="genre"><br>
  Duration (min): <input name="duration" type="number"><br>
  Show time (YYYY-MM-DD HH:MM:SS): <input name="show_time"><br>
  Price: <input name="price" type="number" step="0.01"><br>
  <button>Add</button>
</form>

<h3>Existing</h3>
<table border=1>
<?php while($m = $movies->fetch_assoc()): ?>
<tr><td><?= e($m['id']) ?></td><td><?= e($m['title']) ?></td><td><?= e($m['show_time']) ?></td></tr>
<?php endwhile; ?>
</table>
<a href="index.php">Back to site</a>
