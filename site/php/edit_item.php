<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 2016-04-11
 * Time: 3:23 AM
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
                 <p class="hidden" id="IID"></p>
                     <tr class="spaceB">
                         <td>Item Name </td>
                         <td><input type="text" id="ItName"></td>
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="ItdASubmit">Submit</button>');
        exit;
    }

    $query = '
                SELECT * FROM Item WHERE ID = \''.$_POST["edid"].'\'
    ';

    $employee = '';
    $result = $conn->query($query) or trigger_error($conn->error." ".$query);
    //var_dump($result);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            /* */
            echo('
                 <table class="table employee">
                 <p class="hidden" id="IID">'.$row["ID"].'</p>
                     <tr class="spaceB">
                         <td>Item Name </td>
                         <td><input type="text" id="ItName" value="'.$row["Name"].'"></td>
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="ItdSubmit">Submit</button>
                 <strong>OR</strong>
                 <button type="button" id="ItdDSubmit">Delete</button>
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

        echo('Adding new order <br>');
        $query = 'INSERT INTO Item (Name) 
                  VALUES (\''.$_POST['iname'].'\');';
        //echo('\''.$_POST['iname'].'\'');

    } elseif (0 == strcmp($_POST["action"],'edit')){

        echo('Updating item<br>');
        $query = 'UPDATE Item
            SET Name = \''.$_POST['iname'].'\'
            WHERE ID = \''.$_POST['iid'].'\';';

    } elseif (0 == strcmp($_POST["action"],'del')){

        echo('Deleting item<br>');
        $query = 'DELETE FROM Item WHERE ID = \''.$_POST['iid'].'\';';

    } else {
        echo '<p class="error">A problem occured</p>';
    }

    $result = $conn->query($query) or trigger_error($conn->error." ".$query);
    if($result == 1){
        echo('<br>Success!');
    } else {
        echo('<br>Fail'.$result);
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
    <h1>Edit Items</h1>

    <p>Please input the Item's ID:</p>
    <input type="text" id="IID" name="IID">
    <button type="button" id="ItESubmit">Submit</button>
    <strong>OR</strong>
    <button type="button" id="ItEASubmit">Add</button>

</section>
<?php include(P_ROOT."views/footer.html")?>
</body>
</html>