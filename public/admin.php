<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php'; // Connect to the database

// Fetch all users
$stmtUsers = $pdo->prepare('SELECT * FROM users WHERE role IN ("umkm", "user")');
$stmtUsers->execute();
$users = $stmtUsers->fetchAll();

// Fetch product data
$stmtProducts = $pdo->prepare('SELECT * FROM products');
$stmtProducts->execute();
$products = $stmtProducts->fetchAll();

// Fetch order data
$stmtOrders = $pdo->prepare('SELECT orders.*, products.name AS product_name, users.username AS user_name 
                              FROM orders 
                              JOIN products ON orders.product_id = products.id 
                              JOIN users ON orders.user_id = users.id');
$stmtOrders->execute();
$orders = $stmtOrders->fetchAll();

// Process product deletion
if (isset($_GET['delete_product'])) {
    $productId = $_GET['delete_product'];
    $deleteStmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $deleteStmt->execute([$productId]);
    header("Location: admin.php"); // Redirect after deletion
    exit();
}

// Process user deletion
if (isset($_GET['delete_user'])) {
    $userId = $_GET['delete_user'];
    
    // Delete products associated with the user before deleting the user
    $deleteProductsStmt = $pdo->prepare('DELETE FROM products WHERE user_id = ?');
    $deleteProductsStmt->execute([$userId]);
    
    // Delete user
    $deleteUserStmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $deleteUserStmt->execute([$userId]);
    header("Location: admin.php"); // Redirect after deletion
    exit();
}

// Process order deletion
if (isset($_GET['delete_order'])) {
    $orderId = $_GET['delete_order'];
    $deleteOrderStmt = $pdo->prepare('DELETE FROM orders WHERE id = ?');
    $deleteOrderStmt->execute([$orderId]);
    header("Location: admin.php"); // Redirect after deletion
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">

<div class="flex">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-md h-screen">
        <div class="p-4">
            <h2 class="text-lg font-bold">Admin Dashboard</h2>
        </div>
        <ul class="mt-6">
            <li class="px-4 py-2 hover:bg-gray-200">
                <a href="admin.php" class="flex items-center text-gray-700">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
            </li>
            <li class="px-4 py-2 hover:bg-gray-200">
                <a href="add_admin.php" class="flex items-center text-gray-700">
                    <i class="fas fa-user-plus mr-2"></i> Tambah Admin
                </a>
            </li>
            <li class="px-4 py-2 hover:bg-gray-200">
                <a href="logout.php" class="flex items-center text-gray-700">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-5">
        <h1 class="text-3xl font-bold mb-6 text-center">Dashboard Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Box for viewing products -->
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h2 class="font-semibold text-xl mb-4">Daftar Produk</h2>
                <ul class="mt-2">
                    <?php foreach ($products as $product): ?>
                        <li class="flex justify-between items-center py-2 border-b">
                            <div class="flex items-center">
                                <i class="fas fa-box text-blue-500 mr-2"></i>
                                <span><?php echo $product['name']; ?> - Rp <?php echo number_format($product['price'], 2, ',', '.'); ?></span>
                            </div>
                            <a href="#" onclick="confirmDelete('admin.php?delete_product=<?php echo $product['id']; ?>')" class="text-red-500 hover:text-red-700 transition">Hapus</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Box for viewing users -->
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h2 class="font-semibold text-xl mb-4">Daftar Pengguna</h2>
                <ul class="mt-2">
                    <?php foreach ($users as $user): ?>
                        <li class="flex justify-between items-center py-2 border-b">
                            <div class="flex items-center">
                                <i class="fas fa-user text-green-500 mr-2"></i>
                                <span><?php echo $user['username']; ?> (<?php echo $user['role']; ?>)</span>
                            </div>
                            <a href="#" onclick="confirmDelete('admin.php?delete_user=<?php echo $user['id']; ?>')" class="text-red-500 hover:text-red-700 transition">Hapus</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Box for viewing all orders -->
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h2 class="font-semibold text-xl mb-4">Pesanan yang Diterima</h2>
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
                                    <a href="#" onclick="confirmDelete('admin.php?delete_order=<?php echo $order['id']; ?>')" class="text-red-500 hover:text-red-700 transition">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(url) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda tidak dapat mengembalikan ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>

</body>
</html>
