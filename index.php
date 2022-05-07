<?php 
  include 'login verified/loginverifi.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include 'template/header.php';
    ?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <?php
    include 'template/navbar.php';
    ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <?php
                    include 'template/topbar.php';
                    ?>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
               
                <div class="wrapper">
                    <div class="container-fluid">
                    <div class="row">
                    <div class="col-md-12">
                        <h1> Hi, welcome to the website! </h1> <br>
                        <h4>
                            Website ini dapat anda gunakan untuk melakukan monitoring sistem anda dari serangan
                            melalui jaringan komputer atau dari sistem file anda.
                            Website ini hanya mendukung tools Snort sebagai pendeteksi serangan melalui traffic jaringan
                            dan AIDE sebagai pendeteksi serangan pada sistem file.
                            Untuk itu pastikan anda sudah melakukan instalasi kedua tools tersebut.
                        </h4>
                    </div>
                    </div>
                    </div>
                </div>
              
              <br>
              <br>
              <br>
              <center>
              <div class="col-lg-4 mb-4">
              <!-- Project Card Example -->
              <div class="card shadow mb-4">
                  <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">Snort</h6>
                  </div>
                  <div class="card-body">
                      <h5><b>Security<span class="float-right"> Base Score Rating</span></b></h5>
                      <br>
                  </div>
              </div>
              <div class="card shadow mb-4">
                  <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">AIDE</h6>
                  </div>
                  <div class="card-body">
                  <h5><b>
                      <form action="index.php" method="POST">
                          <input type="submit" value="Cek" name="btn" class="btn btn-danger">
                      </form>
                      <?php
                      shell_exec('sudo aide -c /etc/aide/aide.conf --limit /etc --check > aide_check.txt');
                      ?>
                      <?php
                      shell_exec('python3 aide_check.py');
                      ?>
                  </b></h5>
                  <h5><b>
                      <a href="AIDE-Monitor.php"><button class="btn btn-danger">see the data</button></a>
                  </b></h5>
                  </div>
              </div>
              </center>
</div>
            <!-- Footer -->
            <?php
            include 'template/footer.php';
            ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <?php
    include 'template/scrollup.php';
    ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
