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
                        <h1 class="h3 mb-0 text-gray-800">Data Rules AIDE</h1>
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
                            'rulesname' => $searchKeyword,
                            'rules' => $searchKeyword,
                            'argument' => $searchKeyword,
                        );
                    }

                    // Get count of the users
                    $con = array(
                        'like_or' => $searchArr,
                        'return_type' => 'count'
                    );
                    $rowCount = $db->getRows('aide_rules', $con);

                    // Initialize pagination class
                    $pagConfig = array(
                        'baseURL' => 'Aide-List.php'.$searchStr,
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
                    $rules = $db->getRows('aide_rules', $con);

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
                                <a href="AIDE-Tambah.php" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> New AIDE Rules</a>
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
                                    <th>Rules Name</th>
                                    <th>Rules 
                                       <i data-toggle="modal" data-target="#exampleModalLong" class="fa fa-question"></i>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Group definitions configuration lines</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                        <p>AIDE has set of pre-defined groups which help in specifying which parameters need to be monitored
                                            for particular file /folder or directory hierarchy. Basic AIDE groups are</p>
                                            <table border="1" width="100%">
                                                <th>Indicator</th>
                                                <th>Description</th>
                                                <tr>
                                                    <td>p</td>
                                                    <td>File permissions (rwx, etc.)</td>
                                                </tr>
                                                <tr>
                                                    <td>i</td>
                                                    <td>Inode number</td>
                                                </tr>
                                                <tr>
                                                    <td>l</td>
                                                    <td>Link name</td>
                                                </tr>
                                                <tr>
                                                    <td>n</td>
                                                    <td>Number of links</td>
                                                </tr>
                                                <tr>
                                                    <td>u</td>
                                                    <td>User owner of file</td>
                                                </tr>
                                                <tr>
                                                    <td>g</td>
                                                    <td>Group owner of file</td>
                                                </tr>
                                                <tr>
                                                    <td>s</td>
                                                    <td>Size of file</td>
                                                </tr>
                                                <tr>
                                                    <td>m</td>
                                                    <td>Modification time of file</td>
                                                </tr>
                                                <tr>
                                                    <td>a</td>
                                                    <td>Access time of file</td>
                                                </tr>
                                                <tr>
                                                    <td>c</td>
                                                    <td>Status change or configuration change</td>
                                                </tr>
                                                <tr>
                                                    <td>S</td>
                                                    <td>Checking for growing file size. This is very important for log files whose size should always increase.
                                                        This wont cause any alarms if size change and final size increases. But if final size is less then the
                                                        last time database was initialized(updated) then reduction in size is reported during check operation.</td>
                                                </tr>
                                                <tr>
                                                    <td>I</td>
                                                    <td>        Ignore changed filename. AIDE can recognize the same file with inode number. Now if we do not care
                                                        if filename changes but some other parameter like owner, size, timestamps etc. remain same then we can
                                                        use this option. Note that I will cause 'c' to get ignored as if we change file name the status change
                                                        or configuration change timestamp will get updated.</td>
                                                </tr>
                                                <tr>
                                                    <td>ANF</td>
                                                    <td>Allow new files</td>
                                                </tr>
                                                <tr>
                                                    <td>ARF</td>
                                                    <td>Allow removed files</td>
                                                </tr>
                                                <tr>
                                                    <td>md5, ha1. sha256, sha512, rmd160, tiger, haval, crc32</td>
                                                    <td>calculate respective checksum</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                        <h8>src : <a href="https://www.sbarjatiya.com/notes_wiki/index.php/Configuring_basic_AIDE_server#Group_definitions_configuration_lines" target="_blank"> Configuring_basic_AIDE_server</h8>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                    </th>
                                    <th>Argument</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(!empty($rules)){ $count = 0; 
                                    foreach($rules as $rule){ $count++;
                                ?>
                                <tr>
                                    <td><?php echo ''.$count; ?></td>
                                    <td><?php echo $rule['rulesname']; ?></td>
                                    <td><?php echo $rule['rules']; ?></td>
                                    <td><?php echo $rule['argument']; ?></td>
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
