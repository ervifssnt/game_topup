<?php
session_start();
include 'db.php';

// Get game id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No game selected.");
}
$game_id = intval($_GET['id']);

// Fetch game
$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

if (!$game) {
    die("Game not found.");
}

// Fetch top-up options
$stmt = $conn->prepare("SELECT * FROM topup_options WHERE game_id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$options = $stmt->get_result();

// Set currency label
$currency = ($game['name'] == "Mobile Legends") ? "Diamonds" : (($game['name'] == "Roblox") ? "Robux" : "Coins");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Top Up - <?php echo htmlspecialchars($game['name']); ?></title>
</head>
<body>
    <h2>Top Up for <?php echo htmlspecialchars($game['name']); ?></h2>

    <?php if ($options->num_rows > 0): ?>
        <form method="post" action="checkout.php">
            <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">

            <label for="account_id">Enter your Account ID:</label><br>
            <input type="text" name="account_id" required><br><br>

            <label for="topup_option">Choose Top Up:</label><br>
            <select name="topup_option_id" required>
                <?php while ($row = $options->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo $row['amount'] . " " . $currency . " - Rp" . number_format($row['price']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br><br>

            <button type="submit">Proceed to Checkout</button>
        </form>
    <?php else: ?>
        <p>No top-up options available for this game.</p>
    <?php endif; ?>
</body>
</html>
