<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='css/index.css' />
<title>Budget</title>

<div class='header' ;>
  <h1>Budget</br>Overview</h1>
</div>

<!-- Navigation Bar -->
<div class='navbar'>
  <div class="dropdown">
    <button style="color:yellow" class="dropbtn">Income</button>
    <div class="dropdown-content">
      <a href='transactions/deposit.php'>Deposit</a>
      <a href='finance_details/totals.php'>Recent Deposits</a>
    </div>
  </div>

  <div class="dropdown">
    <button style="color:yellow" class="dropbtn">Expenses</button>
    <div class="dropdown-content">
      <a href='transactions/expense.php?exp=Groceries'>Groceries</a>
      <a href='transactions/expense.php?exp=Housing'>Housing</a>
      <a href='transactions/expense.php?exp=Leisure'>Leisure</a>
      <a href='transactions/expense.php?exp=Loan'>Loans</a>
      <a href='transactions/expense.php?exp=Other'>Misc. Expenses</a>
      <a href='transactions/expense.php?exp=Transportation'>Transportation</a>
      <a href='transactions/expense.php?exp=Utilities'>Utilities</a>
    </div>
  </div>
  <div class="dropdown">
    <button style="color:yellow" class="dropbtn">Monthly Entries</button>
    <div class="dropdown-content">
      <a href=''>Credit Cards</a>
    </div>
  </div>
  <div class="dropdown">
    <button style="color:yellow" class="dropbtn">Add Expense Type</button>
    <div class="dropdown-content">
      <a href=''>Add Credit Card</a>
      <a href=''>Add Expense</a>
    </div>
  </div>
</div>

<?php
include 'db_connections/connection_pdo.php';
if ($dbname == 'budget_dev') {
  echo "On Dev Environment<br><br>";

  $get_avg = $pdo->prepare("SELECT item_purchased, round(avg(amount),2) AS avg_cost, category, kind
                            FROM (SELECT item_purchased, amount, category, kind
                                FROM expenses
                                -- WHERE item_purchased = :exp_name
                                ORDER BY purchase_date DESC
                                -- LIMIT 12
                                ) as monthly_cost
                            GROUP BY item_purchased, category, kind
                            ORDER BY category, kind, item_purchased;");
  $get_avg->execute();

  // $get_expense = $pdo->prepare("SELECT expense_name FROM expense_categories ORDER BY expense_type, expense_category, expense_name;");
  // $get_expense->execute();
  foreach ($get_avg as $row) {
    $exp_name = $row['item_purchased'];
    $month_avg = $row['avg_cost'];
    $kind = $row['kind'];
    // echo $exp_name . ': ' . $month_avg . ', ' . $kind . '<br><br>';
  }


  //   foreach ($get_expense as $row) {
  //     $exp_name = $row['expense_name'];
  //     $get_avg->execute(['exp_name' => $exp_name]);
  //     while (($rows = $get_avg->fetch())) {
  //       $month_avg = $rows['avg_cost'];
  //       $kind = $rows['kind'];
  //     }
  //     echo $exp_name . ': ' . $month_avg . ', ' . $kind . '<br><br>';
  //   }
}

?>