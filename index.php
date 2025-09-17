<?php
include 'db.php';
$result = $conn->query("SELECT * FROM games");
?>

<h1>Game Top-Up</h1>
<ul>
<?php while($row = $result->fetch_assoc()): ?>
    <li>
        <a href="topup.php?id=<?= $row['id'] ?>">
            <?= $row['name'] ?>
        </a>
    </li>
<?php endwhile; ?>
</ul>