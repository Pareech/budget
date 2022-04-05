<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/validate_cc.css' />
<title>Budget Projections</title>

<div class='header'>
    <h1>Validate Credit Card<br>Charges and Payments</h1>
</div>


<?php
include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

$cc_check = $pdo->prepare("SELECT how_paid FROM payment_method ORDER BY how_paid ASC;");
$cc_check->execute();
?>

<form name="display" action="" method="POST">
    <div class="grid-container">
        <div class="item1">
            <h2>Source</h2>
            <select id="textboxid" name="cc_used" value='' class=dropmenus></option>
                <option value=""></option>
                <?php
                foreach ($cc_check as $row) {
                    echo "<option value='$row[how_paid]'>$row[how_paid]</option>";
                }
                ?>
            </select>
        </div>

        <div class="item2">
            <h2>Start Date</h2>
            <input type="date" id="textboxid" name="start_date" />
        </div>

        <div class="item3">
            <h2>End Date</h2>
            <input type="date" id="textboxid" name="end_date" />
        </div>

        <div class="item4">
            <button type="submit" id="transaction_button" name="check_cc" class="button" value="submit">Validate<br>Transactions</button>
        </div>
</form>

<?php

if (isset($_POST['check_cc'])) {
    $cc = $_POST['cc_used'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if ($end_date == NULL) {
        $end_date = date('Y-m-d', strtotime("+1 day"));
    } else {
        $end_date = date('Y-m-d', strtotime($end_date . " +1 day"));
    }

    // Get all charges for a credit card period
    $verify = $pdo->prepare("SELECT item_purchased, amount, purchase_date, note 
                         FROM expenses
                         WHERE (purchase_date,purchase_date) OVERLAPS (:startDate::DATE, :endDate::DATE) AND paid_by = :cc
                         ORDER BY purchase_date;");
    $verify->execute(['cc' => $cc, 'startDate' => $start_date, 'endDate' => $end_date]);

    // Sum all charges for a credit card period
    $cc_charges = $pdo->prepare("SELECT sum(amount) 
                             FROM expenses
                             WHERE (purchase_date,purchase_date) OVERLAPS (:startDate::DATE, :endDate::DATE) AND paid_by = :cc;");
    $cc_charges->execute(['cc' => $cc, 'startDate' => $start_date, 'endDate' => $end_date]);
    $cc_sum = $cc_charges->fetchColumn();

    $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
    $cc_sum = $money->formatCurrency($cc_sum, 'USD');

?>
    <div class="item5">
        <table class='table'>
            <tr>
                <th colspan="4" ; class='heading'>
                    <?php echo $cc . "<br>Monthly Charges: " . $cc_sum; ?>
                </th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Item</th>
                <th>Cost</th>
                <th>Note</th>
            </tr>

            <?php
            foreach ($verify as $row) {
                echo "<tr>";
                    echo "<td>" . date('d-M', strtotime($row['purchase_date'])) . "</td>";
                    echo "<td>" . $row['item_purchased'] . "</td>";
                    echo "<td>" . $money->formatCurrency($row['amount'], 'USD')  . "</td>";
                    echo "<td>" . $row['note']  . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
<?php
}
?>
</div>