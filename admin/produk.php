<?php
  require '../koneksi.php';
  checkLogin();
  $produk = mysqli_query($koneksi, "SELECT tb_produk.*, tb_kategori.nama_kategori, tb_admin.username
  FROM tb_produk
  INNER JOIN tb_kategori ON tb_produk.id_kategori = tb_kategori.id_kategori
  INNER JOIN tb_admin ON tb_produk.id_user = tb_admin.id_user
  ORDER BY tb_produk.nama_produk ASC");


  $kategori = mysqli_query($koneksi, "SELECT * FROM tb_kategori ORDER BY nama_kategori ASC");

  // jika tombol ubah produk ditekan
  if (isset($_POST['btnUbahProduk'])) {
    if (ubahProduk($_POST) > 0) {
      setAlert("Berhasil diubah", "Produk berhasil diubah", "success");
      header("Location: produk.php");
    }
  }

  // jika tombol tambah Produk ditekan
  if (isset($_POST['btnTambahProduk'])) {
    // Panggil fungsi tambahProduk dengan data Produk
    if (tambahProduk($_POST) > 0) {
        $nama_produk = htmlspecialchars(addslashes(ucwords($_POST['nama_produk'])));
        setAlert("Berhasil ditambahkan", "Produk $nama_produk berhasil ditambahkan", "success");
        header("Location: produk.php");
        exit();
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
  <?php include '../include_admin/css.php'; ?>
  <title>Daftar Produk</title>
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
            <h1 class="m-0 text-dark">Daftar Produk</h1>
          </div><!-- /.col -->
          <div class="col-sm text-right">
            <button type="button" data-toggle="modal" data-target="#tambahProdukModal" class="btn btn-primary"><i class="fas fa-fw fa-plus"></i> Tambah Produk</button>
            <!-- Modal -->
            <div class="modal fade text-left" id="tambahProdukModal" tabindex="-1" role="dialog" aria-labelledby="tambahProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahProdukModalLabel">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group text-center">
                        <a href="../assets/img/products/default.png" class="enlarge" id="check_enlarge_photo">
                            <img src="../assets/img/products/default.png" height="200px" width="200px" class="img-profile rounded" id= "check_photo" alt="cover produk">
                        </a>
                        <div class="form-group">
                            <label for="photo">Gambar Produk</label>
                            <input type="file" name="gambar_produk" id="photo" class="btn btn-sm btn-primary form-control form-control-file" accept="image/*">

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="brand">Nama Brand</label>
                        <input type="text" name="brand" required class="form-control" id="brand">
                    </div>
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" name="nama_produk" required class="form-control" id="nama_produk">
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" name="harga" required class="form-control" id="harga">
                    </div>
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" name="stok" required class="form-control" id="stok">
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" required class="form-control" id="deskripsi" cols="30" rows="10"></textarea>
                    </div> <div class="form-group">
                    <label for="kategori">Kategori</label>
                      <?php foreach ($kategori as $cat): ?>
                    <div class="form-check">
                        <input type="radio" name="id_kategori" id="kategori<?= $cat['id_kategori']; ?>" value="<?= $cat['id_kategori']; ?>" class="form-check-input">
                        <label class="form-check-label" for="kategori<?= $cat['id_kategori']; ?>"><?= ucwords($cat['nama_kategori']); ?></label>
                    </div>
                    <?php endforeach ?>   
                    </div>
                    

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Batal</button>
                    <button type="submit" name="btnTambahProduk" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan</button>
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
                    <th>Gambar Produk</th>
                    <th>Brand</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                    <th>Pemosting</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach ($produk as $df): ?>
         
                    <tr>
                      <td><?= $i++; ?></td>
                        <td>
                          <a href="../assets/img/product/<?= $df['gambar_produk']; ?>" class="enlarge">
                            <img class="img-list-cover" src="../assets/img/products/<?= $df['gambar_produk']; ?>" alt="<?= $df['gambar_produk']; ?>"  height="200px" width="200px">
                          </a>
                        </td>
                      <td><?= $df['brand']; ?></td>
                      <td><?= $df['nama_produk']; ?></td>
                      <td><?= $df['harga']; ?></td>
                      <td><?= $df['stok']; ?></td>
                      <td><?= ucwords($df['nama_kategori']); ?></td>
                      <td><?= isset($df['username']) ? $df['username'] : ''; ?></td>
                      <td>
                      
                        <button class="btn btn-sm m-1 text-center mx-auto btn-success" type="button" data-toggle="modal" data-target="#ubahProdukModal<?= $df['id_produk']; ?>"><i class="fas fa-fw fa-edit"></i> Ubah</button>
                        <!-- Modal -->
                        <a href="hapus_produk.php?id_produk=<?= $df['id_produk']; ?>" data-nama="produk produk: <?= $df['nama_produk']; ?>" class="btn-hapus btn btn-sm m-1 text-center mx-auto btn-danger"><i class="fas fa-fw fa-trash"></i> Hapus</a>
                        <div class="modal fade" id="ubahProdukModal<?= $df['id_produk']; ?>" tabindex="-1" role="dialog" aria-labelledby="ubahProdukModalLabel<?= $df['id_produk']; ?>" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <form method="post" enctype="multipart/form-data">
                              <input type="hidden" name="id_produk" value="<?= $df['id_produk']; ?>">
                              <input type="hidden" name="gambar_produk_lama" value="<?= $df['gambar_produk']; ?>">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="tambahProdukModalLabel">Ubah produk</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group text-center">
                                    <a href="../assets/img/products/<?= $df['id_produk']; ?>" class="enlarge check_enlarge_photo">
                                      <img src="../assets/img/products/<?= $df['gambar_produk']; ?>" class="img-profile rounded check_photo" alt="cover produk"  height="200px" width="200px">
                                    </a>
                                    <div class="form-group">
                                      <label for="cover_produk<?= $df['id_produk']; ?>">Cover Produk</label>
                                      <input type="file" name="cover_produk" id="cover_produk<?= $df['id_produk']; ?>" class="photo btn btn-sm btn-primary form-control form-control-file" accept="image/*">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="brand<?= $df['id_produk']; ?>">Nama Brand</label>
                                    <input type="text" name="brand" value="<?= $df['brand']; ?>" required class="form-control" id="brand<?= $df['id_produk']; ?>">
                                  </div>
                                  <div class="form-group">
                                    <label for="nama_produk<?= $df['id_produk']; ?>">Nama produk</label>
                                    <input type="text" name="nama_produk" value="<?= $df['nama_produk']; ?>" required class="form-control" id="nama_produk<?= $df['id_produk']; ?>">
                                  </div>
                                  <div class="form-group">
                                    <label for="harga<?= $df['id_produk']; ?>">Harga</label>
                                    <input type="number" name="harga" value="<?= $df['harga']; ?>" required class="form-control" id="harga<?= $df['id_produk']; ?>">
                                  </div><div class="form-group">
                                    <label for="stok<?= $df['id_produk']; ?>">Stok</label>
                                    <input type="number" name="stok" value="<?= $df['stok']; ?>" required class="form-control" id="stok<?= $df['id_produk']; ?>">
                                  </div>
                                  <div class="form-group">
                                    <label for="deskripsi<?= $df['id_produk']; ?>">Deskripsi</label>
                                    <textarea name="deskripsi" required class="form-control" id="deskripsi<?= $df['id_produk']; ?>" cols="30" rows="10"><?= $df['deskripsi']; ?></textarea>
                                  </div>
                                  <?php foreach ($kategori as $cat): ?>
                                  <div class="form-check">
                                            <?php
                                            // Periksa apakah kategori saat ini sama dengan kategori sebelumnya
                                            $isChecked = ($cat['id_kategori'] == $df['id_kategori']) ? 'checked' : '';
                                            ?>
                                             <input type="radio" name="id_kategori" id="id_kategori<?= $df['id_produk'] . '_' . $cat['id_kategori']; ?>" class="form-check-input" value="<?= $cat['id_kategori']; ?>" <?= $isChecked; ?>>
                                            <label class="form-check-label" for="id_kategori<?= $df['id_produk'] . '_' . $cat['id_kategori']; ?>"><?= ucwords($cat['nama_kategori']); ?></label>
                                        </div>
                                    <?php endforeach ?>



                                  
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Batal</button>
                                  <button type="submit" name="btnUbahProduk" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                        

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
  </footer>

</div>
<!-- ./wrapper -->
</body>
</html>
