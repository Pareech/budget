<!DOCTYPE html>

<h3>
    <div class="item1">
        <table class='table'>
            <?php
            $usd_value->execute();

            $money = new NumberFormatter('en', NumberFormatter::CURRENCY);
            ?>

            <!-- Get Current USD Values -->
            <?php
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