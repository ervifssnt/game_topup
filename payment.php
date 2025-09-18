<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';
$id = $_GET['id'];

$conn->query("UPDATE transactions SET status='paid' WHERE id=$id");
echo "Payment successful for Transaction #$id";