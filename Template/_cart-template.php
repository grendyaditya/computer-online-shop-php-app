<?php

include_once "koneksi.php";

$product = new Product($koneksi);
$cart = new Cart($koneksi);

// Ambil id_user dari sesi atau pengguna yang sedang login
$id_user = 2; // Contoh: Anda bisa mengambil id_user dari sesi atau pengguna yang sedang login

// Fungsi untuk menambah item ke dalam keranjang
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_to_cart'])) {
    $cart->addToCart($id_user, $_POST['product_id']);
}

// Fungsi untuk menghapus item dari keranjang
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_item'])) {
    $cart->deleteCart($_POST['id_cart']);
}

// Fungsi untuk memperbarui kuantitas item dalam keranjang
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

// Fungsi untuk melakukan checkout
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['checkout'])) {
    $checkout_success = $cart->checkout($id_user);
}

// Query untuk mengambil data keranjang belanja dari tabel tb_cart berdasarkan id_user
$query = "SELECT c.id_cart, p.nama_produk, p.harga, c.qty, p.gambar_produk, p.stok, p.brand
          FROM tb_cart c
          INNER JOIN tb_produk p ON c.id_produk = p.id_produk
          WHERE c.id_user = $id_user";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Error dalam query: " . mysqli_error($koneksi));
}

// Menyimpan hasil query dalam array
$cart_items = [];
$total_price = 0;
$total_items = 0; // Initialize total items count

while ($row = mysqli_fetch_assoc($result)) {
    $row['subtotal'] = $row['harga'] * $row['qty'];
    if ($row['stok'] > 0) {
        $total_price += $row['subtotal'];
        $total_items++; // Increment total items count
    }
    $cart_items[] = $row;
}
?>

<!-- Shopping cart section  -->
<section id="cart" class="py-3 mb-5">
    <div class="container-fluid w-75">
        <h5 class="font-baloo font-size-20">Shopping Cart</h5>

        <!--  shopping cart items   -->
        <div class="row">
            <div class="col-sm-9">
                <!-- Cart Items Display -->
<?php foreach ($cart_items as $cart_item): ?>
    <!-- cart item -->
    <div class="row border-top py-3 mt-3 align-items-center">
        <div class="col-sm-2 text-center">
            <img src="assets/img/products/<?= htmlspecialchars($cart_item['gambar_produk']); ?>" style="height: 120px;" alt="<?= htmlspecialchars($cart_item['nama_produk']); ?>" class="img-fluid">
        </div>
        <div class="col-sm-8">
            <h5 class="font-baloo font-size-20"><?= htmlspecialchars($cart_item['nama_produk']); ?></h5>
            <small><?= htmlspecialchars($cart_item['brand']); ?></small>
            <p class="font-size-14 text-secondary">Stok: <?= htmlspecialchars($cart_item['stok']); ?></p>

            <!-- product qty -->
            <?php if ($cart_item['stok'] > 0): ?>
                <div class="qty d-flex pt-2">
                    <div class="d-flex font-rale w-25 align-items-center">
                        <form method="post" class="mr-1">
                            <input type="hidden" name="id_cart" value="<?= htmlspecialchars($cart_item['id_cart']); ?>">
                            <input type="hidden" name="qty" value="<?= htmlspecialchars($cart_item['qty']); ?>">
                            <button type="submit" name="qty_up" class="qty-up border bg-light"><i class="fas fa-angle-up"></i></button>
                        </form>
                        <input type="text" data-id="<?= htmlspecialchars($cart_item['id_cart']); ?>" class="qty_input border px-2 w-100 bg-light text-center" disabled value="<?= htmlspecialchars($cart_item['qty']); ?>" placeholder="<?= htmlspecialchars($cart_item['qty']); ?>">
                        <form method="post" class="ml-1">
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
                RP.<span class="product_price" data-id="<?= htmlspecialchars($cart_item['id_cart']); ?>"><?= htmlspecialchars(number_format($cart_item['subtotal'], 2)); ?></span>
            </div>
        </div>
    </div>
    <!-- !cart item -->
<?php endforeach; ?>
<!-- !Cart Items Display -->

            </div>
            <!-- subtotal section-->
            <div class="col-sm-3">
                <div class="sub-total border text-center mt-2"> 
                    <h6 class="font-size-12 font-rale text-success py-3"><i class="fas fa-check"></i> Your order is
                        eligible for FREE Delivery.</h6>
                    <div class="border-top py-4">
                        <h5 class="font-baloo font-size-20">Subtotal ( <?= $total_items; ?> items):&nbsp; <span
                                class="text-danger">RP.<span class="text-danger"
                                    id="deal-price"><?= htmlspecialchars(number_format($total_price, 2)); ?></span> </span> </h5>
                        <!-- Tambahkan link checkout -->
                    <a href="checkout.php" class="btn btn-warning mt-3">Proceed to Checkout</a>

                    </div>
                </div>
            </div>
            <!-- !subtotal section-->
        </div>
        <!--  !shopping cart items   -->
    </div>
</section>
<!-- !Shopping cart section  -->

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
