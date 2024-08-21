<?php
include "koneksi.php";

// Fungsi untuk membuat nama_kategori URL-friendly
function urlFriendly($string) {
    // Ubah menjadi huruf kecil
    $string = strtolower($string);
    // Ganti spasi dengan tanda hubung
    $string = str_replace(' ', '-', $string);
    // Hapus karakter khusus
    $string = preg_replace('/[^a-z0-9\-]/', '', $string);
    // Hapus tanda hubung ganda
    $string = preg_replace('/-+/', '-', $string);
    // Hapus tanda hubung di awal dan akhir
    $string = trim($string, '-');
    return $string;
}

// Tangkap parameter nama_kategori dari URL
$nama_kategori_url = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$produk_dalam_kategori = ''; // Initialize variable to hold product display

if (!empty($nama_kategori_url)) {
    // Menyiapkan query untuk mengambil detail kategori berdasarkan nama_kategori
    $query_kategori = "SELECT * FROM tb_kategori WHERE LOWER(REPLACE(nama_kategori, ' ', '-')) = ?";
    $stmt_kategori = mysqli_prepare($koneksi, $query_kategori);
    if ($stmt_kategori) {
        mysqli_stmt_bind_param($stmt_kategori, 's', $nama_kategori_url);
        mysqli_stmt_execute($stmt_kategori);
        $result_kategori = mysqli_stmt_get_result($stmt_kategori);

        if ($result_kategori && mysqli_num_rows($result_kategori) > 0) {
            $kategori = mysqli_fetch_assoc($result_kategori);
            $kategori_nama = htmlspecialchars($kategori['nama_kategori']);
            $kategori_id = htmlspecialchars($kategori['id_kategori']);

            // Query untuk mengambil produk sesuai dengan kategorinya
            $query_produk = "SELECT * FROM tb_produk WHERE id_kategori = ?";
            $stmt_produk = mysqli_prepare($koneksi, $query_produk);
            if ($stmt_produk) {
                mysqli_stmt_bind_param($stmt_produk, 'i', $kategori_id);
                mysqli_stmt_execute($stmt_produk);
                $result_produk = mysqli_stmt_get_result($stmt_produk);

                if ($result_produk && mysqli_num_rows($result_produk) > 0) {
                    // Tampilkan produk sesuai dengan kategorinya
                    while ($produk = mysqli_fetch_assoc($result_produk)) {
                        $produk_nama = htmlspecialchars($produk['nama_produk']);
                        $produk_harga = htmlspecialchars($produk['harga']);
                        $produk_id = htmlspecialchars($produk['id_produk']);
                        $produk_gambar = htmlspecialchars($produk['gambar_produk']);

                        $produk_dalam_kategori .= <<<HTML
                        <div class="d-flex Brand$kategori_id">
                            <div class="item d-flex align-items-center card py-2 card-item" style="width: 200px;">
                                <div class="product font-rale">
                                    <a href="detail_produk.php?item=$produk_nama"><img src="assets/img/products/$produk_gambar" alt="$produk_nama" class="img-fluid"></a>
                                    <div class="text-center">
                                        <h6>$produk_nama</h6>
                                        <div class="rating text-warning font-size-12">
                                            <span><i class="fas fa-star"></i></span>
                                            <span><i class="fas fa-star"></i></span>
                                            <span><i class="fas fa-star"></i></span>
                                            <span><i class="fas fa-star"></i></span>
                                            <span><i class="far fa-star"></i></span>
                                        </div>
                                        <div class="price py-2">
                                            <span>Rp. $produk_harga</span>
                                        </div>
                                        <button class="btn btn-warning font-size-12">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        HTML;
                    }
                } else {
                    // Tampilkan pesan jika tidak ada produk dalam kategori tersebut
                    $produk_dalam_kategori = "<p>Belum ada produk dalam kategori ini.</p>";
                }

                mysqli_stmt_close($stmt_produk);
            } else {
                $produk_dalam_kategori = "<p>Terjadi kesalahan pada query produk.</p>";
            }
        } else {
            $produk_dalam_kategori = "<p>Kategori tidak ditemukan.</p>";
        }

        mysqli_stmt_close($stmt_kategori);
    } else {
        $produk_dalam_kategori = "<p>Terjadi kesalahan pada query kategori.</p>";
    }
} else {
    $produk_dalam_kategori = "<p>Nama kategori tidak valid.</p>";
}
?>

<?php ob_start(); ?>
<?php include 'header.php'; ?>
<section class="py-4">
<style>
        .card-item {
            width: 100%;
            max-width: 250px; /* Atur lebar maksimal */
            min-height: 350px; /* Atur tinggi minimal */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease; /* Menambahkan transisi halus */
        }
        .card-item img {
            max-width: 150px;
            max-height: 150px;
        }
        .card-item:hover {
            transform: scale(1.05); /* Menambahkan efek perbesaran sebesar 5% */
        }
        .card-item .text-center {
            margin-top: auto;
        }
    </style>
    <div class="container py-5">
        <h2 class="font-rubik text-center"><?= htmlspecialchars($kategori_nama) ?></h2>
        <div class="row">
            <?= $produk_dalam_kategori ?>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>
