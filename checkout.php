<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = $_POST['game_id'];
    $account_id = $_POST['account_id'];
    $topup_option_id = $_POST['topup_option_id'];

    $user_id = 1;

    $conn->query("INSERT INTO transactions (user_id, game_id, account_id, topup_option_id, status)
                  VALUES ($user_id, $game_id, '$account_id', $topup_option_id, 'pending')");

    $transaction_id = $conn->insert_id;
    echo "<h3>Order Placed! Transaction ID: $transaction_id</h3>";
    echo "<a href='payment.php?id=$transaction_id'>Go to Payment</a>";
}
