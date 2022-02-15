<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/details.css' />
<title>Recent</title>

<div class='header'>
    <h1>Last 10</br>Transactions</h1>
</div>

<?php

include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';
echo "<br><br>";

$recent_trasactions = $pdo->prepare("SELECT * 
                                 FROM (SELECT deposit_date AS date, deposit_amount AS amount, note AS item
                                        FROM income
                                        WHERE deposit_date >= CURRENT_DATE - INTERVAL '1 year' AND deposit_date <= CURRENT_DATE
                                        ORDER BY deposit_date DESC
                                        LIMIT 5) AS deposits

                                        UNION

                                        (SELECT purchase_date AS date, amount * -1, item_purchased
                                        FROM expenses
                                        WHERE purchase_date >= CURRENT_DATE - INTERVAL '1 year' AND purchase_date <= CURRENT_DATE
                                        ORDER BY purchase_date DESC
                                        LIMIT 5)
                                  ORDER by date;");
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
        $date = $row['date'];
        $transaction = $row['amount'];
        $item = $row['item'];

        $amount = $money->formatCurrency($transaction, 'USD');
        echo "<tr>";
        echo "<td>" . $date . "</td>";
        if ($transaction < 0) {
            echo "<td style='color:red'>" . $amount . "</td>";
        } else {
            echo "<td style='color:green'>" . $amount . "</td>";
        }

        // echo "<td>" . $amount . "</td>";
        echo "<td>" . $item  . "</td>";
        echo "</tr>";
    }
    ?>

</table>