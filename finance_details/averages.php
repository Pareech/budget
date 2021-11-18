<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='css/details.css' />
<title>Averages</title>



<div class="grid-container_avg" ; id="grid_format">

    <?php

    $categories = $pdo->prepare("SELECT DISTINCT expense_type FROM expense_categories;");
    $categories->execute();

    foreach ($categories as $row) {
        // $name = $row['expense_name'];
        // $category = $row['expense_category'];
        $type = $row['expense_type'];
        // $frequency = $row['payment_frequency'];
        getTotals($pdo, $type);
    }

    function getTotals($pdo, $type)
    {
        $get_avg = $pdo->prepare("SELECT item_purchased, kind, ROUND(AVG(month_spend), 2) AS avg_cost
                                    FROM (SELECT item_purchased, kind, DATE_TRUNC('month',purchase_date) AS  buy_month, sum(amount) AS month_spend
                                        FROM expenses
                                        -- WHERE purchase_date > CURRENT_DATE - INTERVAL '12 months' AND kind = :expkind
                                        -- WHERE purchase_date BETWEEN purchase_date AND NOW() AND item_purchased = 'Mortgage'
                                        WHERE purchase_date BETWEEN purchase_date AND NOW() AND kind = :expkind
                                        GROUP BY item_purchased, kind, DATE_TRUNC('month', purchase_date)
                                        ORDER BY buy_month) AS monthly_average
                                    GROUP BY item_purchased, kind
                                    ORDER BY item_purchased;");
        $get_avg->execute(['expkind' => $type]);
    ?>

        <table class="table_avg">
            <tr>
                <th colspan="4"; class='heading'>
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
        $get_payments = $pdo->prepare("SELECT round(sum(amount), 2) AS year_spend, count(amount) AS numb_payments FROM expenses WHERE item_purchased = :exp_name;");
        $get_expense = $pdo->prepare("SELECT payment_frequency, expense_type FROM expense_categories WHERE expense_name = :exp_name;");

        foreach ($get_avg as $row) {
            $exp_name = $row['item_purchased'];
            $get_payments->execute(['exp_name' => $exp_name]);
            while ($row_get = $get_payments->fetch()) {
                $year_total = $row_get['year_spend'];
                $numb_payments = $row_get['numb_payments'];
            }

            $get_expense->execute(['exp_name' => $exp_name]);
            while ($rows = $get_expense->fetch()) {
                $frequency = $rows['payment_frequency'];
            }

            if ($frequency == 1 or $frequency == 4 OR $type == 'Variable') {
                $monthly = $year_total / 12;
                $bimonthly = $year_total / 12 / 2;
            } elseif ($frequency == 6) {
                $monthly = $year_total / $numb_payments / 2;
                $bimonthly = $year_total / $numb_payments / 4;
            } elseif ($frequency == 12) {
                $monthly = $year_total / $numb_payments;
                $bimonthly = $year_total / $numb_payments / 2;
            } elseif ($frequency == 26) {
                $monthly = $year_total / $numb_payments * 2;
                $bimonthly = $year_total / $numb_payments;
            }
            echo "<tr>";
                echo "<td>" . $exp_name . "</td>";
                echo "<td> $" . number_format($monthly, 2) . "</td>";
                echo "<td> $" . number_format($bimonthly, 2) . "</td>";
                echo "<td> $" . number_format(($year_total), 2) . "</td>";
            echo "</tr>";
        }
    }
        ?>
        </table>
</div>