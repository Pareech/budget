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
      <a href=''>Other</a>
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
      <a href='transactions/cc_monthlies.php'>Credit Cards</a>
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
    echo "On Dev Environment";
  }
?>