<!-- The below php script runs when the admin clicks that button indicating that payment has been made and recieved -->
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
<h2>Student on Waiting List Administrator Dashboard</h2>
<div class="infoWindows">
<h2>Has Payment Been Made?</h2>
<form method="post">
<button class='label' type="submit" name="paymentMade">Yes Payment Made</button>
</form>
<?php
session_start();
?>

<?php 
//Email info---------------------------------------------------------------------------------------------------------------------------------
//References for this Script page: I followed the instructions as explained on https://github.com/PHPMailer/PHPMailer to get this working
// This is to import the PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//The below requires are placed in here because i did not use composer and loaded each class file manually with these three lines, as explained om https://github.com/PHPMailer/PHPMailer
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// This line is to Create a new instance of the PHPMailer class and is set to true to allow exeptions
$mail = new PHPMailer(true);
//Email info end---------------------------------------------------------------------------------------------------------------------------------

//BELOW IS THE BUTTON THE ADMIN PRESSES TO APROVE PAYMENT
if(isset($_POST['paymentMade'])) {
 //the below three variables are used to get all needed info for the 'yes payment made button' to be pressed
 $StudentSchoolNumber = $_GET['StudentNumber'];
  $AdminID = $_SESSION['AdminID'];
  $ParentIdNumber = $_GET['ParentIdNumber'];
//the below code is to make connection to the pdo
  $host = 'localhost';
  $dbname = 'assessment2lockers';
  $username = 'root';
  $dbpassword = '';      

  try {
    // The line below is to create a new instance of the PDO c
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $dbpassword);
    // The below line of code is to set the error mode c
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* following sql code was adapted from a SQL statemnt from the delete booking page*/
    $sql2 = "UPDATE waitinglistparents SET WaitingForPayment = 0 WHERE StudentSchoolNumber = $StudentSchoolNumber";
    $stmt2 = $pdo->prepare($sql2);

    //This executes the data into the waitinglistparents table c
    $stmt2->execute();

/* EMAIL CODE Starts HERE BELOW!!!!!!---------------------------------------------------------------------------------- */

// THE BELOW CODE GETS THE EMAIL ADRESS FOR THE PARENT TO SENT THE WAITING LIST INFO TO THEM
$sql3 = "SELECT * FROM parentaccount WHERE ParentIdNumber = $ParentIdNumber";
    $stmt3 = $pdo->prepare($sql3);
// this next line Executes the query c
        $stmt3->execute();
$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
 $parentEmail = $row3['ParentEmailAddress'];

 // THE BELOW CODE GETS THE EMAIL ADRESS FOR THE ADMIN TO SEND THE WAITING LIST INFO TO THEM
$sql4 = "SELECT * FROM administratoraccount WHERE AdminID = $AdminID";
    $stmt4 = $pdo->prepare($sql4);
// this next line Executes the query c
        $stmt4->execute();
$row4 = $stmt4->fetch(PDO::FETCH_ASSOC);
 $adminEmail = $row4['EmailAddress'];
  
 /* -----------------------------------------------------------------------------------------------------------
 THE INFO TO SEND AN EMAIL IS HERE  below here-- */
  try {
    // These are the settings for the Server
    //To get this to work with a gmail smtp mail address i used this reference https://mailtrap.io/blog/phpmailer-gmail/
    $mail->isSMTP(); //sends using smtp
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'robertomiguelgouveiacatanho@gmail.com'; 
    $mail->Password   = 'jwmdmdeheuodudnh';   
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    //---------------------------------------------------------------------------------------
    // the bottom line is the SENDERS information
    //---------------------------------------------------------------------------------------
    $mail->setFrom('robertomiguelgouveiacatanho@gmail.com', 'Amandla School Admin');

    //----------------------------------------------------------------------------------------------
    //the below lines are the RECIEVERS information
    //----------------------------------------------------------------------------------------------
    //The below lines Can send to multiple recipients
    //The below lines are for the recipients email adresses
    $mail->addAddress($adminEmail, 'Admin');
    //THE BELOW LINE GETS THE EMAIL FOR THE PARENT
    $mail->addAddress($parentEmail, 'Parent');
    // The below lines are to set the emails contents, and it sets the email formatting to enable html formating
    $mail->isHTML(true);
    $mail->Subject = 'Parent Has Paid';
    $mail->Body    = '<h3>Proof of payment recieved</h3>';
    $mail->AltBody = 'Proof of payment recieved';

    $mail->send();
    echo 'Email Message Sent to parent: ';
    echo $parentEmail;
    echo "<br>";
    echo 'Email Message Sent to admin: ';
    echo $adminEmail;
} catch (Exception $e) {
    //the below echos an error message if the email is not sent, helpful for debugging
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
//----------------------------------------------------------------------------------------------
// THE INFO TO EMAIL TO IS HERE 
//------------------------------------------------------------------------------------------------
    

//If all is a success the next line creates a button that if clicked takes the USER BACK TO the bookingManagementPage c
    echo "<h2>Payment Recieved Updated Table</h2>";
} catch (PDOException $e) {
    // This last line displays an error message if the connection fails to give helpfull information to the user c
    echo "Database connection failed: " . $e->getMessage();
  }
}
?>
<?php
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
//The 1 line bellow uses the different named keys from the assosiative array $row to display a friendly personal message to the Admin user c
echo "Welcome Admin: " .$row['AdminUserName']."<br>";
echo "Admin ID: " .$row['AdminID']."<br>"; 

// OK 1 ROW WORKS NOW 
echo "<h3>Students on waitinglist:</h3>";
// Setting variables correctly 
$StudentSchoolNumber = $_GET['StudentNumber'];
// The following SQL SELECT statemnt collects all the data from the user table to be displayed on the parents dashboard c
    $sql1 = "SELECT * FROM waitinglistparents WHERE AdminID = :AdminID AND StudentSchoolNumber = :StudentSchoolNumber";
    $stmt1 = $pdo->prepare($sql1);

// The 1 line below binds the :ParentIdNumber placeholder to the $ParentIdNumber variable from the global session c
    $stmt1->bindParam(':AdminID', $AdminID, PDO::PARAM_STR);
    $stmt1->bindParam(':StudentSchoolNumber', $StudentSchoolNumber, PDO::PARAM_STR);
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
        echo "<br>";
    //The below if statment checks if payment has been made, if it has then a button apears to allow the admin to book a locker
    if ($row1['WaitingForPayment']==0) {
    echo "<h3>Locker has been paid for.</h3>";
    echo "<button class='label' onclick=\"window.location.href='adminMoveToSuccess.php?StudentNumber=$studentNumberToCarry&ParentIdNumber=$ParentIdNumberToCarry'\">Book a Locker for Student</button>";
    }
    elseif ($row1['WaitingForPayment']==1) {
        echo "<h3>Locker has not been paid for</h3>";
    }
        
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
<!-- Navigation Button to go to the Index for the website-->
<br>
<br>
<div class="button-container">
<button class="label" onclick="window.location.href='adminWaitingListManagement.php'">
Go Back to Admin Waiting List Management
</button>