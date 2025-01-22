<?php
session_start();
include '../includes/db.php'; // Menghubungkan ke database

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Mengambil detail produk berdasarkan product_id
if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Mengambil produk dari database
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    // Jika produk tidak ditemukan
    if (!$product) {
        die("Produk tidak ditemukan.");
    }
} else {
    // Jika product_id tidak set, tampilkan pesan error
    die("Data pemesanan tidak valid.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_purchase'])) {
    $quantity = $_POST['quantity']; // Mengambil jumlah dari form
    $size = isset($_POST['size']) ? $_POST['size'] : null; // Mengambil ukuran dari form
    $userId = $_SESSION['user_id'];

    try {
        // Menyimpan pemesanan ke dalam database
        $stmtOrder = $pdo->prepare('INSERT INTO orders (product_id, user_id, order_date, status, size, quantity) VALUES (?, ?, NOW(), ?, ?, ?)');
        $stmtOrder->execute([$productId, $userId, 'pending', $size, $quantity]);

        // Redirect ke halaman pembayaran
        header("Location: payment.php?product_id={$productId}&quantity={$quantity}");
        exit();
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage(); // Menangani error jika terjadi
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-5">
    <h2 class="text-2xl font-bold mb-4">Pembelian Produk</h2>

    <div class="bg-white p-6 rounded shadow-md">
        <h3 class="font-semibold text-lg"><?php echo htmlspecialchars($product['name']); ?></h3>
        <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-auto max-h-60 object-contain mb-4">
        <p class="text-gray-800">Harga: Rp <?php echo number_format($product['price'], 2, ',', '.'); ?></p>
        <p class="text-gray-600"><?php echo htmlspecialchars($product['description']); ?></p>

        <form method="post" action="">
            <div class="mt-4">
                <label for="quantity" class="block text-gray-700">Jumlah:</label>
                <input type="number" name="quantity" id="quantity" min="1" value="1" class="mt-1 block w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mt-4" id="sizeInput" style="display: <?php echo ($product['category_id'] == 1) ? 'block' : 'none'; ?>;">
                <label for="size" class="block text-gray-700">Pilih Ukuran (jika berlaku):</label>
                <select name="size" id="size" class="mt-1 block w-full p-2 border border-gray-300 rounded">
                    <option value="">-- Pilih Ukuran --</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                </select>
            </div>
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <button type="submit" name="confirm_purchase" class="mt-4 w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Konfirmasi Pembelian</button>
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
