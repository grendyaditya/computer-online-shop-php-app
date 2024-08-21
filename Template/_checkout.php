<?php
    include_once "koneksi.php";
    $cart = new Cart($koneksi);

    
    
    // Inisialisasi variabel untuk produk individual
    $product_id = isset($_POST['item_id']) ? $_POST['item_id'] : null;
    $product_qty = isset($_POST['qty']) ? $_POST['qty'] : 1;

    // Initialize user ID from session or the logged-in user
    $id_user = 2;

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        
        if (isset($_POST['add_to_cart'])) {
            $cart->addToCart($id_user, $_POST['product_id']);
        } elseif (isset($_POST['delete_item'])) {
            $cart->deleteCart($_POST['id_cart']);
        } elseif (isset($_POST['qty_up']) || isset($_POST['qty_down'])) {
            // Handle quantity update
        } elseif (isset($_POST['checkout'])) {

            // Hapus data form dari session setelah checkout berhasil
            unset($_SESSION['form_data']);
        }

        // Simpan data form sebelumnya dalam session
        $_SESSION['form_data'] = $_POST;
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST" && (isset($_POST['qty_up']) || isset($_POST['qty_down']))) {
        $id_cart = $_POST['id_cart'];
        $qty = $_POST['qty'];
        
        // Get the current stock for the product in the cart
        $query = "SELECT p.stok 
                FROM tb_cart c
                INNER JOIN tb_produk p ON c.id_produk = p.id_produk
                WHERE c.id_cart = $id_cart";
        $result = mysqli_query($koneksi, $query);
        $product = mysqli_fetch_assoc($result);

        if (isset($_POST['qty_up'])) {
            if ($qty < $product['stok']) {
                $qty++;
                $cart->updateCartQty($id_cart, $qty);
            } else {
                echo "<script>alert('Quantity cannot exceed stock.');</script>";
            }
        } else if (isset($_POST['qty_down']) && $qty > 1) {
            $qty--;
            $cart->updateCartQty($id_cart, $qty);
        }
    }
    
    // Ambil data form sebelumnya dari session
    $form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

    // Query untuk mengambil item dari keranjang
    $query = "SELECT c.id_cart, p.id_produk, p.nama_produk, p.harga, c.qty, p.gambar_produk, p.stok
            FROM tb_cart c
            INNER JOIN tb_produk p ON c.id_produk = p.id_produk
            WHERE c.id_user = $id_user";
    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        die("Error in query: " . mysqli_error($koneksi));
    }

    $cart_items = [];
    $total_price = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $subtotal = $row['harga'] * $row['qty'];
        $total_price += $subtotal;
        $row['subtotal'] = $subtotal;
        $cart_items[] = $row;
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['checkout'])) {
        $_SESSION['form_data'] = $_POST;
        $nama_pelanggan = isset($_POST['nama_pelanggan']) ? $_POST['nama_pelanggan'] : '';
        $alamat_pelanggan = isset($_POST['alamat_pelanggan']) ? $_POST['alamat_pelanggan'] : '';
        $metode_pembayaran = isset($_POST['metode_pembayaran']) ? $_POST['metode_pembayaran'] : '';
        $response = [];

    // Handle quantity up action
    if ($action == "qty_up") {
        // Logic to update the quantity in the cart
        $cart->updateCartQty($item_id, 'increase');

        $response[] = ['item_price' => $item_price];
    }

    // Handle quantity down action
    if ($action == "qty_down") {
        // Logic to update the quantity in the cart
        $cart->updateCartQty($item_id, 'decrease');

        $response[] = ['item_price' => $item_price];
    }

    echo json_encode($response);


        // Validate session product data for checkout
        if (isset($_SESSION['checkout_product'])) {
            $product_id = $_SESSION['checkout_product']['id'];
            $product_qty = $_SESSION['checkout_product']['qty'];
        }

        // Lakukan proses checkout dan dapatkan ID penjualan
        $id_penjualan = $cart->checkout($id_user, $nama_pelanggan, $alamat_pelanggan, $metode_pembayaran);

        if ($id_penjualan) {
            // Redirect ke halaman transaksi.php dengan menggunakan query string untuk mengirim ID penjualan
            header("Location: transaksi.php");
            exit();
        } else {
            echo "<script>alert('Checkout failed!');</script>";
        }

        $_SESSION['transaksi'] = [
            'nama_pelanggan' => $_POST['nama_pelanggan'],
            'alamat_pelanggan' => $_POST['alamat_pelanggan'],
            'metode_pembayaran' => $_POST['metode_pembayaran'],
            'items' => $cart_items  // Sesuaikan dengan struktur item yang sesuai dengan kebutuhan aplikasi Anda
        ];
        
        // Lakukan redirect atau tampilkan halaman checkout setelah menyimpan data ke session
        header('Location: transaksi.php');
    }

    if (empty($cart_items)) {
        // Redirect to index.php if cart is empty
        header('Location: index.php');
        exit();
    }
    ?>

<!-- Checkout section -->
<section id="checkout" class="py-3 mb-5">
    <div class="container-fluid w-75">
        <h5 class="font-baloo font-size-20">Checkout</h5>   
    <?php    // Ambil data form sebelumnya dari session
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
?>

<form method="post" id="checkout-form">
    <div class="form-group">
        <label for="nama_pelanggan">Nama Pelanggan</label>
        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="<?= isset($form_data['nama_pelanggan']) ? htmlspecialchars($form_data['nama_pelanggan']) : '' ?>" required>
    </div>
    <div class="form-group">
        <label for="alamat_pelanggan">Alamat Pelanggan</label>
        <textarea class="form-control" id="alamat_pelanggan" name="alamat_pelanggan" rows="3" required><?= isset($form_data['alamat_pelanggan']) ? htmlspecialchars($form_data['alamat_pelanggan']) : '' ?></textarea>
    </div>
    <div class="form-group">
        <label for="metode_pembayaran">Metode Pembayaran</label>
        <div>
            <input type="radio" id="tunai" name="metode_pembayaran" value="tunai" <?= isset($form_data['metode_pembayaran']) && $form_data['metode_pembayaran'] == 'tunai' ? 'checked' : '' ?> required>
            <label for="tunai">Tunai</label>
        </div>
        <div>
            <input type="radio" id="bank_bca" name="metode_pembayaran" value="bank_bca" <?= isset($form_data['metode_pembayaran']) && $form_data['metode_pembayaran'] == 'bank_bca' ? 'checked' : '' ?> required>
            <label for="bank_bca">Bank BCA</label>
        </div>
        <div>
            <input type="radio" id="qris" name="metode_pembayaran" value="qris" <?= isset($form_data['metode_pembayaran']) && $form_data['metode_pembayaran'] == 'qris' ? 'checked' : '' ?> required>
            <label for="qris">QRIS</label>
        </div>
    </div>

            <div class="row">
                <div class="col-sm-9">
                    <?php foreach ($cart_items as $cart_item): ?>
                        <!-- cart item -->
                        <div class="row border-top py-3 mt-3">
                            <div class="col-sm-2">
                                <img src="assets/img/products/<?= htmlspecialchars($cart_item['gambar_produk']); ?>" style="height: 120px;"
                                     alt="<?= htmlspecialchars($cart_item['nama_produk']); ?>" class="img-fluid">
                            </div>
                            <div class="col-sm-8">
                                <h5 class="font-baloo font-size-20"><?= htmlspecialchars($cart_item['nama_produk']); ?></h5>
                                <small>by Brand Name</small>
                                <p class="font-size-14 text-secondary">Stok: <?= htmlspecialchars($cart_item['stok']); ?></p>

                                <!-- product qty -->
                                <?php if ($cart_item['stok'] > 0): ?>
                                    <div class="qty d-flex pt-2">
                                        <div class="d-flex font-rale w-25">
                                            <form method="post">
                                                <input type="hidden" name="id_cart" value="<?= htmlspecialchars($cart_item['id_cart']); ?>">
                                                <input type="hidden" name="qty" value="<?= htmlspecialchars($cart_item['qty']); ?>">
                                                <button type="submit" name="qty_up" class="qty-up border bg-light"><i class="fas fa-angle-up"></i></button>
                                            </form>
                                            <input type="text" data-id="<?= htmlspecialchars($cart_item['id_cart']); ?>"
                                                   class="qty_input border px-2 w-100 bg-light" disabled
                                                   value="<?= htmlspecialchars($cart_item['qty']); ?>" placeholder="<?= htmlspecialchars($cart_item['qty']); ?>">
                                            <form method="post">
                                                <input type="hidden" name="id_cart" value="<?= htmlspecialchars($cart_item['id_cart']); ?>">
                                                <input type="hidden" name="qty" value="<?= htmlspecialchars($cart_item['qty']); ?>">
                                                <button type="submit" name="qty_down" class="qty-down border bg-light"><i class="fas fa-angle-down"></i></button>
                                            </form>
                                        </div>
                                        <form method="post" class="px-3 border-right">
                                            <input type="hidden" name="id_cart" value="<?= htmlspecialchars($cart_item['id_cart']); ?>">
                                            <button type="submit" name="delete_item" class="btn font-baloo text-danger">Delete</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <p class="text-danger">Out of Stock</p>
                                <?php endif; ?>
                                <!-- !product qty -->

                            </div>

                            <div class="col-sm-2 text-right">
                                <div class="font-size-20 text-danger font-baloo">
                                    $<span class="product_price" data-id="<?= htmlspecialchars($cart_item['id_cart']); ?>"><?= htmlspecialchars(number_format($cart_item['subtotal'], 2)); ?></span>
                                </div>
                            </div>
                        </div>
                        <!-- !cart item -->
                    <?php endforeach; ?>
                </div>
                <!-- subtotal section-->
                <div class="col-sm-3">
                    <div class="sub-total border text-center mt-2">
                        <h6 class="font-size-12 font-rale text-success py-3"><i class="fas fa-check"></i> Your order is eligible for FREE Delivery.</h6>
                        <div class="border-top py-4">
                            <h5 class="font-baloo font-size-20">Subtotal ( <?= count($cart_items); ?> items):&nbsp; <span class="text-danger">$<span class="text-danger" id="deal-price"><?= htmlspecialchars(number_format($total_price, 2)); ?></span> </span> </h5>
                            <button type="submit" name="checkout" class="btn btn-warning mt-3">Proceed to Buy</button>
                        </div>
                    </div>
                </div>
                <!-- !subtotal section-->
            </div>
        </form>
    </div>
</section>
<!-- !Checkout section -->


<!-- !Checkout section -->
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
</body>
</html>
