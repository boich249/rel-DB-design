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
$GLOBALS['p'] = 4;

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
<h1>Best Customers</h1>

<?php
$query = '  SELECT C.Name, C.Address, C.Phone, I.Name Item, OD.Price OrderPrice, OD.Quantity OrderQuantity
            FROM Customer C, Item I, Orders O, OrderDetail OD, Inventory Inv, InColor SK
            WHERE C.Name = O.CustName AND Inv.LotNb = OD.LotNb AND OD.OID = O.ID AND I.ID = SK.IID AND SK.SKU = Inv.SKU AND
                C.Name IN (SELECT Name 
                            FROM (SELECT C.Name Name, SUM(OD.Price) TotalSales
                                FROM Customer C,Item I, Orders O, OrderDetail OD, Inventory Inv, InColor SK
                                WHERE I.ID = SK.IID AND SK.SKU = Inv.SKU AND Inv.LotNb = OD.LotNb AND OD.OID = O.ID AND C.Name = O.CustName AND
                                    O.OrderDate <= CURDATE() AND O.Orderdate >= DATE_ADD(CURDATE(), INTERVAL - 12 MONTH)
                                GROUP BY Name
                                ORDER BY TotalSales DESC
                                LIMIT 3
                                ) Best
                            )
            ORDER BY C.Name DESC;';

$result = $conn->query($query) or trigger_error($conn->error." ".$query);
//var_dump($result);
if ($result->num_rows > 0) {

    echo '<div class="panel panel-default">
            <div class="panel-heading">Best Customers</div>
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Item</th>
                        <th>Order Price</th>
                        <th>Order Quantity</th>
                    </tr>';
        $current = '';
    while($row = $result->fetch_assoc()) {

        $disp = (0 != strcmp($current, $row['Name'])) ? true : false;
        $current = $row['Name'];
        echo ('
                <tr>
                    <td>'.(($disp) ? $row['Name'] : '').'</td>
                    <td>'.(($disp) ? $row['Address'] : '').' </td>
                    <td>'.(($disp) ? $row['Phone'] : '').'</td>
                    <td>'.$row['Item'].'</td>
                    <td>'.number_format($row['OrderPrice'],2).'</td>
                    <td>'.$row['OrderQuantity'].'</td>
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
