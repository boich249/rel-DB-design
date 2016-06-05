<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 2016-04-11
 * Time: 12:27 AM
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
                 <p class="hidden" id="cNameKey"></p>
                     <tr class="spaceB">
                         <td>Name </td>
                         <td><input type="text" id="CName"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>Address </td>
                         <td><input type="text" id="CAddress"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>Phone </td>
                         <td><input type="text" id="CPhone"></td>
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="CdASubmit">Submit</button>');
        exit;
    }

    $query = '
                SELECT * FROM Customer WHERE Name = \''.$_POST["edid"].'\'
    ';

    $employee = '';
    $result = $conn->query($query) or trigger_error($conn->error." ".$query);
    //var_dump($result);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            /* */
            echo('
                 <table class="table employee">
                 <p class="hidden" id="cNameKey">'.$row["Name"].'</p>
                     <tr class="spaceB">
                         <td>Name </td>
                         <td><input type="text" id="CName" value="'.$row["Name"].'"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>Address </td>
                         <td><input type="text" id="CAddress" value="'.$row["Address"].'"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>Phone </td>
                         <td><input type="text" id="CPhone" value="'.$row["Phone"].'"></td>
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="CdSubmit">Submit</button>
                 <strong>OR</strong>
                 <button type="button" id="CdDSubmit">Delete</button>
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

        echo('Adding new customer <br>');
        $query = 'INSERT INTO Customer (Name, Address, Phone) 
                  VALUES (\''.$_POST['cname'].'\',\''.$_POST['cAddress'].'\',\''.$_POST['cphone'].'\');';
        echo('\''.$_POST['cname'].'\',\''.$_POST['cphone'].'\',\''.$_POST['cAddress'].'\'');

    } elseif (0 == strcmp($_POST["action"],'edit')){

        echo('Updating customer<br>');
        $query = 'UPDATE Customer
            SET Name = \''.$_POST['cname'].'\', Address = \''.$_POST['cAddress'].'\', Phone = \''.$_POST['cphone'].'\' 
            WHERE Name = \''.$_POST['cnamekey'].'\';';
        //echo('\''.$_POST['cname'].'\',\''.$_POST['cphone'].'\',\''.$_POST['cAddress'].'\',##{\''.$_POST['cnamekey'].'\'}##');

    } elseif (0 == strcmp($_POST["action"],'del')){

        echo('Deleting customer<br>');
        $query = 'DELETE FROM Customer WHERE Name = \''.$_POST['cnamekey'].'\';';

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
    <h1>Edit Customers</h1>

    <p>Please input the Customer's name:</p>
    <input type="text" id="CName" name="CName">
    <button type="button" id="CESubmit">Submit</button>
    <strong>OR</strong>
    <button type="button" id="CEASubmit">Add</button>

</section>
<?php include(P_ROOT."views/footer.html")?>
</body>
</html>
