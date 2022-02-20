<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/projections.css' />
<title>Budget Projections</title>

<div class='header'>
    <h1>Monthly</br>Budget Projection</h1>
</div>


<?php

include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

?>
<div class="grid-container_total" ; id="grid_format">

    <?php
    for ($i = 0; $i < 2; ++$i) {
        $monthly_total = 0;
        $month_year = date('F Y', strtotime('first day of +' . $i . 'month'));
        $get_month = date('Y-m', strtotime($month_year));
        getProjections($pdo, $monthly_total, $month_year, $get_month);
    }

    function getProjections($pdo, $monthly_total, $month_year, $get_month)
    {
        $monies = $pdo->prepare("SELECT due_date, payee, payment_amount, transaction_type 
                                FROM budget_projection
                                WHERE due_date::text ILIKE :get_month
                                ORDER BY due_date, payee, payment_amount;");
    ?>

        <!-- Monthly Net Table -->
        <table class='table_totals'>
            <?php
            $monies->execute(['get_month' => '%' . $get_month . '%']);

            foreach ($monies as $row) {
                $amount = $row['payment_amount'];
                $monthly_total += $amount;
            }
            $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
            $net_total = $money->formatCurrency($monthly_total, 'USD');

            if ($monthly_total < 0) {
                $monthly_total = '<span style="color:red;">' . $net_total;
            }

            ?>
            <tr>
                <th colspan="4" ; class='heading'>
                    <?php echo $month_year . "<br>
                    Estimated Monthly Net " . $monthly_total;
                    ?>
                </th>
            </tr>

            <!-- Monthly Transactions Table -->
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Expense</th>
                <th>Deposit</th>
            </tr>

        <?php
        $monies->execute(['get_month' => '%' . $get_month . '%']);
        foreach ($monies as $row) {
            $date = date('d-M', strtotime($row['due_date']));
            $source = $row['payee'];
            $payment_amount = $row['payment_amount'];
            $type = $row['transaction_type'];

            echo "<tr>";
            echo "<td>" . $date . "</td>";
            echo "<td>" . $source . "</td>";

            $money_amount = $money->formatCurrency($payment_amount, 'USD');


            if ($type == 'payment') {
                echo "<td style='color:red'>" . $money_amount . "</td>" . "<td>" . "</td>";
            } else {
                echo "<td>" . "</td>" . "<td>" . $money_amount . "</td>";
            }
            echo "</tr>";
        }
    }
        ?>
        </table>
</div>