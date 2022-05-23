<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/payment_updates.css' />
<title>Update Non-Personal Expenses Entered</title>

<div class='header'>
    <h1>Update Entered</br>Non Personal Expenses</h1>
</div>


<?php

include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

$get_payment = $pdo->prepare("SELECT how_paid FROM payment_method ORDER BY how_paid ASC;");
$get_payment->execute();

?>

<!-- Get Payment Method -->
<form name="display" action="" method="POST">
    <div class="grid-container">
        <div class="item1">
            <h2>Source</h2>
            <select id="textboxid" name="cc_used" value='' class=dropmenus></option>
                <option value=""></option>
                <?php
                foreach ($get_payment as $row) {
                    echo "<option value='$row[how_paid]'>$row[how_paid]</option>";
                }
                ?>
            </select>
        </div>

        <div class="item2">
            <button type="submit" id="transaction_button" name="check_cc" class="button" value="submit">Choose<br>Payment</button>
        </div>

        <!-- Get Last 45 days of Bill Entry for Payment Method -->
        <?php
        if (isset($_POST['check_cc'])) {
            $cc_used = $_POST['cc_used'];
        ?>
            <div class="item3">
                <button type="submit" id="transaction_button" name="update_payments" class="button" value="submit">Update<br>Payments</button>
            </div>

            <?php
            $list_payments = $pdo->prepare("SELECT not_exp_pk, not_exp_source, not_exp_cc, not_exp_amount, not_exp_date, not_exp_note
                                            FROM not_expenses
                                                WHERE not_exp_date <= CURRENT_DATE AND not_exp_date >= CURRENT_DATE - 45 
                                                AND not_exp_cc = :cc_used
                                            ORDER BY not_exp_date desc, not_exp_amount DESC;");
            $list_payments->execute(['cc_used' => $cc_used]);

            $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
            ?>

            <div class="item4">
                <table>
                    <tr>
                        <th colspan="7" ; class='heading'>
                            <?php echo "Update<br>" . $cc_used; ?>
                        </th>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Cost</th>
                        <th>Note</th>
                        <th style='background-color:#000000; width:0.063rem;'></th>
                        <th>Correct Date</th>
                        <th>Correct Amount</th>
                    </tr>

                <?php
                foreach ($list_payments as $row) {
                    echo "<tr>";
                        echo "<input type='hidden' name='primary_key[]' value='" . $row['not_exp_pk'] . "'>";
                        echo "<td>" . date('d-M', strtotime($row['not_exp_date'])) . "</td>";
                        echo "<input type='hidden' name='purchase_date[]' value='" . $row['not_exp_date'] . "'>";
                        echo "<td>" . $row['not_exp_source'] . "</td>";
                        echo "<td>" . $money->formatCurrency($row['not_exp_amount'], 'USD')  . "</td>";
                        echo "<input type='hidden' name='payment_amount[]' value='" . $row['not_exp_amount'] . "'>";
                        echo "<td>" . $row['not_exp_note']  . "</td>";
                        echo "<td style='background-color:#000000 ; width:0.063rem;'></td><";
                        echo "td> <center><input id='textboxid' name='corrected_date[]' placeholder='Date Correction' type='date' /></center> </td>";
                        echo "<td> <center><input id='textboxid' name='corrected_amount[]' placeholder='Correct Amount' type='text' /></center> </td>";
                    echo "</tr>";
                }
            }
                ?>
                </table>
            </div>
    </div>
</form>

<!-- Update incorrectly Entered Bill Information -->
<?php
if (isset($_POST['update_payments'])) {
    $corrected_date = $_POST['corrected_date'];
    $purchase_date = $_POST['purchase_date'];
    $primary_key = $_POST['primary_key'];
    $payment_amount = $_POST['payment_amount'];
    $corrected_amount = $_POST['corrected_amount'];

    $size = count($corrected_amount);

    for ($i = 0; $i < $size; ++$i) {

        if ($corrected_amount[$i] <> '' or $corrected_date[$i] <> '') {

            if ($corrected_date[$i] == '') {
                $date = $purchase_date[$i];
            } else {
                $date = $corrected_date[$i];
            }

            if ($corrected_amount[$i] == '') {
                $amount = $payment_amount[$i];
            } else {
                $amount = $corrected_amount[$i];
            }

            $update_expenses = $pdo->prepare("UPDATE not_expenses 
                                              SET not_exp_date = :purchase_date,
                                                  not_exp_amount = :amount_due
                                              WHERE not_exp_pk = :pk;");
            $update_expenses->execute(['purchase_date' => $date, 'amount_due' => $amount, 'pk' => $primary_key[$i]]);
        }
    }
    echo  "<script> window.location.href='..' </script>";
}
?>