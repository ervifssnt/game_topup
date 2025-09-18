<?php
session_start();
include 'db.php';

// Check if user selected a game
if (!isset($_GET['id'])) {
    echo "No game selected.";
    exit;
}

$game_id = (int) $_GET['id'];

// Fetch the game
$game_result = $conn->query("SELECT * FROM games WHERE id = $game_id");
if ($game_result->num_rows == 0) {
    echo "Game not found.";
    exit;
}
$game = $game_result->fetch_assoc();

// Show game title
echo "<h2>Top Up for {$game['name']}</h2>";

// Fetch top-up options for this game
$options = $conn->query("SELECT * FROM topup_options WHERE game_id = $game_id");

if ($options->num_rows > 0) {
    while ($row = $options->fetch_assoc()) {
        echo "
            <form action='checkout.php' method='POST'>
                <input type='hidden' name='game_id' value='{$game_id}'>
                <input type='hidden' name='topup_option_id' value='{$row['id']}'>
                <p>{$row['coins']} Coins - Rp {$row['price']}</p>
                Account ID: <input type='text' name='account_id' required>
                <button type='submit'>Checkout</button>
            </form>
            <hr>
        ";
    }
} else {
    echo "No top-up options available for this game.";
}
?>
