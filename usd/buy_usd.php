<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/usd_values.css' />
<title>Buy USD</title>

<?php include '../db_connections/connection_pdo.php'; ?>

<div class="grid-container">

    <?php include 'usd_avgs.php'; ?>

    <div class="item2" id="header">
        <h1>Buy US<br>Dollars</h1>
    </div>
</div>

<?php
include '../misc_files/nav_bar_links.php';
?>

<form name="display" action="" method="POST">
    <div class="grid-container_bottom">
        
        <div class="item3">
            <h2>Canadian Amount</h2>
            <input id="textboxid" name="cdn_amount" placeholder="CDN Amount" type="text" />
        </div>

        <div class="item4">
            <h2>USD Amount</h2>
            <input id="textboxid" name="usd_amt" placeholder="USD Amount" type="text" />
        </div>

        <div class="item5">
            <h2>Date</h2>
            <input type="date" id="textboxid" name="date" />
        </div>

        <div class="item6">
            <button type="submit" id="transaction_button" name="buy_usd" class="button" value="submit">Buy<br>USD</button>
        </div>


</form>


<div class="item7">
    <table class='table_exch'>
        <tr>
            <th colspan="4" ; id='th_bottom'>
                <?php echo "Average of Last<br>10 Rates: $" . $latest_avg; ?>
            </th>
        </tr>
        <tr>
            <th class="row">Date</th>
            <th class="row">Exch. Rate</th>
            <th class="row">Bought</th>
        </tr>

        <?php
        foreach ($last_ten as $row) {
            echo "<tr>";
                echo "<td id='td_bottom'>" . $row['buy_date'] . "</td>";
                echo "<td id='td_bottom'>" . $row['exch_rate'] . "</td>";
                echo "<td id='td_bottom'>" . $row['usd_value'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

<?php
if (isset($_POST['buy_usd'])) {
    $date = $_POST['date'];
    $cdn_amount = $_POST['cdn_amount'];
    $usd_amt = $_POST['usd_amt'];

    $exch_rate = round($cdn_amount / $usd_amt, 4);

    // Update USD Table
    $buy_usd = $pdo->prepare("INSERT INTO usd_acct(buy_date, buy_amt, exch_rate, usd_value)
                              VALUES (:buy_date, :buy_amt, :exch_rate, :usd_value);");
    $buy_usd->execute(['buy_date' => $date, 'buy_amt' => $cdn_amount, 'exch_rate' => $exch_rate, 'usd_value' => $usd_amt]);

    // Update Budget Projection Table
    $update_projection = $pdo->prepare("INSERT INTO budget_projection(payee, payment_amount, due_date, transaction_type, payment_method)
                                        VALUES ('US Dollar Buy', :cdnAmount, :due_date, 'payment','Interact Transfer');");
    $update_projection->execute(['cdnAmount' => $cdn_amount * -1, 'due_date' => $date]);

    // Update Expenses
    $update_expenses = $pdo->prepare("INSERT INTO expenses(item_purchased, amount, category, kind, paid_by, purchase_date, note)
                                      VALUES ('US Dollar Buy', :buy_amt, 'Investment', 'Fixed', 'Interact Transfer', :pay_date, :note);");
    $update_expenses->execute(['buy_amt' => $cdn_amount, 'pay_date' => $date, 'note' => 'Exch. Rate: ' . $exch_rate]);

    echo "<script> window.location.href='usd_historical.php' </script>";
}
?>