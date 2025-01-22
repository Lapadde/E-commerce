<?php
session_start();
// Pastikan user adalah UMKM
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'umkm') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Mengambil data produk yang dikelola oleh UMKM
$stmt = $pdo->prepare('SELECT * FROM products WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$products = $stmt->fetchAll();

// Logika untuk pencarian produk
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search_term'];
    $stmt = $pdo->prepare('SELECT * FROM products WHERE user_id = ? AND name LIKE ?');
    $stmt->execute([$_SESSION['user_id'], '%' . $searchTerm . '%']);
    $products = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman UMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        #sidebar {
            background-color: #f8f9fa;
            height: 100vh;
            padding: 15px;
        }
        #sidebar .nav-link {
            color: #495057;
        }
        #sidebar .nav-link:hover {
            background-color: #e9ecef;
            border-radius: 5px;
        }
        #sidebar h4 {
            margin-bottom: 20px;
        }

        /* CSS untuk menyembunyikan tampilan dropdown pada perangkat besar (desktop) */
        @media (min-width: 768px) {
            #dropdownMenuButton {
                display: none; /* Sembunyikan tombol dropdown di layar lebar */
            }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar untuk Desktop -->
    <div id="sidebar" class="border-right">
        <h4>Menu UMKM</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="umkm.php"><i class="fas fa-home"></i> Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="tpkshop.php"><i class="fas fa-tachometer-alt"></i> Toko</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="view_products.php"><i class="fas fa-box"></i> Produk</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php"><i class="fas fa-comments"></i> Pesan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="activity.php"><i class="fas fa-list-alt"></i> Aktivitas Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </div>

    <div class="flex-grow-1 p-4">
        <!-- Dropdown untuk tampilan mobile -->
        <div class="dropdown mb-3">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Menu
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="umkm.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a class="dropdown-item" href="tpkshop.php"><i class="fas fa-tachometer-alt"></i> Toko</a></li>
                <!-- <li><a class="dropdown-item" href="umkm.php"><i class="fas fa-box"></i> Produk</a></li> -->
                <li><a class="dropdown-item" href="pesanan.php"><i class="fas fa-comments"></i> Pesan</a></li>
                <li><a class="dropdown-item" href="activity.php"><i class="fas fa-list-alt"></i> Aktivitas Pesanan</a></li>
                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <h2>Selamat datang di Halaman UMKM</h2>
        <p>Ini adalah halaman untuk pengelola produk.</p>

        <!-- Fitur pencarian produk -->
        <form method="post" action="" class="mb-4">
            <input type="text" name="search_term" class="form-control" placeholder="Cari produk..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" name="search" class="btn btn-primary mt-2">Cari</button>
        </form>

        <h3>Daftar Produk Anda</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo number_format($product['price'], 2); ?> IDR</td>
                            <td><img src="../uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="width: 50px; height: auto;"></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada produk yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <p><a href="add_product.php" class="btn btn-success">Tambah Produk</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
