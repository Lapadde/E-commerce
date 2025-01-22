<?php
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validasi data yang diterima dari halaman pemesanan
if (!isset($_GET['product_id']) || !isset($_GET['quantity'])) {
    die("Data pemesanan tidak valid.");
}

$productId = $_GET['product_id']; // Ambil product_id dari query
$quantity = $_GET['quantity']; // Ambil jumlah dari query

// Ambil detail produk dari database
include '../includes/db.php'; // Menghubungkan ke database
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    die("Produk tidak ditemukan.");
}

// Hitung total harga
$totalAmount = $product['price'] * $quantity; // Total harga berdasarkan jumlah produk
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Style untuk gambar QRIS agar responsif */
        .qris-image {
            max-width: 100%; /* Maksimum lebar 100% dari kontainer */
            height: auto; /* Tinggi otomatis sesuai rasio */
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-5">
    <h2 class="text-2xl font-bold mb-4">Metode Pembayaran</h2>
    
    <h3 class="font-semibold mb-2">Total Pembayaran: Rp <?php echo number_format($totalAmount, 2, ',', '.'); ?></h3>

    <!-- List of payment methods -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white border rounded-lg shadow-md p-4 text-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/512px-Logo_dana_blue.svg.png" alt="DANA" class="w-20 mx-auto mb-2">
            <!-- <h4 class="font-bold">DANA</h4> -->
            <!-- <p>No. Telepon: 0812-3456-7890</p> -->
            <button onclick="showAccountInfo('dana')" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Pilih</button>
        </div>
        <div class="bg-white border rounded-lg shadow-md p-4 text-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/512px-Logo_ovo_purple.svg.png" alt="OVO" class="w-20 mx-auto mb-2">
            <!-- <h4 class="font-bold">OVO</h4>
            <p>No. Telepon: 0812-3456-7891</p> -->
            <button onclick="showAccountInfo('ovo')" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Pilih</button>
        </div>
        <div class="bg-white border rounded-lg shadow-md p-4 text-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/512px-Logo_ovo_purple.svg.png" alt="GoPay" class="w-20 mx-auto mb-2">
            <!-- <h4 class="font-bold">GoPay</h4>
            <p>No. Telepon: 0812-3456-7892</p> -->
            <button onclick="showAccountInfo('gopay')" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Pilih</button>
        </div>
        <div class="bg-white border rounded-lg shadow-md p-4 text-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BRI_2020.svg/800px-BRI_2020.svg.png" alt="BRI" class="w-20 mx-auto mb-2">
            <!-- <h4 class="font-bold">BRI</h4>
            <p>No. Rekening: 123-456-7890</p> -->
            <button onclick="showAccountInfo('bri')" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Pilih</button>
        </div>
        <div class="bg-white border rounded-lg shadow-md p-4 text-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/213px-Bank_Mandiri_logo_2016.svg.png?20211228163717" alt="Mandiri" class="w-20 mx-auto mb-2">
            <!-- <h4 class="font-bold">Mandiri</h4>
            <p>No. Rekening: 987-654-3210</p> -->
            <button onclick="showAccountInfo('mandiri')" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Pilih</button>
        </div>
        <div class="bg-white border rounded-lg shadow-md p-4 text-center">
            <img src="../assets/img/qris.jpg" alt="QRIS" class="w-32 h-32 mx-auto mb-2">
            <!-- <h4 class="font-bold">QRIS</h4>
            <p>Pindai QR Code</p> -->
            <button onclick="showAccountInfo('qris')" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Pilih</button>
        </div>
    </div>
    
    <div id="accountInfo" class="mt-5"></div>
</div>

<script>
    function showAccountInfo(method) {
        const accountInfoDiv = document.getElementById('accountInfo');
        let message = "";

        switch (method) {
            case 'dana':
                message = "Transfer ke nomor Dana 0812-4281-8675";
                break;
            case 'ovo':
                message = "Transfer ke nomor telepon 0812-3456-7891";
                break;
            case 'gopay':
                message = "Transfer ke nomor telepon 0812-3456-7892";
                break;
            case 'bri':
                message = "Transfer ke no rekening 123-456-7890 BRI";
                break;
            case 'mandiri':
                message = "Transfer ke no rekening 987-654-3210 Mandiri";
                break;
            case 'qris':
                message = "Silakan pindai QR code di bawah untuk melakukan pembayaran.";
                // Pengaturan lebih lanjut untuk menampilkan gambar besar QRIS
                accountInfoDiv.innerHTML = `<div class="bg-white p-4 border rounded">Silakan pindai QR code di atas untuk melakukan pembayaran.</div>
                                             <img src="../assets/img/qris.jpg" alt="QRIS" class="w-full h-auto mt-4">`;
                break;
            default:
                message = "Pilih metode pembayaran yang sesuai.";
        }

        if (method !== 'qris') {
            accountInfoDiv.innerHTML = `<div class="bg-white p-4 border rounded">${message}</div>`;
        }

        // Hide all payment method buttons after selection
        const paymentButtons = document.querySelectorAll(".bg-white.border.rounded-lg");
        paymentButtons.forEach(button => {
            button.style.display = 'none'; // Hide all buttons
        });

        // Show back button to allow user to select another method
        const backButton = document.createElement('button');
        backButton.innerText = "Kembali ke Pilihan Pembayaran";
        backButton.className = "mt-4 bg-gray-300 text-black p-2 rounded";
        backButton.onclick = () => {
            // Show buttons again and clear account info
            accountInfoDiv.innerHTML = ""; // Clear account info
            paymentButtons.forEach(button => {
                button.style.display = 'block'; // Show buttons
            });
            backButton.remove(); // Remove back button
        };

        accountInfoDiv.appendChild(backButton); // Add back button to account info div
    }
</script>

</body>
</html>
