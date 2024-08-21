<?php 
	require '../koneksi.php';

	// Periksa apakah id_kategori telah diset sebelum mengaksesnya
	if (isset($_GET['id_kategori'])) {
		$id_kategori = $_GET['id_kategori'];

		// Ambil data kategori berdasarkan id_kategori
		$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_kategori WHERE id_kategori = '$id_kategori'"));

		// Periksa apakah data ditemukan
		if ($data) {
			$nama_kategori = ucwords($data['nama_kategori']);

			// Panggil fungsi hapusKategori jika data ditemukan
			if (hapusKategori($id_kategori) > 0) {
				setAlert("Berhasil dihapus", "Kategori $nama_kategori berhasil dihapus", "success");
      			header("Location: kategori_produk.php");
			}
		} else {
			// Tampilkan pesan kesalahan jika data tidak ditemukan
			setAlert("Error", "Kategori tidak ditemukan", "error");
			header("Location: kategori_produk.php");
		}
	} else {
		// Tampilkan pesan kesalahan jika id_kategori tidak diset
		setAlert("Error", "ID Kategori tidak ditemukan", "error");
		header("Location: kategori_produk.php");
	}
?>
