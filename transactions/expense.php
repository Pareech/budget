<!DOCTYPE html>

<?php
include '../db_connections/connection_pdo.php';
?>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/transaction.css' />
<title>Enter Expenses</title>

<?php

$category = $_GET['exp'];

if ($category == 1) {
    $category = 'Yearly';
}

echo "<div class='header'>
    <h1>Enter $category<br>Expenses</h1>
</div>";

include '../misc_files/nav_bar_links.php';

$choose_expense = $pdo->prepare("SELECT expense_name FROM expense_categories WHERE expense_category = :exp_cat AND payment_frequency::integer <> 1 ORDER BY expense_name;");
$choose_expense->execute(['exp_cat' => $category]);

$yearly_expense = $pdo->prepare("SELECT expense_name FROM expense_categories WHERE payment_frequency::integer = 1 ORDER BY expense_name;");
$yearly_expense->execute();

$cc_charges = $pdo->prepare("SELECT how_paid, payment_type FROM payment_method ORDER BY how_paid;");
$cc_charges->execute();

?>

<form name="display" action="" method="POST">
    <div class="grid-container">
        <div>
            <h2>Expense</h2>
            <select id="textboxid" name="expense" value='' class=dropmenus></option>
                <option value=""></option>
                <?php
                if ($category <> 'Yearly') {
                    foreach ($choose_expense as $row) {
                        echo "<option value='$row[expense_name]'>$row[expense_name]</option>";
                    }
                } else {
                    foreach ($yearly_expense as $row) {
                        echo "<option value='$row[expense_name]'>$row[expense_name]</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div>
            <h2>Credit Card</h2>
            <select id="textboxid" name="card" value='' class=dropmenus></option>
                <option value=""></option>
                <?php
                foreach ($cc_charges as $row) {
                    echo "<option value='$row[how_paid]'>$row[how_paid]</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <h2>Amount</h2>
            <input id="textboxid" name="cost" placeholder="Expense Amount" type="text" />
        </div>
        <div>
            <h2>Date</h2>
            <input type="date" id="textboxid" name="date" />
        </div>
        <div>
            <h2>Note</h2>
            <input type="text" id="textboxid" name="note" />
        </div>
        <div>
            <h2>Add Projection</h2>
            <input type="checkbox" name="add_projection" value="yes">
        </div>
        <div>
            <br><br><br>
            <button type="submit" id="transaction_button" name="submit_expense" class="button" value="submit">Submit<br>Expense</button>
        </div>
    </div>
</form>

<!-- Enter Expense into the Database -->
<?php
if (isset($_POST['submit_expense'])) {

    $required = array('expense', 'cost', 'date');

    $error = false;
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $error = true;
        }
    }

    if ($error) {
        echo
        "<script>
            alert('All fields are required, except for Credit Card and Notes.');
        </script>";
    } else {
        $expense = $payee = $_POST['expense'];
        $card_used = $_POST['card'];
        $payment_amount = $cost = $_POST['cost'];
        $date = $due_date = $_POST['date'];
        $note = $_POST['note'];
        $entry_type = 'payment';

        if ($note == '') {
            $note = NULL;
        }

        // Get the Type of Expense Being Entered
        $get_details = $pdo->prepare("SELECT expense_type FROM expense_categories WHERE expense_name = :expense_name;");
        $get_details->execute(['expense_name' => $expense]);
        foreach ($get_details as $row) {
            $type = $row['expense_type'];
        }

        $spend = $pdo->prepare("INSERT INTO expenses (item_purchased, amount, category, kind, paid_by, purchase_date, note)
                                VALUES (:item_purchased, :cost, :category, :kind, :cc, :expense_date, :note);");
        $spend->execute(['item_purchased' => $expense, 'cost' => $cost, 'category' => $category, 'kind' => $type, 'cc' => $card_used, 'expense_date' => $date, 'note' => $note]);

        if (isset($_POST['add_projection'])) {
            $payment_amount *= -1;

            $payment_type = $pdo->prepare("SELECT payment_type FROM payment_method WHERE how_paid = :payment_type;");
            $payment_type->execute(['payment_type' => $card_used]);
            $payment_used = $payment_type->fetchColumn();

            if ($payment_used == 'Cash') {
                $payment_used = 'Interact Transfer';
            }
            include '../projections/enter_projections_db.php';
        }
        echo "<script> window.location.href='..' </script>";
    }
}
?>