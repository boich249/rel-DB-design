<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 2016-04-08
 * Time: 6:36 PM
 */
namespace dbProject;
session_start();
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config.php');
$GLOBALS['p'] = 5;

/* for deployment on encs.concordia.ca */
$servername = "wmc353_4.encs.concordia.ca";
$username = "wmc353_4";
$password = "lastgrp";
$dbname = "wmc353_4";

// Create connection
$conn = new \mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
/**/
/*  for production * /
$servername = "awsdb.ctybzyc27la2.us-east-1.rds.amazonaws.com";
$username = "sebec2db";
$password = "p9nbF5btIVgb";
$dbname = "353Project";
$port = "3306";

// Create connection
$conn = new \mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
/**/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database project</title>
    <?php include(P_ROOT."views/links.html")?>
    <style type="text/css">

    </style>
</head>
<body>
<?php include(P_ROOT."views/header.html")?>
<?php include(P_ROOT . "views/navbar.php") ?>
<section>
<h1>Accounts receivable</h1>

<?php
$query = '  SELECT C.Name, C.Address, A.InvoiceNb, A.OID OrderID, OD.DetailNb, S.DateShipped, COALESCE(R.PaymentDue, A.TotalAmount) AmountOwed
            FROM (Customer C, Orders O, OrderDetail OD, Shipment S, Account A) LEFT OUTER JOIN ReceivablesInstallment R ON R.InvoiceNb = A.InvoiceNb
            WHERE C.Name = O.CustName AND O.ID = OD.OID AND OD.DetailNb = S.OID AND O.ID = A.OID AND A.Paid = 0
            ORDER BY C.Name;';

$result = $conn->query($query) or trigger_error($conn->error." ".$query);
//var_dump($result);
if ($result->num_rows > 0) {

    echo '<div class="panel panel-default">
            <div class="panel-heading">Outstanding payments for shipped items</div>
                <table class="table">
                    <tr>
                        <th>Customer Name</th>
                        <th>Customer Address</th>
                        <th>Invoice Number</th>
                        <th>Order ID</th>
                        <th>Date Shipped</th>
                        <th>Amount Owed</th>
                    </tr>';
    $current = '';
    while($row = $result->fetch_assoc()) {
        $disp = (0 != strcmp($current, $row['Name'])) ? true : false;
        $current = $row['Name'];
        echo ('
                <tr>
                    <td>'.(($disp) ? $row['Name'] : '').'</td>
                    <td>'.(($disp) ? $row['Address'] : '').' </td>
                    <td>'.$row['InvoiceNb'].'</td>
                    <td>'.$row['OrderID'].'</td>
                    <td>'.date_format(date_create($row['DateShipped']), 'l jS F Y').'</td>
                    <td>'.$row['AmountOwed'].'</td>
                </tr>'
        ); //var_dump($row);
    }
    echo '</table>
        </div>';
} else {
    echo '<p class="error">No results</p>';
}
$conn->close();
?>

</section>
<?php include(P_ROOT."views/footer.html")?>
</body>
</html>

<?php ?> 
