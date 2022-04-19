<?php

// Start session
session_start();

// Load and initialize database class
require_once 'config.php';

$db = new DB();

$tblName = 'snort_rules';

// Set default redirect url
$redirectURL = 'Snort-List.php';

if(isset($_POST['dataSubmit'])){

    // Get submitted data
    $protokol = trim(strip_tags($_POST['protokol']));
    $ips = trim(strip_tags($_POST['ips']));
    $ports = trim(strip_tags($_POST['ports']));
    $direct = trim(strip_tags($_POST['direct']));
    $ipd = trim(strip_tags($_POST['ipd']));
    $portd = trim(strip_tags($_POST['portd']));
    $opt = trim(strip_tags($_POST['opt']));
    $klasifikasi = trim(strip_tags($_POST['klasifikasi']));

    // Fields validation
    $errorMsg = '';
    if(empty($protokol)){
        $errorMsg .= '<p>Please choose the protocol.</p>';
    }
    if(empty($ips) || !preg_match("/^[.0-9]{6,20}$/", $ips)){
        $errorMsg .= '<p>Please enter a valid ip.</p>';
    }
    if(empty($ports)){
        $errorMsg .= '<p>Please enter a valid port source.</p>';
    }
    if(empty($direct)){
        $errorMsg .= '<p>Please choose the direction.</p>';
    }
    if(empty($ipd) || !preg_match("/^[.0-9]{6,20}$/", $ipd)){
        $errorMsg .= '<p>Please enter a valid ip.</p>';
    }
    if(empty($portd)){
        $errorMsg .= '<p>Please enter a valid port destination.</p>';
    }
    if(empty($opt)){
        $errorMsg .= '<p>Please enter a valid opt.</p>';
    }
    if(empty($klasifikasi)){
        $errorMsg .= '<p>Please enter a valid classification.</p>';
    }
/*    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errorMsg .= '<p>Please enter a valid email.</p>';
    }
    if(empty($phone) || !preg_match("/^[-+0-9]{6,20}$/", $phone)){
        $errorMsg .= '<p>Please enter a valid phone number.</p>';
    } */
    
    // Submitted form data
    $snortData = array(
        'protokol'  => $protokol,
        'ips' => $ips,
        'ports' => $ports,
        'direct'  => $direct,
        'ipd' => $ipd,
        'portd' => $portd,
        'opt'  => $opt,
        'klasifikasi' => $klasifikasi
    );
    
    // Store the submitted field value in the session
    $sessData['snortData'] = $snortData;
    
    // Submit the form data
    if(empty($errorMsg)){
        if(!empty($_POST['id'])){
            // Update user data
            $condition = array('id' => $_POST['id']);
            $update = $db->update($tblName, $snortData, $condition);
            
            if($update){
                $sessData['status']['type'] = 'success';
                $sessData['status']['msg'] = 'User data has been updated successfully.';
                
                // Remote submitted fields value from session
                unset($sessData['snortData']);
            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'Some problem occurred, please try again.';
                
                // Set redirect url
                $redirectURL = 'Snort-Tambah.php';
            }
        }else{
            // Insert user data
            $insert = $db->insert($tblName, $snortData);
            
            if($insert){
                $sessData['status']['type'] = 'success';
                $sessData['status']['msg'] = 'User data has been added successfully.';
                
                // Remote submitted fields value from session
                unset($sessData['snortData']);
            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'Some problem occurred, please try again.';
                
                // Set redirect url
                $redirectURL = 'Snort-List.php';
            }
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = '<p>Please fill all the mandatory fields.</p>'.$errorMsg;
        
        // Set redirect url
        $redirectURL = 'Snort-Tambah.php';
    }
    
    // Store status into the session
    $_SESSION['sessData'] = $sessData;
}elseif(($_REQUEST['action_type'] == 'delete') && !empty($_GET['id'])){
    // Delete data
    $condition = array('id' => $_GET['id']);
    $delete = $db->delete($tblName, $condition);
    
    if($delete){
        $sessData['status']['type'] = 'success';
        $sessData['status']['msg'] = 'Rule has been deleted successfully.';
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Some problem occurred, please try again.';
    }
    
    // Store status into the session
    $_SESSION['sessData'] = $sessData;
}

// Redirect to the respective page
header("Location:".$redirectURL);
exit();
?>
