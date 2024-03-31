<?php 
  require 'connection.php';
  checkLogin();
  $bulan_pembayaran = mysqli_query($conn, "SELECT * FROM bulan_pembayaran ORDER BY tahun ASC");
  if (isset($_POST['btnAddBulanPembayaran'])) {
    if (addBulanPembayaran($_POST) > 0) {
      setAlert("Cash has Been Added", "Successfully Added", "Success");
      header("Location: uang_kas.php");
    }
  }

  if (isset($_POST['btnEditBulanPembayaran'])) {
    if (editBulanPembayaran($_POST) > 0) {
      setAlert("Cash has Been Changed", "Successfully Changed", "Success");
      header("Location: uang_kas.php");
    }
  }


?>

<!DOCTYPE html>
<html>
<head>
  <?php include 'include/css.php'; ?>
  <title>Cash</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <?php include 'include/navbar.php'; ?>

  <?php include 'include/sidebar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row justify-content-center mb-2">
          <div class="col-sm">
            <h1 class="m-0 text-dark">Cash</h1>
          </div><!-- /.col -->
          <div class="col-sm text-right">
            <?php if ($_SESSION['id_jabatan'] !== '3'): ?>
              <button class="btn btn-primary" data-toggle="modal" data-target="#tambahBulanPembayaranModal"><i class="fas fa-fw fa-plus"></i> Add Month</button>
              <!-- Modal -->
              <div class="modal fade text-left" id="tambahBulanPembayaranModal" tabindex="-1" role="dialog" aria-labelledby="tambahBulanPembayaranModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <form method="post">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="tambahBulanPembayaranModalLabel">Add Monthly Payment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-lg">
                            <div class="form-group">
                              <label for="nama_bulan">Month Name</label>
                              <select name="nama_bulan" id="nama_bulan" class="form-control">
                                <option value="januari">January</option>
                                <option value="februari">February</option>
                                <option value="maret">March</option>
                                <option value="april">April</option>
                                <option value="mei">May</option>
                                <option value="juni">June</option>
                                <option value="juli">July</option>
                                <option value="agustus">August</option>
                                <option value="september">September</option>
								<option value="september">October</option>
                                <option value="november">November</option>
                                <option value="desember">Desember</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-lg">
                            <div class="form-group">
                              <label for="tahun">Year</label>
                              <input type="number" required name="tahun" value="<?= date('Y'); ?>" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="pembayaran_perminggu">Weekly Payment</label>
                          <input type="number" name="pembayaran_perminggu" id="pembayaran_perminggu" required class="form-control" placeholder="Rp.">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                        <button type="submit" name="btnAddBulanPembayaran" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Save</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            <?php endif ?>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-lg text-left">
            <h5>Choose Month</h5>
          </div>
        </div>
        <div class="row">
          <?php foreach ($bulan_pembayaran as $dbp): ?>
            <?php 
              $id_bulan_pembayaran = $dbp['id_bulan_pembayaran'];
              $total_uang_kas_bulan_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(minggu_ke_1 + minggu_ke_2 + minggu_ke_3 + minggu_ke_4) as total_uang_kas_bulan_ini FROM uang_kas WHERE id_bulan_pembayaran = '$id_bulan_pembayaran'"));
              $total_uang_kas_bulan_ini = $total_uang_kas_bulan_ini['total_uang_kas_bulan_ini'];
            ?>
            <div class="col-lg-3">
              <div class="card shadow">
                <div class="card-body">
                  <h5><a href="detail_bulan_pembayaran.php?id_bulan_pembayaran=<?= $dbp['id_bulan_pembayaran']; ?>" class="text-dark"><?= ucwords($dbp['nama_bulan']); ?></a></h5>
                  <h6 class="text-muted"><?= $dbp['tahun']; ?></h6>
                  <h6>Rp. <?= number_format($dbp['pembayaran_perminggu']); ?> / week</h6>
                  <h6>Total Cash This Month: <span class="my-2 btn btn-success">Rp. <?= number_format($total_uang_kas_bulan_ini); ?></span></h6>
                  <a href="detail_bulan_pembayaran.php?id_bulan_pembayaran=<?= $dbp['id_bulan_pembayaran']; ?>" class="btn btn-info"><i class="fas fa-fw fa-align-justify"></i></a>
                  <!-- <button type="button" data-toggle="modal" data-target="#editBulanPembayaranModal<?= $dbp['id_bulan_pembayaran']; ?>" class="btn btn-success"><i class="fas fa-fw fa-edit"></i></button> -->
                  <!-- Modal -->
                  <div class="modal fade" id="editBulanPembayaranModal<?= $dbp['id_bulan_pembayaran']; ?>" tabindex="-1" role="dialog" aria-labelledby="editBulanPembayaranModalLabel<?= $dbp['id_bulan_pembayaran']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <form method="post">
                        <input type="hidden" name="id_bulan_pembayaran" value="<?= $dbp['id_bulan_pembayaran']; ?>">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editBulanPembayaranModalLabel<?= $dbp['id_bulan_pembayaran']; ?>">Change Month</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-lg">
                                <div class="form-group">
                                  <label for="nama_bulan<?= $dbp['id_bulan_pembayaran']; ?>">Month Name</label>
                                  <input type="hidden" name="nama_bulan" value="<?= $dbp['nama_bulan']; ?>">
                                  <input style="cursor: not-allowed;" disabled type="text" class="form-control" id="nama_bulan<?= $dbp['id_bulan_pembayaran']; ?>" value="<?= $dbp['nama_bulan']; ?>">
                                </div>
                              </div>
                              <div class="col-lg">
                                <div class="form-group">
                                  <label for="tahun<?= $dbp['id_bulan_pembayaran']; ?>">Year</label>
                                  <input type="hidden" name="tahun" value="<?= $dbp['tahun']; ?>">
                                  <input style="cursor: not-allowed;" disabled type="number" id="tahun<?= $dbp['id_bulan_pembayaran']; ?>" value="<?= $dbp['tahun']; ?>" class="form-control">
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="pembayaran_perminggu<?= $dbp['id_bulan_pembayaran']; ?>">Weekly Payment</label>
                              <input type="number" name="pembayaran_perminggu" id="pembayaran_perminggu<?= $dbp['id_bulan_pembayaran']; ?>" required class="form-control" placeholder="Rp." value="<?= $dbp['pembayaran_perminggu']; ?>">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                            <button type="submit" name="btnEditBulanPembayaran" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Save</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <?php if ($_SESSION['id_jabatan'] == '1'): ?>
                    <a href="hapus_bulan_pembayaran.php?id_bulan_pembayaran=<?= $dbp['id_bulan_pembayaran']; ?>" class="btn btn-danger btn-delete" data-nama="<?= ucwords($dbp['nama_bulan']); ?> | <?= $dbp['tahun']; ?>"><i class="fas fa-fw fa-trash"></i></a>
                  <?php endif ?>
                </div>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2024 by Agustin Rismawati.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 8.2.4
    </div>
  </footer>

</div>
</body>
</html>
