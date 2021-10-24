<!DOCTYPE html>

<?php
include '../db_connections/connection_pdo.php';
?>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/transaction.css' />
<title>Enter Expenses</title>

<?php
$category = $_GET['exp'];

echo "<div class='header'>
    <h1>Enter $category<br>Expenses</h1>
</div>";

include '../misc_files/nav_bar_links.php';

$choose_expense = $pdo->prepare("SELECT expense_name FROM expense_listing WHERE expense_category = :exp_cat ORDER BY expense_name;");
$choose_expense->execute(['exp_cat' => $category]);

?>

<form name="display" action="" method="POST">
    <div class="grid-container">
        <div class="item1a">
            <h2>Select Expense</h2>
        </div>
        <div class="item2a">
            <h2>Amount</h2>
        </div>
        <div class="item3a">
            <h2>Date</h2>
        </div>
        <div class="item4a">
            <h2>Note</h2>
        </div>
        <div class="item1b">
            <select name="expense" value='' class=dropmenus></option>
                <option value=""></option>
                <?php
                foreach ($choose_expense as $row) {
                    echo "<option value='$row[expense_name]'>$row[expense_name]</option>";
                }
                ?>
            </select>
        </div>

        <div class="item2b"><input id="textboxid" name="cost" placeholder="Expense Amount" type="text" /></div>
        <div class="item3b"><input type="date" id="textboxid" name="date" /></div>
        <div class="item4b"><input type="text" id="textboxid" name="note" /></div>
        <div class="item5"><button type="submit" id="transaction_button" name="submit_expense" class="button" value="submit">Submit<br>Expense</button></div>
    </div>
</form>

<!-- Enter expense into the databse -->
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
            alert('All fields are required, except for Notes.');
        </script>";
    } else {
        $expense = $_POST['expense'];
        $cost = $_POST['cost'];
        $date = $_POST['date'];
        $note = $_POST['note'];

        $get_details = $pdo->prepare("SELECT expense_name, expense_type FROM expense_listing WHERE expense_name = :expense_name;");
        $get_details->execute(['expense_name' => $expense]);
        foreach ($get_details as $row) {
            $type = $row['expense_type'];
        }

        $spend = $pdo->prepare("INSERT INTO expenses (item_purchased, amount, category, kind, purchase_date, note)
                                VALUES (:item_purchased, :cost, :category, :kind, :expense_date, :note);");
        $spend->execute(['item_purchased' => $expense, 'cost' => $cost, 'category' => $category, 'kind' => $type, 'expense_date' => $date, 'note' => $note]);

        header("Location: ..");
    }
}
?>