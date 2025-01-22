<?php
session_start();
include '../includes/db.php'; // Menghubungkan ke database

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Mengambil semua produk dari database
$searchTerm = "";
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search_term'];
    $stmt = $pdo->prepare('SELECT * FROM products WHERE name LIKE ?');
    $stmt->execute(['%' . $searchTerm . '%']);
} else {
    $stmt = $pdo->prepare('SELECT * FROM products');
    $stmt->execute();
}
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko UMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body class="bg-gray-100">

<div class="flex">
    <!-- Sidebar Menu -->
    <div class="w-64 bg-white border-r shadow-md h-screen">
        <div class="p-4">
            <h2 class="text-lg font-semibold">Menu Toko</h2>
        </div>
        <ul class="flex flex-col">
            <li><a class="flex items-center p-2 hover:bg-gray-200" href="tpkshop.php"><i class="fas fa-home w-4 h-4 mr-2"></i> Home</a></li>
            <?php if ($_SESSION['role'] === 'umkm'): ?>
                <li><a class="flex items-center p-2 hover:bg-gray-200" href="umkm.php"><i class="fas fa-box w-4 h-4 mr-2"></i> Dashboard UMKM</a></li>
            <?php endif; ?>
            <li><a class="flex items-center p-2 hover:bg-gray-200" href="pesanan.php"><i class="fas fa-comments w-4 h-4 mr-2"></i> Pesanan</a></li>
            <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'umkm'): ?>
                <li><a class="flex items-center p-2 hover:bg-gray-200" href="activity.php"><i class="fas fa-list-alt w-4 h-4 mr-2"></i> Aktivitas Pesanan</a></li>
            <?php endif; ?>
            <li><a class="flex items-center p-2 hover:bg-gray-200" href="logout.php"><i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Konten Utama -->
    <div class="flex-grow p-5">
        <h2 class="text-2xl font-bold mb-4">Produk Tersedia</h2>

        <!-- Form Pencarian Produk -->
        <form method="post" action="" class="mb-4">
            <input type="text" name="search_term" placeholder="Cari produk..." class="border p-2 rounded w-full">
            <button type="submit" name="search" class="mt-2 bg-blue-500 text-white p-2 rounded w-full">Cari</button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="bg-white border rounded-lg shadow-md overflow-hidden flex flex-col">
                        <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-40 object-cover">
                        <div class="p-4 flex-grow">
                            <h3 class="font-semibold"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="text-gray-800">Harga: Rp <?php echo number_format($product['price'], 2, ',', '.'); ?></p>
                            <p class="text-gray-600"><?php echo htmlspecialchars($product['description']); ?></p>
                        </div>
                        <div class="p-4">
                            <form method="post" action="purchase.php">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Beli</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-600">Tidak ada produk yang ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
