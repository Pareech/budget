<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/details.css' />
<title>Recent Deposits</title>

<div class='header'>
    <h1>Last 10</br>Deposits</h1>
</div>

<?php

include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

$recent_trasactions = $pdo->prepare("SELECT deposit_date, deposit_amount, note
                                    FROM(SELECT deposit_date, deposit_amount, note
                                        FROM income
                                        WHERE deposit_date >= CURRENT_DATE - INTERVAL '1 year' AND deposit_date <= CURRENT_DATE
                                        ORDER BY deposit_date DESC
                                        LIMIT 10) AS deposits
                                    ORDER BY deposit_date;");
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
        $date = $row['deposit_date'];
        $amount = $row['deposit_amount'];
        $item = $row['note'];
        $deposit_amount = $money->formatCurrency($amount, 'USD');


        echo "<tr>";
        echo "<td>" . $date . "</td>";
        echo "<td>" . $deposit_amount . "</td>";
        echo "<td>" . $item  . "</td>";
        echo "</tr>";
    }
    ?>

</table>