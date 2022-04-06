<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/validate_cc.css' />
<title>Validate CC Date Period</title>

<?php $cc = $_POST['cc_used']; ?>

<div class='header'>
    <h1><?php echo '<span style="color:#FFA500">' . $cc . '</span>' ?></span><br>Charges Validation</h1>
</div>


<?php
include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';
?>

<div class="grid-container">
    <?php
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

    // Sum the amounts for each category of CC period being verified
    $cc_categories = $pdo->prepare("SELECT item_purchased, sum(amount) AS cat_amount
                                    FROM expenses
                                    WHERE (purchase_date,purchase_date) OVERLAPS (:startDate::DATE, :endDate::DATE) AND paid_by = :cc
                                    GROUP BY item_purchased;");
    $cc_categories->execute(['cc' => $cc, 'startDate' => $start_date, 'endDate' => $end_date]);


    // Sum all charges for a credit card period
    $cc_charges = $pdo->prepare("SELECT sum(amount) 
                             FROM expenses
                             WHERE (purchase_date,purchase_date) OVERLAPS (:startDate::DATE, :endDate::DATE) AND paid_by = :cc;");
    $cc_charges->execute(['cc' => $cc, 'startDate' => $start_date, 'endDate' => $end_date]);
    $cc_sum = $cc_charges->fetchColumn();

    $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
    $cc_sum = $money->formatCurrency($cc_sum, 'USD');
    ?>

    <div>
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

    <div>
        <table class='cc_dat'>
            <tr>
                <th colspan="2" ; class='top'> Category Totals </th>
            </tr>
            <tr>
                <th>Category</th>
                <th>Amount</th>
            </tr>

            <?php
            foreach ($cc_categories as $row) {
                echo "<tr class='toprow'>";
                    echo "<td>" . $row['item_purchased'];
                    echo "<td>" . $money->formatCurrency($row['cat_amount'], 'USD')  . "</td>";
                echo "<tr>";
            }
            ?>
        </table>
    </div>
</div>