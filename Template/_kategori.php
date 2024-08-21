<?php
include "koneksi.php";

$query = "SELECT * FROM tb_kategori";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error dalam query: " . mysqli_error($koneksi));
}

// Menyimpan hasil query dalam array
$categories = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

// Fungsi untuk membuat nama_kategori URL-friendly
function urlFriendly($string)
{
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
?>

<section class="col-12 container my-2 mb-5" id="kategori">

    <div class="container card shadow py-3">
        <h4 class="font-rubik bg-primary w-25 two-values py-2 px-5 text-white"
            style="border-radius: 2px 40px ; text-indent:4px;">Kategori</h4>
        <div class="row mx-auto gy-2 mt-3">
                <div class="col-2 d-flex flex-column">
                    <a href="produk.php" class="m-auto">
                        <img src="assets/img/kategori/all.png" width="80"
                            height="80" alt="All Category">
                    </a>
                    <p class="text-center text-decoration-none">All Category</p>
                </div>
            <?php foreach ($categories as $category): ?>
                <?php $kategori_url = urlFriendly($category['nama_kategori']); ?>
                <div class="col-2 d-flex flex-column">
                    <a href="detail_kategori.php?kategori=<?= htmlspecialchars($kategori_url) ?>" class="m-auto">
                        <img src="assets/img/kategori/<?= htmlspecialchars($category['gambar_kategori']) ?>" width="80"
                            height="80" alt="<?= htmlspecialchars($category['nama_kategori']) ?>">
                    </a>
                    <p class="text-center text-decoration-none"><?= htmlspecialchars($category['nama_kategori']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>