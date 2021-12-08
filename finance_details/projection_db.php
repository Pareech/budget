<!DOCTYPE html>

<?php

// echo "Payment Used: " . $payee . "<br>";
// echo "Payment Amount: " . $payment_amount . "<br>";
// echo "Due Date: " . $due_date . "<br>";
// echo "Entry Type: " . $entry_type . "<br>";

$projections = $pdo->prepare("INSERT INTO budget_projection(payee, payment_amount, due_date, transaction_type)
                                  VALUES (:payee, :payment_amount, :due_date, :entry_type);");
$projections->execute(['payee' => $payee, 'payment_amount' => $payment_amount, 'due_date' => $due_date, 'entry_type' => $entry_type]);

// header("Location: ..");
// echo "Successfully Added <a href='..'>Click Here to Continue</a>";



?>