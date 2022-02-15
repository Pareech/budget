<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/index.css' />
<title>Investments Navigation Bar</title>

<!-- Navigation Bar -->
<div class='navbar'>
  <div class="dropdown">
    <button style="color:yellow" class="dropbtn">Income
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href='../transactions/deposit.php'>Deposits</a>
    </div>
  </div>

  <div class="dropdown">
    <button style="color:yellow" class="dropbtn">Expenses
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href='../transactions/expense.php?exp=Groceries'>Groceries</a>
      <a href='../transactions/expense.php?exp=Housing'>Housing</a>
      <a href='../transactions/expense.php?exp=Leisure'>Leisure</a>
      <a href='../transactions/expense.php?exp=Loan'>Loans</a>
      <a href='../transactions/expense.php?exp=Other'>Misc. Expenses</a>
      <a href='../transactions/expense.php?exp=Transportation'>Transportation</a>
      <a href='../transactions/expense.php?exp=Utilities'>Utilities</a>
    </div>
  </div>
  <div class="dropdown">
    <button style="color:yellow" class="dropbtn">Budget Projections</button>
    <div class="dropdown-content">
      <a href='../finance_details/enter_projections.php'>Enter a Projection</a>
      <a href='../finance_details/monthly_projection.php'>Monthly Projections</a>
      <a href='../finance_details/update_monthly_projection.php'>Update Projections</a>
    </div>
  </div>
  <!-- <div class="dropdown">
    <button style="color:yellow" class="dropbtn">Monthly Entries</button>
    <div class="dropdown-content">
      <a href=''>Credit Cards</a>
    </div>
  </div> -->
  <!-- <div class="dropdown">
    <button style="color:yellow" class="dropbtn">Add Expense Type</button>
    <div class="dropdown-content">
      <a href=''>Add Credit Card</a>
      <a href=''>Add Expense</a>
    </div>
  </div> -->
  <div class="dropdown">
    <button style="color:yellow" class="dropbtn">Transactions</button>
    <div class="dropdown-content">
      <a href='../transactions/recent_transactions.php'>Last 10 Transactions</a>
      <a href='../transactions/recent_deposits.php'>Last 10 Deposits</a>
      <a href='../transactions/recent_expenses.php'>Last 10 Expenses</a>
    </div>
  </div>
  <!-- <div class="dropdown">
    <button style="color:red" class="dropbtn">Projections_test</button>
    <div class="dropdown-content">
      <a href='../_test_folder/enter_enter_projections.php?proj=credit_cards'>Credit Cards</a>
      <a href='../_test_folder/enter_enter_projections.php?proj=deposits'>Deposits</a>
      <a href='../_test_folder/enter_enter_projections.php?proj=fixed'>Fixed Expenses</a>
    </div>
  </div> -->
  <a style="color:white" ; text-decoration="none" ; href='../index.php'>Main Page</a>
</div>

<?php
include '../db_connections/connection_pdo.php';
if ($dbname == 'budget_dev') {
  echo "On Dev Environment<br><br>";
}
?>