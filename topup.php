<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $accountId = trim($_POST['account_id'] ?? '');
    $topupOptionId = intval($_POST['topup_option_id'] ?? 0);

    if ($accountId === '' || $topupOptionId <= 0) {
        die("Invalid request.");
    }

    // Start DB transaction
    $conn->begin_transaction();

    try {
        // Fetch topup option from DB (trusted values only)
        $stmt = $conn->prepare("SELECT coins, price FROM topup_options WHERE id = ?");
        $stmt->bind_param("i", $topupOptionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $option = $result->fetch_assoc();
        $stmt->close();

        if (!$option) {
            throw new Exception("Invalid top-up option.");
        }

        $coins = $option['coins'];
        $price = $option['price'];

        // Insert transaction securely
        $stmt = $conn->prepare("
            INSERT INTO transactions 
            (user_id, account_id, topup_option_id, status, created_at, coins, price) 
            VALUES (?, ?, ?, 'pending', NOW(), ?, ?)
        ");
        $stmt->bind_param("isiid", $userId, $accountId, $topupOptionId, $coins, $price);
        $stmt->execute();

        // ✅ Grab insert_id before closing or committing
        $transactionId = $conn->insert_id;

        $stmt->close();

        // Commit transaction
        $conn->commit();

        // Redirect to checkout
        header("Location: checkout.php?id=" . $transactionId);
        exit;


    } catch (Exception $e) {
        $conn->rollback();
        error_log("Top-up error: " . $e->getMessage());
        echo "Failed to process top-up.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Top Up</title></head>
<body>
<h2>Top Up</h2>
<form method="POST" action="topup.php">
    Account ID: <input type="text" name="account_id" required><br><br>
    <label for="topup_option_id">Select package:</label>
    <select name="topup_option_id" required>
        <?php
        $res = $conn->query("SELECT id, coins, price FROM topup_options");
        while ($row = $res->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>";
            echo htmlspecialchars($row['coins'] . " Coins - Rp" . number_format($row['price'], 2), ENT_QUOTES, 'UTF-8');
            echo "</option>";
        }
        ?>
    </select><br><br>
    <button type="submit">Proceed to Checkout</button>
</form>
</body>
</html>
