<!-- After submiting the form -->
<?php
	// Initialize the session
	session_start();
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$card_number = $_POST['card_number'];
		$pin = $_POST['pin'];
		$branch_id = $_POST['branch_id'];

		// Get all the the pins with card number then I'll match with inputed data
		$query = "SELECT * FROM credit_card";
		$credit_cards = $conn->query($query);

		if ($credit_cards->num_rows > 0) {
			// Declare some helper arrays
			$card_number_array = array();
			while($row = $credit_cards->fetch_assoc()) {
				array_push($card_number_array, $row["ac_number"]);
			}
		}

		$matched = False;
		// Match with the inputed data
		for($i=0; $i<count($card_number_array); $i++){
			if($card_number == $card_number_array[$i]){
				$matched = True;
				break;
			}
		}
		// After matching 
		if($matched) {
			// Again call db for specific response
			$card_data_sql = "SELECT * FROM credit_card WHERE ac_number=".$card_number." AND pin=".$pin;
			$card_data = $conn->query($card_data_sql);

			if($card_data->num_rows == 0){
				echo '<p class="error-message">You are not authorised!!</p>';
			}

			if($card_data->num_rows == 1){
				$row = $card_data->fetch_row();
				$allowed_branches = $row[1];
				$ac_status = $row[5];

				if($ac_status == 1){
					if(strpos($allowed_branches, $branch_id)) {
						// set AC num and pin to session
						$_SESSION['account'] = $card_number;
						$_SESSION['account_id'] = $row[4];
						$_SESSION['branch_pk'] = $branch_id;
						header("Location: transaction.php");
					}else {
						echo '<p class="error-message">SORRY! This Branch is not Allowed!!</p>';
						// Account will be locked now.
						/*
							0 = Block
							1 = Active	
						*/
						$update_block_sql = "UPDATE credit_card SET status=0 WHERE ac_number=".$card_number;
						$updated_block_status = $conn->query($update_block_sql);
						if($updated_block_status) {
							echo '<p class="error-message">Account will be blocked!!</p>';
							$block_history_sql = "INSERT INTO block_history (account_id, branch_id) VALUES(".$row[4].", ".$branch_id.")";
							$conn->query($block_history_sql);	
						}
					}
				}else {
					echo '<p class="error-message">Your account has blocked!!</p>';
				}

				
			}
			
		}else {
			echo '<p class="error-message">You are not authorised!!</p>';
		}
	}
	
?>

<div class="row">
	<!-- <h2 class="text-center">Dashboard</h2>
	<p class="text-center">Authorized Card Number: 5531886652142950, Pin code: 3310</p>
	<p class="text-center">Login Email: tester@gmail.com, Password: 12345</p> -->
	<?php 
    // Get branches data from the database
    $sql = "SELECT * FROM branch";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Store bank names and IDs for later use
        $banks = array();
        
        echo '<div class="row-justify-content-center">
                <div class="col-xs-6 col-md-4">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="d-inline-block" id="bankName">'.$result->fetch_assoc()["name"].' Branch</h3>
                            <div class="hamburger-menu d-inline-block">
                                <div class="menu-icon">&#9776;</div>
                                <ul class="bank-list">';
        
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $banks[] = array(
                'id' => $row["id"],
                'name' => $row["name"]
            );
            
            // Create a list of banks in the menu
            echo '<li data-id="'.$row['id'].'">'.$row['name'].'</li>';
        }
        
        echo '                  </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="" class="form-group">
                                <label>Your Card Number</label>
                                <input type="text" name="card_number" class="form-control" required/>
                                <br/>
                                <label>Your PIN</label>
                                <input type="password" name="pin" class="form-control" required/>
                                <input type="hidden" id="selectedBranchId" name="branch_id" value="'.$banks[0]["id"].'"/>
                                <br/>
                                <input class="btn btn-success btn-block" type="submit" name="submit" value="Withdraw"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>';
    } else {
        echo "0 results";
    }
?>



<style>
    .row-justify-content-center{
        display: flex;
    justify-content: center;
    }
    .hamburger-menu {
        /* display: inline-block;
        float: right; */
        cursor: pointer;
    }
    
    .menu-icon {
        font-size: 18px;
        padding: 5px;
    }
    
    .bank-list {
        display: none;
        position: absolute;
        background-color: #fff;
        list-style-type: none;
        padding: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid #ccc;
        z-index: 1;
    }
    
    .bank-list li {
        cursor: pointer;
        padding: 5px;
    }



</style>

<script>
    // JavaScript to toggle the bank list when the menu is clicked
    const menuIcon = document.querySelector('.menu-icon');
    const bankList = document.querySelector('.bank-list');
    const bankName = document.getElementById('bankName');
    const selectedBranchId = document.getElementById('selectedBranchId');
    
    menuIcon.addEventListener('click', function() {
        bankList.classList.toggle('show');
    });
    
    // JavaScript to handle bank selection
    const bankItems = document.querySelectorAll('.bank-list li');
    
    bankItems.forEach(item => {
        item.addEventListener('click', function() {
            const branchId = this.getAttribute('data-id');
            const selectedBankName = this.textContent;
            
            // Update the panel heading with the selected bank name
            bankName.textContent = selectedBankName;
            
            // Update the hidden input field with the selected branch ID
            selectedBranchId.value = branchId;
            
            // Hide the bank list
            bankList.classList.remove('show');
        });
    });
</script>


</div>
