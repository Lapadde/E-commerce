<?php
session_start();
include '../includes/db.php'; // Menghubungkan ke database

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Memeriksa peran pengguna
$isUmkm = $_SESSION['role'] === 'umkm';

// Mengambil data berdasarkan peran
if ($isUmkm) {
    // Jika pengguna adalah UMKM, ambil semua produk yang di-upload
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT * FROM products WHERE user_id = ?');
    $stmt->execute([$userId]);
    $products = $stmt->fetchAll();
} else {
    // Jika pengguna adalah User, ambil semua pesanan mereka
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT orders.*, products.name AS product_name, products.image AS product_image FROM orders 
                            JOIN products ON orders.product_id = products.id 
                            WHERE orders.user_id = ?');
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan</title>
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
            <li><a class="flex items-center p-2 hover:bg-gray-200" href="tpkshop.php"><i class="fas fa-shopping-cart w-4 h-4 mr-2"></i> Toko</a></li>
            <!-- <li><a class="flex items-center p-2 hover:bg-gray-200" href="view_products.php"><i class="fas fa-box w-4 h-4 mr-2"></i> Produk</a></li> -->
            <li><a class="flex items-center p-2 hover:bg-gray-200" href="pesanan.php"><i class="fas fa-comments w-4 h-4 mr-2"></i> Pesanan</a></li>
            <?php if ($isUmkm): ?>
                <li><a class="flex items-center p-2 hover:bg-gray-200" href="activity.php"><i class="fas fa-list-alt w-4 h-4 mr-2"></i> Aktivitas Pesanan</a></li>
            <?php endif; ?>
            <li><a class="flex items-center p-2 hover:bg-gray-200" href="logout.php"><i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Konten Utama -->
    <div class="flex-grow p-5">
        <h1 class="text-2xl font-bold mb-4">Pesanan Anda</h1>

        <?php if ($isUmkm): ?>
            <h2 class="font-semibold mb-4">Produk Anda</h2>
            <ul class="list-disc pl-5 mb-5">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <li class="flex justify-between">
                            <span><?php echo htmlspecialchars($product['name']); ?> - Rp <?php echo number_format($product['price'], 2, ',', '.'); ?></span>
                            <a href="admin.php?delete_product=<?php echo $product['id']; ?>" class="text-red-500">Hapus</a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Tidak ada produk yang telah di-upload.</li>
                <?php endif; ?>
            </ul>
        <?php else: ?>
            <h2 class="font-semibold mb-4">Daftar Pesanan Anda</h2>
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Produk</th>
                        <th class="border px-4 py-2">Tanggal Pesan</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($orders) > 0): ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($order['id']); ?></td>
                            <td class="border px-4 py-2">
                                <img src="../uploads/<?php echo htmlspecialchars($order['product_image']); ?>" alt="<?php echo htmlspecialchars($order['product_name']); ?>" class="w-16 h-16 inline-block object-cover">
                                <?php echo htmlspecialchars($order['product_name']); ?>
                            </td>
                            <td class="border px-4 py-2"><?php echo date('d-m-Y H:i', strtotime($order['order_date'])); ?></td>
                            <td class="border px-4 py-2">
                                <span class="text-yellow-500"><?php echo htmlspecialchars(ucfirst($order['status'])); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center border px-4 py-2">Tidak ada pesanan saat ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
