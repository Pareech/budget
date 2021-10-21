<!DOCTYPE html>

<?php
include '../db_connections/connection_pdo.php';
?>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/expense.css' />
<title>Salary</title>

<div class='header'>
    <h1>Enter Expense<br>Information</h1>
</div>

<?php
include '../misc_files/nav_bar_links.php';
?>

<form name="display" action="" method="POST">
    <div id="container">
        <div id="column_left">
            <table>
                <tr>
                    <th colspan="2">Deposit Information</th>
                </tr>
                <tr>
                    <td>Who</td>
                    <td>
                        <center><input id="textboxid" name="who" placeholder="Depositor" type="text" /></center>
                    </td>
                </tr>
                <tr>
                    <td>Amount</td>
                    <td>
                        <center><input id="textboxid" name="salary" placeholder="Deposit Amount" type="text" /></center>
                    </td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td>
                        <center><input id="textboxid" name="note" placeholder="eg. Salary" type="text" /></center>
                    </td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>
                        <center><input type="date" id="textboxid" name="date" /></center>
                    </td>
                </tr>
            </table>
        </div>
        <div id="column_right">
            <button type="submit" id="deposit" name="submit_deposit" class="button" value="submit">Submit the<br>Deposit</button>
        </div>
    </div>
</form>


<?php
if (isset($_POST['submit_deposit'])) {
    $salary = $_POST['salary'];
    $depositor = $_POST['who'];
    $date = $_POST['date'];
    $note = $_POST['note'];

    $deposit = $pdo->prepare("INSERT INTO income (deposit_amount, depositor, deposit_date, note)
                          VALUES (:salary_amount, :depositor, :deposit_date, :deposit_type);");
    $deposit->execute(['salary_amount' => $salary, 'depositor' => $depositor, 'deposit_date' => $date, 'deposit_type' => $note]);

    header("Location: ..");
}
?>