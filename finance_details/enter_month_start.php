<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/payment_updates.css' />
<title>Monthly Cash Start Amount</title>

<?php
include '../db_connections/connection_pdo.php';
?>

<div class="header">
    <h1>Enter Monthly<br>Starting Value</h1>
</div>

<?php
include '../misc_files/nav_bar_links.php';
?>

<form name="display" action="" method="POST">
    <div class="grid-container">

        <div>
            <h2>Starting Amount</h2>
            <input id="textboxid" name="start_value" placeholder="Month Starting Value" type="text" />
        </div>

        <div>
            <h2>Date</h2>
            <input type="date" id="textboxid" name="date" />
        </div>

        <div class="itemEMSbutton">
            <button type="submit" id="transaction_button" name="month_start" class="button" value="submit">Add Month<br>Starting Value</button>
        </div>
    </div>
</form>

<?php
if (isset($_POST['month_start'])) {
    $date = $_POST['date'];
    $start_value = $_POST['start_value'];

    $buy_usd = $pdo->prepare("INSERT INTO monthly_variance(date, amount)
                              VALUES (:date, :monthly_start);");
    $buy_usd->execute(['date' => $date, 'monthly_start' => $start_value]);

    echo "<script> window.location.href='..' </script>";
}
?>