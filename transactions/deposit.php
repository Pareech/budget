<!DOCTYPE html>

<?php
include '../db_connections/connection_pdo.php';
?>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/transaction.css' />
<title>Salary</title>

<div class='header'>
    <h1>Enter Deposit<br>Information</h1>
</div>

<?php
include '../misc_files/nav_bar_links.php';
?>


<form name="display" action="" method="POST">
    <div class="grid-container">
        <div class="item1a">
            <h2>Who</h2>
        </div>
        <div class="item2a">
            <h2>Amount</h2>
        </div>
        <div class="item3a">
            <h2>Type</h2>
        </div>
        <div class="item4a">
            <h2>Date</h2>
        </div>
        <div class="item1b"><center><input id="textboxid" name="who" placeholder="Depositor" type="text" /></center></div>
        <div class="item2b"><center><input id="textboxid" name="salary" placeholder="Deposit Amount" type="text" /></center></div>
        <div class="item3b"><center><input id="textboxid" name="note" placeholder="eg. Salary" type="text" /></center></div>
        <div class="item4b"><center><input type="date" id="textboxid" name="date" /></center></div>
        <div class="item5"> <button type="submit" id="transaction_button" name="submit_deposit" class="button" value="submit">Submit<br>Deposit</button></div>
    </div>
</form>


<?php
if (isset($_POST['submit_deposit'])) {

    $required = array('salary', 'who', 'date', 'note');

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

        $salary = $_POST['salary'];
        $depositor = $_POST['who'];
        $date = $_POST['date'];
        $note = $_POST['note'];

        $deposit = $pdo->prepare("INSERT INTO income (deposit_amount, depositor, deposit_date, note)
                          VALUES (:salary_amount, :depositor, :deposit_date, :deposit_type);");
        $deposit->execute(['salary_amount' => $salary, 'depositor' => $depositor, 'deposit_date' => $date, 'deposit_type' => $note]);

        header("Location: ..");
    }
}
?>