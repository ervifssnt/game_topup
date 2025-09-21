<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Game Top Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }
        .header {
            padding: 15px;
            background: #333;
            color: white;
            text-align: left; /* keep welcome on the left */
        }
        .header a {
            color: #ffcc00;
            text-decoration: none;
            margin-left: 10px;
        }
        .container {
            display: flex;
            justify-content: center; /* center horizontally */
            align-items: center;     /* center vertically */
            flex-direction: column;
            min-height: 80vh;
        }
        .game-card {
            background: white;
            padding: 15px;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
            width: 300px;
            text-align: center;
        }
        .game-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .game-card a:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="header">
    <?php
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "<a href='login.php'>Login</a> | <a href='register.php'>Register</a>";
        exit;
    } else {
        echo "Welcome, " . htmlspecialchars($_SESSION['username']) . " 🎮 | <a href='logout.php'>Logout</a>";
    }
    ?>
</div>

<div class="container">
    <h2>Available Games for Top Up</h2>
    <?php
    // Fetch all games
    $result = $conn->query("SELECT * FROM games");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='game-card'>
                    <h3>" . htmlspecialchars($row['name']) . "</h3>
                    <a href='topup.php?id={$row['id']}'>Top Up</a>
                </div>
            ";
        }
    } else {
        echo "No games available.";
    }
    ?>
</div>

</body>
</html>
