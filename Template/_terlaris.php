<?php
include_once "koneksi.php";

$query = "SELECT * FROM tb_produk ORDER BY RAND() LIMIT 20";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error dalam query: " . mysqli_error($koneksi));
}

// Menyimpan hasil query dalam array
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['terlaris_submit'])) {
        // Menggunakan objek $cart untuk memanggil metode addToCart
        $cart->addToCart($_POST['id_user'], $_POST['id_produk']);
        // Menyimpan informasi produk yang telah ditambahkan ke sesi
        $_SESSION['cart'][$_POST['id_produk']] = true;
    }
}

// Mengecek apakah produk sudah ada di keranjang
?>

<!-- New Phones Section -->
<section id="new-phones" class="container mb-5">
    <div class="container card shadow py-3">
        <h4 class="font-rubik bg-primary w-25 two-values py-2 px-5 text-white"
            style="border-radius: 2px 40px ; text-indent:4px;">Terlaris</h4>
        
        <!-- Owl Carousel -->
        <div class="owl-carousel owl-theme">
            <?php foreach ($products as $product): ?>
                <!-- Item -->
                <?php
                // Mengecek apakah produk sudah ada di keranjang
                $product_in_cart = $cart->isInCart($id_user, $product['id_produk']);

                // Mengecek stok produk
                $product_stock = $cart->checkStock($product['id_produk']);
                ?>
                <div class="item py-2 bg-white m-3">
        <div class="product font-rale card p-5 card-item">
            <a href="detail_produk.php?item=<?= urlencode($product['nama_produk']) ?>">
                <img class="img-list-cover img-fluid d-block mx-auto"
                    src="assets/img/products/<?= htmlspecialchars($product['gambar_produk']); ?>"
                    alt="<?= htmlspecialchars($product['gambar_produk']); ?>">
            </a>
            <div class="text-center mt-3">
                <h6 class="font-weight-bold" style="min-width: 150px;">
                    <?= htmlspecialchars($product['nama_produk']) ?>
                </h6>
            </div>
            <div class="text-center">
                <div class="price py-2">
                    <span class="font-weight-bold">Rp. <?= htmlspecialchars($product['harga']) ?></span>
                </div>
                <form method="post" class="py-3">
                    <div class="d-flex justify-content-center">
                        <input type="hidden" name="id_produk" value="<?= htmlspecialchars($product['id_produk']); ?>">
                        <input type="hidden" name="id_user" value="<?= $id_user; ?>"> <!-- Mengambil id_user dari sesi -->
                        <?php if (!$product_stock): ?>
                            <button type="button" class="btn btn-danger font-size-12 mr-2" disabled>Stok Habis</button>
                        <?php elseif ($product_in_cart): ?>
                            <button type="button" class="btn btn-secondary font-size-12 mr-2" disabled>In Cart</button>
                        <?php else: ?>
                            <button type="submit" name="terlaris_submit" class="btn btn-primary font-size-12 mr-2">Add to Cart</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
                <!-- !Item -->
            <?php endforeach; ?>
        </div>
        <!-- !Owl Carousel -->
    </div>
</section>
<!-- !New Phones Section -->

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT3BGzJu0gDgJS5vAnm6RYIVOpV49jFOeRTuWTvdE0H8F9CZ4F"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"
    integrity="sha384-Ksvt7BlTKrFcC1mFp3tPRgdt2n04fjNa25o+QQfIUPA9E+s0hEM+lHD1aQ5dPt4X"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
    crossorigin="anonymous"></script>

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
    integrity="sha256-pY4aD6W1uNg6MSjRZp5lKCCzUmw7n8cg5pxtlFThDUc=" crossorigin="anonymous"></script>

    <style>
        .card-item {
            width: 100%;
            max-width: 250px; /* Atur lebar maksimal */
            min-height: 400px; /* Atur tinggi minimal */
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

