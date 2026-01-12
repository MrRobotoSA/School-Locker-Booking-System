<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- THIS PAGE IS HOW THE PARENT CREATES A PARENT ACCOUNTE c-->
    <title>Create Parent Account</title>
    <!-- IMPORTANT all these LINES ARE ALL JUST CSS STYLES AS ON PREVIOUSE PAGEs just coppy pasted here c-->
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
<!-- BELOW CODE IS just the text on the screen instructing parent what to do c-->
<div class="button-container">
<h2>Create a New User</h2>
<div class="infoWindows">
<h3>Please enter your credentials</h3>
<!-- Below CODE IS the FORM to gather parent info inserted to create a parent account c-->
<form method="POST">
1 ParentTitle (Mr or Mrs): <input type="text" name="ParentTitle"><br><br>
2 Parent Id Number: <input type="text" name="ParentIdNumber"><br><br>
3 First Name: <input type="text" name="ParentFirstNames"><br><br>
4 Last Name: <input type="text" name="ParentSurname"><br><br>
5 Email Address: <input type="text" name="ParentEmailAddress"><br><br>
6 Home Address: <input type="text" name="ParentHomeAddress"><br><br>
7 Phone Number: <input type="text" name="ParentPhoneNumber"><br><br>
 8 Password: <input type="password" name="Password"><br><br>
<input type="submit" value="Create User">
</form>
</div>
</div>
<?php
//The code below starts the session used later to save the parentID into a session variable c
  session_start();
// this checks if the form was submitted c
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieves the values from the form that the parents entered and saves each input into a php variable used later on this page c
$ParentTitle = $_POST['ParentTitle'];
$ParentIdNumber = $_POST['ParentIdNumber'];
$ParentFirstNames = $_POST['ParentFirstNames'];
$ParentSurname = $_POST['ParentSurname'];
  $ParentEmailAddress = $_POST['ParentEmailAddress'];
  $ParentHomeAddress = $_POST['ParentHomeAddress'];
  $ParentPhoneNumber = $_POST['ParentPhoneNumber'];
  $Password = $_POST['Password'];

 // Saves to session variable for other pages to read this later c
$_SESSION['ParentIdNumber'] = $ParentIdNumber; 

  // the needed info to create the Database connection c
$host = 'localhost';
$dbname = 'assessment2lockers';
  $username = 'root';
  $dbpassword = '';      

  try {
    // this is to Create a new PDO instance connecting to the database
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $dbpassword);
  // Set error mode to exception for the PDO
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // the bellow code inserts the parents info into the correct parentaccount database table c
    $sql = "INSERT INTO parentaccount (ParentTitle, ParentIdNumber, ParentFirstNames, ParentSurname, ParentEmailAddress, ParentHomeAddress, ParentPhoneNumber, Password) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

  // this is to create the prepared sql statement from above c
  $stmt = $pdo->prepare($sql);
 //This executes the data into the parentaccount table c
  $stmt->execute([$ParentTitle, $ParentIdNumber, $ParentFirstNames, $ParentSurname, $ParentEmailAddress, $ParentHomeAddress, $ParentPhoneNumber, $Password]);
    
    // If the data is successfully entered by the parent, it takes the parent user to a successful parrent acocunt created page c
    header("Location: success_page.php");
  } catch (PDOException $e) {
    // This last line Display error message is here if connection fails to give helpfull information to the user c
    echo "Database connection failed: " . $e->getMessage();
  }
}
?>
<div class="button-container">
<button class="label" onclick="window.location.href='index.php'">
Go Back to Home Page
</button>
</body>
</html>