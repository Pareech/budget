<!DOCTYPE html>

<?php

$payee = $_POST['payment_used'];
$payment_amount = $_POST['amount'];
$due_date = $_POST['date'];

//Is it a projected income or expense
$entry_type = $_POST['entry_type'];

if ($entry_type <> 'payment') {
    $entry_type = 'income';
}


echo "Payment Used: " . $payee . "<br>";
echo "Payment Amount: " . $payment_amount . "<br>";
echo "Due Date: " . $due_date . "<br>";
echo "Entry Type: " . $entry_type . "<br>";

// $projections = $pdo->prepare("INSERT INTO budget_projection(payee, payment_amount, due_date, transaction_type)
//                                   VALUES (::payee, :payment_amount, :due_date, :transaction_type);");
// $proejctions->execute(['payee' => $payee, 'payment_amount' => $payment_amount, 'due_date' => $due_date, 'entry_type' => $entry_type]);
?>