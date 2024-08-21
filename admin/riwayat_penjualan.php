<?php 
  require '../koneksi.php';
  checkLogin();
  $riwayat = mysqli_query($koneksi, "SELECT * FROM tb_penjualan ORDER BY tanggal_penjualan DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <?php include '../include_admin/css.php'; ?>
  <title>Riwayat</title>
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
            <h1 class="m-0 text-dark">Riwayat Penjualan</h1>
          </div><!-- /.col -->
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
                    <th>Nama Pelanggan</th>
                    <th>Deskripsi</th>
                    <th>Alamat Pelanggan</th>
                    <th>Metode Pembayaran</th>
                    <th>Total Pembayaran</th>
                    <th>Tanggal Penjualan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php while ($data = mysqli_fetch_assoc($riwayat)): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= $data['nama_pelanggan']; ?></td>
                      <td><?= $data['deskripsi']; ?></td>
                      <td><?= $data['alamat_pelanggan']; ?></td>
                      <td><?= $data['metode_pembayaran']; ?></td>
                      <td>Rp <?= number_format($data['total_pembayaran'], 0, ',', '.'); ?></td>
                      <td><?= date('d-m-Y H:i:s', strtotime($data['tanggal_penjualan'])); ?></td>
                    </tr>
                  <?php endwhile; ?>
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
