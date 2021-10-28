<!DOCTYPE html>

<?php
include '../db_connections/connection_pdo.php';
?>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/transaction.css' />
<title>Credit Card Monthly Charges</title>


<div class='header'>
    <h1>Enter Credit Card<br>Monthly Charges</h1>
</div>

<?php
include '../misc_files/nav_bar_links.php';

$cc_charges = $pdo->prepare("SELECT cc_name FROM credit_cards_owned;");
$cc_charges->execute();

?>

<form name="display" action="" method="POST">
    <div class="grid-container">
        <div class="item1">
            <h2>Credit Card</h2>
            <select id="textboxid" name="card" value='' class=dropmenus></option>
                <option value=""></option>
                <?php
                foreach ($cc_charges as $row) {
                    echo "<option value='$row[cc_name]'>$row[cc_name]</option>";
                }
                ?>
            </select>
        </div>
        <div class="item2">
            <h2>Amount</h2>
            <input id="textboxid" name="cost" placeholder="Monthly Charge" type="text" />
        </div>
        <div class="item3">
            <h2>Date</h2>
            <input type="date" id="textboxid" name="date" />
        </div>
        <div class="item4">
            <h2>Note</h2>
            <input type="text" id="textboxid" name="note" />
        </div>
        <div class="item5">
            <button type="submit" id="transaction_button" name="submit_charge" class="button" value="submit">Submit<br>Charge</button>
        </div>
    </div>
</form>

<!-- Enter Monthly CC Charge into the Database -->
<?php
if (isset($_POST['submit_charge'])) {

    $required = array('card', 'cost', 'date');

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
        $expense = $_POST['card'];
        $cost = $_POST['cost'];
        $date = $_POST['date'];
        $note = $_POST['note'];

        $spend = $pdo->prepare("INSERT INTO credit_card_monthlies (credit_card, amount, charge_month, note)
                                VALUES (:cc, :charge, :cc_month, :note);");
        $spend->execute(['cc' => $expense, 'charge' => $cost, 'cc_month' => $date, 'note' => $note]);

        header("Location: ..");
    }
}
?>