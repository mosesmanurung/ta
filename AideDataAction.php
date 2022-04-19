<?php

// Start session
session_start();

// Load and initialize database class
require_once 'config.php';

$db = new DB();

$tblName = 'aide_rules';

// Set default redirect url
$redirectURL = 'Aide-List.php';

if(isset($_POST['dataSubmit'])){

    // Get submitted data
    $rulesname = trim(strip_tags($_POST['rulesname']));
    $rules = trim(strip_tags($_POST['rules']));

    // Fields validation
    $errorMsg = '';
    if(empty($rulesname)){
        $errorMsg .= '<p>Please enter a valid rules name.</p>';
    }
    if(empty($rules)){
        $errorMsg .= '<p>Please enter a valid rules.</p>';
    }
/*    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errorMsg .= '<p>Please enter a valid email.</p>';
    }
    if(empty($phone) || !preg_match("/^[-+0-9]{6,20}$/", $phone)){
        $errorMsg .= '<p>Please enter a valid phone number.</p>';
    } */
    
    // Submitted form data
    $aideData = array(
        'rulesname'  => $rulesname,
        'rules' => $rules,
    );
    
    // Store the submitted field value in the session
    $sessData['aideData'] = $aideData;
    
    // Submit the form data
    if(empty($errorMsg)){
        if(!empty($_POST['id'])){
            // Update user data
            $condition = array('id' => $_POST['id']);
            $update = $db->update($tblName, $aideData, $condition);
            
            if($update){
                $sessData['status']['type'] = 'success';
                $sessData['status']['msg'] = 'User data has been updated successfully.';
                
                // Remote submitted fields value from session
                unset($sessData['aideData']);
            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'Some problem occurred, please try again.';
                
                // Set redirect url
                $redirectURL = 'AIDE-Tambah.php';
            }
        }else{
            // Insert user data
            $insert = $db->insert($tblName, $aideData);
            
            if($insert){
                $sessData['status']['type'] = 'success';
                $sessData['status']['msg'] = 'User data has been added successfully.';
                
                // Remote submitted fields value from session
                unset($sessData['aideData']);
            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'Some problem occurred, please try again.';
                
                // Set redirect url
                $redirectURL = 'Aide-List.php';
            }
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = '<p>Please fill all the mandatory fields.</p>'.$errorMsg;
        
        // Set redirect url
        $redirectURL = 'AIDE-Tambah.php';
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
