<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die("You must log in first.");
    }

    $user_id = $_SESSION['user_id'];
    $account_id = $_POST['account_id'] ?? null;
    $topup_option_id = $_POST['topup_option_id'] ?? null;

    if (!$account_id || !$topup_option_id) {
        die("Missing account ID or top-up option.");
    }

    // Insert transaction
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, account_id, topup_option_id, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("isi", $user_id, $account_id, $topup_option_id);

    if ($stmt->execute()) {
        // ✅ Get the new transaction ID
        $transaction_id = $stmt->insert_id;

        // ✅ Redirect to payment page with the transaction id
        header("Location: payment.php?id=" . $transaction_id);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
