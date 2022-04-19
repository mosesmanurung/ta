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
                        <h1 class="h3 mb-0 text-gray-800">Add Snort Rules</h1>
                    </div>

                </div>

                <!-- Content Row -->
                <div class="wrapper">
                <div class="container-fluid">
                <div class="row">
                <div class="col-md-12">
                
                <?php

                $postData = $snort_rules = array();

                // Get session data
                $sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

                // Get status message from session
                if(!empty($sessData['status']['msg'])){
                    $statusMsg = $sessData['status']['msg'];
                    $statusMsgType = $sessData['status']['type'];
                    unset($_SESSION['sessData']['status']);
                }

                // Get posted data from session
                if(!empty($sessData['postData'])){
                    $postData = $sessData['postData'];
                    unset($_SESSION['sessData']['postData']);
                }

                // Get user data
                if(!empty($_GET['id'])){
                    include 'config.php';
                    $db = new DB();
                    $conditions['where'] = array(
                        'id' => $_GET['id'],
                    );
                    $conditions['return_type'] = 'single';
                    $snort_rules = $db->getRows('snort_rules', $conditions);
                }

                // Pre-filled data
                $snort_rules = !empty($postData)?$postData:$snort_rules;

                // Define action
                $actionLabel = !empty($_GET['id'])?'Edit':'Add';

                ?>

                <!-- Display status message -->
                <?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
                <div class="alert alert-success"><?php echo $statusMsg; ?></div>
                <?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
                <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
                <?php } ?>

                <!-- Add/Edit form -->
                <div class="panel panel-default">
                    <div class="panel-heading"><a href="Snort-List.php" class="glyphicon glyphicon-arrow-left"></a></div>
                    <div class="panel-body">
                        <form method="post" action="SnortDataAction.php" class="form">
                            <div class="form-group">
                                <label>Protokol</label>
                                <select class="form-control" name="protokol" value="<?php echo !empty($snort_rules['protokol'])?$snort_rules['protokol']:''; ?>">>
                                <option disabled selected></option>
                                <option value="TCP">TCP</option>
                                <option value="UDP">UDP</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>IP Source</label>
                                <input type="text" class="form-control" name="ips" value="<?php echo !empty($snort_rules['ips'])?$snort_rules['ips']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Port Source</label>
                                <input type="text" class="form-control" name="ports" value="<?php echo !empty($snort_rules['ports'])?$snort_rules['ports']:''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Direction</label>
                                <select class="form-control" name="direct" value="<?php echo !empty($snort_rules['direct'])?$snort_rules['direct']:''; ?>">>
                                <option disabled selected></option>
                                <option value="->">-></option>
                                <option value="<>"><></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>IP Destination</label>
                                <input type="text" class="form-control" name="ipd" value="<?php echo !empty($snort_rules['ipd'])?$snort_rules['ipd']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Port Destination</label>
                                <input type="text" class="form-control" name="portd" value="<?php echo !empty($snort_rules['portd'])?$snort_rules['portd']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Opt Rules</label>
                                <input type="text" class="form-control" name="opt" value="<?php echo !empty($snort_rules['opt'])?$snort_rules['opt']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Klasifikasi Serangan</label>
                                <input type="text" class="form-control" name="klasifikasi" value="<?php echo !empty($snort_rules['klasifikasi'])?$snort_rules['klasifikasi']:''; ?>">
                            </div>
                            <input type="hidden" name="id" value="<?php echo !empty($snort_rules['id'])?$snort_rules['id']:''; ?>">
                            <input type="submit" name="dataSubmit" class="btn btn-success" value="SUBMIT"/>
                        </form>
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