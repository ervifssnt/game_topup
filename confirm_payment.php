<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['transaction_id'])) {
        echo "No transaction selected.";
        exit;
    }

    $transaction_id = intval($_POST['transaction_id']);
    $user_id = $_SESSION['user_id'];

    // Update transaction to "paid"
    $sql = "UPDATE transactions 
            SET status = 'paid' 
            WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $transaction_id, $user_id);

    if ($stmt->execute()) {
        echo "<h2>Payment Successful ✅</h2>";
        echo "<p>Your transaction #$transaction_id has been marked as <b>paid</b>.</p>";
        echo "<a href='index.php'>Back to Homepage</a>";
    } else {
        echo "Error updating payment.";
    }
} else {
    echo "Invalid request.";
}
?>
