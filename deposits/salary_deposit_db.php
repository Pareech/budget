<!DOCTYPE html>



<?php
include '../db_connections/connection_pdo.php';
?>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/deposit.css' />
<title>Salary Deposit DB</title>



<?php
  session_start();
    $depositor = $_POST['who'];
    $salary = $_POST['salary'];
    $date = $_POST['date'];


echo $depositor."<br>";
echo $salary."<br>";
echo $date."<br>";

exit();

?>
