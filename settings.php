<?php
    include 'helper/config.php';

    session_start();

    if (isset($_SESSION['username'])) {
        // logged in
        // Something will happen here....
    } else {
        // not logged in
        header('Location: login.php');
    }
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Settings | Credit Card</title>

	<!-- Load all static files -->
	<link rel="stylesheet" type="text/css" href="assets/BS/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
</head>
<body class="container">
    <!-- Config included -->
	<?php 
        include 'helper/navbar.html';
    ?>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <!-- After submitting the form -->
            <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $trans_limit = $_POST['trans_limit'];
                    if($trans_limit > 0){
                        $user_pk = $_POST['user_id'];
                        $update_sql = "UPDATE account SET trans_limit=".$trans_limit." WHERE user_id=".$user_pk;
                        
                        $updated = $conn->query($update_sql);
                        if($updated) {
                            echo '<p class="success-message">Successfully set!!</p>';
                        }else{
                            echo '<p class="error-message">May be you are doing wrong<br>Contact with the Service Provider</p>';
                        }
                    }else {
                        echo '<p class="error-message">SORRY!! You can\'t set 0(Zero) here</p>';
                    }
                }

                // To changing status
				 if(isset($_SERVER['REQUEST_METHOD']) && isset($_GET['submit'])) {
                if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['submit'] == 'Submit') {
                    $status_code = $_GET['status'];
                    $account_pk = $_SESSION['account_id'];
                    $update_status_sql = "UPDATE credit_card SET status=".$status_code." WHERE account_id=".$account_pk;
                    $updated_status = $conn->query($update_status_sql);
                    if($updated_status) {
                        echo '<p class="success-message">Successfully set!!</p>';
                    }else {
                        echo '<p class="error-message">May be you are doing wrong. Contact with the Service Provider</p>';
                    }
                }
				 }
                // For updating branches
				 if(isset($_SERVER['REQUEST_METHOD']) && isset($_GET['submit'])) {
                if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['submit'] == 'Update') {
                    $selected_array =  $_GET['branch_pk'];
                    $array_len = sizeof($selected_array);
                    
                    $formated_string = "[";

                    for($i=0; $i<$array_len; $i++) {
                        $formated_string = $formated_string." ".$selected_array[$i];
                    }
                    $formated_string = $formated_string."]";
                    $account_pk = $_SESSION['account_id'];

                    $update_branch_sql = "UPDATE credit_card SET allowed_branches='".$formated_string."' WHERE account_id=".$account_pk;
                    $updated_data = $conn->query($update_branch_sql);
                    if($updated_data) {
                        echo '<p class="success-message">Successfully set!!</p>';
                    }else {
                        '<p class="error-message">May be you are doing wrong<br>Contact with the Service Provider</p>';
                    }
				 }}
            ?>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <p class="text-22px text-center">Settings</p>
                </div>
                <div class="panel-body">
                    <div class="col-sm-6 col-md-6">
                        <?php
                        // get user data (id only)
                        $email = $_SESSION['username'];
                        $user_data_sql = "SELECT id FROM users WHERE email='".$email."'";
                        $user_data = $conn->query($user_data_sql);
                        if($user_data->num_rows == 1){
                            $user_pk = $user_data->fetch_row()[0];
                            // Now I got login user ID
                            $ac_info_sql = "SELECT id, trans_limit FROM account WHERE user_id=".$user_pk;
                            $account_data = $conn->query($ac_info_sql);
                            
                            // Work with account data
                            if($account_data->num_rows == 1) {
                                $temp_result = $account_data->fetch_row();
                                $transaction_limit = $temp_result[1];
                                // Temporary session
                                $_SESSION['account_id'] = $temp_result[0];
                            }
                        }
                        echo '
                            <p class="list-head">Per Transaction Limit</p>
                            <div class="mini-container">
                                <div class="nice-border">
                                    <form method="POST" action="" class="form-group p-a-sm">
                                        <label>Transaction Limit</label>
                                        <input type="hidden" name="user_id" value="'.$user_pk.'">
                                        <input type="number" name="trans_limit" class="form-control" 
                                            placeholder="Current limit : '.$transaction_limit.'" required/>
                                        <br/>
                                        <input class="btn btn-info btn-block" type="submit" name="submit" value="Update"/>
                                    </form>
                                </div>
                            </div>
                        ';

                        echo "<br><br>";

                        // Change Account Status
                        $account_pk = $_SESSION['account_id'];
                        $get_status_sql = "SELECT status FROM credit_card WHERE account_id=".$account_pk;
                        $status_obj = $conn->query($get_status_sql);
                        if($status_obj->num_rows == 1) {
                            $status = $status_obj->fetch_row()[0];
                        }
                        echo '
                            <p class="list-head">Change Status</p>
                            <div class="mini-container">
                                <div class="nice-border">
                                    <form method="GET" action="" class="form-group p-a-sm">
                                        <div class="checkbox">
                                            <label>
                        ';
                        if($status == 0) {
                            echo '
                                <input class="custom-checkbox" type="checkbox" name="status" value="1">
                                <span class="check-label">Active</span>
                            ';
                        }else {
                            echo '
                                <input class="custom-checkbox" type="checkbox" name="status" value="0">
                                <span class="check-label">Blocked</span>
                            ';
                        }
                        
                        echo'                    </label>
                                        </div>
                                        <br/>
                                        <input class="btn btn-info btn-block" type="submit" name="submit" value="Submit"/>
                                    </form>
                                </div>
                            </div>
                        ';

                        ?>

                    </div>

                    <div class="col-sm-6 col-md-6">

                        <?php
                            // A Function for producing string to array with desire formation
                            function str_to_array($string) {
                                $length = strlen($string);
                                $branch_ids_str = substr($string, 1, $length-2);
                                $branch_ids_list = array_map('intval', explode(" ", $branch_ids_str));

                                return $branch_ids_list;
                            }

                            $branches_sql = "SELECT * FROM branch ORDER BY name";
                            $account_id = $_SESSION['account_id'];
                            $allowed_branches_sql = "SELECT * FROM credit_card WHERE account_id=".$account_id;
                            
                            // Execute queries
                            $branches = $conn->query($branches_sql);
                            $allowed_branches_data = $conn->query($allowed_branches_sql);
                            if($allowed_branches_data->num_rows == 1) {
                                $temp_result = $allowed_branches_data->fetch_row();
                                $allowed_branches = $temp_result[1];
                                $card_id = $temp_result[0];
                                
                                $branch_ids_list = str_to_array($allowed_branches);
                                $branch_ids_list = implode("','",$branch_ids_list);
                                
                                $filtered_branches_sql = "SELECT * FROM branch WHERE id IN ('".$branch_ids_list."')";
                                $filtered_branches_data = $conn->query($filtered_branches_sql);

                            }

                            

                            echo '
                                <p class="list-head">Allowed Branches</p>
                                <div class="mini-container">
                                    <div class="nice-border">
                                        <form method="GET" action="" class="form-group p-a-sm">
                                            <label>Your Allowed Branches</label>
                                            <div class="bg-list-item">
                                ';

                            while($row = $filtered_branches_data->fetch_assoc()) {
                                echo '<span class="list-item">'.$row["name"].'</span>';
                            }
                            
                            echo ' 
                                            </div>
                                            <br/>
                                            <label>Set Allowed Branches</label>
                                ';

                            while($row = $branches->fetch_assoc()) {
                                echo '
                                    <div class="checkbox">
                                        <label>
                                            <input class="custom-checkbox" type="checkbox" name="branch_pk[]" value="'.$row["id"].'">
                                            <span class="check-label">'.$row["name"].'</span>
                                        </label>
                                    </div>
                                ';
                            } 

                            echo '
                                            <br/>
                                            <input class="btn btn-info btn-block" type="submit" name="submit" value="Update"/>
                                        </form>
                                    </div>
                                </div>
                            ';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

     
</body>
<footer>
	<!-- All the Javascript will be load here... -->
	<script type="text/javascript" src="assets/JS/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="assets/JS/main.js"></script>
	<script type="text/javascript" src="assets/BS/js/bootstrap.min.js"></script>
</footer>
</html>