<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/usd_values.css' />
<title>USD Interest</title>

<?php include '../db_connections/connection_pdo.php'; ?>

<div class="grid-container">

    <?php include 'usd_avgs.php'; ?>

    <div class="item2" id="header">
        <h1>Interest<br>Earned</h1>
    </div>
</div>

<?php
include '../misc_files/nav_bar_links.php';
?>

<form name="display" action="" method="POST">
    <div class="grid-container_bottom">

        <div class="item3">
            <h2>Interest Earned</h2>
            <input id="textboxid" name="interest_earned" placeholder="Interest Earned" type="text" />
        </div>

        <div class="item4">
            <h2>Date</h2>
            <input type="date" id="textboxid" name="date" />
        </div>

        <div class="item5b">
            <button type="submit" id="transaction_button" name="buy_usd" class="button" value="submit">Add<br>Interest</button>
        </div>
    </div>
</form>

<?php
if (isset($_POST['buy_usd'])) {
    $date = $_POST['date'];
    $interest = $_POST['interest_earned'];

    $buy_usd = $pdo->prepare("INSERT INTO usd_acct(buy_date, interest)
                              VALUES (:buy_date, :interest);");
    $buy_usd->execute(['buy_date' => $date, 'interest' => $interest]);

    echo "<script> window.location.href='..' </script>";
}
?>