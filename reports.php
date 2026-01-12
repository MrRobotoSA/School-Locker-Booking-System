<!DOCTYPE html>
<html lang="en">
<head>
<!-- This page is the admin reports page that shows the needed reports for the grade 8, grade 11 and all successfull bookings c-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports</title>
    <style>
        h1 {
        text-align: center;
         padding: 0;
        font-size: 300%;
        }
        /*Styles below is for the screen to help with readibility c*/ 
        body {
        background-color: lightcyan;
        }
         h2, p, button {
        display: flex;
        justify-content: center;
         align-items: center;
        height: 10vh;
        margin: 0;
        }
        /* Styles below are for the table borders */
    table, th {
    border: 2px solid black;
        }
    td {
    border: 1px solid black
        }
        /*The below code keeps all content centered c*/
        .all-container {
        display: flex;
        flex-direction: column;
        align-items: center;
         gap: 10px;
        }
        /*Styles below are for the buttons c*/ 
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
        transform: translate(-10%) scale(1.1); 
        }
    </style>
</head>
<body>
<div class="all-container">
<h2>Grade 8 and 11 Locker Reports</h2>
<!-- This PHP Script bellow is used to create a PDO object and connect to the database c-->
<?php
session_start();
//Below is the needed info for the database object to connect to the databse c
 $host = 'localhost';  
 $dbname = 'assessment2lockers';  
 $dbUsername = 'root';  
 $dbPassword = '';  
    try {
        // Create an instance of a PDO for the database to successsfully connect c
     $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // This is to what will display if there is an error with the pdo showing any database connection errors c
    echo "Error: " . $e->getMessage();
    }
?>
<!-- The below PHP script is displaying the results for grade 8 students using a PDO object and an SQL querry c -->
<?php 
$sqlstatment1 = "SELECT lockersuccessfulbookings.*, studentchild.StudentName, studentchild.StudentSurname, studentchild.StudentGrade
            FROM lockersuccessfulbookings
         JOIN studentchild 
            ON lockersuccessfulbookings.StudentSchoolNumber = studentchild.StudentSchoolNumber
            WHERE studentchild.StudentGrade = 'Grade 8'";

 $statement1 = $pdo->prepare($sqlstatment1);
    $statement1->execute();
//below is code that is a php variable that stores the results of the above SQL querry c
    $grade8results = $statement1->fetchAll();
//Below is the code that then uses the above $grade8results php variable and then displays the rows in a neat table c
    if ($grade8results) {
    echo "<h2>Grade 8's in successfull bookings table</h2>";
    echo "<table>";
echo "<thead><tr><th>Student Number</th><th>Firstname</th><th>Surname</th><th>Grade</th><th>Locker Number</th><th>Booking ID</th></tr></thead>";
echo "<tbody>";
foreach ($grade8results as $row) {
    echo "<tr>";
     echo "<td>" . ($row['StudentSchoolNumber']) . "</td>";
     echo "<td>" . ($row['StudentName']) . "</td>";
     echo "<td>" . ($row['StudentSurname']) . "</td>";
    echo "<td>" . ($row['StudentGrade']) . "</td>";
     echo "<td>" . ($row['LockerNumber']) . "</td>";
     echo "<td>" . ($row['LockerBookingID']) . "</td>";
    echo "</tr>";
    }
echo "</tbody>";
echo "</table>";}
// The code below provides a count of how many grade 8 students are currently in the successfull bookings table (so we know how many grade 8 students have a successful booking) (RC Comment)
$Grade8BookedCount = count($grade8results);
//The below line of code displays the $Grade8BookedCount on the screen
echo "Number of grade 8's with a booked locker: ".$Grade8BookedCount;
echo "<br>";
//The below line of code uses a mathematical calculation to work out how many lockers are left for the grade 8's (limited to 10 Lockers)
echo "Number of grade 8 lockers remaining: ". 10 - $Grade8BookedCount;
?>
<br>
<br>
<!-- The below PHP script is displaying the results and reports for grade 11 students ONLY c -->
<?php 
$sqlstatement2 = "SELECT lockersuccessfulbookings.*, studentchild.StudentName, studentchild.StudentSurname, studentchild.StudentGrade
            FROM lockersuccessfulbookings
            JOIN studentchild 
         ON lockersuccessfulbookings.StudentSchoolNumber = studentchild.StudentSchoolNumber
            WHERE studentchild.StudentGrade = 'Grade 11'";

    $statement2 = $pdo->prepare($sqlstatement2);
$statement2->execute();
//below is a line of code that contains a php variable that stores the results of the above SQL querry that is then used to provide grade 11 information as part of the reports c
    $grade11results = $statement2->fetchAll();
//Below is the php code that then uses the above $grade11results php variable and then displays the rows in a neat table c
    if ($grade11results) {
    echo "<h2>Grade 11's in successfull bookings table</h2>";
    echo "<table>";
     echo "<thead><tr><th>Student Number</th><th>Firstname</th><th>Surname</th><th>Grade</th><th>Locker Number</th><th>Booking ID</th></tr></thead>";
    echo "<tbody>";
    foreach ($grade11results as $row) {
    echo "<tr>";
        echo "<td>" . ($row['StudentSchoolNumber']) . "</td>";
        echo "<td>" . ($row['StudentName']) . "</td>";
      echo "<td>" . ($row['StudentSurname']) . "</td>";
     echo "<td>" . ($row['StudentGrade']) . "</td>";
        echo "<td>" . ($row['LockerNumber']) . "</td>";
        echo "<td>" . ($row['LockerBookingID']) . "</td>";
    echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";}
/// The code below provides a count of how many grade 11 students are currently in the successfull bookings table  c
$Grade11BookedCount = count($grade11results);
//The below line of code displays the $Grade11BookedCount on the screen c
echo "Number of grade 11's with a booked locker: ".$Grade11BookedCount;
echo "<br>";
//The below line of code uses a mathematical calculation to work out how many lockers are left for the grade 11's limited to 10 c
echo "Number of grade 11 lockers remaining: ". 10 - $Grade11BookedCount;
?>
<!-- The below is just the heading of the next table which is all the lockers booked c-->
<h2>All Lockers booked</h2>
<p>Period January 2026 to June 2026</p>
<!-- The below php script is used to show the summary of all booked lockers c-->
<?php 
//Below is the query used to get results for all the successfull bookings (note this is used again at the bottom of the report by the last table displayed) c
$sqlstatement3 = "SELECT lockersuccessfulbookings.*, studentchild.StudentName, studentchild.StudentSurname, studentchild.StudentGrade
        FROM lockersuccessfulbookings
            JOIN studentchild 
            ON lockersuccessfulbookings.StudentSchoolNumber = studentchild.StudentSchoolNumber";
    $statement3 = $pdo->prepare($sqlstatement3);
 $statement3->execute();
//below is a line of code that contains a php variable that stores the results of the above SQL querry to show all successfull bookings c
    $allGradeResults = $statement3->fetchAll();
/// The code below provides a count of how many total bookings are currently in the successfull bookings table  c
$allBookedCount = count($allGradeResults);
//The below line of code displays the $allBookedCount on the screen c
echo "Total number of ALL GRADE'S lockers booked: ".$allBookedCount;
echo "<br>";

//Below is the query used to get results for all the GRADE 8 successfull bookings c
$sqlstatement4 = "SELECT lockersuccessfulbookings.*
    FROM lockersuccessfulbookings
        JOIN studentchild 
        ON lockersuccessfulbookings.StudentSchoolNumber = studentchild.StudentSchoolNumber
        WHERE studentchild.StudentGrade = 'Grade 8'";         
    $statement4 = $pdo->prepare($sqlstatement4);
    $statement4->execute();
//below is a line of code that contains a php variable that stores the results of the above SQL querry that is then used to provide other grade 8 information c
    $allGrade8Results = $statement4->fetchAll();
/// The code below provides a count of how many grade 8 bookings are currently in the successfull bookings table c
$allBookedCount8 = count($allGrade8Results);
echo "<br>";
//The below line of code displays the $allBookedCount on the screen c
echo "Total number of GRADE 8 lockers booked: ".$allBookedCount8;
echo "<br>";

//Below is the query used to get results for all the GRADE 9 successfull bookings c
$sqlstatement5 = "SELECT lockersuccessfulbookings.*
        FROM lockersuccessfulbookings
        JOIN studentchild 
        ON lockersuccessfulbookings.StudentSchoolNumber = studentchild.StudentSchoolNumber
         WHERE studentchild.StudentGrade = 'Grade 9'";         
    $statement5 = $pdo->prepare($sqlstatement5);
    $statement5->execute();
//below is a line of code that contains a php variable that stores the results of the above SQL querry that is then used to provide grade 9 information as part of the reports c
    $allGrade9Results = $statement5->fetchAll();
/// The code below provides a count of how many total bookings are currently in the successfull bookings table  c
$allBookedCount9 = count($allGrade9Results);
echo "<br>";
//The below line of code displays the $allBookedCount for grade 9 on the screen c
 echo "Total number of GRADE 9 lockers booked: ".$allBookedCount9;
 echo "<br>";

//Below is the query used to get results for all the GRADE 10 successfull bookings c
$sqlstatement6 = "SELECT lockersuccessfulbookings.*
        FROM lockersuccessfulbookings
        JOIN studentchild 
         ON lockersuccessfulbookings.StudentSchoolNumber = studentchild.StudentSchoolNumber
         WHERE studentchild.StudentGrade = 'Grade 10'";         
    $statement6 = $pdo->prepare($sqlstatement6);
$statement6->execute();
//below is a line of code that contains a php variable that stores the results of the above SQL querry that is for grade 10 c
    $allGrade10Results = $statement6->fetchAll();
/// The code below provides a count of how many total grade 10 bookings are currently in the successfull bookings table  c
$allBookedCount10 = count($allGrade10Results);
echo "<br>";
//The below line of code displays the $allBookedCount10 on the screen c
echo "Total number of GRADE 10 lockers booked: ".$allBookedCount10;
echo "<br>";

//Below is the query used to get results for all the GRADE 11 successfull bookings c
$sqlstatement7 = "SELECT lockersuccessfulbookings.*
        FROM lockersuccessfulbookings
         JOIN studentchild 
         ON lockersuccessfulbookings.StudentSchoolNumber = studentchild.StudentSchoolNumber
         WHERE studentchild.StudentGrade = 'Grade 11'";         
    $statement7 = $pdo->prepare($sqlstatement7);
 $statement7->execute();
//below is a line of code that contains a php variable that stores the results of the above SQL querry that is then used to provide grade 11 info c
    $allGrade11Results = $statement7->fetchAll();
/// The code below provides a count of how many grade 11 bookings are currently in the successfull bookings table  c
$allBookedCount11 = count($allGrade11Results);
echo "<br>";
//The below line of code displays the $allBookedCount11 on the screen c
echo "Total number of GRADE 11 lockers booked: ".$allBookedCount11;
echo "<br>";


//Below is the query used to get results for all the GRADE 12 successfull bookings c
$sqlstatement8 = "SELECT lockersuccessfulbookings.*
             FROM lockersuccessfulbookings
            JOIN studentchild 
            ON lockersuccessfulbookings.StudentSchoolNumber = studentchild.StudentSchoolNumber
            WHERE studentchild.StudentGrade = 'Grade 12'";         
    $statement8 = $pdo->prepare($sqlstatement8);
 $statement8->execute();
//below is a line of code that contains a php variable that stores the results of the above SQL querry that is then used to provide grade 12 info
    $allGrade12Results = $statement8->fetchAll();
/// The code below provides a count of how many total grade 12 bookings are currently in the successfull bookings table  c
$allBookedCount12 = count($allGrade12Results);
echo "<br>";
//The below line of code displays the $allBookedCount12 on the screen c
echo "Total number of GRADE 12 lockers booked: ".$allBookedCount12;
echo "<br>";

//Below is the php code that then uses the above $allBookedCount php variable and then displays the rows in a neat table for all the successfull bookings c
 if ($allGradeResults) {
echo "<h2>Table of all bookings</h2>";
 echo "<table>";
echo "<tr><th>Student Number</th<thead><th>Firstname</th><th>Surname</th><th>Grade</th><th>Locker Number</th><th>Booking ID</th></tr></thead>";
echo "<tbody>";
foreach ($allGradeResults as $row) {
echo "<tr>";
     echo "<td>" . ($row['StudentSchoolNumber']) . "</td>";
     echo "<td>" . ($row['StudentName']) . "</td>";
    echo "<td>" . ($row['StudentSurname']) . "</td>";
     echo "<td>" . ($row['StudentGrade']) . "</td>";
     echo "<td>" . ($row['LockerNumber']) . "</td>";
    echo "<td>" . ($row['LockerBookingID']) . "</td>";
echo "</tr>";
    }
echo "</tbody>";
echo "</table>";}
?>
<!-- Below is the button to go back to the admin dashboard-->
<button class="label" onclick="window.location.href='adminDashboard.php'">
Back to Admin Dashboard
</button>
</div>
</body>
</html>