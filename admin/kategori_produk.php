<?php 
	require '../koneksi.php';
	checkLogin();
	$kategori = mysqli_query($koneksi, "SELECT * FROM tb_kategori");
	// jika tombol ubah kategori ditekan
	if (isset($_POST['btnUbahKategori'])) {
		if (ubahKategori($_POST) > 0) {
			setAlert("Berhasil diubah", "Kategori berhasil diubah", "success");
			header("Location: kategori_produk.php");
		}
	}
	// jika tombol tambah kategori ditekan
  if (isset($_POST['btnTambahKategori'])) {
    $result = tambahKategori($_POST);
    if ($result === 0) {
        // Kategori sudah ada, lakukan penanganan kesalahan di sini
        // Misalnya, tampilkan pesan kesalahan kepada pengguna
        echo "Kategori sudah ada. Silakan masukkan kategori yang berbeda.";
    } elseif ($result > 0) {
        // Kategori berhasil ditambahkan
        // Lakukan tindakan yang sesuai, misalnya redirect ke halaman kategori
        setAlert("Berhasil ditambahkan", "Kategori $nama_kategori berhasil ditambahkan", "success");
        header("Location: kategori_produk.php");
        exit;
    } else {
        // Terjadi kesalahan lain, lakukan penanganan sesuai kebutuhan
        echo "Terjadi kesalahan saat menambahkan kategori.";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  <?php include '../include_admin/css.php'; ?>
  <title>Kategori produk</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <?php include '../include_admin/navbar.php'; ?>

  <?php include '../include_admin/sidebar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm">
            <h1 class="m-0 text-dark">Kategori produk</h1>
          </div><!-- /.col -->
          <div class="col-sm text-right">
            <button type="button" data-toggle="modal" data-target="#tambahKategoriModal" class="btn btn-primary"><i class="fas fa-fw fa-plus"></i> Tambah Kategori</button>
            <!-- Modal -->
            <div class="modal fade text-left" id="tambahKategoriModal" tabindex="-1" role="dialog" aria-labelledby="tambahKategoriModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <form method="post" enctype="multipart/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="tambahKategoriModalLabel">Tambah Kategori</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                    <div class="form-group text-center">
                        <img src="../assets/img/kategori/default.png" class="img-profile rounded" id="check_photo" alt="gambar kategori">
                        <div class="form-group">
                            <label for="photo">Gambar Kategori</label>
                            <input type="file" name="gambar_kategori" id="photo" class="btn btn-sm btn-primary form-control form-control-file" accept="image/*">
                        </div>
                    </div>

                      <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" name="nama_kategori" required class="form-control" id="nama_kategori">
                      </div>
                    </div>
                    
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Batal</button>
                      <button type="submit" name="btnTambahKategori" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg">
            <div class="table-responsive">
              <table class="table table-bordered table-hover table-striped" id="table_id">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Gambar Kategori</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach ($kategori as $dg): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td>
                        <a href="../assets/img/kategori/<?= $dg['gambar_kategori']; ?>" class="enlarge">
                            <img class="img-list-cover" src="../assets/img/kategori/<?= $dg['gambar_kategori']; ?>" alt="<?= $dg['gambar_kategori']; ?>">
                        </a>
                    </td>


                      <td><?= $dg['nama_kategori']; ?></td>
                      <td>
                        <button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#ubahKategoriModal<?= $dg['id_kategori']; ?>"><i class="fas fa-fw fa-edit"></i> Ubah</button>
                        <!-- Modal -->
                        <div class="modal fade" id="ubahKategoriModal<?= $dg['id_kategori']; ?>" tabindex="-1" role="dialog" aria-labelledby="ubahKategoriModalLabel<?= $dg['id_kategori']; ?>" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <form method="post" enctype="multipart/form-data">
                              <input type="hidden" name="id_kategori" value="<?= $dg['id_kategori']; ?>">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="ubahKategoriModalLabel<?= $dg['id_kategori']; ?>">Ubah kategori</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group text-center">
                                    <a href="../assets/img/kategori/<?= $dg['id_kategori']; ?>" class="enlarge check_enlarge_photo">
                                      <img src="../assets/img/kategori/<?= $dg['gambar_kategori']; ?>" class="img-profile rounded check_photo" alt="cover produk">
                                    </a>
                                    <div class="form-group">
                                      <label for="gambar_kategori<?= $dg['id_kategori']; ?>">Kategori Produk</label>
                                      <input type="file" name="gambar_kategori" id="gambar_kategori<?= $dg['id_kategori']; ?>" class="photo btn btn-sm btn-primary form-control form-control-file" accept="image/*">
                                    </div>
                                   </div>
                                  <div class="form-group">
                                    <label for="nama_kategori<?= $dg['id_kategori']; ?>">Nama kategori</label>
                                    <input type="text" name="nama_kategori" id="nama_kategori<?= $dg['id_kategori']; ?>" class="form-control" value="<?= $dg['nama_kategori']; ?>" required>
                                  </div>
                                </div>
                                
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Batal</button>
                                  <button type="submit" name="btnUbahKategori" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                        <a href="hapus_kategori_produk.php?id_kategori=<?= $dg['id_kategori']; ?>" data-nama="kategori produk: <?= $dg['nama_kategori']; ?>" class="btn-hapus btn btn-sm btn-danger"><i class="fas fa-fw fa-trash"></i> Hapus</a>
                      </td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
  <strong>&copy; <?php echo date("Y"); ?> Grendy Aditya Pangestu</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>

</div>
<!-- ./wrapper -->
</body>
</html>
