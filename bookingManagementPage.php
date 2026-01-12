<!DOCTYPE html>
<html lang="en">
<head>
<!--This is the booking Management Page that is used by parents/children/admin to manage specific locker bookings linked to the student number variables passed by the browser c -->
<!-- THE FIRST 60 LINES IS THE CSS STYLES APPLIED TO THIS PAGE c-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locker Booking Management</title>
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
         /* This is a flex container for buttns c */
    .button-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px; /* Space between buttons c */
        }
        
    button {
        background-color: lightblue;
        border: 2px solid black;
        padding: 1% 2%;
        cursor: pointer;
        font-size: 110%;
        border-radius: 15px;
       transition: background-color 500ms, transform 1s;
        }
        
    .label:hover {
        background-color: rgb(0, 226, 226);
        transform: translate(-10%) scale(1.1); /*scales the btns make them 10% larger c */
        }
        .infoWindows{
            background-color: lightcyan;
            padding: 1%;
        }
</style>
</head>
<body>
<!-- This is the video that plays in the background of the page on loop, PERHAPS REMOVE TO AID WITH VIEWING INFO, - c -->
<div class='section'>
 <video autoplay loop muted id="bgvid">
 <source src="bgvidchems.mp4">
</video>
</div>
<?php
//The following lines are using the global GET method to make sure the correct student number is carried form a previouse page c
// NOTE: its passed via the browser url c
session_start();
// The following line is to set the ParentIdNumber Variable to the session variable for the ParentIdNumber c
$ParentIdNumber = $_SESSION['ParentIdNumber'];
$host = 'localhost';
$dbname = 'assessment2lockers';
$dbUsername = 'root';
$dbPassword = '';
//The following if statement makes sure the StudentNumber passed through the url is then set to the StudentNumber for the session variable c
if (isset($_GET['StudentNumber'])) { 
//The following sets the StudentNumber session variable to the correct StudentNumber passed by the url c
$_SESSION['StudentNumber'] = $_GET['StudentNumber'];
//The following line sets the php $StudentSchoolNumber variable to the StudentNumber session variable c
$StudentSchoolNumber = $_SESSION['StudentNumber'];
//next two lines are to display text to instruct the user what to do
echo "<h1>Locker Booking Management</h1> ";
echo "<h3>This is where you book lockers, or manage any bookings you do have:</h3> ";
}
else {
    echo "You are not a valid user";
}

//THE FOLLOWING TRY CATCH STATMENT IS TO CONNECT TO THE DATABASE AND DISPLAY ALL OTHER NEEDED INFO TO THE PAGE c
try {
// The below 2 lines create the PDO whitch connects to the database c
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//This if statment bellow checks to make sure there is actually a valid student coming to this page with the correct student number passed via the url c
if ($StudentSchoolNumber) { 
//The following code was coppy pasted from parent dashboard page to reuse it here on the student dashboard
// The following SQL SELECT statemnt collects all the data from the user table to be displayed on the parents dashboard c
    $sql1 = "SELECT * FROM studentchild WHERE StudentSchoolNumber = :StudentSchoolNumber";
    $stmt1 = $pdo->prepare($sql1);

// The 1 line below binds the :ParentIdNumber placeholder to the $ParentIdNumber variable from the global session c
    $stmt1->bindParam(':StudentSchoolNumber', $StudentSchoolNumber, PDO::PARAM_STR);
// this next line Executes the query c
    $stmt1->execute();
//The next line of code is important, it creates an associative array that will be used to get the correct info from the studentchild table c
    $rowStudent = $stmt1->fetch(PDO::FETCH_ASSOC);

//The following 3 echo statements personalise some text that apears on the screen to help the PArent as a user of this page c
echo "<h2>Welcome ". $rowStudent['StudentName'] . " ". $rowStudent['StudentSurname']."<h2> ";
echo "<h3>Student Number: ". $StudentSchoolNumber. "</h3>";
echo "<h3>Grade: ".$rowStudent['StudentGrade']."</h3><br>";

//THE CODE BELOW ONLY DISPLAYS IF THE STUDENT HAS MADE AN ACTUAL BOOKING AND IS IN THE WAITINGLIST c
echo "<div class=infoWindows>";
echo "<h2>Locker booking status:</h2>";
// The following SQL SELECT statemnt collects all the data from the user table to be displayed on the parents dashboard c
    $sql1 = "SELECT * FROM waitinglistparents WHERE StudentSchoolNumber = :StudentSchoolNumber";
    $stmt1 = $pdo->prepare($sql1);

// The 1 line below binds the :ParentIdNumber placeholder to the $ParentIdNumber variable from the global session c
    $stmt1->bindParam(':StudentSchoolNumber', $StudentSchoolNumber, PDO::PARAM_STR);
// this next line Executes the query c
        $stmt1->execute();

//This code was adapted from the parent dashboard code used , it will display all the rows for this student in the waiting list table c
$rowCount = 0;
while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
    $rowCount++;
    if ($rowCount) {
    //the single echo below is just to display inforamtion for the user to know their waiting list number c
    echo "<h3>"."Waiting list number: " . $row1['WaitingListNumber']."<br>"."</h3>";
    //the bottom variable is used to display whether the parent has paid or not paid for their childs locker booking c
        $PaymentStatusVar = "No change";
            //the whole if statement here is to display the payment status to the user for this specific booking c
            if ($row1['WaitingForPayment'] == 1) {
                    $PaymentStatusVar = "No payment made, please make payment and send proof of payment";
            } elseif ($row1['WaitingForPayment'] == 0) {
                    $PaymentStatusVar = "Payment made, Thank you";
            }
    
    //The next line here uses the result of the above if statement to display payment status and intructions for the parent on what to do next interms of payment c
    echo "<h3>"."Payment status: ". $PaymentStatusVar."<br>"."</h3>";
    //The bottom variable is used to display the waiting status to the user for this locker c
    $WaitingForLocker = "No change";
    //the whole if statement here is to display the payment status to the user for this specific booking c
            if ($row1['WaitingForLocker'] == 1) {
                $WaitingForLocker = "Still waiting, once a locker is booked for you, you will be informed.";
            } elseif ($row1['WaitingForLocker'] == 0) {
                $WaitingForLocker = "You have a successful locker booking!";
            } 
    
    //The following line displays whether the locker is successfully booked or still waiting c
        echo "<h3>"."Locker status: ". $WaitingForLocker."<br>"."</h3>";
    //The following line is to display information to the parent about the Admin ID assigned to their booking c
        echo "<h3>"."Your Administrator's ID: ". $row1['AdminID']."<br>"."</h3>";
    //The bellow two lines of code are vital to carry the correct childs details over to the delete booking page
        $studentNumberToCarry = $row1['StudentSchoolNumber'];
        echo "<h3><button class='label' onclick=\"window.location.href='deleteBookingPage.php?StudentNumber=$studentNumberToCarry'\">Delete Booking</button></h3>";
        echo "<br>";
        echo "<h3><button class='label' onclick=\"window.location.href='parentPaymentMade.php?StudentNumber=$studentNumberToCarry&ParentIdNumber=$ParentIdNumber'\">Manage Proof of Payment</button></h3>";
        echo "<br><br>";
    }
    else {
        echo "No locker booking made";
    }

}

//The bellow two lines of code are vital to carry the correct childs and parent details over to the createBooking page c
//THIS BUTTON CARRIES 2 VARIABLES OVER TO createLockerBooking.php page USING THE URL to carry over variales created for scope here c
$studentNumberToCarry = $rowStudent['StudentSchoolNumber'];
echo "<h3><button class='label' onclick=\"window.location.href='createLockerBooking.php?StudentNumber=$studentNumberToCarry&ParentIdNumber=$ParentIdNumber'\">Create a booking for this student</button></h3>";
echo "<br>";
echo "<br>";
echo "<br>";
 // the below displays a button on the screen that carries over the correct studentNumber via the url to go back to the Student Dashboard page for navigation purposes c
 echo "<h3><button class='label' onclick=\"window.location.href='studentDashboard.php?StudentNumber=$studentNumberToCarry'\">Go Back To Student Dashboard</button></h3>";
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
echo "</div>";
?>