<!DOCTYPE html>

<?php
$get_avg = $pdo->prepare("SELECT item_purchased
FROM (SELECT item_purchased, DATE_TRUNC('month',purchase_date) AS buy_month, sum(amount) AS month_spend
        FROM expenses
        WHERE purchase_date >= CURRENT_DATE - INTERVAL '1 year' AND purchase_date <= CURRENT_DATE AND kind = :expkind
        GROUP BY item_purchased, kind, purchase_date
        ORDER BY buy_month) AS monthly_average
GROUP BY item_purchased
ORDER BY item_purchased;");

$get_avg->execute(['expkind' => $type]);

?>