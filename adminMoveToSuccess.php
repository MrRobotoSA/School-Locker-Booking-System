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
<h2>Move Student to Successfull Bookings Table</h2>
<div class="infoWindows">
<?php
session_start();
// The bellow 1 line saves the $AdminID variable as the ID entered by the admin user on the previouse pages session to ensure its the correct person logged in c
$AdminID = $_SESSION['AdminID'];
$host = 'localhost';
$dbname = 'assessment2lockers';
$dbUsername = 'root';
$dbPassword = '';

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
echo "<h3>Book a locker</h3>";
// Setting variables correctly 
$StudentSchoolNumber = $_GET['StudentNumber'];
$ParentIdNumber = $_GET['ParentIdNumber'];
echo "Student school number: $StudentSchoolNumber";
echo "<br>";
echo "Parent ID:  $ParentIdNumber";
// The following SQL SELECT statemnt collects all the data from the studentchild table to be displayed on the dashboard c
    $sql1 = "SELECT * FROM studentchild WHERE ParentIdNumber = :ParentIdNumber AND StudentSchoolNumber = :StudentSchoolNumber";
    $stmt1 = $pdo->prepare($sql1);

// The 1 line below binds the :ParentIdNumber and :StudentSchoolNumber placeholder to the $ParentIdNumber variable from the browser url c
    $stmt1->bindParam(':ParentIdNumber', $ParentIdNumber, PDO::PARAM_STR);
    $stmt1->bindParam(':StudentSchoolNumber', $StudentSchoolNumber, PDO::PARAM_STR);
// this next line Executes the query c
$stmt1->execute();
$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
$StudentGrade = $row1['StudentGrade'];
echo "<br>";
echo "Student Grade: $StudentGrade";



/*-----------------------------------------------------------------------------------------------------------
The following code gets the open locker that matches the grade of the student to an available locker
----------------------------------------------------------------------------------------------------------- */
    $sql2 = "SELECT * FROM lockers WHERE LockerGrade = :StudentGrade AND LockerBookedStatus = 0";
    $stmt2 = $pdo->prepare($sql2);

// The 1 line below binds the :ParentIdNumber and :StudentSchoolNumber placeholder to the $ParentIdNumber variable from the browser url c
    $stmt2->bindParam(':StudentGrade', $StudentGrade, PDO::PARAM_STR);
// this next line Executes the query c
$stmt2->execute();
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
if ($row2) {
$LockerNumber = $row2['LockerNumber'];
$LockerGrade = $row2['LockerGrade'];
echo "<br>";
echo "<h2>Locker Number $LockerNumber". " is available for ". $LockerGrade . "<br></h2>";

echo "<h2>Book a locker</h2>";
echo "<h2><form method='post'><button class='label' type='submit' name='paymentMade'>Yes Book</button></form></h2>";
}
else {
    //ADD THE EMAIL CODE HERE FOR ALL LOCKERS FULL
    //-----------------------------------------------
 echo "<br>";
 echo "<h2>No Lockers Available for this Grade.<br></h2>";
 echo "<br>";
 echo "<br>";
echo "<h2><form method='post'><button class='label' type='submit' name='lockersFull'>Inform Parent Lockers are full</button></form></h2>";
}
/*-----------------------------------------------------------------------------------------------------------
Code above Works 
-------------------------------------------------------------------------------------------------------------*/



if(isset($_POST['lockersFull'])) {
    // the below variables provide the credentials for the PDO connection to the database c
  $StudentSchoolNumber = $_GET['StudentNumber'];
  $host = 'localhost';
  $dbname = 'assessment2lockers';
  $username = 'root';
  $dbpassword = '';      

  try {
    // The line below is to create a new instance of the PDO c
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $dbpassword);
    // The below line of code is to set the error mode c
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* EMAIL CODE Starts HERE BELOW!!!!!!---------------------------------------------------------------------------------- */
// THE BELOW CODE GETS THE EMAIL ADRESS FOR THE PARENT TO SENT THE WAITING LIST INFO TO THEM
$sql9 = "SELECT * FROM parentaccount WHERE ParentIdNumber = $ParentIdNumber";
    $stmt9 = $pdo->prepare($sql9);
// this next line Executes the query c
        $stmt9->execute();
$row9 = $stmt9->fetch(PDO::FETCH_ASSOC);
 $parentEmail = $row9['ParentEmailAddress'];

 // THE BELOW CODE GETS THE EMAIL ADRESS FOR THE ADMIN TO SEND THE WAITING LIST INFO TO THEM
$sql10 = "SELECT * FROM administratoraccount WHERE AdminID = $AdminID";
    $stmt10 = $pdo->prepare($sql10);
// this next line Executes the query c
        $stmt10->execute();
$row10 = $stmt10->fetch(PDO::FETCH_ASSOC);
 $adminEmail = $row10['EmailAddress'];
  
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
    //-----------------------------------------------------
    //Line below removed----------------------------------
    // $mail->addAddress($adminEmail, 'Admin');
    //THE BELOW LINE GETS THE EMAIL FOR THE PARENT
    $mail->addAddress($parentEmail, 'Parent');
    // The below lines are to set the emails contents, and it sets the email formatting to enable html formating
    $mail->isHTML(true);
    $mail->Subject = 'Unfortunately all lockers fully booked for this Grade';
    $mail->Body    = '<h3>There are no lockers left for your students grade. You will remain on the waiting list until a locker becomes available</h3>';
    $mail->AltBody = 'There are no lockers left for your students grade. You will remain on the waiting list until a locker becomes available';

    $mail->send();
    echo 'Email Message Sent to parent: ';
    echo $parentEmail;
    echo "<br>";
    echo "<br>";
} catch (Exception $e) {
    //the below echos an error message if the email is not sent, helpful for debugging
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
//----------------------------------------------------------------------------------------------
// THE INFO TO EMAIL TO IS HERE 
//------------------------------------------------------------------------------------------------

} catch (PDOException $e) {
    // This last line displays an error message if the connection fails to give helpfull information to the user c
    echo "Database connection failed: " . $e->getMessage();
  }
}
//-----------------------------------------------------------------------------------------------------------
//END OF THE lockers full button script
//-------------------------------------------------------------------------------------------------------------


/*-------------------------------------------------------------------------------------------------------- 
Below is all the code for if the "yes book" locker button is pressed
-----------------------------------------------------------------------------------------------------------*/
if(isset($_POST['paymentMade'])) {
    // the below variables provide the credentials for the PDO connection to the database c
  $StudentSchoolNumber = $_GET['StudentNumber'];
  $host = 'localhost';
  $dbname = 'assessment2lockers';
  $username = 'root';
  $dbpassword = '';      


  
  try {
    // The line below is to create a new instance of the PDO c
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $dbpassword);
    // The below line of code is to set the error mode c
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* following code Inserts the student into the successful bookings table */
$sql3 = "INSERT INTO lockersuccessfulbookings (StudentSchoolNumber, LockerNumber) 
            VALUES (?, ?)";
    $stmt3 = $pdo->prepare($sql3);
//This executes the data into the successfull bookings table c
    $stmt3->execute([$StudentSchoolNumber, $LockerNumber]);

/* following code removes the student from the waiting list as they are moved to the successfull bookings table */
    $sql4 = "DELETE FROM waitinglistparents WHERE `waitinglistparents`.`StudentSchoolNumber` = $StudentSchoolNumber";
    $stmt4 = $pdo->prepare($sql4);

    //This executes the data into the waitinglistparents table c
    $stmt4->execute();

/*section here to get the locker booking id FROM SUCCESSFULL BOOKINGS TABLE and put it INTO TH BOOKING ID OF THE LOCKERS TABEL 
LockerNumber
-------------------------------------------------------------------------------------------------------------------------------------*/
    $sql6 = "SELECT * FROM lockersuccessfulbookings WHERE StudentSchoolNumber = :StudentSchoolNumber";
    $stmt6 = $pdo->prepare($sql6);

// The 1 line below binds the :StudentSchoolNumber
    $stmt6->bindParam(':StudentSchoolNumber', $StudentSchoolNumber, PDO::PARAM_STR);
// this next line Executes the query c
$stmt6->execute();
$row3 = $stmt6->fetch(PDO::FETCH_ASSOC);
//THE FOLLOWING LINE IS USED TO GET THE LOCKER BOOKING ID FROM THE lockersuccessfulbookings TABLE, TO AD TO THE lockers table as a FK
$LockerBookingID = $row3['LockerBookingID'];

/* following sql updates the locker table to ensure the locker status is set to booked correctly, AND UPDATES THE LOCKER BOOKING ID TO MATCH THE success table*/
    $sql5 = "UPDATE lockers SET LockerBookedStatus = 1, LockerBookingID = $LockerBookingID WHERE LockerNumber = $LockerNumber";
    $stmt5 = $pdo->prepare($sql5);

    //This executes the data into the waitinglistparents table c
    $stmt5->execute();
//If all correct a message apears that the student has been moved to successfull bookings table c
    echo "<h2>Student moved to Successfull Booking Table</h2>";

/* EMAIL CODE Starts HERE BELOW!!!!!!---------------------------------------------------------------------------------- */

// THE BELOW CODE GETS THE EMAIL ADRESS FOR THE PARENT TO SENT THE WAITING LIST INFO TO THEM
$sql7 = "SELECT * FROM parentaccount WHERE ParentIdNumber = $ParentIdNumber";
    $stmt7 = $pdo->prepare($sql7);
// this next line Executes the query c
        $stmt7->execute();
$row7 = $stmt7->fetch(PDO::FETCH_ASSOC);
 $parentEmail = $row7['ParentEmailAddress'];

 // THE BELOW CODE GETS THE EMAIL ADRESS FOR THE ADMIN TO SEND THE WAITING LIST INFO TO THEM
$sql8 = "SELECT * FROM administratoraccount WHERE AdminID = $AdminID";
    $stmt8 = $pdo->prepare($sql8);
// this next line Executes the query c
        $stmt8->execute();
$row8 = $stmt8->fetch(PDO::FETCH_ASSOC);
 $adminEmail = $row8['EmailAddress'];
  
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
    $mail->Subject = 'Locker successfully booked';
    $mail->Body    = '<h3>Your child has a locker booked</h3>';
    $mail->AltBody = 'Your child has a locker booked';

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

} catch (PDOException $e) {
    // This last line displays an error message if the connection fails to give helpfull information to the user c
    echo "<h3>Student does already hava a booking</h3><br>";
    echo "Database connection failed: " . $e->getMessage();
  }
}
/*-------------------------------------------------------------------------------------------------------- 
end of all SQL on this page
-----------------------------------------------------------------------------------------------------------*/
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
<br>
<div class="button-container">
<button class="label" onclick="window.location.href='adminWaitingListManagement.php'">
Go Back to Admin Waiting List Management
</button>
<!-- Navigation Button to go to the main REPORTS for the website-->
<br>
<br>
<div class="button-container">
<button class="label" onclick="window.location.href='adminDashboard.php'">
Go back to Admin Dashboard
</button>


</div>