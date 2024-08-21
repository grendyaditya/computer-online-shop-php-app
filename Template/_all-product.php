<?php
include_once "koneksi.php";

$cart = new Cart($koneksi);



if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['all_product_submit'])) {
        // Menggunakan objek $cart untuk memanggil metode addToCart
        $cart->addToCart($_POST['id_user'], $_POST['id_produk']);
        // Menyimpan informasi produk yang telah ditambahkan ke sesi
        $_SESSION['cart'][$_POST['id_produk']] = true;
    }
}
// Mengatur jumlah produk per halaman
$products_per_page = 20;

// Mengambil halaman saat ini dari parameter URL, default ke 1 jika tidak ada parameter
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Mengambil filter harga dari parameter URL, default ke 0 jika tidak ada parameter
$filter_min_price = isset($_GET['min_price']) ? intval($_GET['min_price']) : 0;
$filter_max_price = isset($_GET['max_price']) ? intval($_GET['max_price']) : 99999999999999999;

// Mengambil filter pengurutan dari parameter URL, default ke 'asc' (harga termurah) jika tidak ada parameter
$sort_order = isset($_GET['sort']) && ($_GET['sort'] == 'desc') ? 'desc' : 'asc';

// Mengambil keyword pencarian dari parameter URL, default ke string kosong jika tidak ada parameter
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Menghitung offset untuk query SQL
$offset = ($page - 1) * $products_per_page;

// Membuat kondisi tambahan untuk filter harga
$price_condition = "harga BETWEEN $filter_min_price AND $filter_max_price";

// Query untuk mengambil total jumlah produk dengan filter pencarian dan harga
$total_query = "SELECT COUNT(*) as total FROM tb_produk WHERE nama_produk LIKE '%$search_query%' AND $price_condition";
$total_result = mysqli_query($koneksi, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];

// Menghitung jumlah total halaman
$total_pages = ceil($total_products / $products_per_page);

// Query untuk mengambil produk dengan filter pencarian dan harga dengan limit dan offset serta pengurutan
$query = "SELECT * FROM tb_produk WHERE nama_produk LIKE '%$search_query%' AND $price_condition ORDER BY harga $sort_order LIMIT $products_per_page OFFSET $offset";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error dalam query: " . mysqli_error($koneksi));
}

// Menyimpan hasil query dalam array
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!-- Special Price Section -->
<section id="special-price justify-content-center">
    <div class="container my-3">
        <div class="card col-6 shadow m-auto p-5">
            <h4 class="font-rubik font-size-20 text-center">Telusuri</h4>
            <!-- Search Box -->
            <div class="row justify-content-center mb-3">
                <div class="col-md-12">
                    <form action="" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search..." value="<?= htmlspecialchars($search_query) ?>">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form> 
                </div>
            </div>
            <!-- End Search Box -->
    
            <!-- Price Filter -->
            <div class="row justify-content-center mb-3">
                <div class="col-md-12">
                    <form action="" method="get">
                        <div class="row ">
                            <div class="col-4">
                                <input type="number" class="form-control" name="min_price" placeholder="Min Price" value="<?= $filter_min_price ?>">
                            </div>
                            <div class="col-4">
                                <input type="number" class="form-control" name="max_price" placeholder="Max Price" value="<?= $filter_max_price ?>">
                            </div>
                            <div class="col-4">
                                <button class="btn btn-primary" type="submit">Filter Price</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Price Filter -->
    
            <!-- Sort Order -->
            <div class="row justify-content-center mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="sort">Sort by Price:</label>
                        <select class="form-control" id="sort" onchange="location = this.value;">
                            <option value="?sort=asc<?= isset($_GET['page']) ? '&page=' . $_GET['page'] : '' ?>" <?= $sort_order == 'asc' ? 'selected' : '' ?>>Lowest Price</option>
                            <option value="?sort=desc<?= isset($_GET['page']) ? '&page=' . $_GET['page'] : '' ?>" <?= $sort_order == 'desc' ? 'selected' : '' ?>>Highest Price</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- End Sort Order -->
        </div>

        <div class="grid text-center">
            <?php foreach ($products as $product): ?>
                <?php
                // Mengecek apakah produk sudah ada di keranjang
                $product_in_cart = $cart->isInCart($id_user, $product['id_produk']);

                // Mengecek stok produk
                $product_stock = $cart->checkStock($product['id_produk']);
                ?>
                <div class="grid-item card p-3 justify-content-center m-3 special-price-<?= htmlspecialchars($product['brand']) ?>">
                    <div class="item py-2 " style="width: 200px;">
                        <div class="product font-rale">
                            <a href="product.php?item=<?= urlencode($product['nama_produk']) ?>">
                                <img class="img-list-cover" src="assets/img/products/<?= htmlspecialchars($product['gambar_produk']) ?>" alt="<?= htmlspecialchars($product['nama_produk']) ?>" width="200px" height="200px">
                            </a>
                            <div class="text-center" style="min-height: 3em;">
                                <h6 class="mt-2 mb-1"><?= htmlspecialchars($product['nama_produk']) ?></h6>
                            </div>
                            <div class="text-center">
                                <div class="price py-2">
                                    <span>Rp. <?= number_format($product['harga'], 0, ',', '.') ?></span>
                                </div>
                                <form method="post" class="py-3">
                                    <input type="hidden" name="id_produk" value="<?= htmlspecialchars($product['id_produk']); ?>">
                                    <input type="hidden" name="id_user" value="<?= $id_user; ?>"> <!-- Mengambil id_user dari sesi -->
                                    <?php if (!$product_stock): ?>
                                        <button type="button" class="btn btn-danger font-size-12" disabled>Stok Habis</button>
                                    <?php elseif ($product_in_cart): ?>
                                        <button type="button" class="btn btn-secondary font-size-12" disabled>In Cart</button>
                                    <?php else: ?>
                                        <button type="submit" name="all_product_submit" class="btn btn-primary font-size-12">Add to Cart</button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination Section -->
        <div class="pagination justify-content-center mt-4">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn btn-outline-secondary">&laquo; Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="btn btn-outline-secondary <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn btn-outline-secondary">Next &raquo;</a>
            <?php endif; ?>
        </div>
        <!-- !Pagination Section -->
    </div>
</section>
<!-- !Special Price Section -->
