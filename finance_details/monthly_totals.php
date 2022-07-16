<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/details.css' />

<?php

$monthly_income = $pdo->prepare("SELECT to_char(due_date, 'YYYY-MM') AS transaction_month, SUM(payment_amount) AS monthly_income_total
                                 FROM budget_projection
                                      WHERE date_trunc('month', due_date) > date_trunc('month', CURRENT_DATE - INTERVAL '6 months') 
                                      AND date_trunc('month', due_date) <= date_trunc('month', current_date) AND payment_amount > 0
                                 GROUP BY transaction_month
                                 ORDER BY transaction_month;;");
$monthly_income->execute();

$monthly_expenses = $pdo->prepare("SELECT to_char(due_date, 'YYYY-MM') AS transaction_month, SUM(payment_amount) AS monthly_spend_total
                                   FROM budget_projection
                                        WHERE date_trunc('month', due_date) > date_trunc('month', CURRENT_DATE - INTERVAL '6 months') 
                                        AND date_trunc('month', due_date) <= date_trunc('month', current_date) AND payment_amount < 0
                                   GROUP BY transaction_month
                                   ORDER BY transaction_month;");
$monthly_expenses->execute();

$monthArray = array();  //To store created Month Year (ie Apr 2022)
$totalIncomeArray = array();  //To store net income for a given month
$totalExpenseArray = array(); //To store net expenses for a given month

foreach ($monthly_income as $row) {
    $month =  $row['transaction_month'];
    $year_name = date('Y', strtotime($row['transaction_month']));
    $month_name =  date('M', strtotime($row['transaction_month']));
    $monthly_transactions =  $month_name . ' ' . $year_name;
    $monthlyIncomeTotal = $row['monthly_income_total'];

    array_push($monthArray, $monthly_transactions);
    array_push($totalIncomeArray, $monthlyIncomeTotal);
}

foreach ($monthly_expenses as $row) {
    $monthlyExpenseTotal = $row['monthly_spend_total'];
    array_push($totalExpenseArray, $monthlyExpenseTotal);
}

$elements = count($monthArray);
?>

<div class="padding_top">
    <div class="grid-container_total" ; id="grid_monthly">
        <br>
        <?php
        for ($i = 0; $i < $elements; $i++) {
            echo
            "<div class='item' style='background-color:#FF6700; color:#663399'>
            $monthArray[$i]
        </div>";
        }
        ?>
    </div>

    <div class="grid-container_total" ; id="grid_format">
        <div class='item' style='background-color:#05C3DD'>
            Income
        </div>

        <?php
        $money = new NumberFormatter('en', NumberFormatter::CURRENCY);

        for ($i = 0; $i < $elements; $i++) {
            $monthly_deposits = $money->formatCurrency($totalIncomeArray[$i], 'USD');
            echo
            "<div class='item' style='background-color:#ECF2E0'>
            $monthly_deposits
        </div>";
        }
        ?>
    </div>

    <div class="grid-container_total" ; id="grid_format">
        <div class='item' style='background-color:#05C3DD'>
            Expense
        </div>
        <?php
        for ($i = 0; $i < $elements; $i++) {
            $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
            $monthly_spending = $money->formatCurrency($totalExpenseArray[$i] * -1, 'USD');
            echo
            "<div class='item' style='background-color:#ECF2E0'>
            $monthly_spending
        </div>";
        }
        ?>
    </div>

    <div class="grid-container_total" ; id="grid_format">
        <div class='item' style='background-color:#05C3DD'>
            Monthly Net
        </div>
        <?php
        for ($i = 0; $i < $elements; $i++) {
            $find_monthly_net = $totalIncomeArray[$i] + $totalExpenseArray[$i];
            $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
            $monthly_net = $money->formatCurrency($find_monthly_net, 'USD');

            if ($find_monthly_net > 0) {
                $monthly_net = '<span style="color:#089000;">' . $monthly_net . '</span>';
            } else {
                $monthly_net = '<span style="color:#FF0000;">' . $monthly_net . '</span>';
            }

            echo
            "<div class='item' style='background-color:#ECF2E0'>
            $monthly_net
        </div>";
        }
        ?>
    </div>
</div>