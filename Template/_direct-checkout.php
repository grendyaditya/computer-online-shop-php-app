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

    
    // Simpan data form sebelumnya dalam session
    $_SESSION['form_data'] = $_POST;

    if (isset($_POST['checkout'])) {
        // Ambil data form sebelumnya dari session
        $form_data = $_SESSION['form_data'];
        
        $nama_pelanggan = isset($form_data['nama_pelanggan']) ? $form_data['nama_pelanggan'] : '';
        $alamat_pelanggan = isset($form_data['alamat_pelanggan']) ? $form_data['alamat_pelanggan'] : '';
        $metode_pembayaran = isset($form_data['metode_pembayaran']) ? $form_data['metode_pembayaran'] : '';

        // Validate session product data for checkout
        if (isset($_SESSION['checkout_product'])) {
            $product_id = $_SESSION['checkout_product']['item_id'];
            $product_qty = $_SESSION['checkout_product']['qty'];
            
            // Query untuk mengambil informasi produk berdasarkan id_produk
            $query_produk = "SELECT nama_produk, harga FROM tb_produk WHERE id_produk = ?";
            $stmt_produk = mysqli_prepare($koneksi, $query_produk);
            mysqli_stmt_bind_param($stmt_produk, 'i', $product_id);
            mysqli_stmt_execute($stmt_produk);
            $result_produk = mysqli_stmt_get_result($stmt_produk);
            
            if ($result_produk && mysqli_num_rows($result_produk) > 0) {
                $product = mysqli_fetch_assoc($result_produk);
                $harga_produk = $product['harga'];
                $subtotal = $harga_produk * $product_qty;
                
                // Lakukan proses checkout dan dapatkan ID penjualan
                $id_penjualan = $cart->checkout($id_user, $nama_pelanggan, $alamat_pelanggan, $metode_pembayaran, $product_id, $product_qty);

                if ($id_penjualan) {
                    // Hapus data form dari session setelah checkout berhasil
                    unset($_SESSION['form_data']);
                    unset($_SESSION['checkout_product']); // Clear the session data

                    // Redirect ke halaman transaksi.php dengan menggunakan query string untuk mengirim ID penjualan
                    header("Location: transaksi.php" . $id_penjualan);
                    exit();
                } else {
                    echo "<script>alert('Checkout failed!');</script>";
                }
            } else {
                echo "Produk tidak ditemukan.";
            }
        }
    }
}

// Ambil data form sebelumnya dari session
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Checkout</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Direct Checkout Page</h2>

        <!-- Formulir Checkout -->
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="nama_pelanggan">Nama Pelanggan:</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control" value="<?= isset($form_data['nama_pelanggan']) ? htmlspecialchars($form_data['nama_pelanggan']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat_pelanggan">Alamat Pelanggan:</label>
                <textarea id="alamat_pelanggan" name="alamat_pelanggan" class="form-control" rows="3" required><?= isset($form_data['alamat_pelanggan']) ? htmlspecialchars($form_data['alamat_pelanggan']) : '' ?></textarea>
            </div>
            <div class="form-group">
                <label for="metode_pembayaran">Metode Pembayaran:</label>
                <select id="metode_pembayaran" name="metode_pembayaran" class="form-control" required>
                    <option value="transfer" <?= isset($form_data['metode_pembayaran']) && $form_data['metode_pembayaran'] == 'transfer' ? 'selected' : '' ?>>Transfer Bank</option>
                    <option value="cod" <?= isset($form_data['metode_pembayaran']) && $form_data['metode_pembayaran'] == 'cod' ? 'selected' : '' ?>>Cash on Delivery (COD)</option>
                </select>
            </div>
            <button type="submit" name="checkout" class="btn btn-primary">Checkout</button>
        </form>

        <!-- Direct Checkout Product -->
        <?php if (isset($_SESSION['checkout_product'])): ?>
            <h3 class="mb-3">Direct Checkout Product:</h3>
            <?php
                $product_id = $_SESSION['checkout_product']['item_id'];
                $product_qty = $_SESSION['checkout_product']['qty'];
                // Query produk langsung dari detail_produk.php
                $query = "SELECT nama_produk, harga FROM tb_produk WHERE id_produk = $product_id";
                $result = mysqli_query($koneksi, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $product = mysqli_fetch_assoc($result);
                    $subtotal = $product['harga'] * $product_qty;
            ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['nama_produk']); ?> - <?= $product_qty; ?> x RP. <?= htmlspecialchars($product['harga']); ?> = RP. <?= htmlspecialchars($subtotal); ?></h5>
                        </div>
                    </div>
            <?php
                }
            ?>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
