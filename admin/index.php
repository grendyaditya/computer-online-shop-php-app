<?php 
  require '../koneksi.php';
  checkLogin();
  $jumlah_produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_produk) AS jumlah_produk FROM tb_produk"));
  $jumlah_produk = $jumlah_produk['jumlah_produk'];

  $jumlah_kategori = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_kategori) AS jumlah_kategori FROM tb_kategori"));
  $jumlah_kategori = $jumlah_kategori['jumlah_kategori'];
?>
<!DOCTYPE html>
<html>
<head>
  <?php include '../include_admin/css.php'; ?>
  <title>Dashboard</title>
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
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row my-2">
          <div class="col-lg-3">
            <div class="card">
              <div class="card-body">
                <h5><i class="fas fa-fw fa-theater-masks"></i> Kategori Produk</h5>
                <h6 class="mb-2 text-muted">Jumlah Kategori: <?= $jumlah_kategori; ?></h6>
                <a href="kategori_produk.php" class="card-link btn btn-primary"><i class="fas fa-fw fa-align-justify"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="card">
              <div class="card-body">
                <h5><i class="fas fa-fw fa-book"></i> Produk</h5>
                <h6 class="mb-2 text-muted">Jumlah Produk: <?= $jumlah_produk; ?></h6>
                <a href="produk.php" class="card-link btn btn-primary"><i class="fas fa-fw fa-align-justify"></i></a>
              </div>
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
