<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 2016-04-08
 * Time: 5:16 PM
 */
namespace dbProject;
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config.php');
$GLOBALS['p'] = 1;

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

if(isset($_POST["DID"]) || isset($_POST["DName"])){
    $query = '  SELECT  E.ID, E.Name, E.SIN, E.Position, E.Address, E.Phone, E.DateOfBirth, Count(D.SIN) NumberOfDependants, 
                    COALESCE((P.HourlyRate * P.HoursWorked * 4), (F.Salary / 12)) MonthlyWage, CASE WHEN P.HourlyRate IS NOT NULL THEN \'Part Time\' ELSE \'Full Time\' END AS Status
                FROM ((WorksFor W, Department Dep, (Employee E LEFT OUTER JOIN Dependant D ON (E.ID = D.EID))) 
                    LEFT OUTER JOIN PartTime P ON E.ID = P.ID) LEFT OUTER JOIN FullTime F ON E.ID = F.ID
                WHERE (Dep.ID = \''.$_POST["DID"].'\' OR Dep.Name = \''.$_POST["DName"].'\') AND
                    Dep.ID = W.DID AND	
                    W.EID = E.ID AND
                    CURDATE() BETWEEN W.StartDate AND W.EndDate
                GROUP BY E.ID;';


    //$return =
    echo('
        <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Department Name</div>
        <!-- Table -->
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>SIN</th>
                <th>Position</th>
                <th>Address</th>
                <th>Phone</th>
                <th>DateOfBirth</th>
                <th>NumberOfDependants</th>
                <th>MonthlyWage</th>
                <th>Status</th>
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
                    <td>'.$row["SIN"].'</td>
                    <td>'.$row["Position"].'</td>
                    <td>'.$row["Address"].'</td>
                    <td>'.$row["Phone"].'</td>
                    <td>'.date_format(date_create($row["DateOfBirth"]), 'jS F Y').'</td>
                    <td>'.$row["NumberOfDependants"].'</td>
                    <td>'.number_format($row["MonthlyWage"],2).'</td>
                    <td>'.$row["Status"].'</td>
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
</head>
<body>
<?php include(P_ROOT."views/header.html")?>
<?php include(P_ROOT . "views/navbar.php") ?>

<section class="sec">
<h1>Employee Detail</h1>


    <p>Please input the department Id or Name:</p>
    <table class="inputs">
        <tr class="spaceB">
            <td>ID:</td>
            <td><input type="text" id="DID" name="DID"></td>
        </tr>
        <tr>
            <td>Name:</td>
            <td><input type="text" id="DName" name="DName"></td>
        </tr>
    </table>
    <br>
    <button type="button" id="submit">Submit</button>
    
<?php
?>

</section>
<?php include(P_ROOT."views/footer.html")?>
</body>
</html>

<?php ?> 
