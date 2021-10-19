<!DOCTYPE html>



<?php
include '../db_connections/connection_pdo.php';
?>

<meta http-equiv="Content-Type" content="text/html;" />
<link rel='stylesheet' type='text/css' href='../css/deposit.css' />
<title>Salary</title>

<h1>Enter Salary Deposit</h1>

<?php
include '../misc_files/nav_bar_links.php';
?>

<form name="display" action="salary_deposit_db.php" method="POST">
    <div>
        <table>
            <tr>
                <th colspan="2">Deposit Information</th>
            </tr>
            <tr>
                <td style="background:palegreen;">Who</td>
                <td style="background:palegreen;">
                    <center><input id="textboxid" name="who" placeholder="Depositor" type="text" /></center>
                </td>
            </tr>
            <tr>
                <td style="background:palegreen;">Amount</td>
                <td style="background:palegreen;">
                    <center><input id="textboxid" name="salary" placeholder="amount" type="text" /></center>
                </td>
            </tr>
            <tr>
                <td style="background:palegreen;">Date</td>
                <td>
                    <center><input id="textboxid" name="date" placeholder="YYYY-MM-DD" type="text" /></center>
                </td>
            </tr>
        </table>

        </br></br>
        <button type="submit" id="enter_salary" name="submit_salary" class="button" value="submit">Enter Deposit</button>
    </div>
</form>