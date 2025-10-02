<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Game Top Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: #111;
            color: white;
        }
        header a {
            color: #ffcc00;
            text-decoration: none;
            margin-left: 10px;
        }
        .container {
            text-align: center;
            margin-top: 30px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .game-card {
            background: white;
            width: 280px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .game-card img {
            width: 150px;
            height: auto;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #00ff55;
            color: black;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #00cc44;
            color: white;
        }
    </style>
</head>
<body>

<header>
    <h1>Game Topup</h1>
    <div>
        <?php
        if (!isset($_SESSION['user_id'])) {
            echo "<a href='login.php'>Login</a> | <a href='register.php'>Register</a>";
            exit;
        } else {
            echo "Welcome, " . htmlspecialchars($_SESSION['username']) . " | <a href='logout.php'>Logout</a>";
        }
        ?>
    </div>
</header>

<div class="container">
    <h2>Available Games for Top Up</h2>

    <?php
    // Fetch all games
    $result = $conn->query("SELECT * FROM games");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // choose logo based on game
            $logo = '';
            if (strtolower($row['name']) === 'mobile legends') {
                $logo = 'images/mobile_legends.png';
            } elseif (strtolower($row['name']) === 'roblox') {
                $logo = 'images/roblox.png';
            }

            echo "
                <div class='game-card'>
                    <h3>" . htmlspecialchars($row['name']) . "</h3>
                    " . ($logo ? "<img src='$logo' alt='".htmlspecialchars($row['name'])."'>" : "") . "
                    <br>
                    <a class='btn' href='topup.php?id={$row['id']}'>Top Up</a>
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
