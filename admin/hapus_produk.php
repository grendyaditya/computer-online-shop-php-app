<?php 
    require '../koneksi.php';

    // Periksa apakah parameter id_produk sudah diberikan
    if(isset($_GET['id_produk'])) {
        $id_produk = $_GET['id_produk'];

        // Ambil data produk berdasarkan id_produk
        $query_produk = mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$id_produk'");
        
        // Periksa apakah data produk ditemukan
        if($query_produk && mysqli_num_rows($query_produk) > 0) {
            $data = mysqli_fetch_assoc($query_produk);
            $nama_produk = $data['nama_produk'];

            // Panggil fungsi hapusProduk dengan id_produk yang diberikan
            if(hapusProduk($id_produk)) {
                // Jika penghapusan berhasil, set pesan berhasil
                $_SESSION['pesan'] = "Produk berhasil dihapus.";
            } else {
                // Jika penghapusan gagal, set pesan gagal
                $_SESSION['pesan'] = "Gagal menghapus produk.";
            }
        } else {
            // Jika data produk tidak ditemukan, set pesan gagal
            $_SESSION['pesan'] = "Data produk tidak ditemukan.";
        }
    } else {
        // Jika id_produk tidak diberikan, set pesan gagal
        $_SESSION['pesan'] = "ID produk tidak ditemukan.";
    }

    // Redirect kembali ke halaman sebelumnya atau halaman lain
    header("Location: produk.php");
?>
