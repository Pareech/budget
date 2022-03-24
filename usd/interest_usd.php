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
    <div class="item1">
        <table class='table'>
            <?php
            $usd_value->execute();

            $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
            ?>

            <!-- Get Current USD Values -->
            <?php
            $usd_value->execute();
            foreach ($usd_value as $row) {
                $buy_amount = $row['buy_amt'];
                $net_usd = $row['net_usd'];
                $avg_exch = $row['avg_exch'];
                $interest = $row['interest'];
            ?>
                <tr>
                    <td id=alnright>CDN Spent</td>
                    <td id=alnleft><?php echo $money->formatCurrency($buy_amount, 'USD'); ?></td>
                </tr>

                <tr>
                    <td id=alnright>Avg Exch. Rate</td>
                    <td id=alnleft><?php echo $money->formatCurrency($avg_exch, 'USD'); ?></td>
                </tr>

                <tr>
                    <td id=alnright>Available USD</td>
                    <td id=alnleft><?php echo $money->formatCurrency($net_usd, 'USD'); ?></td>
                </tr>

                <tr>
                    <td id=alnright>Interest Earned</td>
                    <td id=alnleft><?php echo $money->formatCurrency($interest, 'USD'); ?></td>
                </tr>
            <?php }
            ?>
        </table>
    </div>
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
    $interest = $_POST['interest_earned'];
    $date = $_POST['date'];

    $buy_usd = $pdo->prepare("INSERT INTO usd_acct(buy_date, interest)
                              VALUES (:buy_date, :interest);");
    $buy_usd->execute(['buy_date' => $date, 'interest' => $interest]);
}
?>