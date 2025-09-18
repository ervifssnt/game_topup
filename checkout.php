<?php
session_start();
include 'db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['account_id']) || !isset($_POST['topup_option_id'])) {
    die("Invalid request");
}

$user_id = $_SESSION['user_id'];
$account_id = $_POST['account_id'];
$topup_option_id = intval($_POST['topup_option_id']);

// Fetch option details
$stmt = $conn->prepare("SELECT * FROM topup_options WHERE id = ?");
$stmt->bind_param("i", $topup_option_id);
$stmt->execute();
$option = $stmt->get_result()->fetch_assoc();

if (!$option) {
    die("Top-up option not found");
}

$coins = $option['coins'];
$price = $option['price'];

// Insert into transactions
$stmt = $conn->prepare("INSERT INTO transactions (user_id, game_id, account_id, topup_option_id, status, coins) VALUES (?, ?, ?, ?, 'pending', ?)");
$stmt->bind_param("iisii", $user_id, $option['game_id'], $account_id, $topup_option_id, $coins);

if ($stmt->execute()) {
    echo "<h2>Checkout Successful!</h2>";
    echo "<p>You bought {$coins} coins for Rp {$price}.</p>";
    echo "<a href='index.php'>Back to Home</a>";
} else {
    echo "Error: " . $stmt->error;
}
?>
