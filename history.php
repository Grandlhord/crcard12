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
	<title>History | Credit Card</title>

	<!-- Load all static files -->
	<link rel="stylesheet" type="text/css" href="assets/BS/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
</head>
<body class="container">
    <!-- Config included -->
	<?php 
        include 'helper/navbar.html';
    ?>

    <!-- Transaction history -->
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <p class="text-22px text-center">Transaction History</p>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <?php
                            $email = $_SESSION['username'];
                            $user_data_sql = "SELECT id FROM users WHERE email='".$email."'";
                            $user_data = $conn->query($user_data_sql);
                            if($user_data->num_rows == 1){
                                $user_pk = $user_data->fetch_row()[0];
                            }
                            $get_account_sql = "SELECT id from account WHERE user_id=".$user_pk;
                            $account_data = $conn->query($get_account_sql);
                            if($account_data->num_rows == 1) {
                                $account_pk = $account_data->fetch_row()[0];
                            }

                            $sql = "SELECT transaction.account_id, transaction.amount, transaction.branch_id, created_at, account.id, account.user_id, users.id, users.name AS user_name, branch.id, branch.name AS branch_name FROM transaction, account, users, branch WHERE transaction.account_id=".$account_pk." AND account.user_id=".$user_pk." AND branch.id=transaction.branch_id AND users.id=".$user_pk." ORDER BY transaction.id";
                             
                            $transaction_data = $conn->query($sql);
                            
                            if($transaction_data->num_rows > 0) {
                                echo '
                                    <thead>
                                        <tr>
                                            <th>User Name</th>
                                            <th>Branch Name</th>
                                            <th>Amount</th>
                                            <th>Transaction Date & Time</th>
                                        </tr>
                                    </thead>
                                ';
                                while($row = $transaction_data->fetch_assoc()){
                                    echo '
                                        <tbody>
                                            <tr>
                                                <td>'.$row["user_name"].'</td>
                                                <td>'.$row["branch_name"].'</td>
                                                <td>'.$row["amount"].'</td>
                                                <td>'.$row["created_at"].'</td>
                                            </tr>
                                        </tbody>
                                    ';
                                }
                            }else{
                                echo '<p class="text-center">No data to show</p>';
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Blocking History -->
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <p class="text-22px text-center">Blocking History</p>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <?php
                            $email = $_SESSION['username'];
                            $user_data_sql = "SELECT id FROM users WHERE email='".$email."'";
                            $user_data = $conn->query($user_data_sql);
                            if($user_data->num_rows == 1){
                                $user_pk = $user_data->fetch_row()[0];
                            }
                            
                            $get_account_sql = "SELECT id from account WHERE user_id=".$user_pk;
                            $account_data = $conn->query($get_account_sql);
                            if($account_data->num_rows == 1) {
                                $account_pk = $account_data->fetch_row()[0];
                            }

                            $sql = "SELECT block_history.account_id, block_history.branch_id, created_at, account.user_id, users.id, users.name AS user_name, branch.id, branch.name AS branch_name FROM block_history, account, users, branch WHERE block_history.account_id=".$account_pk." AND block_history.branch_id=branch.id AND account.user_id=".$user_pk." AND users.id=".$user_pk." ORDER BY created_at";
                             
                            $blocking_history = $conn->query($sql);
                        
                            if($blocking_history->num_rows > 0) {
                                echo '
                                    <thead>
                                        <tr>
                                            <th>Branch Name</th>
                                            <th>Account User Name</th>
                                            <th>Date & Time</th>
                                        </tr>
                                    </thead>
                                ';
                                while($row = $blocking_history->fetch_assoc()){
                                    echo '
                                        <tbody>
                                            <tr>
                                                <td>'.$row["branch_name"].'</td>
                                                <td>'.$row["user_name"].'</td>
                                                <td>'.$row["created_at"].'</td>
                                            </tr>
                                        </tbody>
                                    ';
                                }
                            }else{
                                echo '<p class="text-center">No data to show</p>';
                            }
                        ?>
                    </table>
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