<?php
session_start();
include 'db.php';

// ✅ Check if transaction ID is passed
if (!isset($_GET['id'])) {
    die("No transaction selected.");
}
$transaction_id = intval($_GET['id']);

// ✅ Fetch transaction details
$stmt = $conn->prepare("
    SELECT t.id, t.account_id, t.status, g.name AS game_name, o.amount, o.price
    FROM transactions t
    JOIN topup_options o ON t.topup_option_id = o.id
    JOIN games g ON o.game_id = g.id
    WHERE t.id = ?
");
$stmt->bind_param("i", $transaction_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Transaction not found.");
}
$transaction = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
</head>
<body>
    <h2>Payment Confirmation</h2>
    <p><strong>Game:</strong> <?= htmlspecialchars($transaction['game_name']); ?></p>
    <p><strong>Account ID:</strong> <?= htmlspecialchars($transaction['account_id']); ?></p>
    <p><strong>Amount:</strong> <?= htmlspecialchars($transaction['amount']); ?></p>
    <p><strong>Price:</strong> Rp <?= number_format($transaction['price'], 0, ',', '.'); ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($transaction['status']); ?></p>

    <form method="post" action="confirm_payment.php">
        <input type="hidden" name="transaction_id" value="<?= $transaction['id']; ?>">
        <button type="submit">Confirm Payment</button>
    </form>
</body>
</html>
