<!DOCTYPE html>
<html lang="en">
<head>
<!-- IMPORTANT the following page is the Student Dashboard where all student management activities take place c-->
    <!-- THE FIRST 60 LINES IS THE CSS STYLES APPLIED TO THIS PAGE c -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student/Child Dashboard</title>
<style>
        h1 {
        text-align: center;
        padding: 0;
        font-size: 300%;
        }
        
    h3, h2, p, button {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 10vh;
         margin: 0;
        }
        
        video#bgvid {
           position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        background-size: cover;
        z-index: -1;
        }
         /* The following is a flex container for buttons to ensure they are centered c*/
        .button-container {
        display: flex;
        flex-direction: column;
        align-items: center;
         gap: 10px; /* This creates space between any Buttons c*/
        }
        button {
        background-color: lightblue;
        border: 2px solid black;
        padding: 1% 2%;
        cursor: pointer;
        font-size: 110%;
        border-radius: 15px; /*this is for the roundness off buttonns c*/
         transition: background-color 500ms, transform 1s;
        }
        
    .label:hover {
        background-color: rgb(0, 226, 226);
         transform: translate(-10%) scale(1.1); /* cahnges buttton scales to 110% c*/
        }
       .infoWindows{
            background-color: lightcyan;
            padding: 1%;
        }
</style>
</head>
<body>
<!-- This is the video that plays in the background of the page on loop c-->
<div class='section'>
<video autoplay loop muted id="bgvid">
<source src="bgvidchems.mp4">
</video>
</div>
<?php
//The following lines are using the global GET method to make sure the correct student number is carried form a previouse page c
// NOTE: its passed via the browser url c
session_start();
$ParentIdNumber = $_SESSION['ParentIdNumber'];
$host = 'localhost';
$dbname = 'assessment2lockers';
$dbUsername = 'root';
$dbPassword = '';

try {
    // The below 2 lines create the PDO whitch connects to the database c
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//This if statment bellow checks to make sure there is actually a valid parent coming to this page with the correct student number pased via the url browser c
if (isset($_GET['StudentNumber'])) { 
    //The line below uses the GET method retrieving the value passed via the url and then saves it as the StudentNumber global variable c
    $_SESSION['StudentNumber'] = $_GET['StudentNumber'];
//The following line of code then saves the StudentNumber global variable into a local php variable for ease of use later on this page c
$StudentSchoolNumber = $_SESSION['StudentNumber'];

//The following code was coppy pasted from parent dashboard page to reuse it here on the student dashboard
// The following SQL SELECT statemnt collects all the data from the user table to be displayed on the parents dashboard c
$sql1 = "SELECT * FROM studentchild WHERE StudentSchoolNumber = :StudentSchoolNumber";
$stmt1 = $pdo->prepare($sql1);

// The 1 line below binds the :ParentIdNumber placeholder to the $ParentIdNumber variable c
 $stmt1->bindParam(':StudentSchoolNumber', $StudentSchoolNumber, PDO::PARAM_STR);
// this next line Executes the query c
 $stmt1->execute();
//The next line creates an associative array that will be used to get the correct info from the studentchild table c
    $rowStudent = $stmt1->fetch(PDO::FETCH_ASSOC);

//The following 3 echo statements personalise some text that apears on the screen to help the PArent as a user of this page c
echo "<h1>Student/Child Dashboard:<h1> ";
echo "<h2>Welcome ". $rowStudent['StudentName'] . " ". $rowStudent['StudentSurname']."<h2> ";
echo "<h3>Student Number: ". $StudentSchoolNumber. "</h3>";
echo "<h3>Grade: ".$rowStudent['StudentGrade']."</h3><br>";
//The bellow two lines of code are vital to carry the correct childs details over to the bookingManagementPage c
$studentNumberToCarry = $rowStudent['StudentSchoolNumber'];

//-----------------------------------------------------------------------------------------------------------------------
// The following SQL SELECT statemnt collects data from lockersuccessfulbookings to display if this student is on the Successful bookings table
$sql2 = "SELECT * FROM lockersuccessfulbookings WHERE StudentSchoolNumber = :StudentSchoolNumber";
$stmt2 = $pdo->prepare($sql2);

// The 1 line below binds the :studentnumber placeholder to the $student variable c
 $stmt2->bindParam(':StudentSchoolNumber', $StudentSchoolNumber, PDO::PARAM_STR);
// this next line Executes the query c
 $stmt2->execute();
//The next line creates an associative array that will be used to get the correct info from the lockersuccessfulbookings table c
    $rowStudent2 = $stmt2->fetch(PDO::FETCH_ASSOC);

if ($rowStudent2) {
echo "<div class='infoWindows'>";
echo "<h2>You have a locker booked!</h2>";
echo "<h3>Locker Booking ID: ". $rowStudent2['LockerBookingID'] ."</h3> ";
echo "<h3>Locker Number: ".$rowStudent2['LockerNumber']."</h3>";
echo "</div>";
} else {
    echo "<h3>No locker Booked</h3>";
 echo "<h3><button class='label' onclick=\"window.location.href='bookingManagementPage.php?StudentNumber=$studentNumberToCarry'\">Manage and Create Locker Booking</button></h3>";
}
//---------------------------------------------------------------------------------------------------------------------------




}
else {
    //The following code should only execute if someone tries to bypass security and enter this part of the site without a valid parrent account or valid student
    echo "You are not a valid user";
}
}
//The folloing line of code is to ensure that if there is a failior to connect to the database by the PDO some helpfull error message is passed to the screen
catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
<br>
<!-- Navigation Button to go to the parentDashboard page for the APP c-->
<div class="button-container">
<button class="label" onclick="window.location.href='parentDashboard.php'">Back to Parent Dashboard</button>
<br>
</div>