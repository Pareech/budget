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
            <h2>Type</h2>
            <input id="textboxid" name="note" placeholder="eg. Salary" type="text" />
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

    $required = array('who', 'deposit', 'note', 'date');

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

        $depositor = $_POST['who'];
        $deposit_amt = $_POST['deposit'];
        $note = $_POST['note'];
        $date = $_POST['date'];

        $deposit = $pdo->prepare("INSERT INTO income (deposit_amount, depositor, deposit_date, note)
                                  VALUES (:deposit_amt, :depositor, :deposit_date, :deposit_type);");
        $deposit->execute(['deposit_amt' => $deposit_amt, 'depositor' => $depositor, 'deposit_date' => $date, 'deposit_type' => $note]);

        header("Location: ..");
    }
}
?>