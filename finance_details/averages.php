<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='css/details.css' />
<title>Averages</title>



<div class="grid-container_avg"; id="grid_format">

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
                                    WHERE purchase_date > CURRENT_DATE - INTERVAL '12 months' AND kind = :expkind
                                    GROUP BY item_purchased, kind, DATE_TRUNC('month', purchase_date)
                                    ORDER BY buy_month) AS monthly_average
                              GROUP BY item_purchased, kind
                              ORDER BY item_purchased;");
        $get_avg->execute(['expkind' => $type]);
    ?>

        <table class="table_avg">
            <tr>
                <th colspan="4">
                    <?php echo $type." Expenses"; ?>
                </th>
            </tr>
            <tr>
                <th>Expense</th>
                <th>Montly Average</th>
                <th>Bi-Monthly</th>
                <th>Last 12 Months</th>
            </tr>

        <?php
        foreach ($get_avg as $row) {
            $exp_name = $row['item_purchased'];
            $month_avg = $row['avg_cost'];
            // $cat = $row['category'];
            // $kind = $row['kind'];

            // $get_expense = $pdo->prepare("SELECT payment_frequency FROM expense_categories WHERE expense_name = :exp_name;");
            // $get_expense->execute(['exp_name' => $exp_name]);
            // while ($rows = $get_expense->fetch()) {
            //     $frequency = $rows['payment_frequency'];
            // }

            // if ($frequency <> null) {
            //     $month_avg = round($month_avg / 12, 2);
            // }
            echo "<tr>";
            echo "<td>" . $exp_name . "</td>";
            echo "<td> $" . number_format($month_avg, 2) . "</td>";
            echo "<td> $" . number_format(($month_avg  / 2), 2) . "</td>";
            echo "<td> $" . number_format(($month_avg * 12), 2) . "</td>";
            // echo "<td>" . $cat . "</td>";
            // echo "<td>" . $kind . "</td>";


            echo "</tr>";
        }
    }
        ?>
        </table>
</div>