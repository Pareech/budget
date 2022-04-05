<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/projections.css' />
<title>Budget Projections</title>

<div class='header'>
    <h1><?php echo date('F Y', strtotime('now')); ?> </br>Budget Projection</h1>
</div>


<?php
include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';
?>
<div class="grid-container">

    <?php
    $monthly_total = 0;
    $get_month = date('Y-m', strtotime($month_year));

    $current_month = $pdo->prepare("SELECT due_date, payee, payment_amount, transaction_type 
                                    FROM budget_projection
                                    WHERE due_date::text ILIKE :get_month
                                    ORDER BY due_date, payment_amount DESC;");
    ?>

    <!-- Current Month Net Table -->
    <div class="item3">
        <table>
            <?php
            $current_month->execute(['get_month' => '%' . $get_month . '%']);

            foreach ($current_month as $row) {
                $amount = $row['payment_amount'];
                $monthly_total += $amount;
            }
            $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
            $net_total = $money->formatCurrency($monthly_total, 'USD');

            if ($monthly_total < 0) {
                $net_total = '<span style="color:#FF0000;">' . $net_total;
            } else {
                $net_total = '<span style="color:#006400;">' . $net_total;
            }
            ?>

            <tr>
                <th colspan="4" ; class='heading'>
                    <?php echo $month_year . "<br>Net " . $net_total; ?>
                </th>
            </tr>

            <!-- Curent Month Transactions Table -->
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Expense</th>
                <th>Deposit</th>
            </tr>

            <?php
            $current_month->execute(['get_month' => '%' . $get_month . '%']);
            foreach ($current_month as $row) {
                $date = date('d-M', strtotime($row['due_date']));
                $source = $row['payee'];
                $payment_amount = $row['payment_amount'];
                $type = $row['transaction_type'];

                echo "<tr>";
                echo "<td>" . $date . "</td>";
                echo "<td>" . $source . "</td>";

                $money_amount = $money->formatCurrency($payment_amount, 'USD');

                if ($type == 'payment') {
                    echo "<td style='color:#FF0000'>" . $money_amount . "</td>" . "<td> </td>";
                } else {
                    echo "<td> </td>" . "<td style='color:#006400'>" . $money_amount . "</td>";
                }
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>