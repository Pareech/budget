<!DOCTYPE html>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/transaction.css' />
<title>Enter Credit Card Payment</title>


<div class='header'>
    <h1>Credit Card</br>Payment</h1>
</div>

<?php
include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

$payment_method = $pdo->prepare("SELECT how_paid FROM payment_method ORDER BY how_paid ASC;");
$payment_method->execute();
?>

<form name="display" action="" method="POST">
    <div class="grid-container">
        <div>
            <h2>Amount</h2>
            <input id="textboxid" name="amount" placeholder="amount" type="text" />
        </div>

        <div>
            <h2>Source</h2>
            <select id="textboxid" name="payment_used" value='' class=dropmenus></option>
                <option value=""></option>
                <?php
                foreach ($payment_method as $row) {
                    echo "<option value='$row[how_paid]'>$row[how_paid]</option>";
                }
                ?>
            </select>
        </div>
        
        <div>
            <h2>Date</h2>
            <input type="date" id="textboxid" name="date" />
        </div>
        
        <div class="cc_pay">
            <button type="submit" id="transaction_button" name="submit_amount" class="button" value="submit">Submit<br>Action</button>
        </div>

    </div>
</form>


<!-- Enter Budget Icome and Expenses Information Into the Database -->
<?php
if (isset($_POST['submit_amount'])) {

    $required = array('amount', 'payment_used', 'date');

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

        $payee = $_POST['payment_used'];
        $payment_amount = $_POST['amount'];
        $due_date = $_POST['date'];

        $payment_type = $pdo->prepare("SELECT payment_type FROM payment_method WHERE how_paid = :payment_type;");
        $payment_type->execute(['payment_type' => $payee]);
        $payment_used = $payment_type->fetchColumn();

        $entry_type = 'payment';
        $payment_amount *= -1;

        include 'enter_projections_db.php';
    }
}
?>