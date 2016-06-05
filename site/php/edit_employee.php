<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 2016-04-10
 * Time: 9:22 PM
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
                 <p class="hidden" id="eid"></p>
                     <tr class="spaceB">
                         <td>Name </td>
                         <td><input type="text" id="EName"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>Address </td>
                         <td><input type="text" id="EAddress"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>DateOfBirth </td>
                         <td><input type="text" id="EDoB"></td>
                     </tr>
                     <tr>
                         <td>Position </td>
                         <td><input type="text" id="EPos"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>Phone </td>
                         <td><input type="text" id="EPhone"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>SIN </td>
                         <td><input type="text" id="ESIN"></td>
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="EdASubmit">Submit</button>');
        exit;
    }

    $query = '
                SELECT * FROM Employee WHERE ID = \''.$_POST["edid"].'\'
    ';

    $result = $conn->query($query) or trigger_error($conn->error." ".$query);
    //var_dump($result);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            /* */
            echo('
                 <table class="table employee">
                 <p class="hidden" id="eid">'.$row["ID"].'</p>
                     <tr class="spaceB">
                         <td>Name </td>
                         <td><input type="text" id="EName" value="'.$row["Name"].'"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>Address </td>
                         <td><input type="text" id="EAddress" value="'.$row["Address"].'"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>DateOfBirth </td>
                         <td><input type="text" id="EDoB" value="'.$row["DateOfBirth"].'"></td>
                     </tr>
                     <tr>
                         <td>Position </td>
                         <td><input type="text" id="EPos" value="'.$row["Position"].'"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>Phone </td>
                         <td><input type="text" id="EPhone" value="'.$row["Phone"].'"></td>
                         <td>&nbsp;&nbsp;</td>
                         <td>SIN </td>
                         <td><input type="text" id="ESIN" value="'.$row["SIN"].'"></td>
                     </tr>
                 </table>
                 <br>
                 <button type="button" id="EdSubmit">Submit</button>
                 <strong>OR</strong>
                 <button type="button" id="EdDSubmit">Delete</button>
            ');
            //var_dump($row); /**/
        }
    } else {
        echo '<p class="error">No results</p>';
    }

    $query2 = 'SELECT * FROM Dependant WHERE EID = '.$_POST['edid'].';';

    echo('<table class="table employee">');
    $result2 = $conn->query($query2) or trigger_error($conn->error." ".$query2);
    if ($result2->num_rows > 0) {
        $i = 0;
        while($row = $result2->fetch_assoc()) {
            $i++;
            echo(' <!-- <table>-->
                   <tr class="spaceB" data-sin="'.$row["SIN"].'" data-i="'.$i.'">
                       <td>Name </td>
                       <td><input type="text" id="DName'.$i.'" value="'.$row["Name"].'"></td>
                       <td>DateOfBirth </td>
                       <td><input type="text" id="DDoB'.$i.'" value="'.$row["DateOfBirth"].'"></td>
                       <td>SIN </td>
                       <td><input type="text" id="DSIN'.$i.'" value="'.$row["SIN"].'"></td>
                       <td><button type="button" class="DedSubmit">Submit</button></td>
                       <td><strong>OR</strong></td>
                       <td><button type="button" class="DedDSubmit">Delete</button></td>
                   </tr>
                 <br>
            ');
            //var_dump($row); /**/
        }


    } else {
        echo '<p class="error">No results</p>';
    }

    echo('
        <tr class="spaceB">
            <td>Name </td>
            <td><input type="text" id="DName"></td>
            <td>DateOfBirth </td>
            <td><input type="text" id="DDoB"></td>
            <td>SIN </td>
            <td><input type="text" id="DSIN"></td>
            <td><button type="button" id="DedASubmit">Add</button></td>
        </tr>
    </table>');
    
    $conn->close();
    exit;
}


if(isset($_POST["action"])){

    if(0 == strcmp($_POST["action"],'add')){

        echo('Adding new employee <br>');
        $query = 'INSERT INTO Employee(Name, SIN, Position, Phone, Address, DateOfBirth) 
                VALUES (\''.$_POST['ename'].'\',\''.$_POST['esin'].'\',\''.$_POST['epos'].'\',\''.$_POST['ephone'].'\',\''.$_POST['eAddress'].'\',\''.$_POST['edob'].'\');';
        //echo('\''.$_POST['ename'].'\',\''.$_POST['esin'].'\',\''.$_POST['epos'].'\',\''.$_POST['ephone'].'\',\''.$_POST['eAddress'].'\',\''.$_POST['edob'].'\'');

    } elseif (0 == strcmp($_POST["action"],'edit')){

        echo('Updating employee<br>');
        $query = 'UPDATE Employee
            SET Name = \''.$_POST['ename'].'\', SIN = \''.$_POST['esin'].'\', Position = \''.$_POST['epos'].'\', Phone = \''.$_POST['ephone'].'\', Address = \''.$_POST['eAddress'].'\', DateOfBirth = \''.$_POST['edob'].'\'
            WHERE ID = \''.$_POST['eid'].'\';';

    } elseif (0 == strcmp($_POST["action"],'del')){

        echo('Deleting employee<br>');
        $query = 'DELETE FROM Employee WHERE ID = \''.$_POST['eid'].'\';';
        
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

if(isset($_POST["daction"])){

    if(0 == strcmp($_POST["daction"],'add')){

        echo('Adding new dependant <br>');
        $query = 'INSERT INTO Dependant(EID, Name, SIN, DateOfBirth) 
                VALUES (\''.$_POST['eid'].'\',\''.$_POST['dname'].'\',\''.$_POST['dsin'].'\',\''.$_POST['ddob'].'\');';

    } elseif (0 == strcmp($_POST["daction"],'edit')){

        echo('Updating dependant<br>');
        $query = 'UPDATE Dependant
            SET Name = \''.$_POST['dname'].'\', SIN = \''.$_POST['dsin'].'\', DateOfBirth = \''.$_POST['ddob'].'\'
            WHERE EID = \''.$_POST['eid'].'\' AND SIN = \''.$_POST['sin'].'\';';

    } elseif (0 == strcmp($_POST["daction"],'del')){

        echo('Deleting employee<br>');
        $query = 'DELETE FROM Dependant WHERE EID = \''.$_POST['eid'].'\' AND SIN = \''.$_POST['sin'].'\';';

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
    <h1>Edit Employees & Dependants</h1>
    
    <p>Please input the employee's ID:</p>
    <input type="text" id="EID" name="EID">
    <button type="button" id="EESubmit">Submit</button>
    <strong>OR</strong>
    <button type="button" id="EEASubmit">Add</button>

</section>
<?php include(P_ROOT."views/footer.html")?>
</body>
</html>
