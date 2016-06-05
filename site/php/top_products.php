<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 2016-04-08
 * Time: 6:13 PM
 */
namespace dbProject;
session_start();
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config.php');

$GLOBALS['p'] = 2;

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
    <h1>Top Products</h1>
    
    <?php
        $query = '
                SELECT I.ID, I.Name, SUM(OD.Price) TotalSales, COUNT(OD.Price) NbOrders
                FROM Item I, Orders O, OrderDetail OD, Inventory Inv, InColor SK
                WHERE I.ID = SK.IID AND SK.SKU = Inv.SKU AND Inv.LotNb = OD.LotNb AND OD.OID = O.ID AND
                    O.OrderDate <= CURDATE() AND O.Orderdate >= DATE_ADD(CURDATE(), INTERVAL - 12 MONTH)
                GROUP BY I.ID
                ORDER BY TotalSales DESC
                LIMIT 3;
        ';
    
    $result = $conn->query($query) or trigger_error($conn->error." ".$query);
    //var_dump($result);
    if ($result->num_rows > 0) {
        
    echo '<div class="panel panel-default">
            <div class="panel-heading">Top Products</div>
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Total Sales</th>
                        <th>Number Of Orders</th>
                    </tr>';
        
        while($row = $result->fetch_assoc()) {
            echo ('
                <tr>
                    <td>'.$row["ID"].'</td>
                    <td>'.$row["Name"].' </td>
                    <td>'.number_format($row['TotalSales'],2).'</td>
                    <td>'.$row['NbOrders'].'</td>
                </tr>'
            );//var_dump($row);
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