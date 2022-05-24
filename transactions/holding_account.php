<!DOCTYPE html>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/transaction.css' />
<title>Enter Holding Account Expenses</title>

<div class='header'>
    <h1>Enter Holding<br>Account Expenses</h1>
</div>

<?php

include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

$cc_charges = $pdo->prepare("SELECT how_paid, payment_type FROM payment_method WHERE is_used IS NULL ORDER BY how_paid;");
$cc_charges->execute();

?>

<form name="display" action="" method="POST">
    <div class="grid-container">
        <div>
            <h2>Charge Source</h2>
            <input id="textboxid" name="charge_source" placeholder="Charge Source" type="text" />
        </div>
        <div>
            <h2>Credit Card</h2>
            <select id="textboxid" name="card" value='' class=dropmenus></option>
                <option value=""></option>
                <?php
                foreach ($cc_charges as $row) {
                    echo "<option value='$row[how_paid]'>$row[how_paid]</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <h2>Amount</h2>
            <input id="textboxid" name="cost" placeholder="Expense Amount" type="text" />
        </div>
        <div>
            <h2>Date</h2>
            <input type="date" id="textboxid" name="date" />
        </div>
        <div>
            <h2>Note</h2>
            <input type="text" id="textboxid" name="note" />
        </div>
        <div>
            <br><br><br>
            <button type="submit" id="transaction_button" name="submit_expense" class="button" value="submit">Submit<br>Expense</button>
        </div>
    </div>
</form>

<!-- Enter Expense into the Database -->
<?php
if (isset($_POST['submit_expense'])) {

    $required = array('cost', 'date');

    $error = false;
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $error = true;
        }
    }

    if ($error) {
        echo
        "<script>
            alert('All fields are required, except for Credit Card and Notes.');
        </script>";
    } else {
        $charge_source = $_POST['charge_source'];
        $card_used = $_POST['card'];
        $payment_amount = $cost = $_POST['cost'];
        $date = $due_date = $_POST['date'];
        $note = $_POST['note'];

        $spend = $pdo->prepare("INSERT INTO holding_acct (hold_acct_source, hold_acct_cc, hold_acct_amount, hold_acct_date, hold_acct_note)
                                VALUES (:exp_source, :cc, :cost, :expense_date, :note);");
        $spend->execute(['exp_source'=>$charge_source,'cc' => $card_used, 'cost' => $cost, 'expense_date' => $date, 'note' => $note]);

        echo "<script> window.location.href='..' </script>";
    }
}
?>