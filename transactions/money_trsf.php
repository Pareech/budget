<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/money_trsf.css' />
<title>Enveloppe Transfers</title>

<?php
$who = $_GET['who'];
include '../db_connections/connection_pdo.php';
?>

<div class='header'>
    <h1>Budget Enveloppe<br>Transfers <?php echo $who ?> </h1>
</div>

<?php
include '../misc_files/nav_bar_links.php';

// Get amount to transfer for each expense type enveloppe
$who_trsf = $pdo->prepare("SELECT c.expense_category AS category, e.item_purchased AS item,
                                  CASE WHEN e.item_purchased = 'Mortgage' THEN round(sum(e.amount) / count(e.amount) * 2 / 2, 2)
                                       WHEN e.item_purchased SIMILAR TO '%Dollar%|%RRSP%|%RESP%' THEN round(sum(e.amount) / count(e.amount), 2)
                                       ELSE round(sum(e.amount) / 12 / 2, 2)
                                  END AS trsfamount
                           FROM expenses AS e
                           JOIN expense_categories AS c
                           ON e.item_purchased = c.expense_name
                           WHERE e.purchase_date > NOW() - INTERVAL '1 year' AND e.purchase_date < NOW() AND c.account_trsf SIMILAR TO :who
                           GROUP BY e.item_purchased, c.expense_category
                           ORDER BY e.item_purchased;");
$who_trsf->execute(['who' => '%' . $who . '%']);

// Get the amount to transfer per category of expense
$trsf_sum = $pdo->prepare("SELECT e.category AS category, round(sum(e.amount) / 12 / 2,2) AS amount
                           FROM expenses AS e
                           JOIN expense_categories AS c
                           ON e.item_purchased = c.expense_name
                           WHERE e.purchase_date > NOW() - INTERVAL '1 year' AND e.purchase_date < NOW() 
                                AND c.account_trsf SIMILAR TO :who AND e.category <> 'Other'
                           GROUP BY e.category
                           ORDER BY e.category;");
$trsf_sum->execute(['who' => '%' . $who . '%']);

$money = new NumberFormatter('en', NumberFormatter::CURRENCY);
?>

<div class="grid-container">
    <div>
        <table class='table1'>
            <tr>
                <th>Item</th>
                <th>Category</th>
                <th>Amount</th>
            </tr>

            <?php
            foreach ($who_trsf as $row) {
                $item = $row['item'];
                $category = $row['category'];
                $trsfamount = $row['trsfamount'];

                echo "<tr>";
                    echo "<td>" . $item . "</td>";
                    echo "<td>" . $category . "</td>";
                    echo "<td>" . $money->formatCurrency($trsfamount, 'USD') . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    <div>
        <table class='table2'>
            <tr>
                <th>Category Totals</th>
                <th>Amount</th>
            </tr>

            <?php
            foreach ($trsf_sum as $row) {
                $category = $row['category'];
                $sum_amount = $row['amount'];

                if ($category == 'Housing' AND $who == 'Isabelle') {
                    $sum_amount += 555.17 / 26 * 2;
                }

                echo "<tr>";
                    echo "<td>" . $category . "</td>";
                    echo "<td>" . $money->formatCurrency($sum_amount, 'USD') . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>