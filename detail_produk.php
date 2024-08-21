<?php
include_once "koneksi.php";

// Mengambil nama produk dari URL
$product_name = isset($_GET['item']) ? $_GET['item'] : '';

if (!empty($product_name)) {
    // Menyiapkan query untuk mengambil detail produk berdasarkan nama_produk
    $query = "SELECT p.*, k.nama_kategori AS kategori 
    FROM tb_produk p 
    INNER JOIN tb_kategori k ON p.id_kategori = k.id_kategori 
    WHERE p.nama_produk = ?";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 's', $product_name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        die("Produk tidak ditemukan.");
    }

    mysqli_stmt_close($stmt);
} else {
    die("Nama produk tidak valid.");
}

?>


<!-- Sertakan template produk -->
<?php include "Template/_products.php"; ?>

<!-- JavaScript Opsional -->
<!-- jQuery pertama, lalu Popper.js, lalu Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT3BGzJu0gDgJS5vAnm6RYIVOpV49jFOeRTuWTvdE0H8F9CZ4F" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-Ksvt7BlTKrFcC1mFp3tPRgdt2n04fjNa25o+QQfIUPA9E+s0hEM+lHD1aQ5dPt4X" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"></script>
