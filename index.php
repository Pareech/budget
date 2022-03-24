<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='css/index.css' />
<title>Budget</title>

<?php
include 'db_connections/connection_pdo.php';
$month_year = date('F Y', strtotime('now'));
?>

<div class="grid-container">
  <div class='header' ;>
    <div class="item1">
      <?php include 'finance_details/year_averages.php'; ?>
    </div>
  </div>

  <div class='header' ;>
    <div class="item2">
      <h1>Budget<br>Overview</h1>
    </div>
  </div>
</div>

<!-- Navigation Bar -->
<div class='navbar'>
  <div class="dropdown">
    <button style="color:#FFFF00" class="dropbtn">Budget Projections</button>
    <div class="dropdown-content">
      <a href='projections/monthly_projection.php'><?php echo $month_year; ?> Projections</a>
      <a> --------- </a>
      <a href='projections/create_projections.php?generating=Expense'>Generate Expense Projection Series</a>
      <a href='projections/create_projections.php?generating=Income'>Generate Income Projection Series</a>
    </div>
  </div>
  <div class="dropdown">
    <button style="color:#FFFF00" class="dropbtn">Credit Card Actions</button>
    <div class="dropdown-content">
      <a href='projections/enter_cc_payments.php'>Enter Credit Card Payment</a>
      <a href='finance_details/validate_cc.php'>Validate Monthly Charges</a>
    </div>
  </div>
  <div class="dropdown">
    <button style="color:#FFFF00" class="dropbtn">Expenses</button>
    <div class="dropdown-content">
      <a href='transactions/expense.php?exp=Groceries'>Groceries</a>
      <a href='transactions/expense.php?exp=Housing'>Housing</a>
      <a href='transactions/expense.php?exp=Leisure'>Leisure</a>
      <a href='transactions/expense.php?exp=Loan'>Loans</a>
      <a href='transactions/expense.php?exp=Other'>Misc. Expenses</a>
      <a href='transactions/expense.php?exp=Transportation'>Transportation</a>
      <a href='transactions/expense.php?exp=Utilities'>Utilities</a>
      <a href='transactions/expense.php?exp=Yearly'>Yearly Charges</a>
    </div>
  </div>
  <div class="dropdown">
    <button style="color:#FFFF00" class="dropbtn">Income</button>
    <div class="dropdown-content">
      <a href='transactions/deposit.php'>Deposit</a>
    </div>
  </div>
  <div class="dropdown">
    <button style="color:#FFFF00" class="dropbtn">Transfers</button>
    <div class="dropdown-content">
      <a href='transactions/money_trsf.php?who=Ian'>Ian</a>
      <a href='transactions/money_trsf.php?who=Isabelle'>Isabelle</a>
    </div>
  </div>
  <div class="dropdown">
    <button style="color:#FFFF00" class="dropbtn">Updates</button>
    <div class="dropdown-content">
      <a href='finance_details/payment_updates.php'>Update Payment Entry</a>
      <a href='projections/update_monthly_projection.php'>Update Monthly Projections</a>
    </div>
  </div>
  <div class="dropdown">
    <button style="color:#FFFF00" class="dropbtn">USD Account</button>
    <div class="dropdown-content">
      <a href='usd/buy_usd.php'>Buy USD</a>
      <a href='usd/sell_usd.php'>Sell USD</a>
      <a href='usd/interest_usd.php'>Interest Earned</a>
    </div>
  </div>
</div>

<?php
if ($dbname == 'budget_dev') {
  echo "On Dev Environment";
}

include 'finance_details/monthly_totals.php';
include 'finance_details/averages.php';
?>