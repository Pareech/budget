<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<title>Get Yearly Averages (Income, Payments, Net)</title>

<h3>
    <?php

    $monthlyAvg = 0;

    $trans_type = $pdo->prepare("SELECT DISTINCT(transaction_type) AS transaction_type FROM budget_projection;");
    $trans_type->execute();

    $findAvg = $pdo->prepare("SELECT round(AVG(month_total), 2)
                          FROM (SELECT DATE_TRUNC('month',due_date), ABS(sum(payment_amount)) AS month_total
                                FROM budget_projection
                                WHERE EXTRACT(year FROM due_date) = EXTRACT(year FROM now()) AND 
                                        EXTRACT(month FROM due_date) <= EXTRACT(MONTH FROM now())
                                        AND transaction_type = :transactions
                                GROUP BY DATE_TRUNC('month', due_date)
                               ) AS monthly_avg;");

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

    $monthly_avg = $money->formatCurrency($monthlyAvg, 'USD');

    if ($monthlyAvg < 0) {
        $monthly_avg = '<span style="color:red; ">' . $monthly_avg . '</span>';
    }

    ?>
    <!-- <div class="grid-container"> -->

    <div class="item1">
    <table class='table'>
        <tr>
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
    </table>
    </div>

</h3>