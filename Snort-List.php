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
                        <h1 class="h3 mb-0 text-gray-800">Data Rules Snort</h1>
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
                            'action' => $searchKeyword,
                            'protokol' => $searchKeyword,
                            'ips' => $searchKeyword,
                            'ports' => $searchKeyword,
                            'direct' => $searchKeyword,
                            'ipd' => $searchKeyword,
                            'portd' => $searchKeyword,
                            'message' => $searchKeyword
                        );
                    }

                    // Get count of the users
                    $con = array(
                        'like_or' => $searchArr,
                        'return_type' => 'count'
                    );
                    $rowCount = $db->getRows('snort_rules', $con);

                    // Initialize pagination class
                    $pagConfig = array(
                        'baseURL' => 'Snort-List.php'.$searchStr,
                        'totalRows' => $rowCount,
                        'perPage' => $perPageLimit
                    );
                    $pagination = new Pagination($pagConfig);

                    // Get users from database
                    $con = array(
                        'like_or' => $searchArr,
                        'start' => $offset,
                        'limit' => $perPageLimit,
                        'order_by' => 'id DESC',
                    );
                    $rules = $db->getRows('snort_rules', $con);

                    ?>

                    <!-- Display status message -->
                    <?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
                    <div class="alert alert-success"><?php echo $statusMsg; ?></div>
                    <?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
                    <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
                    <?php } ?>

                    <div class="row">
<!--                         <div class="col-md-12 search-panel">
                            <!-- Add link -->
                            <br>
                            <span class="pull-right">
                                <a href="Snort-Tambah.php" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> New Snort Rules</a>
                            </span>
                            <br>
                            <br>
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
                        </div> -->
                        
                        <!-- Data list table --> 
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Action</th>
                                    <th>Protokol</th>
                                    <th>IP Source</th>
                                    <th>Port Source</th>
                                    <th>Direction</th>
                                    <th>IP Destination</th>
                                    <th>Port Destination</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!empty($rules)){ $count = 0;
                                    foreach($rules as $rule){ $count++;
                                ?>
                                <tr>
                                    <td><?php echo ''.$count; ?></td>
                                    <td><?php echo $rule['action']; ?></td>
                                    <td><?php echo $rule['protokol']; ?></td>
                                    <td><?php echo $rule['ips']; ?></td>
                                    <td><?php echo $rule['ports']; ?></td>
                                    <td><?php echo $rule['direct']; ?></td>
                                    <td><?php echo $rule['ipd']; ?></td>
                                    <td><?php echo $rule['portd']; ?></td>
                                    <td><?php echo $rule['message']; ?></td>
<!--                                     <td>
                                        <a href="Snort-Tambah.php?id=<?php echo $rule['id']; ?>" class="fa fa-pen"></a>
                                        <a href="SnortDataAction.php?action_type=delete&id=<?php echo $rule['id']; ?>" class="fa fa-trash" onclick="return confirm('Are you sure to delete?')"></a>
                                    </td> -->
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
