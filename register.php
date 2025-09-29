<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($name === '' || $phone === '' || $password === '' || $confirm === '') {
        $error = "Semua kolom wajib diisi.";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $error = "Nomor telepon harus terdiri dari 10–15 digit.";
    } elseif ($password !== $confirm) {
        $error = "Password tidak sama.";
    } else {
        try {
            $passwordHash = password_hash($password, PASSWORD_ARGON2ID);

            $stmt = $conn->prepare("INSERT INTO users (username, phone, password_hash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $phone, $passwordHash);
            $stmt->execute();
            $stmt->close();

            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['username'] = $name;

            header("Location: index.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                $error = "Nomor telepon sudah terdaftar.";
            } else {
                $error = "Registrasi gagal. Silakan coba lagi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register Member Page</title>
    <style>
        body {
            margin: 0;
            background-color: #1a1a1a;
            font-family: Arial, sans-serif;
            color: white;
        }
       .topbar {
        background: #2b2b2b;
        height: 100px;           /* increase height */
        display: flex;
        align-items: center;
        padding: 0 40px;         /* more left/right spacing */
        }

        .topbar img {
        height: 60px;            /* bigger logo */
        margin-right: 12px;
        }

        .topbar span {
        font-weight: bold;
        font-size: 28px;         /* bigger text next to logo */
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 80px;
        }
        h2 {
            font-size: 36px;
            margin-bottom: 40px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 350px;
        }
        input {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            text-align: center;
            background-color: #d9d9d9;
            color: #000;
        }
        button {
            width: 100%;
            padding: 14px;
            background-color: #444;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 15px;
            cursor: pointer;
        }
        button:hover {
            background-color: #666;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="topbar">
        <img src="\game_topup\images\img\logo\logo.png" alt="UP Store Logo"> <!-- ✅ replace with your logo path -->
    </div>

    <!-- Register Form -->
    <div class="container">
        <h2>Register Member</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <input type="text" name="name" placeholder="Masukkan Nama" required>
            <input type="text" name="phone" placeholder="Masukkan Nomor Telepon" required>
            <input type="password" name="password" placeholder="Masukkan Password" required>
            <input type="password" name="confirm" placeholder="Ulangi Password" required>
            <button type="submit">Daftar</button>
        </form>
    </div>
</body>
</html>
