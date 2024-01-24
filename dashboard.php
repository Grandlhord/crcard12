<!-- After submitting the form -->
<?php
session_start();
include 'helper/config.php';

$warning_messages = array(
    '<p class="warning-container"><strong>Secure Websites:</strong> Only enter credit card information on secure, reputable websites with "https://" in the URL.</p>',
    '<p class="warning-container"><strong>Monitor Statements:</strong> Regularly review credit card statements for any unfamiliar transactions.</p>',
    '<p class="warning-container"><strong>Two-Factor Authentication:</strong> Enable two-factor authentication whenever possible to add an extra layer of security.</p>',
    '<p class="warning-container"><strong>Phishing Awareness:</strong> Be cautious of unexpected emails or messages requesting personal information; verify with the company directly if unsure.</p>',
    '<p class="warning-container"><strong>Update Software:</strong> Keep operating systems, browsers, and security software up to date for the latest protection against vulnerabilities.</p>',
    '<p class="warning-container"><strong>Card Security Features:</strong> Memorize your card\'s security code and never share it; report lost or stolen cards immediately.</p>',
    '<p class="warning-container"><strong>Limit Public Wi-Fi Use:</strong> Avoid accessing sensitive information on public Wi-Fi networks; use a virtual private network (VPN) if necessary.</p>',
    '<p class="warning-container"><strong>Educate Yourself:</strong> Stay informed about common scams and fraud tactics to recognize and avoid potential threats</p>'
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $card_number = $_POST['card_number'];
    $pin = $_POST['pin'];
    $branch_id = $_POST['branch_id'];

    $query = "SELECT * FROM credit_card";
    $credit_cards = $conn->query($query);

    if ($credit_cards->num_rows > 0) {
        $card_number_array = array();
        while ($row = $credit_cards->fetch_assoc()) {
            array_push($card_number_array, $row["ac_number"]);
        }
    }

    $matched = false;
    for ($i = 0; $i < count($card_number_array); $i++) {
        if ($card_number == $card_number_array[$i]) {
            $matched = true;
            break;
        }
    }

    if ($matched) {
        $card_data_sql = "SELECT * FROM credit_card WHERE ac_number=" . $card_number . " AND pin=" . $pin;
        $card_data = $conn->query($card_data_sql);

        if ($card_data->num_rows == 0) {
            echo array_shift($warning_messages);
        } elseif ($card_data->num_rows == 1) {
            $row = $card_data->fetch_row();
            $allowed_branches = $row[1];
            $ac_status = $row[5];

            if ($ac_status == 1) {
                if (strpos($allowed_branches, $branch_id)) {
                    $_SESSION['account'] = $card_number;
                    $_SESSION['account_id'] = $row[4];
                    $_SESSION['branch_pk'] = $branch_id;
                    header("Location: transaction.php");
                } else {
                    echo array_shift($warning_messages);
                    $update_block_sql = "UPDATE credit_card SET status=0 WHERE ac_number=" . $card_number;
                    $updated_block_status = $conn->query($update_block_sql);
                    if ($updated_block_status) {
                        echo '<p class="error-message">Account will be blocked!!</p>';
                        $block_history_sql = "INSERT INTO block_history (account_id, branch_id) VALUES(" . $row[4] . ", " . $branch_id . ")";
                        $conn->query($block_history_sql);
                    }
                }
            } else {
                echo array_shift($warning_messages);
            }
        }
    } else {
        echo '<p class="error-message">You are not authorized!!</p>';
    }
}
?>

<div class="row">
<div id="warning-container" class="warning-container"></div>

    <?php
    $sql = "SELECT * FROM branch";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $banks = array();

        echo '<div class="row-justify-content-center">
                <div class="col-xs-6 col-md-4">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="d-inline-block" id="bankName">' . $result->fetch_assoc()["name"] . ' Branch</h3>
                            <div class="hamburger-menu d-inline-block">
                                <div class="menu-icon">&#9776;</div>
                                <ul class="bank-list">';

        while ($row = $result->fetch_assoc()) {
            $banks[] = array(
                'id' => $row["id"],
                'name' => $row["name"]
            );

            echo '<li data-id="' . $row['id'] . '">' . $row['name'] . '</li>';
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
                                <input type="hidden" id="selectedBranchId" name="branch_id" value="' . $banks[0]["id"] . '"/>
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
        .row-justify-content-center {
            display: flex;
            justify-content: center;
        }

        .hamburger-menu {
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

        .warning-container {
    width: 100%;
    /* height: 50px; */
    padding-top: 10px;
    text-align: center;
    font-weight: bolder;
    font-size: 20px;
    background-color:indianred;
    color: #ffffff;
    border-radius: 5px;
}

    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const warningMessages = <?= json_encode($warning_messages) ?>;
            let currentIndex = 0;

            function displayWarning() {
                const warningContainer = document.getElementById("warning-container");
                warningContainer.innerHTML = warningMessages[currentIndex];

                // Dynamically adjust the height based on the content
                warningContainer.style.height = "auto";
                const containerHeight = warningContainer.clientHeight;
                warningContainer.style.height = containerHeight + "px";

                currentIndex = (currentIndex + 1) % warningMessages.length;

                setTimeout(displayWarning, 3000); // Change the time interval (in milliseconds) between warnings
            }

            displayWarning();

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
        });
    </script>

</div>

