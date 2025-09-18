<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<a href='login.php'>Login</a> | <a href='register.php'>Register</a>";
    exit;
} else {
    echo "Welcome, " . $_SESSION['username'] . "  | <a href='logout.php'>Logout</a><br><br>";
}

// Fetch all games
$result = $conn->query("SELECT * FROM games");

echo "<h2>Available Games for Top Up</h2>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "
            <p>
                {$row['name']} 
                - <a href='topup.php?id={$row['id']}'>Top Up</a>
            </p>
        ";
    }
} else {
    echo "No games available.";
}
?>
