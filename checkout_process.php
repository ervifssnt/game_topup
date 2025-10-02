<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in.");
}

$userId = $_SESSION['user_id'];
$transactionId = intval($_POST['transaction_id'] ?? 0);

$conn->begin_transaction();

try {
    // Lock row for update
    $stmt = $conn->prepare("SELECT price, status FROM transactions WHERE id = ? AND user_id = ? FOR UPDATE");
    $stmt->bind_param("ii", $transactionId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaction = $result->fetch_assoc();
    $stmt->close();

    if (!$transaction) {
        throw new Exception("Transaction not found.");
    }
    if ($transaction['status'] !== 'pending') {
        throw new Exception("Transaction already processed.");
    }

    // Get user balance (lock row)
    $stmt = $conn->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user['balance'] < $transaction['price']) {
        throw new Exception("Not enough balance.");
    }

    // Deduct balance
    $newBalance = $user['balance'] - $transaction['price'];
    $stmt = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $stmt->bind_param("di", $newBalance, $userId);
    $stmt->execute();
    $stmt->close();

    // Mark transaction as paid
    $stmt = $conn->prepare("UPDATE transactions SET status = 'paid' WHERE id = ?");
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    echo "Checkout successful! Your new balance is Rp" . number_format($newBalance, 2);
} catch (Exception $e) {
    $conn->rollback();
    die("Checkout failed: " . $e->getMessage());
}
?>
