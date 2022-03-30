<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='css/details.css' />
<title>Averages</title>

<div class="grid-container_avg" ; id="grid_format">

    <?php

    $categories = $pdo->prepare("SELECT DISTINCT expense_type FROM expense_categories;");
    $categories->execute();

    foreach ($categories as $row) {
        $type = $row['expense_type'];
        getTotals($pdo, $type);
    }

    function getTotals($pdo, $type)
    {
    ?>
        <table class="table_avg">
            <tr>
                <th colspan="4" ; class='heading'>
                    <?php echo $type . "<br>Expenses"; ?>
                </th>
            </tr>
            <tr>
                <th>Expense</th>
                <th>Monthly<br>Average</th>
                <th>Bi-Monthly<br>Average</th>
                <th>Last 12<br>Months</th>
            </tr>

        <?php
        $get_payments = $pdo->prepare("SELECT item_purchased, 
                                                round(sum(amount), 2) AS year_spend, 
                                                round(sum(amount) / 12, 2) AS monthly_avg, 
                                                round(sum(amount) / 12 / 2, 2) AS bi_monthly_avg,
                                                count(amount) AS numb_payments
                                            FROM expenses 
                                            WHERE purchase_date > NOW() - INTERVAL '1 year' AND purchase_date < NOW() AND kind = :expType
                                            GROUP BY item_purchased
                                            ORDER BY item_purchased;");
        $get_payments->execute(['expType' => $type]);

        $money = new NumberFormatter('en', NumberFormatter::CURRENCY);

        foreach ($get_payments as $row) {
            $exp_name = $row['item_purchased'];
            $monthly = $row['monthly_avg'];
            $bimonthly = $row['bi_monthly_avg'];
            $year_total = $row['year_spend'];
            $numb_payments = $row['numb_payments'];

            $get_expense = $pdo->prepare("SELECT payment_frequency FROM expense_categories WHERE expense_name = :exp_name;");
            $get_expense->execute(['exp_name' => $exp_name]);
            
            while ($rows = $get_expense->fetch()) {
                $frequency = $rows['payment_frequency'];
            }

            if ($frequency == 24) {
                $monthly = $year_total / $numb_payments;
                $bimonthly = $year_total / $numb_payments / 2;
            } elseif ($frequency == 26) {
                $monthly = $year_total / $numb_payments * 2;
                $bimonthly = $year_total / $numb_payments;
            }

            echo "<tr>";
                echo "<td>" . $exp_name . "</td>";
                echo "<td>" . $money->formatCurrency($monthly, 'USD') . "</td>";
                echo "<td>" . $money->formatCurrency($bimonthly, 'USD') . "</td>";
                echo "<td>" . $money->formatCurrency($year_total, 'USD') . "</td>";
            echo "</tr>";
        }
    }
        ?>
        </table>
</div>