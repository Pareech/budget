<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/projections.css' />
<title>Budget Projections</title>

<div class='header'>
    <h1>Update Monthly</br>Budget Projection</h1>
</div>


<?php
include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';
?>

<?php
$projections = $pdo->prepare("SELECT payee, payment_amount, due_date 
                              FROM budget_projection
                              WHERE due_date >= date_trunc('day', CURRENT_DATE) AND due_date <= NOW() + INTERVAL '2 months'
                              ORDER BY due_date, payment_amount DESC;");
$projections->execute();
?>

<!-- Current Projections -->
<form class="grid-container" name="display" action="" method="POST">
    <div class="item1">
        <button type="submit" id="transaction_button" class="button" name="projection_update" value="submit" />Update Budget Projections</button>
    </div>
    <div class="item2">
        <button type="reset" id="transaction_button" class="button">Reset the Form</button>
    </div>
    <div class="item3">
        <table>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Expense</th>
                <th>Deposit</th>
                <th style='background-color:#000000'></th>
                <th>Correct Date</th>
                <th>Correct Amount</th>
            </tr>
            <?php
            while ($row = $projections->fetch()) {
                $purchase_date = date('d-M-Y', strtotime($row['due_date']));
                echo "<tr>";
                echo "<td>" . $purchase_date . "</td>";
                echo "<input type='hidden' name='due_date[]' value='" . $row['due_date'] . "'>";
                echo "<td>" .  $row['payee'] . "</td>";
                echo "<input type='hidden' name='payee[]' value='" . $row['payee'] . "'>";

                $transaction = $row['payment_amount'];

                $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
                $transaction_amount = $money->formatCurrency($transaction, 'USD');

                if ($row['payment_amount'] < 0) {
                    echo "<td style='color:#FF0000'>" . $transaction_amount . "</td>" . "<td>" . "</td>";
                } else {
                    echo "<td>" . "</td>" . "<td>" . $transaction_amount . "</td>";
                }
                echo "<input type='hidden' name='payment_amount[]' value='" . $row['payment_amount'] . "'>";

                echo "<td style='background-color:#000000'></td><";
                echo "td> <center><input id='textboxid' name='date_correction[]' placeholder='Date Correction' type='date' /></center> </td>";
                echo "<td> <center><input id='textboxid' name='corrected_amount[]' placeholder='Correct Amount' type='text' /></center> </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</form>

<!-- Update Budget Projection Database -->
<?php
if (isset($_POST['projection_update'])) {
    $date_correction = $_POST['date_correction'];
    $due_date = $_POST['due_date'];
    $payee = $_POST['payee'];
    $payment_amount = $_POST['payment_amount'];
    $corrected_amount = $_POST['corrected_amount'];

    $size = count($corrected_amount);

    for ($i = 0; $i < $size; ++$i) {
        if ($corrected_amount[$i] <> '' or $date_correction[$i] <> '') {

            if ($date_correction[$i] == '') {
                $date = $due_date[$i];
            } else {
                $date = $date_correction[$i];
            }

            if ($corrected_amount[$i] == '') {
                $payment = $payment_amount[$i];
            } else {
                $payment = $corrected_amount[$i];
                if ($payment_amount[$i] < 0) {
                    $payment *= -1;
                }
            }

            $original_date = $due_date[$i];
            $payee_to = $payee[$i];

            $update_projection = $pdo->prepare("UPDATE budget_projection 
                                                SET due_date = :date_due,
                                                    payment_amount = :amount_due
                                                WHERE payee = :pay_to AND due_date = :original_date;");
            $update_projection->execute(['date_due' => $date, 'amount_due' => $payment, 'pay_to' => $payee_to, 'original_date' => $original_date]);

            // Update Income or Expense Database
            if ($payment > 0) {
                $update_income = $pdo->prepare("UPDATE income
                                                SET deposit_date = :deposit_date,
                                                    deposit_amount = :amount
                                                WHERE note = :item AND deposit_date = :original_date;");
                $update_income->execute(['deposit_date' => $date, 'amount' => $payment, 'item' => $payee_to, 'original_date' => $original_date]);
            } else {
                $payment *= -1;
                $update_expenses = $pdo->prepare("UPDATE expenses
                                                  SET purchase_date = :buy_date,
                                                      amount = :amount_due
                                                  WHERE item_purchased = :item AND purchase_date = :original_date;");
                $update_expenses->execute(['buy_date' => $date, 'amount_due' => $payment, 'item' => $payee_to, 'original_date' => $original_date]);
            }
        }
    }
    echo
    "<script> 
        window.location.href='monthly_projection.php'
    </script>";
}
?>