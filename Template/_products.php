<?php
include_once "koneksi.php";

$cart = new Cart($koneksi);
$id_user = 2; // Ganti dengan id_user yang sesuai
$product_name = isset($_GET['item']) ? $_GET['item'] : '';

if (!empty($product_name)) {
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

// Handle form submission untuk add to cart
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['detail_produk_submit'])) {
    $cart->addToCart($id_user, $product['id_produk']);

    header("Location: detail_produk.php?item=" . urlencode($product_name));
    exit();
}

// Handle form submission untuk checkout langsung
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['checkout_submit'])) {
    $item_id = isset($_POST['item_id']) ? $_POST['item_id'] : null;
    $qty = isset($_POST['qty']) ? $_POST['qty'] : 1;

    // Simpan data produk ke dalam session
    $_SESSION['checkout_product'] = [
        'item_id' => $item_id,
        'qty' => $qty
    ];

    // Redirect ke halaman direct checkout
    header("Location: direct_checkout.php");
    exit();
}
?>

<?php include "header.php" ?>

<!-- Bagian Produk -->
<section id="product" class="py-3 mb-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 d-flex flex-column align-items-center">
                <img src="assets/img/products/<?= $product['gambar_produk']; ?>"
                    alt="<?= htmlspecialchars($product['nama_produk']) ?>" class="img-fluid" width="300px" height="300px">
                <p class="my-3"><?= htmlspecialchars($product['nama_produk']); ?></p>
            </div>
            <div class="col-sm-6 px-5">
                <h5 class="font-baloo font-size-24"><?= htmlspecialchars($product['nama_produk']); ?></h5>
                <small><?= htmlspecialchars($product['brand']) ?></small>
                <p class="my-3">Stok: <?= htmlspecialchars($product['stok']); ?></p>
                <hr class="m-0">

                <?php if ($product['stok'] == 0): ?>
                    <div class="alert alert-danger my-3" role="alert">
                        Stok habis
                    </div>
                <?php endif; ?>

                <table class="my-3">
                    <tr class="font-size-20">
                        <td>HARGA :</td>
                        <td class="font-size-24 text-danger"><span>RP. <?= htmlspecialchars($product['harga']); ?></span></td>
                    </tr>
                </table>

                <div class="form-row pt-4 font-size-16 font-baloo">
                    <div class="col">
                        <form method="post" action="">
                            <input type="hidden" name="item_id" value="<?= $product['id_produk']; ?>">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" name="checkout_submit" class="btn btn-danger form-control" <?= $product['stok'] == 0 ? 'disabled' : ''; ?>>Checkout</button>
                        </form>
                    </div>

                    <div class="col">
                        <form method="post">
                            <?php $product_in_cart = $cart->isInCart($id_user, $product['id_produk']);
                                  $product_stock = $cart->checkStock($product['id_produk']); ?>
                                  
                            <input type="hidden" name="id_produk" value="<?= htmlspecialchars($product['id_produk']); ?>">
                            <input type="hidden" name="id_user" value="<?= $id_user; ?>">

                            <?php if (!$product_stock): ?>
                                <button type="button" class="btn btn-danger form-control" disabled>Add to Cart</button>
                            <?php elseif ($product_in_cart): ?>
                                <button type="button" class="btn btn-secondary form-control" disabled>In Cart</button>
                            <?php else: ?>
                                <button type="submit" name="detail_produk_submit" class="btn btn-primary form-control">Add to Cart</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div id="policy" class="py-4">
                    <div class="d-flex">
                        <div class="return text-center mr-5">
                            <div class="font-size-20 my-2 color-second">
                                <span class="fas fa-list border p-3 rounded-pill"></span>
                            </div>
                            <p class="font-rale font-size-12">Kategori: <br><?= htmlspecialchars($product['kategori']) ?></p>
                        </div>
                        <div class="return text-center mr-5">
                            <div class="font-size-20 my-2 color-second">
                                <span class="fas fa-box border p-3 rounded-pill"></span>
                            </div>
                            <p class="font-rale font-size-12">Barang: <br> Surabaya</p>
                        </div>
                        <div class="return text-center mr-5">
                            <div class="font-size-20 my-2 color-second">
                                <span class="fas fa-check-double border p-3 rounded-pill"></span>
                            </div>
                            <p class="font-rale font-size-12">Keamanan: <br> Aman</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h6 class="font-rubik">Deskripsi Produk</h6>
            <hr>
            <p class="font-rale font-size-14"><?= htmlspecialchars($product['deskripsi']) ?></p>
        </div>
    </div>
</section>

<?php include "footer.php" ?>
