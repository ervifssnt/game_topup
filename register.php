<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql)) {
        echo "✅ Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<h2>Register</h2>
<form method="POST">
  Username: <input type="text" name="username" required><br>
  Email: <input type="email" name="email" required><br>
  Password: <input type="password" name="password" required><br>
  <button type="submit">Register</button>
</form>
