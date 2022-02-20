<!DOCTYPE html>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/transaction.css' />
<title>Salary</title>

<div class='header'>
    <h1>Enter Deposit<br>Information</h1>
</div>

<?php
include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';
?>

<form name="display" action="" method="POST">
    <div class="grid-container">
        <div class="item">
            <h2>Who</h2>
            <input id="textboxid" name="who" placeholder="Depositor" type="text" />
        </div>

        <div class="item">
            <h2>Amount</h2>
            <input id="textboxid" name="deposit" placeholder="Deposit Amount" type="text" />
        </div>

        <div class="item">
            <h2>Deposit Source</h2>
            <input type="text" list="deposit_type" name="deposit_source" />
            <datalist id="deposit_type">
                <option value=""></option>
                <option value='Ian Salary'>Ian Salary</option>
                <option value='Isabelle Salary'>Isabelle Salary</option>
                <option value='Tangerine Cashback'>Money-Back Rewards</option>
            </datalist>
        </div>

        <div class="item">
            <h2>Date</h2>
            <input type="date" id="textboxid" name="date" />
        </div>

        <div class="item">
            <br><br><br>
            <button type="submit" id="transaction_button" name="submit_deposit" class="button" value="submit">Submit<br>Deposit</button>
        </div>
    </div>
</form>

<!-- Enter Income into the Database -->
<?php
if (isset($_POST['submit_deposit'])) {

    $required = array('who', 'deposit', 'deposit_source', 'date');

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

        $payee = $_POST['who'];
        $payment_amount = $_POST['deposit'];
        $deposit_source = $_POST['deposit_source'];
        $date = $due_date = $_POST['date'];
        $entry_type = 'income';
        $payment_used = 'Interact Transfer';

        $deposit = $pdo->prepare("INSERT INTO income (deposit_amount, depositor, deposit_date, note)
                                  VALUES (:deposit_amt, :depositor, :deposit_date, :deposit_source);");
        $deposit->execute(['deposit_amt' => $payment_amount, 'depositor' => $payee, 'deposit_date' => $date, 'deposit_source' => $deposit_source]);

        include '../projections/enter_projections_db.php';
    }
}
?>