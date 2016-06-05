<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 2016-04-11
 * Time: 12:28 AM
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
                         <td><input type="text" id="DName" ></td>
                         
                         <td>Room </td>
                         <td><input type="text" id="DRoom" ></td>
                         
                     </tr>
                     <tr>
                         <td>Phone1 </td>
                         <td><input type="text" id="DPhone1" ></td>
                         
                         <td>Phone2 </td>
                         <td><input type="text" id="DPhone2" ></td>
                         
                         <td>Fax </td>
                         <td><input type="text" id="DFax" ></td>
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="DdASubmit">Submit</button>');
        exit;
    }

    $query = '
                SELECT * FROM Department WHERE ID = \''.$_POST["edid"].'\'
    ';

    $employee = '';
    $result = $conn->query($query) or trigger_error($conn->error." ".$query);
    //var_dump($result);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            /* */
            echo('
                 <table class="table employee">
                 <p class="hidden" id="DID">'.$row["ID"].'</p>
                     <tr class="spaceB">
                         <td>Name </td>
                         <td><input type="text" id="DName" value="'.$row["Name"].'"></td>
                         
                         <td>Room </td>
                         <td><input type="text" id="DRoom" value="'.$row["Room"].'"></td>
                         
                     </tr>
                     <tr>
                        <td>Phone1 </td>
                         <td><input type="text" id="DPhone1" value="'.$row["Phone2"].'"></td>
                         
                         <td>Phone2 </td>
                         <td><input type="text" id="DPhone2" value="'.$row["Phone2"].'"></td>
                         
                         <td>Fax </td>
                         <td><input type="text" id="DFax" value="'.$row["Fax"].'"></td>
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="DdSubmit">Submit</button>
                 <strong>OR</strong>
                 <button type="button" id="DdDSubmit">Delete</button>
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
        $query = 'INSERT INTO Department (Name, Room, Phone1, Phone2, Fax) 
                  VALUES (\''.$_POST['dname'].'\',\''.$_POST['droom'].'\',\''.$_POST['dphone1'].'\',\''.$_POST['dphone2'].'\',\''.$_POST['dfax'].'\');';
        echo('\''.$_POST['dname'].'\',\''.$_POST['droom'].'\',\''.$_POST['dphone1'].'\',\''.$_POST['dphone2'].'\',\''.$_POST['dfax'].'\'');

    } elseif (0 == strcmp($_POST["action"],'edit')){

        echo('Updating customer<br>');
        $query = 'UPDATE Department
            SET Name = \''.$_POST['dname'].'\', Room = \''.$_POST['droom'].'\', Phone1 = \''.$_POST['dphone1'].'\', Phone2 = \''.$_POST['dphone2'].'\', Fax = \''.$_POST['dfax'].'\' 
            WHERE ID = \''.$_POST['did'].'\';';
        echo('\''.$_POST['dname'].'\',\''.$_POST['droom'].'\',\''.$_POST['dphone1'].'\',\''.$_POST['dphone2'].'\',\''.$_POST['dfax'].'\'');

    } elseif (0 == strcmp($_POST["action"],'del')){

        echo('Deleting customer<br>');
        $query = 'DELETE FROM Department WHERE ID = \''.$_POST['did'].'\';';

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
    <h1>Edit Departments</h1>

    <p>Please input the Department's ID:</p>
    <input type="text" id="DID" name="DID">
    <button type="button" id="DESubmit">Submit</button>
    <strong>OR</strong>
    <button type="button" id="DEASubmit">Add</button>

</section>
<?php include(P_ROOT."views/footer.html")?>
</body>
</html>