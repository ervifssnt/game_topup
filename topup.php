<?php
include 'db.php';
$game_id = $_GET['id'];
$game = $conn->query("SELECT * FROM games WHERE id=$game_id")->fetch_assoc();
$options = $conn->query("SELECT * FROM topup_options WHERE game_id=$game_id");
?>

<h2>Top-Up for <?= $game['name'] ?></h2>
<form action="checkout.php" method="POST">
    <input type="hidden" name="game_id" value="<?= $game_id ?>">
    Your Game Account ID: <input type="text" name="account_id" required><br><br>

    <label>Select Package:</label><br>
    <?php while($row = $options->fetch_assoc()): ?>
        <input type="radio" name="topup_option_id" value="<?= $row['id'] ?>" required>
        <?= $row['amount'] ?> Diamonds — $<?= $row['price'] ?><br>
    <?php endwhile; ?>

    <br><button type="submit">Proceed to Checkout</button>
</form>