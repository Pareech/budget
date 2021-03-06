<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/usd_values.css' />
<title>Sell USD</title>

<?php include '../db_connections/connection_pdo.php'; ?>

<div class="grid-container">

    <?php include 'usd_avgs.php'; ?>

    <div class="item2" id="header">
        <h1>Sell US<br>Dollars</h1>
    </div>
</div>

<?php
include '../misc_files/nav_bar_links.php';
?>

<form name="display" action="" method="POST">
    <div class="grid-container_bottom">

        <div class="item3">
            <h2>Withdrawl</h2>
            <input id="textboxid" name="withdrawls" placeholder="Withdrawl Amount" type="text" />
        </div>

        <div class="item4">
            <h2>Date</h2>
            <input type="date" id="textboxid" name="date" />
        </div>

        <div class="item5b">
            <button type="submit" id="transaction_button" name="sell_usd" class="button" value="submit">Sell<br>USD</button>
        </div>
    </div>
</form>

<?php
if (isset($_POST['sell_usd'])) {
    $date = $_POST['date'];
    $withdrawls = $_POST['withdrawls'] * -1;

    $buy_usd = $pdo->prepare("INSERT INTO usd_acct(buy_date, withdrawls)
                              VALUES (:buy_date, :withdrawls);");
    $buy_usd->execute(['buy_date' => $date, 'withdrawls' => $withdrawls]);

    echo "<script> window.location.href='..' </script>";
}
?>