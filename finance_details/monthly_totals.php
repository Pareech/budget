<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/details.css' />
<title>Budget</title>

<div class='header'>
    <h1>Monthly Net</br>(Income - Spending)</h1>
</div>

<?php

include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

$monthly_income = $pdo->prepare("SELECT to_char(deposit_date, 'YYYY-MM') AS deposit_month,
                                        round(avg(deposit_amount),2) AS  monthly_income_average,
                                        sum(deposit_amount) AS monthly_income_total
                                 FROM income
                                 WHERE deposit_date >= CURRENT_DATE - INTERVAL '12 months'
                                 GROUP BY deposit_month
                                 ORDER BY deposit_month;");
$monthly_income->execute();


$monthly_expenses = $pdo->prepare("SELECT to_char(purchase_date, 'YYYY-MM') AS purchase_month,
                                          round(avg(amount),2) AS monthly_spend_average,
                                          sum(amount) AS monthly_spend_total
                                   FROM expenses
                                   WHERE purchase_date >= CURRENT_DATE - INTERVAL '12 months'
                                   GROUP BY purchase_month
                                   ORDER BY purchase_month;");
$monthly_expenses->execute();



$monthArray = array();
$totalIncomeArray = array();
$totalExpenseArray = array();


foreach ($monthly_income as $row) {
    $month =  $row['deposit_month'];
    // $month =  date('F', strtotime($row['deposit_month']));
    $monthlyIncomeTotal = $row['monthly_income_total'];

    array_push($monthArray, $month);
    array_push($totalIncomeArray, $monthlyIncomeTotal);
}

foreach ($monthly_expenses as $row) {
    $monthlyExpenseTotal = $row['monthly_spend_total'];
    array_push($totalExpenseArray, $monthlyExpenseTotal);
}

$elements = count($monthArray);
?>

<div class="grid-container_total" ; id="grid_format">
    <?php
    for ($i = 0; $i < $elements; $i++) {
        echo
        "<div class='item' style='background-color:grey'>
            $monthArray[$i]
        </div>";
    }
    ?>
</div>

<div class="grid-container_total" ; id="grid_format">
    <?php
    for ($i = 0; $i < $elements; $i++) {
        $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
        $monthly_deposits = $money->formatCurrency($totalIncomeArray[$i], 'USD');
        echo
        "<div class='item' style='background-color:green'>
            $monthly_deposits
        </div>";
    }
    ?>
</div>

<div class="grid-container_total" ; id="grid_format">
    <?php
    for ($i = 0; $i < $elements; $i++) {
        $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
        $monthly_spending = $money->formatCurrency($totalExpenseArray[$i], 'USD');
        echo
        "<div class='item' style='background-color:red'>
            $monthly_spending
        </div>";
    }
    ?>
</div>

<div class="grid-container_total" ; id="grid_format">
    <?php
    for ($i = 0; $i < $elements; $i++) {
        $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
        $monthly_net = $money->formatCurrency($totalIncomeArray[$i] - $totalExpenseArray[$i], 'USD');
        echo
        "<div class='item'>
            $monthly_net
        </div>";
    }
    ?>
</div>