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
                 <p class="hidden" id="LotNb"></p>
                     <tr class="spaceB">
                         <td>SKU </td>
                         <td><input type="text" id="SKU"></td>
                         
                         <td>Date Of Manufacture </td>
                         <td><input type="text" id="DOM"></td>
                         
                     </tr>
                     <tr>
                         <td>Number of Items </td>
                         <td><input type="text" id="NBI"></td>
                         
                         <td>Unit Price </td>
                         <td><input type="text" id="UP"></td>
                         
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="IdASubmit">Submit</button>');
        exit;
    }

    $query = '
                SELECT * FROM Inventory WHERE LotNb = \''.$_POST["edid"].'\'
    ';

    $employee = '';
    $result = $conn->query($query) or trigger_error($conn->error." ".$query);
    //var_dump($result);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            /* */
            echo('
                 <table class="table employee">
                 <p class="hidden" id="LotNb">'.$row["LotNb"].'</p>
                     <tr class="spaceB">
                         <td>SKU </td>
                         <td><input type="text" id="SKU" value="'.$row["SKU"].'"></td>
                         
                         <td>Date Of Manufacture </td>
                         <td><input type="text" id="DOM" value="'.$row["DateOfManufacture"].'"></td>
                         
                     </tr>
                     <tr>
                         <td>Number of Items </td>
                         <td><input type="text" id="NBI" value="'.$row["NbItems"].'"></td>
                         
                         <td>Unit Price </td>
                         <td><input type="text" id="UP" value="'.$row["UnitPrice"].'"></td>
                         
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="IdSubmit">Submit</button>
                 <strong>OR</strong>
                 <button type="button" id="IdDSubmit">Delete</button>
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

        echo('Adding new inventory <br>');
        $query = 'INSERT INTO Inventory (SKU, DateOfManufacture, NbItems, UnitPrice) 
                  VALUES (\''.$_POST['sku'].'\',\''.$_POST['dom'].'\',\''.$_POST['nbi'].'\',\''.$_POST['up'].'\');';
        echo('\''.$_POST['sku'].'\',\''.$_POST['dom'].'\',\''.$_POST['nbi'].'\',\''.$_POST['up'].'\'');

    } elseif (0 == strcmp($_POST["action"],'edit')){

        echo('Updating inventory<br>');
        $query = 'UPDATE Inventory
            SET SKU = \''.$_POST['sku'].'\', DateOfManufacture = \''.$_POST['dom'].'\', NbItems = \''.$_POST['nbi'].'\', UnitPrice = \''.$_POST['up'].'\' 
            WHERE LotNb = \''.$_POST['lotnb'].'\';';
        echo('\''.$_POST['sku'].'\',\''.$_POST['dom'].'\',\''.$_POST['nbi'].'\',\''.$_POST['up'].'\'');

    } elseif (0 == strcmp($_POST["action"],'del')){

        echo('Deleting inventory<br>');
        $query = 'DELETE FROM Inventory WHERE LotNb = \''.$_POST['lotnb'].'\';';

    } else {
        echo '<p class="error">A problem occured</p>';
    }

    $result = $conn->query($query) or trigger_error($conn->error." ".$query);
    if($result == 1){
        echo('Success!');
    } else {
        echo('Fail '.$result);
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
    <h1>Edit Inventory</h1>

    <p>Please input the Inventory's lot number:</p>
    <input type="text" id="LotNb" name="LotNb">
    <button type="button" id="IESubmit">Submit</button>
    <strong>OR</strong>
    <button type="button" id="IEASubmit">Add</button>

</section>
<?php include(P_ROOT."views/footer.html")?>
</body>
</html>