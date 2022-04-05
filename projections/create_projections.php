<!DOCTYPE html>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/transaction.css' />
<title>Enter a Series of Projections </title>

<?php
$doingWhat = $_GET['generating'];

if ($doingWhat == 'Expense') {
    $title = '<span style="color:#FF0000">' . $doingWhat . '</span>';
} else {
    $title = '<span style="color:#089000">' . $doingWhat . '</span>';
}

?>

<div class='header'>
    <h1>Generate Multiple</br> <?php echo $title; ?> Projections</h1>
</div>

<?php
include '../db_connections/connection_pdo.php';
include '../misc_files/nav_bar_links.php';

$payment_method = $pdo->prepare("SELECT how_paid FROM payment_method ORDER BY how_paid ASC;");
$payment_method->execute();

?>

<form name="display" action="" method="POST">
    <div class="grid-container">
        <div>
            <h2>Projection</h2>
            <input type="text" list="list_item" name="source" id="textboxid" />
            <datalist id="list_item">
                <option value=""></option>
                <?php
                if ($doingWhat == 'Expense') {
                    $choose_expense = $pdo->prepare("SELECT expense_name FROM expense_categories WHERE budget_projection IS NOT NULL ORDER BY expense_name");
                    $choose_expense->execute();
                    foreach ($choose_expense as $row) {
                        echo "<option value='$row[expense_name]'>$row[expense_name]</option>";
                    }
                } else {
                    $choose_income = $pdo->prepare("SELECT income_name FROM income_categories WHERE budget_projection IS NOT NULL ORDER BY income_name");
                    $choose_income->execute();
                    foreach ($choose_income as $row) {
                        echo "<option value='$row[income_name]'>$row[income_name]</option>";
                    }
                }
                ?>
            </datalist>
        </div>

        <div>
            <h2>Amount</h2>
            <input id="textboxid" name="amount" placeholder="amount" type="text" />
        </div>

        <div>
            <h2>Source</h2>
            <input type="text" list="money_source" name="payment_used" id="textboxid" />
            <datalist id="money_source">
                <option value=""></option>
                <?php
                if ($doingWhat == 'Expense') {
                    foreach ($payment_method as $row) {
                        echo "<option value='$row[how_paid]'>$row[how_paid]</option>";
                    }
                } else {
                    echo "<option value='Ian'>Ian</option>";
                    echo "<option value='Interact Transfer'>Interact Transfer</option>";
                    echo "<option value='Isabelle'>Isabelle</option>";
                }
                ?>
            </datalist>
        </div>

        <div>
            <h2>Start Date</h2>
            <input type="date" id="textboxid" name="start_date" />
        </div>

        <div>
            <h2>End Date</h2>
            <input type="date" id="textboxid" name="end_date" />
        </div>

        <div>
            <?php if ($doingWhat == 'Expense') {
                echo
                "<h2>Note</h2>
                 <input type='text' id='textboxid' name='note' />";
            }
            ?>
        </div>

        <div>
            <h2>Interval Time</h2>
            <input type="text" list="interval" name="add_interval" id="textboxid" />
            <datalist id="interval">
                <option value=""></option>
            </datalist>
        </div>

        <div>
            <h2>Interval Period</h2>
            <select id="textboxid" name="interval_period" value='' class=dropmenus></option>
                <option value=""></option>
                <option value='Weeks'>Weeks</option>
                <option value='Months'>Months</option>
            </select>
        </div>


        <div class="item1">
            <button type="submit" id="transaction_button" name="submit_amount" class="button" value="submit">Submit<br>Action</button>
        </div>
    </div>
</form>


<!-- Enter Projections for Income and Expenses into the Database -->
<?php
if (isset($_POST['submit_amount'])) {

    // $required = array('amount', 'payment_used', 'date');

    // $error = false;
    // foreach ($required as $field) {
    //     if (empty($_POST[$field])) {
    //         $error = true;
    //     }
    // }

    // if ($error) {
    //     echo
    //     "<script>
    //         alert('All fields are required.');
    //     </script>";
    // } else {

    $payee = $_POST['source'];
    $payment_amount = $_POST['amount'];
    $payment_method = $_POST['payment_used'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $interval = $_POST['add_interval'] . " " . $_POST['interval_period'];

    if ($doingWhat == 'Expense') {
        $note = $_POST['note'];
    } else {
        $note = '';
    }

    if ($doingWhat == 'Expense') {
        $payment_type = $pdo->prepare("SELECT payment_type FROM payment_method WHERE how_paid = :payment_type;");
        $payment_type->execute(['payment_type' => $payment_method]);
        $payment_used = $payment_type->fetchColumn();

        //For Expenses Table
        $get_category = $pdo->prepare("SELECT expense_category, expense_type FROM expense_categories WHERE expense_name = :expensed_item;");
        $get_category->execute(['expensed_item' => $payee]);

        foreach ($get_category as $row) {
            $item_category = $row['expense_category'];
            $expense_type = $row['expense_type'];
        }

        // Insert into Expenses
        $insert_expense = $pdo->prepare("INSERT INTO expenses(item_purchased, amount, category, kind, paid_by, purchase_date, note)
                                          SELECT :purchased, :amount, :category, :expense_type, :payment_method, pd::date, :note
                                          FROM generate_series(:startDate::timestamp, :endDate::timestamp, :interval_period::interval) AS pd;");
        $insert_expense->execute([
            'purchased' => $payee, 'amount' => $payment_amount, 'category' => $item_category, 'expense_type' => $expense_type,
            'payment_method' => $payment_method, 'note' => $note, 'startDate' => $start_date, 'endDate' => $end_date, 'interval_period' => $interval
        ]);

        // For budget_projection
        $payment_amount *= -1;
        $entry_type = 'payment';
    } else {
        //Insert into income table
        $insert_deposit = $pdo->prepare("INSERT INTO income (deposit_amount, depositor, deposit_date, note)
                                          SELECT :amount, :depositor, dd::date, :note
                                          FROM generate_series(:startDate::timestamp, :endDate::timestamp, :interval_period::interval) AS dd;");
        $insert_deposit->execute(['amount' => $payment_amount, 'depositor' => $payment_method, 'startDate' => $start_date, 'endDate' => $end_date, 'interval_period' => $interval, 'note' => $payee]);

        // For budget_projection
        $entry_type = 'income';
        $payment_type = 'Deposit';
    }

    // Insert into budget_projection database
    $projections = $pdo->prepare("INSERT INTO budget_projection(payee, payment_amount, due_date, transaction_type, payment_method)
                                  SELECT :payee, :payment_amount, due_date::date, :entry_type, :payment_method
                                  FROM generate_series(:startDate::timestamp, :endDate::timestamp, :interval_period::interval) AS due_date;");
    $projections->execute(['payee' => $payee, 'payment_amount' => $payment_amount, 'startDate' => $start_date, 'endDate' => $end_date, 'interval_period' => $interval, 'entry_type' => $entry_type, 'payment_method' => $payment_method]);

    unset($_GET['generating']);
    echo "<script> window.location.href='..'</script>";
}
// }
?>