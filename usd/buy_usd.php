<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/usd_values.css' />
<title>Buy USD</title>

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
        <h1>Buy US<br>Dollars</h1>
    </div>
</div>

<?php
include '../misc_files/nav_bar_links.php';
?>

<form class="form" name="display" action="" method="POST">
    <div>
        <h2>Canadian Amount</h2>
        <input id="textboxid" name="cdn_amount" placeholder="CDN Amount" type="text" />
    </div>

    <div>
        <h2>USD Amount</h2>
        <input id="textboxid" name="usd_amt" placeholder="USD Amount" type="text" />
    </div>

    <div>
        <h2>Date</h2>
        <input type="date" id="textboxid" name="date" />
    </div>

    <div class="item">
        <button type="submit" id="transaction_button" name="buy_usd" class="button" value="submit">Buy<br>USD</button>
    </div>
</form>

<?php
if (isset($_POST['buy_usd'])) {
    $date = $_POST['date'];
    $cdn_amount = $_POST['cdn_amount'];
    $usd_amt = $_POST['usd_amt'];

    $exch_rate = round($cdn_amount / $usd_amt, 4);

    $buy_usd = $pdo->prepare("INSERT INTO usd_acct(buy_date, buy_amt, exch_rate, usd_value)
                              VALUES (:buy_date, :buy_amt, :exch_rate, :usd_value);");
    $buy_usd->execute(['buy_date' => $date, 'buy_amt' => $cdn_amount, 'exch_rate' => $exch_rate, 'usd_value' => $usd_amt]);

    echo
    "<script> 
        window.location.href='..'
    </script>";
}
?>