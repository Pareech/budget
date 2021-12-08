<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/details.css' />
<title>Budget Projections</title>

<div class='header'>
    <h1>Monthly</br>Budget Projection</h1>
</div>

<?php

include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

// Monthly Net
// $month_net = $pdo->prepare("SELECT TO_CHAR(current_timestamp, 'Month') AS monthly_net, net_amount
//                                     FROM(
//                                         SELECT SUM(
//                                         (SELECT SUM(payment_amount)
//                                         FROM
//                                             (SELECT * FROM budget_projection
//                                             WHERE EXTRACT(month FROM due_date) = EXTRACT(month FROM CURRENT_DATE) AND transaction_type = :income
//                                             ORDER BY due_date) AS money_in)  
//                                         +
//                                         (SELECT SUM(payment_amount)
//                                         FROM
//                                             (SELECT payee, payment_amount, due_date, transaction_type
//                                             FROM budget_projection
//                                             WHERE EXTRACT(month FROM due_date) = EXTRACT(month FROM CURRENT_DATE) AND transaction_type = :payment
//                                             ORDER BY due_date
//                                             ) AS money_out) ) AS net_amount) AS toto;");
// $month_net->execute(['income'=>'income', 'payment'=>'payment']);

$monthly_total = 0;

// for ($i = 0; $i < 3; ++$i) {

$month_year = date('F Y',strtotime('first day of +0 month'));
$get_month = date('Y-m', strtotime($month_year));

$monies = $pdo->prepare("SELECT due_date, payee, payment_amount, transaction_type 
                            FROM budget_projection
                            WHERE due_date::text ILIKE :get_month
                            -- WHERE EXTRACT(month FROM due_date) = EXTRACT(month FROM CURRENT_DATE)
                            ORDER BY due_date, payee, payment_amount;");
?>

<!-- Monthly Net Table -->
<table class='table_totals'>
    <tr>
        <th>Date</th>
        <th>Net Amount</th>
    </tr>

    <?php
    $monies->execute(['get_month'=> '%' . $get_month . '%']);

    foreach ($monies as $row) {
        $amount = $row['payment_amount'];
        $monthly_total += $amount;
    }

        echo "<tr>";
        echo "<td>" . $month_year . "</td>";
        echo "<td>$" . $monthly_total . "</td>";
        echo "</tr>";
    ?>
</table>

<!-- Monthly Transactions Table -->
<table class='table_totals'>
    <tr>
        <th>Date</th>
        <th>Description</th>
        <th>Expense</th>
        <th>Deposit</th>
    </tr>
    <br><br>

    <?php
    $monies->execute(['get_month'=> '%' . $get_month . '%']);
    foreach ($monies as $row) {
        $date = date('d-M', strtotime($row['due_date']));
        $source = $row['payee'];
        $money = $row['payment_amount'];
        $type = $row['transaction_type'];

        echo "<tr>";
        echo "<td>" . $date . "</td>";
        echo "<td>" . $source . "</td>";

        if ($type == 'payment') {
            echo "<td style='color:red'>" . $money . "</td>" . "<td>" . "</td>";
        } else {
            echo "<td>" . "</td>" . "<td>" . $money . "</td>";
        }
        echo "</tr>";
    }
    ?>
</table>