<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php'; // Menghubungkan ke database

// Mengambil semua pengguna
$stmtUsers = $pdo->prepare('SELECT * FROM users WHERE role IN ("umkm", "user")');
$stmtUsers->execute();
$users = $stmtUsers->fetchAll();

// Mengambil data produk
$stmtProducts = $pdo->prepare('SELECT * FROM products');
$stmtProducts->execute();
$products = $stmtProducts->fetchAll();

// Mengambil data pesanan
$stmtOrders = $pdo->prepare('SELECT orders.*, products.name AS product_name, users.username AS user_name 
                              FROM orders 
                              JOIN products ON orders.product_id = products.id 
                              JOIN users ON orders.user_id = users.id');
$stmtOrders->execute();
$orders = $stmtOrders->fetchAll();

// Proses penghapusan produk
if (isset($_GET['delete_product'])) {
    $productId = $_GET['delete_product'];
    $deleteStmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $deleteStmt->execute([$productId]);
    header("Location: admin.php"); // Redirect setelah hapus
    exit();
}

// Proses penghapusan pengguna
if (isset($_GET['delete_user'])) {
    $userId = $_GET['delete_user'];
    
    // Hapus produk yang terkait dengan pengguna sebelum menghapus pengguna
    $deleteProductsStmt = $pdo->prepare('DELETE FROM products WHERE user_id = ?');
    $deleteProductsStmt->execute([$userId]);
    
    // Hapus pengguna
    $deleteUserStmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $deleteUserStmt->execute([$userId]);
    header("Location: admin.php"); // Redirect setelah hapus
    exit();
}

// Proses penghapusan pesanan
if (isset($_GET['delete_order'])) {
    $orderId = $_GET['delete_order'];
    $deleteOrderStmt = $pdo->prepare('DELETE FROM orders WHERE id = ?');
    $deleteOrderStmt->execute([$orderId]);
    header("Location: admin.php"); // Redirect setelah hapus
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-5">
    <h1 class="text-2xl font-bold mb-4">Selamat datang di Dashboard Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <!-- Box untuk melihat produk -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold">Daftar Produk</h2>
            <ul class="mt-2">
                <?php foreach ($products as $product): ?>
                    <li class="flex justify-between py-1">
                        <span><?php echo $product['name']; ?> - Rp <?php echo number_format($product['price'], 2, ',', '.'); ?></span>
                        <a href="admin.php?delete_product=<?php echo $product['id']; ?>" class="text-red-500">Hapus</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Box untuk melihat pengguna -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold">Daftar Pengguna</h2>
            <ul class="mt-2">
                <?php foreach ($users as $user): ?>
                    <li class="flex justify-between py-1">
                        <span><?php echo $user['username']; ?> (<?php echo $user['role']; ?>)</span>
                        <a href="admin.php?delete_user=<?php echo $user['id']; ?>" class="text-red-500">Hapus</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Box untuk melihat semua pesanan -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold">Pesanan yang Diterima</h2>
            <table class="table-auto w-full mt-2">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Produk</th>
                        <th class="border px-4 py-2">User</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo $order['id']; ?></td>
                            <td class="border px-4 py-2"><?php echo $order['product_name']; ?></td>
                            <td class="border px-4 py-2"><?php echo $order['user_name']; ?></td>
                            <td class="border px-4 py-2"><?php echo $order['status']; ?></td>
                            <td class="border px-4 py-2">
                                <a href="admin.php?delete_order=<?php echo $order['id']; ?>" class="text-red-500">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href='logout.php' class="btn btn-danger">Logout</a>
</div>

</body>
</html>
