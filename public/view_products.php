<?php
session_start();
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
//     header("Location: login.php");
//     exit();
// }

// Include header
include '../includes/header.php';
?>
<h1>Selamat datang di halaman User!</h1>
<a href='logout.php'>Logout</a>
</div>
</body>
</html>
