<!-- IMPORTANT: This page is the main parent dashboard with many features and is unique to each parent based on the $ParentIDNumber global session variable c-->
<style>
    h1 {
    text-align: center;
    padding: 0;
    font-size: 300%;
        }
        
        h2, p, button {
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
         /* Flex container for buttons c*/
        .button-container {
         display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px; /* CREATES Space between buttons c*/
        }
        .all-container {
         display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px; /* CREATES Space between buttons c*/
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
        transform: translate(-10%) scale(1.1); 
        }
        .infoWindows{
            background-color: lightcyan;
            padding: 5%;
        }
    </style>
<div class='section'>
<video autoplay loop muted id="bgvid">
<source src="bgvidchems.mp4">
</video>
</div>
     <div class="all-container">
<?php
session_start();
// The bellow 1 line saves the $ParentIDNumber variable as the ID entered by the user on the previouse pages session to ensure its the correct person logged in c
$ParentIdNumber = $_SESSION['ParentIdNumber'];
$host = 'localhost';
$dbname = 'assessment2lockers';
$dbUsername = 'root';
 $dbPassword = '';

try {
// The below 2 lines create the PDO whitch connects to the database c
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// The following SQL SELECT statemnt collects all the data from the parentaccount table to be displayed on the parents dashboard c
    $sql = "SELECT * FROM parentaccount WHERE ParentIdNumber = :ParentIdNumber";
    $stmt = $pdo->prepare($sql);

// The 1 line below binds the :ParentIdNumber placeholder to the $ParentIdNumber variable from the global session c
$stmt->bindParam(':ParentIdNumber', $ParentIdNumber);
// this next line Executes the query c
    $stmt->execute();
// The following line creates $row assosiative array variable from the Associative array with named indexs and fetches the row info from the parentaccount table for the specific logged in parent c
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
// The following IF statment Checks if a parent user row was found and echos the parent info from that row onto the screen c
if ($row) {
echo "<div class=infoWindows>";
// the 1 line bellow is just the heading of the parents information which follows c
echo "<h1>Parent Dashboard</h1>";
//The 1 line bellow uses the different named keys from the assosiative array $row to display a friendly personal message to the parent user c
echo "Welcome " . $row['ParentTitle'] . " ".$row['ParentFirstNames']. " ".  $row['ParentSurname'] . "<br>"."<br>";
echo "<h3>Your account information:</h3>";
//the following echo statments use the $row assosiative array with named indexes to display parent information on the screen that comes from the parrentaccount table c
 echo "Title: " . $row['ParentTitle'] . "<br>";
 echo "Id Number: " . $row['ParentIdNumber'] . "<br>";
 echo "First Names: " . $row['ParentFirstNames'] . "<br>";
 echo "Surname: " . $row['ParentSurname'] . "<br>";
 echo "EmailAddress: " . $row['ParentEmailAddress'] . "<br>";
 echo "HomeAddress: " . $row['ParentHomeAddress'] . "<br>";
 echo "PhoneNumber: " . $row['ParentPhoneNumber'] . "<br>";
echo "<br>";

/*THE REST OF THE CODE IS WHERE THE STUDENTS LINKED TO THE CURRENT PARENT LOGGED IN IS DISPLAYED */

//The below code is to display the children/students created by this parent c
echo "<h3>Your Students/Children added:</h3>";
// The following SQL SELECT statemnt collects all the data from the studentchild table to be displayed later in the while loop on the parents dashboard c
$sql1 = "SELECT * FROM studentchild WHERE ParentIdNumber = :ParentIdNumber";
$stmt1 = $pdo->prepare($sql1);

// The 1 line below binds the :ParentIdNumber placeholder to the $ParentIdNumber variable retrieved from the global session variable c
    $stmt1->bindParam(':ParentIdNumber', $ParentIdNumber);
     $stmt1->execute();

// NOTE A PARENT CAN HAVE MORE THAN ONE STUDENT LINKED TO THEIR ACCOUNT
//The below is a variable used to count each row that matches a parent to a student in the studentchild table during the while statment laterc
$rowCount = 0;
//The below while statement is used to display each row in the studentchild table that matches the parrent ID from the previouse sql querry above c
while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
    //the below is to add 1 more to the row count each time the loop continues through each respective pass c
$rowCount++;
//If there is a row that matches a parrent to a student the if statement below displayes information for that specific student and a button c
if ($rowCount) {
    //The three echo statments below display the information for the student in each specific row that is returned by the fetch statement above c
    echo $rowCount .") ". "Studdent Number: " . $row1['StudentSchoolNumber'] ."<br>";
    echo "Name: " . $row1['StudentName'] . " ". $row1['StudentSurname']."<br>";
    echo $row1['StudentGrade']."<br>";
    //The line below creates a variable that is set to the StudentSchoolNumber from the assosiative array variable $row1 to carry the correct childs details over to the next page via the button next
        $studentNumberToCarry = $row1['StudentSchoolNumber'];
    // the below displays a button on the screen that carries over the correct studentNumber via the url to the next page c
    echo "<button class='label' onclick=\"window.location.href='studentDashboard.php?StudentNumber=$studentNumberToCarry'\">Manage and Create Bookings</button>";
    echo "<br><br>";
    }

}

// The following 2 echo statements are just to create space for the buttons to follow and provide a descriptive heading for users c
echo "<br>";
echo "<h3>Functions you can perform</h3>";
// the below line of code is the button that takes a parent to the page where a student account can be created c
echo "<button class='label' onclick=\"window.location.href='createStudentChildWithValidation.php'\">Add a Valid Student/child</button>";
echo "<br>";
echo "<br>";
// the below line of code is the button that takes a parent to the page where a student account can be created c
//echo "<button class='label' onclick=\"window.location.href='createStudentChild.php'\">Add a NON VALIDATED student/child</button>";
echo "<br>";
echo "<br>";
// The below button takes you back to the Index page of the website
echo "<button class='label' onclick=\"window.location.href='index.php'\">Back to Home Page</button>";
    } else {
        //The following will trigger if there is no parent user
    echo "<h3>No user found with that Username.</h3>";
    }
//the below line of code is the end of the try catch statemtn and returns an error if the connection to the PDO fails c
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
echo "</div>";
?>
