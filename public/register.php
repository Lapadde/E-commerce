<?php
session_start();
include '../includes/db.php'; // Menghubungkan ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Menentukan peran pengguna
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Meng-hash password

    // Ubah role jika pengguna memilih customer
    if ($role === 'customer') {
        $role = 'user'; // Mengubah role customer menjadi user
    }

    try {
        // Cek apakah username sudah ada di database
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $error = "Username sudah terpakai."; // Jika username sudah ada
        } else {
            // Menyimpan pengguna baru ke dalam database
            $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
            $stmt->execute([$username, $hashedPassword, $role]);

            // Redirect setelah registrasi berhasil
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage(); // Jika terjadi kesalahan
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Registrasi Pengguna</h2>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username Anda" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password Anda" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Pilih Role:</label>
                            <select name="role" class="form-control" required>
                                <option value="customer">Customer</option>
                                <option value="umkm">UMKM</option>
                                <!-- <option value="admin">Admin</option> -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </form>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login di sini</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
