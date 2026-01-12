<!DOCTYPE html>
<html lang="en">
<!--IMPORTANT: This page is for the PArent specifically to be able to log in as it connects to the parentaccount table in the database r-->
<head>
    <meta charset="UTF-8">
    <!-- The below line of code allows the webpage to remain the same size as the screen on which it is displayed, whetehr open on a desktop, laptop, or mobile screen r-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- The below line of code is just to display the page title on the tab of the webbrowser c-->
    <title>Parent User Login</title>
    <!--Below are the CSS style rules i used to ensure a good look for the webpage c-->
    <style>
        /* Bellow is a CSS style rule that alows an artistic (and very slow moving, looping) video to act as the background of the webpage, i believe it does not affect usability/readability while giving the webpage a great, stylish and proffesional look c*/
        video#backgroundvideo {
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
         /*This bellow CSS style rule is what allows the button and input textfields on the screen to stay neatly centered c*/
        .all-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        }  
               /* Flex container for buttons c*/
        .button-container {
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
    </style>
</head>
<body>
<!--The code below is how the webpage refences the video file and allows it to automatically start playing silently, and repeatately, as the background of the webpage c-->
<video autoplay loop muted id="backgroundvideo">
<source src="bgvidchems.mp4">
</video>
<!--The below is the HTML code which will be used to display the textfields the user will enter data into, which sends a post request (this code will be added later) to the PHP database inorder to validate users c-->
<div class="all-container">
<h2>Login </h2>
<form method="POST">
  Parent Id: <input type="text" name="ParentIdNumber" >
<br>
<br>
  Password :  <input type="password" name="Password" >
<br>
<br>
  <input type="submit" value="Login">
</form>
</div>
<br>
<br>
<br>
<br>
<!-- Navigation Button to go to the INDEX page for the APP c-->
<div class="button-container">
<button class="label" onclick="window.location.href='index.php'">
Back to Home Page
</button>
<br>
</div>
</body>
</html>

<?php
session_start();
// the if statement checks if the form is submitted c
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // The following Gets the login input values from the form that the parents enters c
$ParentIdNumber = $_POST['ParentIdNumber'];
$Password = $_POST['Password'];
    // The following saves a global session variable to carry over to the next page to Save to session and ensure the parrent  see's their own personal dashboard
$_SESSION['ParentIdNumber'] = $ParentIdNumber; 

// The following 4 variables are the PDO connection credentials c
    $host = 'localhost';  
    $dbname = 'assessment2lockers';  
    $dbUsername = 'root';  
    $dbPassword = '';  


try {
    // The next line of code Creates a PDO instance for the database connection to the correct database c
     $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    // The following is the SQL query to search for matching parent ID and password in the parentaccount table c
     $sql = "SELECT * FROM parentaccount WHERE ParentIdNumber = :ParentIdNumber AND Password = :Password";
    $stmt = $pdo->prepare($sql);
        
    // This Binds the parameters of the parentID and password variable from the form to the correct column in the database table c
    $stmt->bindParam(':ParentIdNumber', $ParentIdNumber);
     $stmt->bindParam(':Password', $Password);
        
    // the following executes the stmt query from above c
     $stmt->execute();
        
        // The if statement checks if there is atleast 1 row witch has a matching PArent ID and Password (meaning there is a match) c
if ($stmt->rowCount() > 0) {
        echo "Login successful! Welcome, " . htmlspecialchars($ParentIdNumber) . ".";
        //The 2 lines below are to change the page to the parents main dashboard c
        header("Location: parentDashboard.php");
        // the exit() method makes sure no other code is run after the parent successfully logs in. c
         exit();
} else {
            //the echo statment below only runs if the parent does not enter a valid Parent ID or PAssword c
        echo "Invalid Parent Id Number or password. Try again.";
    }

    } catch (PDOException $e) {
    // This catch statment handle any database connection errors and returns a helpfull message to understand what went wrong c
        echo "Error: " . $e->getMessage();
    }
}
?>
