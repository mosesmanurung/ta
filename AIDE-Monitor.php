<?php 
  include 'login verified/loginverifi.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include 'template/header.php';
    ?>
<script src="https://unpkg.com/gridjs/dist/gridjs.production.min.js"></script>
<link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
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
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">AIDE Monitoring</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Total Allerts -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            <a href="AIDE-Total-Allert.php">Total Allerts</a></div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                $koneksi = mysqli_connect('localhost','ta04','root','ta');
                                                $data_rules = mysqli_query($koneksi, "SELECT * FROM aidemonitor");
                                                $jumlah_rules = mysqli_num_rows($data_rules);
                                                ?>
                                                <?php echo $jumlah_rules; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jumlah Rules -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Jumlah Rules</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                $koneksi = mysqli_connect('localhost','ta04','root','ta');
                                                $data_rules = mysqli_query($koneksi, "SELECT * FROM aide_rules");
                                                $jumlah_rules = mysqli_num_rows($data_rules);
                                                ?>
                                                <?php echo $jumlah_rules; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- IP Address -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">IP Address
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    <?php
                                                    function getClientIP() {

                                                        if (isset($_SERVER)) {

                                                            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                                                                return $_SERVER["HTTP_X_FORWARDED_FOR"];

                                                            if (isset($_SERVER["HTTP_CLIENT_IP"]))
                                                                return $_SERVER["HTTP_CLIENT_IP"];

                                                            return $_SERVER["REMOTE_ADDR"];
                                                        }

                                                        if (getenv('HTTP_X_FORWARDED_FOR'))
                                                            return getenv('HTTP_X_FORWARDED_FOR');

                                                        if (getenv('HTTP_CLIENT_IP'))
                                                            return getenv('HTTP_CLIENT_IP');

                                                        return getenv('REMOTE_ADDR');
                                                    }
                                                    echo getClientIP();
                                                    ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date/Time -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Date/Time</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php
                                                $tz = 'Asia/Jakarta';
                                                $dt = new DateTime("now", new DateTimeZone($tz));
                                                $timestamp = $dt->format('Y-m-d G:i:s');
                                                echo $timestamp;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="wrapper">
                    <div class="container-fluid">
                    <div class="row">
                    <div class="col-md-12">
                        
                        <?php

                        // Get session data
                        $sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

                        // Get status message from session
                        if(!empty($sessData['status']['msg'])){
                            $statusMsg = $sessData['status']['msg'];
                            $statusMsgType = $sessData['status']['type'];
                            unset($_SESSION['sessData']['status']);
                        }

                        // Load pagination class
                        require_once 'Pagination.class.php';

                        // Load and initialize database class
                        require_once 'config.php';
                        $db = new DB();

                        // Page offset and limit
                        $perPageLimit = 10;
                        $offset = !empty($_GET['page'])?(($_GET['page']-1)*$perPageLimit):0;

                        // Get search keyword
                        $searchKeyword = !empty($_GET['sq'])?$_GET['sq']:'';
                        $searchStr = !empty($searchKeyword)?'?sq='.$searchKeyword:'';

                        // Search DB query
                        $searchArr = '';
                        if(!empty($searchKeyword)){
                            $searchArr = array(
                                'objectname' => $searchKeyword,
                                'added' => $searchKeyword,
                                'modified' => $searchKeyword,
                                'removed' => $searchKeyword,
                                'files' => $searchKeyword,
                                'timestamp' => $searchKeyword
                            );
                        }

                        // Get count of the users
                        $con = array(
                            'like_or' => $searchArr,
                            'return_type' => 'count'
                        );
                        $rowCount = $db->getRows('aidemonitor', $con);

                        // Initialize pagination class
                        $pagConfig = array(
                            'baseURL' => 'AIDE-Monitor.php'.$searchStr,
                            'totalRows' => $rowCount,
                            'perPage' => $perPageLimit
                        );
                        $pagination = new Pagination($pagConfig);

                        // Get users from database
                        $con = array(
                            'like_or' => $searchArr,
                            'start' => $offset,
                            'limit' => $perPageLimit,
                            'order_by' => 'timestamp DESC',
                        );
                        $rules = $db->getRows('aidemonitor', $con);

                        ?>

                        <!-- Display status message -->
                        <?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
                        <div class="alert alert-success"><?php echo $statusMsg; ?></div>
                        <?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
                        <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
                        <?php } ?>

                        <div class="row">
                            <div class="col-md-12 search-panel">
                                <!-- Search form -->
                                <form>
                                <div class="input-group">
                                    <input type="text" name="sq" class="form-control" placeholder="Search by keyword..." value="<?php echo $searchKeyword; ?>">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                </form>
                                
                                <br>
                            </div>
                            
                            <!-- Data list table --> 
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Object Name</th>
                                        <th>Added</th>
                                        <th>Modified</th>
                                        <th>Removed</th>
                                        <th>Detail</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($rules)){ $count = 0; 
                                        foreach($rules as $rule){ $count++;
                                    ?>
                                    <tr>
                                        <td><?php echo ''.$count; ?></td>
                                        <td><?php echo $rule['objectname']; ?></td>
                                        <td><?php echo $rule['added']; ?></td>
                                        <td><?php echo $rule['modified']; ?></td>
                                        <td><?php echo $rule['removed']; ?></td>
                                        <td><?php echo $rule['files']; ?></td>
                                        <td><?php echo $rule['timestamp']; ?></td>
                                    </tr>
                                    <?php } }else{ ?>
                                    <tr><td colspan="5">No data(s) found......</td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            
                            <!-- Display pagination links -->
                            <?php echo $pagination->createLinks(); ?>
                        </div>

                </div>

            </div>
            </div>
            </div>
            </div>
            </div>
            <!-- End of Main Content -->

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
