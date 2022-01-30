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
<div class="grid-container_total" ; id="grid_format">

    <?php
    $projections = $pdo->prepare("SELECT payee, payment_amount, due_date 
                                  FROM budget_projection
                                  WHERE due_date >= date_trunc('day', CURRENT_DATE) AND due_date <= NOW() + INTERVAL '2 months'
                                  ORDER BY due_date;");
    $projections->execute();
    ?>

    <!-- Current Projections -->
    <form name="display" action="" method="POST">
        <div class="row">
            <div class="column left">
                <center><button type="submit" id="update_projection" name="projection_update" class="button" value="submit" />Update Budget Projections</button></center>
                <center><button type="reset" class="button">Reset the Form</button></center>
                </br></br>
            </div>
            <table class="table_totals">
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Expense</th>
                    <th>Deposit</th>
                    <th style='background-color:#000000'></th>
                    <th>Correct Date</th>
                    <th>Correct Amount</th>
                    <!-- <th>Delete Entry</th> -->
                </tr>
                <?php
                while ($row = $projections->fetch()) {
                    $purchase_date = date('d-M-Y', strtotime($row['due_date']));
                    echo "<tr>";
                        echo "<td>" . $purchase_date . "</td>";
                        echo "<input type='hidden' name='due_date[]' value='" . $row['due_date'] . "'>";
                        echo "<td>" .  $row['payee'] . "</td>";
                        echo "<input type='hidden' name='payee[]' value='" . $row['payee'] . "'>";

                        if ($row['payment_amount'] < 0) {
                            echo "<td style='color:red'>" . $row['payment_amount'] . "</td>" . "<td>" . "</td>";
                        } else {
                            echo "<td>" . "</td>" . "<td>" . $row['payment_amount'] . "</td>";
                        }
                        echo "<input type='hidden' name='payment_amount[]' value='" . $row['payment_amount'] . "'>";

                        echo "<td style='background-color:#000000'></td><";
                        echo "td> <center><input id='textboxid' name='date_correction[]' placeholder='Date Correction' type='date' /></center> </td>";
                        echo "<td> <center><input id='textboxid' name='corrected_amount[]' placeholder='Correct Amount' type='text' /></center> </td>";
                        // echo "<td> <input type='radio' id='radioItem' name='remove[]' value='to_delete'> </td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </form>
</div>

<!-- Update Budget Projection Database -->
<?php
if (isset($_POST['projection_update'])) {
    $date_correction = $_POST['date_correction'];
    $due_date = $_POST['due_date'];
    $payee = $_POST['payee'];
    $payment_amount = $_POST['payment_amount'];
    $corrected_amount = $_POST['corrected_amount'];
    $to_delete = $_POST['remove'];

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
            }

            $original_date = $due_date[$i];
            $payee_to = $payee[$i];

            // echo "payee_to: " . $payee_to . "<br>";
            // echo "payment amount: " . $payment . "<br>";
            // echo "payment date: " . $date . "<br>";
            // echo "original_date: " . $original_date . "<br><br>";

            $update_projection = $pdo->prepare("UPDATE budget_projection 
                                                SET due_date = :date_due,
                                                    payment_amount = :amount_due
                                                WHERE payee = :pay_to AND due_date = :original_date;");
            $update_projection->execute(['date_due' => $date, 'amount_due' => $payment, 'pay_to' => $payee_to, 'original_date' => $original_date]);
        }
    }
    // Delete Projections Not Needed
    // $get_deletions = count($to_delete);
    // if ($get_deletions > 0) {
    //     for ($j = 0; $j < $get_deletions; ++$j) {
    //         if ($to_delete[$j] == 'to_delete') {
    //             echo "drop it like its hot<br><br>";
    //             echo $j;
    //         } else {
    //             echo "keep that going<br>";
    //         }
    //     }
    // }
    echo
    "<script> 
        window.location.href='monthly_projection.php'
    </script>";
}
?>