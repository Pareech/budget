<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/usd_values.css' />
<title>Sell USD</title>

<?php
include '../db_connections/connection_pdo.php';

$usd_value = $pdo->prepare("SELECT SUM(COALESCE(buy_amt,0)) AS buy_amt, 
                                   SUM(COALESCE(withdrawls,0) + COALESCE(usd_value,0) + COALESCE(interest,0)) AS net_usd, 
                                   ROUND(AVG(exch_rate), 4) AS avg_exch,
                                   SUM(COALESCE(interest,0)) AS interest
                            FROM usd_acct;");

?>

<div class="grid-container">

    <?php include 'usd_avgs.php'; ?>

    <div class="item2" id="header">
        <h1>Sell US<br>Dollars</h1>
    </div>
</div>

<?php
include '../misc_files/nav_bar_links.php';
?>

<form class="form1" name="display" action="" method="POST">
    <div class="itemF">
        <h2>Withdrawl</h2>
        <input id="textboxid" name="withdrawls" placeholder="Withdrawl Amount" type="text" />
    </div>

    <div class="itemF">
        <h2>Date</h2>
        <input type="date" id="textboxid" name="date" />
    </div>

    <div class="itemF1">
        <button type="submit" id="transaction_button" name="sell_usd" class="button" value="submit">Sell<br>USD</button>
    </div>
</form>

<?php
if (isset($_POST['sell_usd'])) {
    $date = $_POST['date'];
    $withdrawls = $_POST['withdrawls'] * -1;

    $buy_usd = $pdo->prepare("INSERT INTO usd_acct(buy_date, withdrawls)
                              VALUES (:buy_date, :withdrawls);");
    $buy_usd->execute(['buy_date' => $date, 'withdrawls' => $withdrawls]);

    echo
    "<script> 
        window.location.href='..'
    </script>";
}
?>