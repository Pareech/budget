<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/details.css' />
<title>Recent Expenses</title>

<div class='header'>
    <h1>Last 10</br>Expenses</h1>
</div>

<?php

include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

$recent_trasactions = $pdo->prepare("SELECT *
                                    FROM(SELECT purchase_date, amount, item_purchased
                                        FROM expenses
                                        WHERE purchase_date >= CURRENT_DATE - INTERVAL '1 year' AND purchase_date <= CURRENT_DATE
                                        ORDER BY purchase_date DESC
                                        LIMIT 10) AS expenses
                                    ORDER BY purchase_date;");
$recent_trasactions->execute();
?>

<table class='table_totals'>
    <tr>
        <th>Date</th>
        <th>Amount</th>
        <th>Transaction</th>
    </tr>

    <?php
    $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
    foreach ($recent_trasactions as $row) {
        $date = $row['purchase_date'];
        $amount = $row['amount'];
        $item = $row['item_purchased'];
        $expense_amount = $money->formatCurrency($amount, 'USD');


        echo "<tr>";
        echo "<td>" . $date . "</td>";
        echo "<td>" . $expense_amount . "</td>";
        echo "<td>" . $item  . "</td>";
        echo "</tr>";
    }
    ?>

</table>