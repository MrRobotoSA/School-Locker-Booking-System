 <head>
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
         /* this is the Flex container for buttons c*/
      .button-container {
          display: flex;
            flex-direction: column;
          align-items: center;
            gap: 10px; /* this creates Space between buttons c*/
        }
        
    button {
        background-color: lightblue;
        border: 2px solid black;
        padding: 1% 2%;
        cursor: pointer;
        font-size: 110%;
        border-radius: 15px; /* this is to Adjust this value to change the roundness of buttns c */
        transition: background-color 500ms, transform 1s;
        }
        
      .label:hover {
          background-color: rgb(0, 226, 226);
          transform: translate(-10%) scale(1.1); /* this Scales the button to 110% (10% larger) for style c*/
        }
                .infoWindows{
            background-color: lightcyan;
            padding: 5%;
        }
    </style>
</head>
<body>
      <!-- Below div is the html to create the background video c -->
<div class='section'>
<video autoplay loop muted id="bgvid">
<source src="bgvidchems.mp4">
</video>
</div>
<div class="button-container">

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
// the 1 line bellow is just the heading of Waiting List Administration Dashboard c
echo "<h1>Waiting List Administrator Dashboard</h1>";
//The 1 line bellow uses the different named keys from the assosiative array $row to display a friendly personal message to the Admin user c
echo " <div class='infoWindows'>";
echo "Welcome: " .$row['AdminUserName']."<br>";
echo "<h3>Your Admin account information:</h3>";
//the following echo statments use the $row assosiative array with named indexes to display information on the users screen that comes from the databasec
echo "Admin ID: " . $row['AdminID'] . "<br>";
echo "First Names: " . $row['FirstName'] . "<br>";
echo "Surname: " . $row['Surname'] . "<br>";
echo "EmailAddress: " . $row['EmailAddress'] . "<br>";
echo "<br>";

// OK 1 ROW WORKS NOW 
echo "<h3>Students on waitinglist:</h3>";
// The following SQL SELECT statemnt collects all the data from the user table to be displayed on the parents dashboard c
    $sql1 = "SELECT * FROM waitinglistparents WHERE AdminID = :AdminID";
    $stmt1 = $pdo->prepare($sql1);

// The 1 line below binds the :ParentIdNumber placeholder to the $ParentIdNumber variable from the global session c
    $stmt1->bindParam(':AdminID', $AdminID, PDO::PARAM_STR);
// this next line Executes the query c
        $stmt1->execute();

//following while loop adapted from the parent dashboard code
$rowCount = 0;
while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
    $rowCount++;
    if ($rowCount) {
        echo $rowCount .") ". "Waitinglist Number: " . $row1['WaitingListNumber'] ."<br>";
        echo "Parent ID Number: " . $row1['ParentIdNumber'] . "<br>";
        echo "Waiting for payment: " . $row1['WaitingForPayment']."<br>";
    echo "Waiting for locker: " . $row1['WaitingForLocker']."<br>";
    echo "Student School Number: " . $row1['StudentSchoolNumber']."<br>";
        //The bellow two lines of code are vital to carry the correct childs details over to the next page
        $studentNumberToCarry = $row1['StudentSchoolNumber'];
        $ParentIdNumberToCarry = $row1['ParentIdNumber'];
        echo "<button class='label' onclick=\"window.location.href='adminOneWaitingListManagement.php?StudentNumber=$studentNumberToCarry&ParentIdNumber=$ParentIdNumberToCarry'\">Manage Waiting List Status</button>";
        echo "<br><br>";
    }

}

    } else {
        echo "<h3>No user found with that Username.</h3>";
    }
//the below line of code is the end of the try catch statemtn and returns an error if the connection to the PDO fails c
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
</div>
</div>

<!-- Navigation Button to go to the main admin DashBoard for the website-->
<br>
<br>
<div class="button-container">
<button class="label" onclick="window.location.href='adminDashboard.php'">
Go back to Admin Dashboard
</button>