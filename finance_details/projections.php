<!DOCTYPE html>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/transaction.css' />
<title>Budget Projections</title>


<div class='header'>
    <h1>Budget</br>Projections</h1>
</div>

<?php
include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

$payment_method = $pdo->prepare("SELECT how_paid FROM payment_method ORDER BY how_paid ASC;");
$payment_method->execute();

// $entry = '';

?>

<form name="display" action="" method="POST">
    <div class="grid-container">
        <div class="item">
            <h2>Entry Type</h2>
            Income <input type="radio" id="radioItem" name="entry_type" value="income"><br>
            Payment <input type="radio" id="radioItem" name="entry_type" value="payment">
        </div>
        <div class="item">
            <h2>Amount</h2>
            <input id="textboxid" name="amount" placeholder="amount" type="text" />
        </div>
        <div class="item">
            <h2>Payment Used*</h2>
            <select id="textboxid" name="payment_used" value='' class=dropmenus></option>
                <option value=""></option>
                <?php
                foreach ($payment_method as $row) {
                    echo "<option value='$row[how_paid]'>$row[how_paid]</option>";
                }
                ?>
            </select>
        </div>
        <div class="item">
            <h2>Date</h2>
            <input type="date" id="textboxid" name="date" />
        </div>
        <div class="item">
            <br><br><br>
            <button type="submit" id="transaction_button" name="submit_amount" class="button" value="submit">Submit<br>Action</button>
        </div>
    </div>
    <h6>*Only for Bill Payments</h5>
</form>


<!-- Enter Budget Icome and Expenses Information Into the Database -->

<?php
if (isset($_POST['submit_amount'])) {

    $required = array('entry_type', 'amount', 'payment_used', 'date');

    $error = false;
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $error = true;
        }
    }

    if ($error) {
        echo
        "<script>
            alert('All fields are required.');
        </script>";
    } else {

        include 'projection_db.php';
    }
}
?>