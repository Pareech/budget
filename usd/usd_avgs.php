<!DOCTYPE html>

<h3>
    <div class="item1">
        <table class='table'>
            <?php
            // Value of USD Account and Canadian dollars exchanged
            $usd_value = $pdo->prepare("SELECT SUM(COALESCE(buy_amt,0)) AS buy_amt, 
                                               SUM(COALESCE(withdrawls,0) + COALESCE(usd_value,0) + COALESCE(interest,0)) AS net_usd, 
                                               ROUND(SUM(buy_amt) / (SUM(usd_value) + SUM(COALESCE(interest,0))),4) AS avg_exch,
                                               SUM(COALESCE(interest,0)) AS interest
                                        FROM usd_acct;");

            // Average exchange rate from Last 10 Buy
            $latest_avg = $pdo->query("SELECT ROUND(AVG(exch_rate), 4) AS average_rate 
                                        FROM (SELECT buy_date, exch_rate, usd_value
                                            FROM usd_acct
                                            WHERE withdrawls IS NULL AND interest IS NULL
                                            ORDER BY buy_date DESC
                                            LIMIT 10
                                            ) AS rates;
                                        ")->fetchColumn();

            // last 10 purchases
            $last_ten = $pdo->prepare("SELECT buy_date, exch_rate, usd_value
                                        FROM usd_acct
                                        WHERE withdrawls IS NULL AND interest IS NULL
                                        ORDER BY buy_date DESC
                                        LIMIT 10;");
            $last_ten->execute();


            $money = new NumberFormatter('en', NumberFormatter::CURRENCY);

            // Get Current USD Values
            $usd_value->execute();
            foreach ($usd_value as $row) {
                $buy_amount = $row['buy_amt'];
                $net_usd = $row['net_usd'];
                $avg_exch = $row['avg_exch'];
                $interest = $row['interest'];
            ?>
                <tr>
                    <td id=alnright>CDN Spent</td>
                    <td id=alnleft><?php echo $money->formatCurrency($buy_amount, 'USD'); ?></td>
                </tr>

                <tr>
                    <td id=alnright>Avg Exch. Rate</td>
                    <td id=alnleft><?php echo "$" . $avg_exch; ?></td>
                </tr>

                <tr>
                    <td id=alnright>Available USD</td>
                    <td id=alnleft><?php echo $money->formatCurrency($net_usd, 'USD'); ?></td>
                </tr>

                <tr>
                    <td id=alnright>Interest Earned</td>
                    <td id=alnleft><?php echo $money->formatCurrency($interest, 'USD'); ?></td>
                </tr>
            <?php }
            ?>
        </table>
    </div>
</h3>