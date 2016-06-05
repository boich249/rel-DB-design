<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 2016-04-08
 * Time: 6:35 PM
 */
namespace dbProject;
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config.php');
$GLOBALS['p'] = 3;
session_start();

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

#######################################################################################################################

if(isset($_POST["month"]) && isset($_POST["year"])){
    $date = $_POST["year"].'-'.$_POST["month"].'-01';
    $query = '
                SELECT I.ID, I.Name, TRUNCATE( SUM( CurCount.Ct * Inv.UnitPrice ) / SUM( CurCount.Ct) , 2 ) AvgPrice
                FROM Item I, Inventory Inv, InColor SK, Orders O, OrderDetail OD, 
                    (SELECT IQ.LotNb LotNb, (SumI - COALESCE(SumO, 0)) Ct
                    FROM(SELECT Inv.LotNb, SUM(Inv.NbItems) SumI
                        FROM Inventory Inv
                        WHERE MONTH(\''.$date.'\') <= MONTH(Inv.DateOfManufacture) AND YEAR(\''.$date.'\') <= YEAR(Inv.DateOfManufacture)
                        GROUP BY Inv.LotNb) IQ LEFT OUTER JOIN 
                        (SELECT OD.LotNb, SUM(OD.Quantity) SumO
                        FROM OrderDetail OD, Orders O
                        WHERE O.ID = OD.OID AND
                            MONTH(\''.$date.'\') < MONTH(O.OrderDate) AND YEAR(\''.$date.'\') <= YEAR(O.OrderDate)
                        GROUP BY OD.LotNb) OQ  ON IQ.LotNb = OQ.LotNb) CurCount
                WHERE I.ID = SK.IID AND SK.SKU = Inv.SKU AND Inv.LotNb = OD.LotNb AND Inv.LotNb = CurCount.LotNb AND CurCount.Ct > 0
                GROUP BY I.ID
                ORDER BY AvgPrice DESC;
    ';


    //$return =
    echo('
        <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Department Name</div>
        <!-- Table -->
        <table class="table">
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Average Price</th>
            </tr>
    ');

    $result = $conn->query($query) or trigger_error($conn->error." ".$query);
    //var_dump($result);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            /* */
            echo('
                <tr>
                    <td>'.$row["ID"].'</td>
                    <td>'.$row["Name"].'</td>
                    <td>'.$row["AvgPrice"].'</td>
                </tr>
            ');
            //var_dump($row); /**/
        }
    } else {
        echo '<p class="error">No results</p>';
    }

    echo('  </table>
        </div>');

    $conn->close();
    exit;
}
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
<section class="sec">
<h1>Average Price</h1>

<nav class="navbar navbar-default avg-price">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">2016</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav" id="2016">
                <li><a href="#" id="01" class="month">January</a></li>
                <li><a href="#" id="02" class="month">February</a></li>
                <li><a href="#" id="03" class="month">March</a></li>
                <li><a href="#" id="04" class="month">April</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<nav class="navbar navbar-default avg-price">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">2017</a>
        </div>
    </div><!-- /.container-fluid -->
</nav>

</section>
<?php include(P_ROOT."views/footer.html")?>
</body>
</html>

<?php ?>
