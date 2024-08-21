<?php
include 'header.php';
session_start();

// Periksa apakah session transaksi tersedia
if (!isset($_SESSION['transaksi'])) {
    echo "Tidak ada transaksi yang ditemukan.";
    exit();
}

// Ambil data transaksi dari session
$transaksi = $_SESSION['transaksi'];

// Lakukan perhitungan total harga atau operasi lainnya sesuai kebutuhan
$total_harga = 0;
foreach ($transaksi['items'] as $item) {
    $total_harga += $item['subtotal'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* CSS kustom (opsional) */
        .countdown {
            font-size: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3 text-center">
                <img src="assets/img/img_properties/qr_code.png" alt="QR Code" class="img-fluid mb-3" width="300px">
                <p>Rekening a.n. Grendy Aditya Pangestu </p>
                <div id="countdown" class="countdown"></div>
            </div>
        </div>
        <h1 class="mt-5">Detail Transaksi</h1>
        <div class="row mt-4">
            <div class="col-md-6">
                <p><strong>Nama Pelanggan:</strong> <?= htmlspecialchars($transaksi['nama_pelanggan']); ?></p>
                <p><strong>Alamat Pelanggan:</strong> <?= htmlspecialchars($transaksi['alamat_pelanggan']); ?></p>
                <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($transaksi['metode_pembayaran']); ?></p>
                <p><strong>Total Harga:</strong> Rp. <?= htmlspecialchars(number_format($total_harga, 2)); ?></p>
            </div>
            <div class="col-md-6">
                <h2>Items</h2>
                <ul class="list-group">
                    <?php foreach ($transaksi['items'] as $item): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($item['nama_produk']); ?> - <?= htmlspecialchars($item['qty']); ?> x Rp. <?= htmlspecialchars(number_format($item['harga'], 2)); ?> = Rp. <?= htmlspecialchars(number_format($item['subtotal'], 2)); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        // Hitung mundur selama 5 detik
        var timeleft = 5;
        var downloadTimer = setInterval(function(){
            if(timeleft <= 0){
                clearInterval(downloadTimer);
                document.getElementById("countdown").innerHTML = "Pembayaran berhasil!";
            } else {
                document.getElementById("countdown").innerHTML = "Segera selesaikan pembayaran anda. Jika anda memilih pembayaran tunai, tunjukkan ini ke kasir !";
            }
            timeleft -= 1;
        }, 1000);
    </script>
</body>
</html>

<?php
include 'footer.php';
?>