<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/validate_cc.css' />
<title>Select CC and Date Range for Validation</title>

<div class='header'>
    <h1>Select Credit Card<br>to Validate Charges</h1>
</div>


<?php
include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

$cc_check = $pdo->prepare("SELECT how_paid FROM payment_method ORDER BY how_paid ASC;");
$cc_check->execute();
?>

<form class="form-container" name="display" action="validate_cc_display.php" method="POST">
    <div class="grid-container">
        <div class="item_form1">
            <h2>Source</h2>
            <select id="textboxid" name="cc_used" value='' class=dropmenus></option>
                <option value=""></option>
                <?php
                foreach ($cc_check as $row) {
                    echo "<option value='$row[how_paid]'>$row[how_paid]</option>";
                }
                ?>
            </select>
        </div>

        <div class="item_form2">
            <h2>Start Date</h2>
            <input type="date" id="textboxid" name="start_date" />
        </div>

        <div class="item_form3">
            <h2>End Date</h2>
            <input type="date" id="textboxid" name="end_date" />
        </div>

        <div class="item_form4">
            <button type="submit" id="transaction_button" name="check_cc" class="button" value="submit">Validate<br>Transactions</button>
        </div>
    </div>
</form>