<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in.");
}

$userId = $_SESSION['user_id'];
$transactionId = intval($_GET['id'] ?? 0);

// Fetch transaction (must belong to logged in user)
$stmt = $conn->prepare("SELECT t.id, t.coins, t.price, t.status, g.name AS game_name 
                        FROM transactions t 
                        JOIN topup_options o ON t.topup_option_id = o.id 
                        JOIN games g ON o.game_id = g.id
                        WHERE t.id = ? AND t.user_id = ?");
$stmt->bind_param("ii", $transactionId, $userId);
$stmt->execute();
$transaction = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$transaction) {
    die("Transaction not found.");
}

// Fetch user balance
$stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$enough = $user['balance'] >= $transaction['price'];
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Checkout</title></head>
<body>
<h2>Checkout</h2>
<p>Game: <?php echo htmlspecialchars($transaction['game_name']); ?></p>
<p>Package: <?php echo $transaction['coins']; ?> Coins</p>
<p>Price: Rp<?php echo number_format($transaction['price'], 2); ?></p>
<p>Your Balance: Rp<?php echo number_format($user['balance'], 2); ?></p>

<?php if ($transaction['status'] !== 'pending'): ?>
    <p>Status: <?php echo htmlspecialchars($transaction['status']); ?></p>
<?php elseif (!$enough): ?>
    <p style="color:red;">Not enough balance. Please top-up your wallet.</p>
<?php else: ?>
    <form method="POST" action="checkout_process.php">
        <input type="hidden" name="transaction_id" value="<?php echo $transaction['id']; ?>">
        <button type="submit">Confirm Checkout</button>
    </form>
<?php endif; ?>
</body>
</html>
