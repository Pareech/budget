<!DOCTYPE html>

<meta name="viewport" http-equiv="Content-Type" content="text/html, width=device-width, initial-scale=1;" />
<link rel='stylesheet' type='text/css' href='../css/usd_values.css' />
<title>Buy USD</title>

<?php include '../db_connections/connection_pdo.php'; ?>

<div class="grid-container">

    <?php include 'usd_avgs.php'; ?>

    <div class="item2" id="header">
        <h1>Buy US<br>Dollars</h1>
    </div>
</div>

<?php
include '../misc_files/nav_bar_links.php';
?>
<div class="grid-container_bottom">

    <div class="hist_buy">
        <table class='table_exch'>
            <tr>
                <th colspan="4" ; id='th_bottom'>
                    <?php echo "Average of Last<br>10 Rates: $" . $latest_avg; ?>
                </th>
            </tr>
            <tr>
                <th class="row">Date</th>
                <th class="row">Exch. Rate</th>
                <th class="row">Bought</th>
            </tr>

            <?php
            foreach ($last_ten as $row) {
                echo "<tr>";
                    echo "<td id='td_bottom'>" . $row['buy_date'] . "</td>";
                    echo "<td id='td_bottom'>" . $row['exch_rate'] . "</td>";
                    echo "<td id='td_bottom'>" . $row['usd_value'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>