<?php
session_start();
include '../includes/db.php'; // Menghubungkan ke database

// Cek apakah user adalah UMKM
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'umkm') {
    header("Location: login.php");
    exit();
}

// Mengambil detail produk berdasarkan product_id
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Mengambil produk dari database
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    // Jika produk tidak ditemukan
    if (!$product) {
        die("Produk tidak ditemukan.");
    }
} else {
    // Jika product_id tidak diatur, redirect
    header("Location: umkm.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productDescription = $_POST['product_description'];
    $category = $_POST['category']; // Mengambil kategori dari form
    $imagePath = $product['image']; // Menyimpan nama gambar asli untuk update

    // Proses upload gambar jika ada
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/'; // Folder tujuan untuk menyimpan gambar
        $uploadFile = $uploadDir . basename($_FILES['product_image']['name']);

        // Memindahkan file yang diterima ke folder penyimpanan
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
            $imagePath = basename($_FILES['product_image']['name']); // Mengupdate nama file gambar
        } else {
            $error = "Gagal meng-upload gambar.";
        }
    }

    try {
        // Memperbarui produk dalam database
        $stmt = $pdo->prepare('UPDATE products SET name = ?, price = ?, description = ?, image = ?, category_id = ? WHERE id = ?');
        $stmt->execute([$productName, $productPrice, $productDescription, $imagePath, $category, $productId]);

        $message = "Produk berhasil diperbarui!"; // Pesan sukses
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage(); // Menangani error
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="flex">
    <!-- Sidebar Menu -->
    <div class="w-64 bg-white border-r shadow-md h-screen">
        <div class="p-4">
            <h2 class="text-lg font-semibold">Dashboard UMKM</h2>
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

    <div class="flex-grow p-5">
        <h2 class="text-2xl font-bold mb-4">Edit Produk</h2>

        <form method="post" action="" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="product_name" class="block text-gray-700">Nama Produk:</label>
                <input type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="product_price" class="block text-gray-700">Harga:</label>
                <input type="number" class="mt-1 block w-full p-2 border border-gray-300 rounded" id="product_price" name="product_price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="product_description" class="block text-gray-700">Deskripsi:</label>
                <textarea class="mt-1 block w-full p-2 border border-gray-300 rounded" id="product_description" name="product_description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="mb-4">
                <label for="product_image" class="block text-gray-700">Gambar Produk:</label>
                <input type="file" class="mt-1 block w-full p-2 border border-gray-300 rounded" id="product_image" name="product_image" accept="image/*">
                <p class="text-gray-500 text-sm">Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-gray-700">Pilih Kategori:</label>
                <select name="category" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
                    <option value="pakaian" <?php echo ($product['category_id'] == 1) ? 'selected' : ''; ?>>Pakaian</option>
                    <option value="motor" <?php echo ($product['category_id'] == 2) ? 'selected' : ''; ?>>Motor</option>
                    <option value="mobil" <?php echo ($product['category_id'] == 3) ? 'selected' : ''; ?>>Mobil</option>
                    <option value="elektronik" <?php echo ($product['category_id'] == 4) ? 'selected' : ''; ?>>Elektronik</option>
                    <option value="lainnya" <?php echo ($product['category_id'] == 5) ? 'selected' : ''; ?>>Lainnya</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Perbarui Produk</button>
        </form>

        <?php if (isset($message)): ?>
            <div class="mt-3 p-2 bg-green-200 text-green-800 rounded">
                <?php echo $message; ?>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="mt-3 p-2 bg-red-200 text-red-800 rounded">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
