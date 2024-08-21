<?php
	include 'include_admin/js.php';
	include 'include/js.php';
	session_start();
	date_default_timezone_set('Asia/Jakarta');

	$server 	= "localhost";
	$user		= "root";
	$password	= "";
	$database	= "tokopc";
	
	$koneksi = mysqli_connect($server,$user,$password) or die("Koneksi Server Gagal!");
	$db = mysqli_select_db($koneksi, $database) or die("Gagal Pilih Database!");

// ====================== FUNCTION ======================
function setAlert($title='', $text='', $type='', $buttons='') {
	$_SESSION["alert"]["title"]		= $title;
	$_SESSION["alert"]["text"] 		= $text;
	$_SESSION["alert"]["type"] 		= $type;
	$_SESSION["alert"]["buttons"]	= $buttons; 
}

if (isset($_SESSION['alert'])) {
	$title 		= $_SESSION["alert"]["title"];
	$text 		= $_SESSION["alert"]["text"];
	$type 		= $_SESSION["alert"]["type"];
	$buttons	= $_SESSION["alert"]["buttons"];

	echo"
		<div id='msg' data-title='".$title."' data-type='".$type."' data-text='".$text."' data-buttons='".$buttons."'></div>
		<script>
			let title 		= $('#msg').data('title');
			let type 		= $('#msg').data('type');
			let text 		= $('#msg').data('text');
			let buttons		= $('#msg').data('buttons');

			if(text != '' && type != '' && title != '') {
				Swal.fire({
					title: title,
					text: text,
					icon: type,
				});
			}
		</script>
	";
	unset($_SESSION["alert"]);
}

function checkLogin() {
	if (!isset($_SESSION['id_user'])) {
		setAlert("Akses ditolak!", "Login terlebih dahulu!", "error");
		header('Location: login.php');
	} 
}

function checkLoginAtLogin() {
	if (isset($_SESSION['id_user'])) {
		setAlert("Anda sudah login!", "Selamat Datang!", "success");
		header('Location: index.php');
	}
}
// DATA USER
function dataUser() {
	global $koneksi;
	if (isset($_SESSION['id_user'])) {
		$id_user = $_SESSION['id_user'];
		return mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_admin WHERE id_user = '$id_user'"));
	} else {
		echo "
		  <script>
		    document.location.href='logout.php'
		  </script>
		";
	}
}
// DATA USER

function ubahProfile($data)
{
    global $koneksi;
    $id_user = $_SESSION['id_user'];
    $username = htmlspecialchars($data['username']);
    $nama_lengkap = htmlspecialchars($data['nama_lengkap']);
    $photo_lama = htmlspecialchars($data['photo_lama']);
    if ($_FILES['photo_profile']['error'] === 4) {
        $photo_profile = $photo_lama;
    } else {
        $photo_profile = uploadproduk();
    }
    mysqli_query($koneksi, "UPDATE tb_admin SET username = '$username', nama_lengkap = '$nama_lengkap', photo_profile = '$photo_profile' WHERE id_user = '$id_user'");
    riwayat($id_user, "Berhasil mengubah profil");
    return mysqli_affected_rows($koneksi);
}

function upload()
{
    $nama_photo = $_FILES['photo_profile']['name'];
    $ukuran_photo = $_FILES['photo_profile']['size'];
    $error = $_FILES['photo_profile']['error'];
    $tmp_name = $_FILES['photo_profile']['tmp_name'];

    // cek apakah mengupload photo
    if ($error === 4) {
        setAlert('Gagal mengubah foto profil', 'Pilih foto terlebih dahulu!', 'error');
        return false;
    }

    // cek ekstensi photo
    $ekstensi_photo_valid = ['jpg', 'jpeg', 'png', 'gif'];
    $ekstensi_photo = explode('.', $nama_photo);
    $ekstensi_photo = strtolower(end($ekstensi_photo));
    if (!in_array($ekstensi_photo, $ekstensi_photo_valid)) {
        setAlert('Gagal mengubah foto profil', 'Pilih foto yang berekstensi gambar (jpg, jpeg, png, gif)!', 'error');
        return false;
    }

    // cek ukuran photo
    if ($ukuran_photo > 1000000) {
        setAlert('Gagal mengubah foto profil', 'Ukuran foto terlalu besar (maksimal 1MB)!', 'error');
        return false;
    }

    // generate nama acak untuk foto baru
    $nama_photo_baru = uniqid() . '.' . $ekstensi_photo;

    // pindahkan foto ke direktori yang tepat
    move_uploaded_file($tmp_name, 'assets/img/img_profile/' . $nama_photo_baru);
    return $nama_photo_baru;
}



function ubahPassword($data)
{
    global $koneksi;
    $id_user = $_SESSION['id_user'];
    $password_lama = htmlspecialchars($data['password_lama']);
    $password_baru = htmlspecialchars($data['password_baru']);
    $verifikasi_password_baru = htmlspecialchars($data['verifikasi_password_baru']);

    // cek password lama sesuai dengan password pada database
    $result = mysqli_query($koneksi, "SELECT * FROM tb_admin WHERE id_user = '$id_user'");
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        if (password_verify($password_lama, $data['password'])) {
            // cek password baru dengan verifikasi password baru
            if ($password_baru == $verifikasi_password_baru) {
                // hash password baru sebelum menyimpannya
                $password_baru = password_hash($password_baru, PASSWORD_DEFAULT);
                mysqli_query($koneksi, "UPDATE tb_admin SET password = '$password_baru' WHERE id_user = '$id_user'");
                riwayat($id_user, "Berhasil mengubah password");
                return mysqli_affected_rows($koneksi);
            } else {
                setAlert('Gagal mengubah password', 'Password baru tidak sesuai dengan verifikasi password baru!', 'error');
                header('Location: profile.php');
                exit();
            }
        } else {
            setAlert('Gagal mengubah password', 'Password lama tidak sesuai!', 'error');
            header('Location: profile.php');
            exit();
        }
    } else {
        setAlert('Gagal mengubah password', 'Terjadi kesalahan saat mengambil data user.', 'error');
        header('Location: profile.php');
        exit();
    }
}


function riwayat($id_user, $tindakan)
{
    global $koneksi;
    $tanggal = time();
    mysqli_query($koneksi, "INSERT INTO tb_riwayat VALUES ('','$id_user', '$tindakan', '$tanggal')");
    return mysqli_affected_rows($koneksi);
}
function tambahKategori($data)
{
    global $koneksi;

    // Pastikan bahwa $_SESSION['id_user'] sudah didefinisikan
    if (!isset($_SESSION['id_user'])) {
        // Atur nilai $_SESSION['id_user'] di sini, misalnya dari hasil proses login
        $_SESSION['id_user'] = 'nilai_id_user';
    }

    // Periksa apakah kategori sudah ada sebelumnya
    $nama_kategori = htmlspecialchars(addslashes(ucwords($data['nama_kategori'])));
    $result = mysqli_query($koneksi, "SELECT * FROM tb_kategori WHERE nama_kategori = '$nama_kategori'");
    if (mysqli_num_rows($result) > 0) {
        // Kategori sudah ada, lakukan penanganan kesalahan di sini
        return 0;
    }

    // Persiapkan data gambar kategori
    if (!isset($_FILES['gambar_kategori']) || $_FILES['gambar_kategori']['error'] !== UPLOAD_ERR_OK) {
        // File gambar tidak berhasil di-upload, beri tahu pengguna atau lakukan tindakan yang sesuai
        return false;
    }

    // Set nama file untuk gambar yang diunggah
    $nama_gambar = htmlspecialchars(addslashes($_FILES['gambar_kategori']['name']));

    // Tentukan direktori tujuan untuk menyimpan gambar
    $upload_dir = '../assets/img/kategori/';

    // Dapatkan ekstensi file yang diunggah
    $ekstensi = pathinfo($_FILES['gambar_kategori']['name'], PATHINFO_EXTENSION);

    // Buat nama unik untuk file gambar
    $nama_file_baru = uniqid() . '.' . $ekstensi;

    // Tentukan lokasi penyimpanan file gambar yang baru
    $gambar_kategori = $upload_dir . $nama_file_baru;

    // Pindahkan file yang diunggah ke lokasi yang diinginkan
    if (!move_uploaded_file($_FILES['gambar_kategori']['tmp_name'], $gambar_kategori)) {
        // Gagal memindahkan file, beri tahu pengguna atau lakukan tindakan yang sesuai
        return false;
    }

    // Lakukan operasi penambahan kategori jika kategori belum ada
    mysqli_query($koneksi, "INSERT INTO tb_kategori (nama_kategori, gambar_kategori) VALUES ('$nama_kategori', '$nama_file_baru')");
    $tindakan = "Menambahkan kategori baru: $nama_kategori";
    riwayat($_SESSION['id_user'], $tindakan);
    return mysqli_affected_rows($koneksi);
}

function ubahKategori($data)
{
    global $koneksi;

    // Pastikan bahwa $_SESSION['id_user'] sudah didefinisikan
    if (!isset($_SESSION['id_user'])) {
        // Atur nilai $_SESSION['id_user'] di sini, misalnya dari hasil proses login
        $_SESSION['id_user'] = 'nilai_id_user';
    }

    // Periksa apakah kategori ada
    $id_kategori = $data['id_kategori'];
    $result = mysqli_query($koneksi, "SELECT * FROM tb_kategori WHERE id_kategori = '$id_kategori'");
    if (mysqli_num_rows($result) == 0) {
        // Kategori tidak ditemukan, lakukan penanganan kesalahan di sini
        return 0;
    }

    // Persiapkan data gambar kategori
    if (!isset($_FILES['gambar_kategori']) || $_FILES['gambar_kategori']['error'] !== UPLOAD_ERR_OK) {
        // File gambar tidak berhasil di-upload, beri tahu pengguna atau lakukan tindakan yang sesuai
        return false;
    }

    // Persiapkan data nama kategori
    if (!isset($data['nama_kategori'])) {
        // Jika nama_kategori tidak diberikan, kembali kegagalan
        return false;
    }

    // Set nama file untuk gambar yang diunggah
    $nama_gambar = htmlspecialchars(addslashes($_FILES['gambar_kategori']['name']));

    // Tentukan direktori tujuan untuk menyimpan gambar
    $upload_dir = '../assets/img/kategori/';

    // Dapatkan ekstensi file yang diunggah
    $ekstensi = pathinfo($_FILES['gambar_kategori']['name'], PATHINFO_EXTENSION);

    // Buat nama unik untuk file gambar
    $nama_file_baru = uniqid() . '.' . $ekstensi;

    // Tentukan lokasi penyimpanan file gambar yang baru
    $gambar_kategori = $upload_dir . $nama_file_baru;

    // Pindahkan file yang diunggah ke lokasi yang diinginkan
    if (!move_uploaded_file($_FILES['gambar_kategori']['tmp_name'], $gambar_kategori)) {
        // Gagal memindahkan file, beri tahu pengguna atau lakukan tindakan yang sesuai
        return false;
    }

    // Ambil nama gambar lama untuk penghapusan setelah penggantian gambar baru
    $row = mysqli_fetch_assoc($result);
    $nama_gambar_lama = $row['gambar_kategori'];

    // Lakukan operasi perubahan kategori
    $nama_kategori = htmlspecialchars(addslashes(ucwords($data['nama_kategori'])));
    mysqli_query($koneksi, "UPDATE tb_kategori SET nama_kategori = '$nama_kategori', gambar_kategori = '$nama_file_baru' WHERE id_kategori = '$id_kategori'");
    $tindakan = "Mengubah gambar kategori dan nama kategori: $id_kategori";
    riwayat($_SESSION['id_user'], $tindakan);

    // Hapus gambar lama jika berhasil mengubah
    if (mysqli_affected_rows($koneksi) > 0) {
        unlink($upload_dir . $nama_gambar_lama);
    }

    return mysqli_affected_rows($koneksi);
}



// Fungsi untuk menghapus kategori berdasarkan id_kategori
function hapusKategori($id_kategori)
{
    global $koneksi;

    // Lakukan query untuk menghapus kategori
    mysqli_query($koneksi, "DELETE FROM tb_kategori WHERE id_kategori = '$id_kategori'");

    // Periksa apakah kategori berhasil dihapus
    if (mysqli_affected_rows($koneksi) > 0) {
        // Jika berhasil, return nilai 1
        return 1;
    } else {
        // Jika tidak berhasil, return nilai 0
        return 0;
    }
}
function uploadProduk()
{
    $nama_gambar = $_FILES['gambar_produk']['name'];
    $ukuran_gambar = $_FILES['gambar_produk']['size'];
    $error = $_FILES['gambar_produk']['error'];
    $tmp_name = $_FILES['gambar_produk']['tmp_name'];

    // Cek apakah berhasil mengupload gambar
    if ($error === 4) {
        setAlert('Gagal mengupload gambar', 'Pilih gambar terlebih dahulu!', 'error');
        return false;
    }

    // Cek ekstensi gambar
    $ekstensi_gambar_valid = ['jpg', 'jpeg', 'png', 'gif'];
    $ekstensi_gambar = explode('.', $nama_gambar);
    $ekstensi_gambar = strtolower(end($ekstensi_gambar));
    if (!in_array($ekstensi_gambar, $ekstensi_gambar_valid)) {
        setAlert('Gagal mengupload gambar', 'Pilih gambar yang berekstensi gambar!', 'error');
        return false;
    }

    // Cek ukuran gambar (1MB)
    $ukuran_max = 1000000;
    if ($ukuran_gambar > $ukuran_max) {
        setAlert('Gagal mengupload gambar', 'Ukuran gambar terlalu besar!', 'error');
        return false;
    }

    // Generate nama baru secara acak
    $nama_gambar_baru = uniqid() . '.' . $ekstensi_gambar;

    // Tentukan direktori tujuan
    $direktori_tujuan = '../assets/img/products/' . $nama_gambar_baru;

    // Pindahkan file gambar ke direktori yang ditentukan
    if (!move_uploaded_file($tmp_name, $direktori_tujuan)) {
        setAlert('Gagal mengupload gambar', 'Terjadi kesalahan saat mengunggah gambar!', 'error');
        return false;
    }

    return $nama_gambar_baru;
}

function tambahProduk($data)
{
    global $koneksi;

    if (!isset($_SESSION['id_user'])) {
        // Atur nilai $_SESSION['id_user'] di sini, misalnya dari hasil proses login
        $_SESSION['id_user'] = 'nilai_id_user';
    }

    $brand = htmlspecialchars(addslashes($data['brand']));
    $nama_produk = htmlspecialchars(addslashes($data['nama_produk']));
    $harga = htmlspecialchars($data['harga']);
    $stok = htmlspecialchars($data['stok']);
    $deskripsi = htmlspecialchars($data['deskripsi']);
    $id_kategori = htmlspecialchars($data['id_kategori']);
    $id_user = $_SESSION['id_user'];

    // Check if the cover image was uploaded successfully
    if (!isset($_FILES['gambar_produk']) || $_FILES['gambar_produk']['error'] !== UPLOAD_ERR_OK) {
        // File tidak berhasil di-upload, beri tahu pengguna atau lakukan tindakan yang sesuai
        return false;
    }

    // Generate unique file name for the uploaded image
    $nama_gambar = uniqid() . '_' . $_FILES['gambar_produk']['name'];

    // Move uploaded file to desired location
    $upload_path = '../assets/img/products/' . $nama_gambar;
    if (!move_uploaded_file($_FILES['gambar_produk']['tmp_name'], $upload_path)) {
        // Gagal memindahkan file, beri tahu pengguna atau lakukan tindakan yang sesuai
        return false;
    }

    // Insert into tb_produk table
    $query = "INSERT INTO tb_produk
              VALUES ('','$brand', '$nama_produk', '$harga', '$stok', '$deskripsi', '$nama_gambar', '$id_kategori', '$id_user')";
    mysqli_query($koneksi, $query);

    // Record the action in the history
    riwayat($id_user, "Berhasil menambahkan produk $nama_produk");

    return mysqli_affected_rows($koneksi);
}



function ubahProduk($data)
{
    global $koneksi;

    if (!isset($_SESSION['id_user'])) {
        // Atur nilai $_SESSION['id_user'] di sini, misalnya dari hasil proses login
        $_SESSION['id_user'] = 'nilai_id_user';
    }

    $id_produk = htmlspecialchars($data['id_produk']);
    $brand = htmlspecialchars(addslashes($data['brand']));
    $nama_produk = htmlspecialchars(addslashes($data['nama_produk']));
    $harga = htmlspecialchars($data['harga']);
    $stok = htmlspecialchars($data['stok']);
    $deskripsi = htmlspecialchars($data['deskripsi']);
    $id_kategori = htmlspecialchars($data['id_kategori']);
    $id_user = $_SESSION['id_user'];

    // Check if the cover image was uploaded successfully
    if (!isset($_FILES['gambar_produk']) || $_FILES['gambar_produk']['error'] !== UPLOAD_ERR_OK) {
        // Jika tidak ada gambar baru diunggah, lakukan pembaruan data produk tanpa mengubah gambar
        $query = "UPDATE tb_produk SET brand = '$brand', nama_produk = '$nama_produk', harga = '$harga', stok = '$stok', deskripsi = '$deskripsi', id_kategori = '$id_kategori' WHERE id_produk = '$id_produk'";
        mysqli_query($koneksi, $query);

        // Record the action in the history
        riwayat($id_user, "Berhasil mengubah produk dengan ID $id_produk");

        return mysqli_affected_rows($koneksi);
    }

    // Generate unique file name for the uploaded image
    $nama_gambar = uniqid() . '_' . $_FILES['gambar_produk']['name'];

    // Move uploaded file to desired location
    $upload_path = '../assets/img/products/' . $nama_gambar;
    if (!move_uploaded_file($_FILES['gambar_produk']['tmp_name'], $upload_path)) {
        // Gagal memindahkan file, beri tahu pengguna atau lakukan tindakan yang sesuai
        return false;
    }

    // Get the current image file name
    $query_get_gambar = mysqli_query($koneksi, "SELECT gambar_produk FROM tb_produk WHERE id_produk = '$id_produk'");
    $row = mysqli_fetch_assoc($query_get_gambar);
    $gambar_produk_lama = $row['gambar_produk'];

    // Update tb_produk table with new data including the new image file name
    $query = "UPDATE tb_produk SET brand = '$brand', nama_produk = '$nama_produk', harga = '$harga', stok = '$stok', deskripsi = '$deskripsi', gambar_produk = '$nama_gambar', id_kategori = '$id_kategori' WHERE id_produk = '$id_produk'";
    mysqli_query($koneksi, $query);

    // Record the action in the history
    riwayat($id_user, "Berhasil mengubah produk dengan ID $id_produk");

    // Delete the old image file if the update was successful
    if (mysqli_affected_rows($koneksi) > 0) {
        unlink('../assets/img/products/' . $gambar_produk_lama);
    }

    return mysqli_affected_rows($koneksi);
}


function hapusProduk($id_produk)
{
    global $koneksi;

    try {
        // Nonaktifkan pengecekan foreign key constraint
        mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 0");

        // Ambil data produk berdasarkan id
        $query_produk = mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id_produk = '$id_produk'");
        $data_produk = mysqli_fetch_assoc($query_produk);
        $nama_produk = $data_produk['nama_produk'];

        // Hapus data produk dari tabel produk
        mysqli_query($koneksi, "DELETE FROM tb_produk WHERE id_produk = '$id_produk'");

        // Aktifkan kembali pengecekan foreign key constraint
        mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 1");

        // Catat riwayat penghapusan produk
        // Pastikan Anda memiliki fungsi riwayat yang sesuai
        // Gantilah $_SESSION['id_user'] dengan nilai yang sesuai
        riwayat($_SESSION['id_user'], "Berhasil menghapus produk $nama_produk");

        // Mengembalikan jumlah baris yang terpengaruh oleh operasi penghapusan data
        return mysqli_affected_rows($koneksi);
    } catch (Exception $e) {
        // Jika terjadi kesalahan, aktifkan kembali pengecekan foreign key constraint
        mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 1");
        throw $e; // lemparkan kembali exception
    }
}
class Product
{
    public $db = null;

    public function __construct($koneksi)
    {
        if (!$koneksi) return null;
        $this->db = $koneksi;
    }

    // fetch product data using getData Method
    public function getData($table = 'tb_produk'){
        $result = $this->db->query("SELECT * FROM {$table}");

        $resultArray = array();

        // fetch product data one by one
        while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $resultArray[] = $item;
        }

        return $resultArray;
    }

    // get product using item id
    public function getProduct($item_id = null, $table= 'tb_produk'){
        if (isset($item_id)){
            $result = $this->db->query("SELECT * FROM {$table} WHERE id_produk={$item_id}");

            $resultArray = array();

            // fetch product data one by one
            while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $resultArray[] = $item;
            }

            return $resultArray;
        }
    }

}
// php cart class
class Cart
{
    public $db = null;

    public function __construct($koneksi)
    {
        if (!$koneksi) return null;
        $this->db = $koneksi;
    }

    // insert into cart table
    public function insertIntoCart($params = null, $table = "tb_cart"){
        if ($this->db != null){
            if ($params != null){
                // get table columns
                $columns = implode(',', array_keys($params));
                $values = implode(',' , array_map(function($value) { return "'$value'"; }, array_values($params)));

                // create sql query
                $query_string = sprintf("INSERT INTO %s(%s) VALUES(%s)", $table, $columns, $values);

                // execute query
                $result = $this->db->query($query_string);
                return $result;
            }
        }
    }

    // to get user_id and item_id and insert into cart table
    public function addToCart($userid, $itemid){
        if (isset($userid) && isset($itemid)){
            $params = array(
                "id_user" => $userid,
                "id_produk" => $itemid,
                "qty" => 1 // default quantity to 1
            );

            // insert data into cart
            $result = $this->insertIntoCart($params);
            if ($result){
                // Reload Page
                header("Location: " . $_SERVER['PHP_SELF']);
            }
        }
    }

    // delete cart item using cart item id
    public function deleteCart($item_id = null, $table = 'tb_cart'){
        if($item_id != null){
            $result = $this->db->query("DELETE FROM {$table} WHERE id_cart={$item_id}");
            if($result){
                header("Location:" . $_SERVER['PHP_SELF']);
            }
            return $result;
        }
    }

    // calculate sub total
    public function getSum($arr){
        if(isset($arr)){
            $sum = 0;
            foreach ($arr as $item){
                $sum += floatval($item['subtotal']);
            }
            return sprintf('%.2f' , $sum);
        }
    }

    // get item_id of shopping cart list
    public function getCartId($cartArray = null, $key = "id_produk"){
        if ($cartArray != null){
            $cart_id = array_map(function ($value) use($key){
                return $value[$key];
            }, $cartArray);
            return $cart_id;
        }
    }

    // update cart item quantity
    public function updateCartQty($item_id, $qty, $table = 'tb_cart'){
        if(isset($item_id) && isset($qty)){
            $result = $this->db->query("UPDATE {$table} SET qty={$qty} WHERE id_cart={$item_id}");
            return $result;
        }
    }

    public function checkout($id_user, $nama_pelanggan, $alamat_pelanggan, $metode_pembayaran, $product_id = null, $product_qty = 1)
    {
        // Ambil data keranjang belanja berdasarkan id_user
        if ($product_id) {
            // Checkout produk satuan
            $query = "SELECT p.id_produk, p.nama_produk, p.harga, $product_qty as qty, p.stok, p.gambar_produk
                      FROM tb_produk p
                      WHERE p.id_produk = $product_id";
        } else {
            // Checkout semua produk dalam keranjang
            $query = "SELECT c.id_cart, p.id_produk, p.nama_produk, p.harga, c.qty, p.stok, p.gambar_produk
                      FROM tb_cart c
                      INNER JOIN tb_produk p ON c.id_produk = p.id_produk
                      WHERE c.id_user = $id_user";
        }
        $result = $this->db->query($query);
    
        if (!$result) {
            die("Error dalam query: " . $this->db->error);
        }
    
        // Menyimpan hasil query dalam array
        $cart_items = [];
        $total_pembayaran = 0;
        while ($row = $result->fetch_assoc()) {
            $subtotal = $row['harga'] * $row['qty'];
            $total_pembayaran += $subtotal;
            $row['subtotal'] = $subtotal;
            $cart_items[] = $row;
        }
    
        // Jika checkout produk satuan, atur qty ke nilai yang diberikan
        if ($product_id) {
            $cart_items[0]['qty'] = $product_qty;
        }
    
        // Buat deskripsi produk yang di-checkout
        $deskripsi_produk = ""; // Inisialisasi deskripsi
        foreach ($cart_items as $cart_item) {
            $deskripsi_produk .= $cart_item['nama_produk'] . " (" . $cart_item['qty'] . "x), ";
        }
        $deskripsi_produk = rtrim($deskripsi_produk, ", "); // Hapus koma terakhir
    
        // Mulai transaksi
        $this->db->begin_transaction();
    
        // Masukkan data ke dalam tabel tb_penjualan
        $tanggal_penjualan = date('Y-m-d H:i:s');
        $query_penjualan = "INSERT INTO tb_penjualan (nama_pelanggan, deskripsi, alamat_pelanggan, metode_pembayaran, total_pembayaran, tanggal_penjualan) VALUES ('$nama_pelanggan', '$deskripsi_produk', '$alamat_pelanggan', '$metode_pembayaran', '$total_pembayaran', '$tanggal_penjualan')";
        $result_penjualan = $this->db->query($query_penjualan);
    
        if (!$result_penjualan) {
            $this->db->rollback(); // Rollback transaksi jika gagal
            die("Error dalam query: " . $this->db->error);
        } else {
            // Hapus keranjang belanja setelah checkout berhasil jika produk dari keranjang
            if (!$product_id) {
                $query_hapus_keranjang = "DELETE FROM tb_cart WHERE id_user = $id_user";
                $result_hapus_keranjang = $this->db->query($query_hapus_keranjang);
                if (!$result_hapus_keranjang) {
                    $this->db->rollback(); // Rollback transaksi jika gagal
                    die("Error dalam menghapus keranjang: " . $this->db->error);
                }
            }
    
            // Kurangi stok produk
            foreach ($cart_items as $cart_item) {
                $new_stok = $cart_item['stok'] - $cart_item['qty'];
                $id_produk = $cart_item['id_produk'];
                $query_update_stok = "UPDATE tb_produk SET stok = $new_stok WHERE id_produk = $id_produk";
                $result_update_stok = $this->db->query($query_update_stok);
                if (!$result_update_stok) {
                    $this->db->rollback(); // Rollback transaksi jika gagal
                    die("Error dalam mengupdate stok produk: " . $this->db->error);
                }
            }
    
            $this->db->commit(); // Commit transaksi jika berhasil
            echo "<script>alert('Checkout berhasil!'); window.location='transaksi.php';</script>";
        }
    }
    
    public function checkStock($itemid)
    {
        $query = "SELECT stok FROM tb_produk WHERE id_produk = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $itemid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['stok'] > 0;
    }
    public function isInCart($userid, $itemid)
    {
        $query = "SELECT * FROM tb_cart WHERE id_user = ? AND id_produk = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $userid, $itemid);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    // Checkout and insert data into tb_penjualan
}


?>