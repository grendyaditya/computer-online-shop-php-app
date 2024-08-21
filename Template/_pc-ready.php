<?php
include_once "koneksi.php";
$cart = new Cart($koneksi);

// Menyiapkan query untuk mengambil produk dengan kategori 'PC'
$query = "SELECT tb_produk.*, tb_kategori.nama_kategori
FROM tb_produk  
INNER JOIN tb_kategori ON tb_produk.id_kategori = tb_kategori.id_kategori 
WHERE nama_kategori = 'PC'";

// Menjalankan query
$result = mysqli_query($koneksi, $query);

// Memeriksa apakah query berhasil dijalankan
if (!$result) {
    die("Error dalam query: " . mysqli_error($koneksi));
}

// Menyimpan hasil query dalam array
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

$id_user = 2; // Ganti dengan id_user yang sebenarnya, misalnya dari sesi

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['pc_ready_submit'])) {
        // Menggunakan objek $cart untuk memanggil metode addToCart
        $cart->addToCart($_POST['id_user'], $_POST['id_produk']);
        // Menyimpan informasi produk yang telah ditambahkan ke sesi
        $_SESSION['cart'][$_POST['id_produk']] = true;

        // Redirect ke halaman yang sama untuk menghindari pengiriman ulang form
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}
?>

<!-- Top Sale Section -->
<section id="top-sale" class="container mb-5">
    <div class="container card shadow py-3">
        <h4 class="font-rubik bg-primary w-25 two-values py-2 px-5 text-white"
            style="border-radius: 2px 40px ; text-indent:4px;">PC Ready</h4>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <?php
                // Mengecek apakah produk sudah ada di keranjang
                $product_in_cart = $cart->isInCart($id_user, $product['id_produk']);

                // Mengecek stok produk
                $product_stock = $cart->checkStock($product['id_produk']);
                ?>
                
                <div class="col-md-4 col-sm-6 my-3">
                    <div class="card d-flex justify-content-between py-3">
                        <div class="product font-rale">
                            <a class="d-flex justify-content-center align-items-center"
                                href="detail_produk.php?item=<?= urlencode(htmlspecialchars($product['nama_produk'])) ?>">
                                <img class="img-list-cover" src="assets/img/products/<?= $product['gambar_produk']; ?>"
                                    alt="<?= $product['gambar_produk']; ?> " width="150px" height="150px">
                            </a>
                            
                            <div class="text-center" style="min-height: 3em;">
                            <h6 style="min-width: 150px; margin: 0 auto;"> <!-- Mengatur lebar maksimal dan posisi tengah -->
                                <?= htmlspecialchars($product['nama_produk']) ?>
                            </h6>
                        </div>
                        <div class="text-center">
                                <div class="price py-2">
                                    <span>RP.<?= htmlspecialchars($product['harga']) ?></span>
                                </div>
                                <form method="post" class="py-3"    >
                                    <input type="hidden" name="id_produk"
                                        value="<?= htmlspecialchars($product['id_produk']); ?>">
                                    <input type="hidden" name="id_user" value="<?= $id_user; ?>"> <!-- Mengambil id_user dari sesi -->
                                    <?php if (!$product_stock): ?>
                                        <button type="button" class="btn btn-danger font-size-12" disabled>Stok Habis</button>
                                    <?php elseif ($product_in_cart): ?>
                                        <button type="button" class="btn btn-secondary font-size-12" disabled>In the Cart</button>
                                    <?php else: ?>
                                        <button type="submit" name="pc_ready_submit" class="btn btn-primary font-size-12">Add to Cart</button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<style>
        .card-item:hover {
            transform: scale(1.05); /* Menambahkan efek perbesaran sebesar 5% */
            transition: transform 0.3s ease; /* Menambahkan transisi halus selama 0.3 detik */
        }
</style>
