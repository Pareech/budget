<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<title>Get Yearly Averages (Income, Payments, Net)</title>

<?php

$monthlyAvg = 0;

// Get Net Change since Beginning of Current Year
$get_diff = $pdo->query("SELECT (r2.amount - r1.amount) AS year_amt
                            FROM monthly_variance AS r1 CROSS JOIN
                                 monthly_variance AS r2
                            WHERE date_part('month', r1.date) = 1
                                AND date_part('year',r1.date) = date_part('year', now())
                                AND date_part('month',r2.date) = date_part('month', now())
                                AND date_part('year',r2.date) = date_part('year', now());")->fetchColumn();

// Get differnt types of transactions (Income / Expense)
$trans_type = $pdo->prepare("SELECT DISTINCT(transaction_type) AS transaction_type FROM budget_projection;");
$trans_type->execute();

// Get averages for each type of transactions
$findAvg = $pdo->prepare("SELECT ROUND(AVG(month_total),2)
                          FROM (SELECT date_trunc('month',due_date), ABS(SUM(payment_amount)) AS month_total
                                FROM budget_projection 
                                WHERE due_date > now() - INTERVAL '6 months' 
                                    AND date_trunc('month', due_date) <= date_trunc('month', now())
                                    AND date_trunc('year' , due_date) <= date_trunc('year',  now())
                                    AND transaction_type = :transactions
                                GROUP BY date_trunc('month', due_date)
                                ) AS month_total;");

foreach ($trans_type as $row) {
    $transaction = $row['transaction_type'];
    $findAvg->execute(['transactions' => $transaction]);
    $getAvg = $findAvg->fetchColumn();

    $money = new NumberFormatter('en', NumberFormatter::CURRENCY);

    if ($transaction == 'payment') {
        $payment = $money->formatCurrency($getAvg, 'USD');
        $getAvg *= -1;
    } else {
        $income = $money->formatCurrency($getAvg, 'USD');
    }

    $monthlyAvg += $getAvg;
}

if ($monthlyAvg < 0) {
    $monthly_avg = '<span style="color:#FF0000; ">' . $money->formatCurrency($monthlyAvg, 'USD') . '</span>';
} else {
    $monthly_avg = '<span style="color:#00FF00;">' . $money->formatCurrency($monthlyAvg, 'USD') . '</span>';
}

if ($get_diff < 0) {
    $year_net = '<span style="color:#FF0000;">' . $money->formatCurrency($get_diff, 'USD') . '</span>';
} else {
    $year_net = '<span style="color:#00FF00;">' . $money->formatCurrency($get_diff, 'USD') . '</span>';
}

?>

<!-- Display Averages and totals -->
<h3>
    <div class="item1">
        <table>
            <tr>
                <td rowspan=3; style='width:100px; background-color:#000000' ;> Last 6<br>Months
                <td id=alnright>Average Income</td>
                <td id=alnleft><?php echo $income; ?></td>
            </tr>

            <tr>
                <td id=alnright>Average Expenses</td>
                <td id=alnleft><?php echo $payment; ?></td>
            </tr>

            <tr>
                <td id=alnright>Monthly Average</td>
                <td id=alnleft><?php echo $monthly_avg; ?></td>
            </tr>
            <tr>
                <td style='background-color:#000000' ;></td>
                <td id=alnright>Year Net</td>
                <td id=alnleft><?php echo $year_net; ?></td>
            </tr>
            </td>
        </table>
    </div>
</h3>