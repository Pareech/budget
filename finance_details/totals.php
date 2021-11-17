<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/details.css' />
<title>Budget</title>

<div class='header'>
    <h1>Monthly</br>Deposits</h1>
</div>

<?php

include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

$monthly_income = $pdo->prepare("SELECT DATE_TRUNC('month',deposit_date) AS deposit_month,
                                        round(avg(deposit_amount),2) AS monthly_average,
                                        sum(deposit_amount) AS monthly_total
                                 FROM income
                                 WHERE deposit_date > CURRENT_DATE - INTERVAL '12 months'
                                 GROUP BY DATE_TRUNC('month',deposit_date)
                                 ORDER BY deposit_month;");
$monthly_income->execute();

$monthArray = array();
$totalArray = array();


foreach ($monthly_income as $row) {
    $month =  date('F', strtotime($row['deposit_month']));
    $monthlyTotal = $row['monthly_total'];

    array_push($monthArray, $month);
    array_push($totalArray, $monthlyTotal);
}

$elements = count($monthArray);
?>

<div class="grid-container_total" ; id="grid_format">
    <?php
    for ($i = 0; $i < $elements; $i++) {
        echo
        "<div class='item'>
            $monthArray[$i]
        </div>";
    }
    ?>
</div>

<div class="grid-container_total" ; id="grid_format">
    <?php
    for ($i = 0; $i < $elements; $i++) {
        $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
        $net_deposits = $money->formatCurrency($totalArray[$i], 'USD');

        echo
        "<div class='item'>
            $net_deposits
        </div>";
    }
    ?>
</div>

<center>
    <h2>Last 10 Deposits</h2>
</center>

<?php
$last_deposits = $pdo->prepare("SELECT deposit_amount, deposit_date, note 
                                FROM (SELECT deposit_amount, deposit_date,note 
                                      FROM income
                                      ORDER BY deposit_date DESC
                                      LIMIT 10) AS ordering
                                ORDER BY deposit_date ASC;");
$last_deposits->execute();
?>

<table class='table_totals'>
    <tr>
        <th>Date</th>
        <th>Amount</th>
        <th>Note</th>
    </tr>

    <?php
    foreach ($last_deposits as $row) {
        $dep_amt = $row['deposit_amount'];
        $dep_date = $row['deposit_date'];
        $dep_note = $row['note'];

        echo "<tr>";
        echo "<td>" . $row['deposit_date'] . "</td>";
        echo "<td> $" . $row['deposit_amount'] . "</td>";
        echo "<td>" . $row['note'] . "</td>";
        echo "</tr>";
    }
    ?>

</table>