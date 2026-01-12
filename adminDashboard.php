<!-- IMPORTANT: This page is the main admin dashboard with many features and is unique to each admin based on the $ParentIDNumber global session variable
NOTE THIS CODE IS ADAPTED FROM THE PARENT DASHBOARD
-->
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
// The bellow 1 line saves the $AdminID variable as the ID entered by the admin user on the previouse pages session to ensure its the correct person logged in c
$AdminID = $_SESSION['AdminID'];
$host = 'localhost';
$dbname = 'assessment2lockers';
$dbUsername = 'root';
$dbPassword = '';
try {
// The below 2 lines create the PDO whitch connects to the database
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// The following SQL SELECT statemnt collects all the data from the administratoraccount table to be displayed on the administrator dashboard c
    $sql = "SELECT * FROM administratoraccount WHERE AdminID = :AdminID";
    $stmt = $pdo->prepare($sql);

// The 1 line below binds the :AdminID placeholder to the $AdminID variable from the global session c
$stmt->bindParam(':AdminID', $AdminID, PDO::PARAM_STR);
// this next line Executes the query c
$stmt->execute();
// The following line creates an Associative array with named indexs and fetches the row info from the waitinglistparents table for the specific logged in admin c
$row = $stmt->fetch(PDO::FETCH_ASSOC);
// The following IF statment Checks if a admin user row was found and echos helpful information onto the screen c
    if ($row) {
// the 1 line bellow is just the heading of Administration Dashboard c
echo "<h1>Administrator Dashboard</h1>";
//The 1 line bellow uses the different named keys from the assosiative array $row to display a friendly personal message to the Admin user c
echo "<div class='infoWindows'>";
echo "Welcome: " .$row['AdminUserName']."<br>";
echo "<h3>Your Admin account information:</h3>";
//the following echo statments use the $row assosiative array with named indexes to display information on the users screen that comes from the databasec
echo "Admin ID: " . $row['AdminID'] . "<br>";
echo "First Names: " . $row['FirstName'] . "<br>";
echo "Surname: " . $row['Surname'] . "<br>";
echo "EmailAddress: " . $row['EmailAddress'] . "<br>";
echo "<br>";

    } else {
        echo "<h3>No user found with that Username.</h3>";
    }
//the below line of code is the end of the try catch statemtn and returns an error if the connection to the PDO fails c
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>

<!-- The following lines display on the page all the needed buttons for the functions the admin user can perform -->
<h3>Functions</h3>
<!-- Navigation Button to go to the main REPORTS for the website-->
<div class="button-container">
<button class="label" onclick="window.location.href='reports.php'">
reports
</button>
<!-- Navigation Button to go to the Index for the website-->
<br>
<br>
<div class="button-container">
<button class="label" onclick="window.location.href='adminCreateParentAccountWithValidation.php'">
Add a Valid Parent Account
</button>
<!-- Navigation Button to go to the Index for the website-->
<br>
<br>
<div class="button-container">
<button class="label" onclick="window.location.href='adminParentDashboard.php'">
Administor Parent Management 
</button>
<!-- Navigation Button to go to the Index for the website-->
<br>
<br>
<div class="button-container">
<button class="label" onclick="window.location.href='adminWaitingListManagement.php'">
Administor Waiting List Management 
</button>
<!-- Navigation Button to go to the Index for the website-->
<br>
<br>
<div class="button-container">
<button class="label" onclick="window.location.href='index.php'">
Go Back to Home Page
</button>
</div>
</div>
</div>
<!-- Navigation Button to go to the main REPORTS for the website-->
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
