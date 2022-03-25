<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/usd_values.css' />
<title>USD Interest</title>

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
        <h1>Interest<br>Earned</h1>
    </div>
</div>

<?php
include '../misc_files/nav_bar_links.php';
?>

<form class="form1" name="display" action="" method="POST">
    <div class="itemF">
        <h2>Interest Earned</h2>
        <input id="textboxid" name="interest_earned" placeholder="Interest Earned" type="text" />
    </div>

    <div class="itemF">
        <h2>Date</h2>
        <input type="date" id="textboxid" name="date" />
    </div>

    <div class="itemF1">
        <button type="submit" id="transaction_button" name="buy_usd" class="button" value="submit">Add<br>Interest</button>
    </div>
</form>

<?php
if (isset($_POST['buy_usd'])) {
    $date = $_POST['date'];
    $interest = $_POST['interest_earned'];

    $buy_usd = $pdo->prepare("INSERT INTO usd_acct(buy_date, interest)
                              VALUES (:buy_date, :interest);");
    $buy_usd->execute(['buy_date' => $date, 'interest' => $interest]);
}
?>