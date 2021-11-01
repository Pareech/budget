<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='css/details.css' />
<title>Budget</title>

<!-- <div class='header' ;>
  <h1>Budget</br>Overview</h1>
</div> -->

<?php


$monthly_income = $pdo->prepare("SELECT
                                    DATE_TRUNC('month',deposit_date)
                                    AS  deposit_month,
                                    round(avg(deposit_amount),2) AS monthly_average,
                                    sum(deposit_amount) AS monthly_total
                                FROM income
                                GROUP BY DATE_TRUNC('month',deposit_date)
                                order by deposit_month;");
$monthly_income->execute();

$monthArray = array();
$totalArray = array();


foreach ($monthly_income as $row) {
    $month =  date('F-Y', strtotime($row['deposit_month']));
    $monthlyTotal = $row['monthly_total'];

    array_push($monthArray, $month);
    array_push($totalArray, $monthlyTotal);
}

$elements = count($monthArray);
?>

<div class="grid-container">
    <?php
    for ($i = 0; $i < $elements; $i++) {
        echo 
        "<div class='item'>
            $monthArray[$i]
        </div>";
    }
    ?>
</div>

<div class="grid-container">
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