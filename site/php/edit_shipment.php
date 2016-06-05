<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 2016-04-11
 * Time: 3:24 AM
 */
namespace dbProject;
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config.php');
$GLOBALS['p'] = 7;

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
#######################################################################################################################

if(isset($_POST["edid"])){

    if(0 == strcmp($_POST['edid'],'add')){
        echo('   <table class="table employee">
                 <p class="hidden" id="OID"></p>
                     <tr class="spaceB">
                         <td>Order ID </td>
                         <td><input type="text" id="SOID"></td>
                         <td>Date Shipped </td>
                         <td><input type="text" id="SDate"></td>
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="SdASubmit">Submit</button>');
        exit;
    }

    $query = '
                SELECT * FROM Shipment WHERE OID = \''.$_POST["edid"].'\'
    ';

    $employee = '';
    $result = $conn->query($query) or trigger_error($conn->error." ".$query);
    //var_dump($result);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            /* */
            echo('
                 <table class="table employee">
                 <p class="hidden" id="OID">'.$row["OID"].'</p>
                     <tr class="spaceB">
                         <td>Customer Name </td>
                         <td><input type="text" id="SOID" value="'.$row["OID"].'"></td>
                         <td>Date Shipped </td>
                         <td><input type="text" id="SDate" value="'.$row["DateShipped"].'"></td>
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="SdSubmit">Submit</button>
                 <strong>OR</strong>
                 <button type="button" id="SdDSubmit">Delete</button>
            ');
            //var_dump($row); /**/
        }
    } else {
        echo '<p class="error">No results</p>';
    }

    //echo($employee);

    $conn->close();
    exit;
}


if(isset($_POST["action"])){

    if(0 == strcmp($_POST["action"],'add')){

        echo('Adding new shipment <br>');
        $query = 'INSERT INTO Shipment (OID , DateShipped) 
                  VALUES (\''.$_POST['soid'].'\',\''.$_POST['sdate'].'\');';

    } elseif (0 == strcmp($_POST["action"],'edit')){

        echo('Updating shipment<br>');
        $query = 'UPDATE Shipment
            SET OID = \''.$_POST['soid'].'\', DateShipped = \''.$_POST['sdate'].'\' 
            WHERE OID = \''.$_POST['oid'].'\';';

    } elseif (0 == strcmp($_POST["action"],'del')){

        echo('Deleting shipment<br>');
        $query = 'DELETE FROM Shipment WHERE OID = \''.$_POST['oid'].'\';';

    } else {
        echo '<p class="error">A problem occured</p>';
    }

    $result = $conn->query($query) ;//or trigger_error($conn->error." ".$query);
    if($result == 1){
        echo('<br>Success!');
    } else {
        echo('<br><strong>Fail, please make sure the OID exists and the date is in the right format: yyyy-mm-dd</strong>'.$result);
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
</head>
<body>
<?php include(P_ROOT."views/header.html")?>
<?php include(P_ROOT . "views/navbar.php") ?>
<section class="sec">
    <h1>Edit Shipments</h1>

    <p>Please input the shipment's ID:</p>
    <input type="text" id="OID" name="OID">
    <button type="button" id="SESubmit">Submit</button>
    <strong>OR</strong>
    <button type="button" id="SEASubmit">Add</button>

</section>
<?php include(P_ROOT."views/footer.html")?>
</body>
</html>