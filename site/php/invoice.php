<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 2016-04-08
 * Time: 6:36 PM
 */
namespace dbProject;
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config.php');
$GLOBALS['p'] = 6;
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


if(isset($_POST["inum"])){
    $query = '  SELECT A.InvoiceNb, C.Name, C.Address, C.Phone, O.ID OrderID, O.OrderDate, I.Name Item, SK.CName Color, Inv.UnitPrice, OD.Quantity, OD.Price ItemTotal, A.TotalAmount, COALESCE(CEIL(A.TotalAmount / R.Installments), "Due In Full") NbPayments, COALESCE(R.Installments, "N/A") PaymentAmt, COALESCE(S.DateShipped, "Not Shipped") DateShipped
                FROM (Customer C, Item I, Inventory Inv, Orders O, InColor SK),  Account A LEFT OUTER JOIN ReceivablesInstallment R ON R.InvoiceNb = A.InvoiceNb, OrderDetail OD LEFT OUTER JOIN Shipment S ON OD.DetailNb = S.OID 
                WHERE C.Name = O.CustName AND O.ID = A.OID AND O.ID = OD.OID AND OD.LotNb = Inv.LotNb AND Inv.SKU = SK.SKU AND SK.IID = I.ID AND A.InvoiceNb = '.$_POST["inum"].';
    ';

    $result = $conn->query($query) or trigger_error($conn->error." ".$query);
    //var_dump($result);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            /* */
            echo('
                <div class="panel panel-default">
                  <div class="panel-heading">Invoice #'.$row["InvoiceNb"].'</div>
                  <div class="panel-body">
                    <table class="table">
                        <tr>
                            <th>Shipped & Billed to:</th>
                            <th>Order:</th>
                            <th>Item:</th>
                            <th>Payment:</th>
                        </tr>
                        <tr>
                            <td>
                                '.$row["Name"].'<br>
                                '.$row["Address"].'<br>
                                '.$row["Phone"].'<br>
                             </td>
                             
                            <td>
                                #'.$row["OrderID"].'<br>
                                Ordered: '.$row["OrderDate"].'<br>
                                Shipped: '.$row["DateShipped"].'<br>
                                
                            </td>
                            
                            <td>
                                '.$row["Item"].'<br>
                                '.$row["Color"].'<br>
                                '.$row["UnitPrice"].' $/unit<br>
                                '.$row["Quantity"].' units<br>
                                '.number_format($row["ItemTotal"],2).' $<br>
                            </td>
                            
                            <td>
                                '.number_format($row["TotalAmount"],2).'$<br>
                                in '.$row["NbPayments"].' payments<br>
                                '.$row["PaymentAmt"].' $/payment<br>
                            </td>
                            
                        </tr>
                    </table>
                  </div>
                </div>
            ');
            //var_dump($row); /**/
        }
    } else {
        echo '<p class="error">No results</p>';
    }

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

    <h1>Invoice</h1>

    <p>Please input the Invoice Number</p>
    <input type="text" id="InvNb" name="InvNb">
    <button type="button" id="iSubmit">Submit</button>


</section>
<?php include(P_ROOT."views/footer.html")?>
</body>
</html>

<?php ?> 
