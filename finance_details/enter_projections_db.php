<!DOCTYPE html>

<?php

// echo "Payment Used: " . $payee . "<br>";
// echo "Payment Amount: " . $payment_amount . "<br>";
// echo "Due Date: " . $due_date . "<br>";
// echo "Entry Type: " . $entry_type . "<br>";

$projections = $pdo->prepare("INSERT INTO budget_projection(payee, payment_amount, due_date, transaction_type, payment_method)
                                  VALUES (:payee, :payment_amount, :due_date, :entry_type, :payment_used);");
$projections->execute(['payee' => $payee, 'payment_amount' => $payment_amount, 'due_date' => $due_date, 'entry_type' => $entry_type, 'payment_used' => $payment_used]);

echo "<script> window.location.href='../index.php'</script>";

?>