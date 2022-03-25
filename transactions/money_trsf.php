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
?>

<div class="grid-container">
    <div class="item1">
        <table class='table'>
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

                $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
                $trsfamount = $money->formatCurrency($trsfamount, 'USD');

                echo "<tr>";
                    echo "<td>" . $item . "</td>";
                    echo "<td>" . $category . "</td>";
                    echo "<td>" . $trsfamount . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    <div class="item2">
        <table class='table'>
            <tr>
                <th>Category Totals</th>
                <th>Amount</th>
            </tr>

            <?php
            foreach ($trsf_sum as $row) {
                $category = $row['category'];
                $sumamount = $row['amount'];

                if ($category == 'Housing') {
                    $sumamount += 555.17 / 26 * 2;
                }

                $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
                $sumamount = $money->formatCurrency($sumamount, 'USD');

                echo "<tr>";
                    echo "<td>" . $category . "</td>";
                    echo "<td>" . $sumamount . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>