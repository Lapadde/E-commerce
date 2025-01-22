<?php
session_start();
include '../includes/db.php'; // Menghubungkan ke database

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'umkm' && $_SESSION['role'] !== 'admin')) {
    header("Location: login.php");
    exit();
}

$stmtOrders = $pdo->prepare('SELECT orders.*, products.name AS product_name, users.username AS user_name 
                              FROM orders 
                              JOIN products ON orders.product_id = products.id 
                              JOIN users ON orders.user_id = users.id');
$stmtOrders->execute();
$orders = $stmtOrders->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivitas Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body class="bg-gray-100">

<div class="flex">
    <!-- Sidebar Menu -->
    <div class="w-64 bg-white border-r shadow-md h-screen">
        <div class="p-4">
            <h2 class="text-lg font-semibold">Menu Dashboard</h2>
        </div>
        <ul class="flex flex-col">
            <li><a class="flex items-center p-2 hover:bg-gray-200" href="umkm.php"><i class="fas fa-home w-4 h-4 mr-2"></i> Home</a></li>
            <li><a class="flex items-center p-2 hover:bg-gray-200" href="tpkshop.php"><i class="fas fa-tachometer-alt w-4 h-4 mr-2"></i> Toko</a></li>
            <!-- <li><a class="flex items-center p-2 hover:bg-gray-200" href="view_products.php"><i class="fas fa-box w-4 h-4 mr-2"></i> Produk</a></li> -->
            <li><a class="flex items-center p-2 hover:bg-gray-200" href="pesanan.php"><i class="fas fa-comments w-4 h-4 mr-2"></i> Pesanan</a></li>
            <li><a class="flex items-center p-2 hover:bg-gray-200" href="activity.php"><i class="fas fa-list-alt w-4 h-4 mr-2"></i> Aktivitas Pesanan</a></li>
            <li><a class="flex items-center p-2 hover:bg-gray-200" href="logout.php"><i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Konten Utama -->
    <div class="flex-grow p-5">
        <h1 class="text-2xl font-bold mb-4">Aktivitas Pesanan</h1>

        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-200">
                    <!-- <th class="border px-4 py-2">ID</th> -->
                    <th class="border px-4 py-2">Produk</th>
                    <th class="border px-4 py-2">Pengguna</th>
                    <th class="border px-4 py-2">Tanggal</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <!-- <td class="border px-4 py-2"><?php echo $order['id']; ?></td> -->
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($order['user_name']); ?></td>
                            <td class="border px-4 py-2"><?php echo date('d-m-Y H:i', strtotime($order['order_date'])); ?></td>
                            <td class="border px-4 py-2">
                                <span class="text-yellow-500"><?php echo htmlspecialchars($order['status']); ?></span>
                            </td>
                            <td class="border px-4 py-2">
                                <a href="admin.php?delete_order=<?php echo $order['id']; ?>" class="text-red-500">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center border px-4 py-2">Tidak ada pesanan saat ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- <p class="mt-4"><a href='logout.php' class="bg-red-500 text-white px-4 py-2 rounded">Logout</a></p> -->
    </div>
</div>

</body>
</html>
