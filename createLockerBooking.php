<!-- IMPORTANT about page: This page is used for the user to click a button to add a booking to the waiting list, 
1)it sends an email to the admin, and the parent (NOT WORKING!)
2)adds the student and the parent to the waitinglistparent table in the database as one row
3)a unique constraint on the StudentSchoolNumber column in the waitinglistparents table  is used to ensure a student cant make more than one booking
-->
<style>
h1 {
    text-align: center;
    padding: 0;
    font-size: 300%;
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
        transform: translate(-1%) scale(1.1); /*scales the btns make them 10% larger c */
        }
        .infoWindows{
            background-color: lightcyan;
            padding: 1%;
        }
</style>
<body>
<!-- This is the video that plays in the background of the page on loop, PERHAPS REMOVE TO AID WITH VIEWING INFO, - c -->
<div class='section'>
 <video autoplay loop muted id="bgvid">
 <source src="bgvidchems.mp4">
</video>
</div>
<!-- The following 8 lines are HTML code that gives the user instructions on what to do on this page-->
<h1>Welcome to the Create a booking page</h1>
<div class="button-container">
<h2>Instructions: Simply click the button to add a lockerbooking</h2>
<h3>You will add the following parent ID and child ID to the waiting list, and will recieve an email to confirm this</h3>
<br>
<div class="infoWindows">
<p>Child Student Number: <?php echo $studentNumberToCarry = $_GET['StudentNumber'];?>
<p>Parent ID Number: <?php echo $ParentIdNumber = $_GET['ParentIdNumber'];?>
<p>All parents booking for themselves will send emails to Admin ID: 1</p>
<h3>Your Assigned Admin ID: 1</h3>
<!-- The following is HTLM code to create a button post method the user clicks to add a locker booking to the waiting list-->
<form method="post">
<button class="label" type="submit" name="buttonAddToWatingList">Create locker booking</button>
</form>
</body>
<!-- The following is the php script that runs when the parent clicks the button and has all the needed info-->
<?php 
/*The following two variables store the session variables into local variables for use on the page */
$studentNumberToCarry = $_GET['StudentNumber'];
$ParentIdNumber = $_GET['ParentIdNumber'];

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


//following code will execute when the user clicks the button, it adds the student and parrent details to the waiting list for the admin to later approve the locker booking 
if(isset($_POST['buttonAddToWatingList'])) {
    //REMOVE THE FOLOWING LINE TO TEST IF BUTTON WAS CLICKED

    
/* THE FOLLOWING CODE INSERTS ALL THE NEEDED INFO INTO THE waitinglistparent table, it also gets a admin ID to use in the table
REMOVE THE TO DO'S LATER WHEN ALL DONE (email feature not working)
TO DO:  1) get the adminNumber to use
        2) get the admin email adress to use
        3) insert all info into the waiting list
*/
// the below variables provide the credentials for the PDO connection to the database c
  $host = 'localhost';
  $dbname = 'assessment2lockers';
  $username = 'root';
  $dbpassword = '';      

  try {
    // The line below is to create a new instance of the PDO to connect to the database c
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $dbpassword);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//The three variables bellow are set to be used in the SQL insert statment next
$WaitingForPayment = 1;
$WaitingForLocker = 1;
$AdminID = 1;
//the bellow code inserts the needed info into the waitinglistparents table c
    $sql = "INSERT INTO waitinglistparents (ParentIdNumber, WaitingForPayment, WaitingForLocker, AdminID, StudentSchoolNumber) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    //This executes the data into the waitinglistparents table c
    $stmt->execute([$ParentIdNumber, $WaitingForPayment, $WaitingForLocker, $AdminID, $studentNumberToCarry]);
    echo "Placed on waiting list successfully. ";

/* EMAIL CODE Starts HERE BELOW!!!!!!---------------------------------------------------------------------------------- */

// THE BELOW CODE GETS THE EMAIL ADRESS FOR THE PARENT TO SENT THE WAITING LIST INFO TO THEM
$sql2 = "SELECT * FROM parentaccount WHERE ParentIdNumber = $ParentIdNumber";
    $stmt2 = $pdo->prepare($sql2);
// this next line Executes the query c
        $stmt2->execute();
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
 $parentEmail = $row2['ParentEmailAddress'];

 // THE BELOW CODE GETS THE EMAIL ADRESS FOR THE ADMIN TO SEND THE WAITING LIST INFO TO THEM
$sql3 = "SELECT * FROM administratoraccount WHERE AdminID = $AdminID";
    $stmt3 = $pdo->prepare($sql3);
// this next line Executes the query c
        $stmt3->execute();
$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
 $adminEmail = $row3['EmailAddress'];
  
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
    $mail->Subject = 'Parent added to Waiting List';
    $mail->Body    = '<h3>New Parent Added to wating list</h3>';
    $mail->AltBody = 'New Parent Added to wating list';

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

//If all is a success the next line automatically takes the USER BACK TO the bookingManagementPage
     echo "<h3><button class='label' onclick=\"window.location.href='bookingManagementPage.php?StudentNumber=$studentNumberToCarry'\">Click to manage this booking</button></h3>";

} catch (PDOException $e) {
  echo "You already have a locker booked<br>";
    // This last line displays an error message if the connection fails to give helpfull information to the user c
    echo "Database connection failed: " . $e->getMessage();
    echo "<h3><button class='label' onclick=\"window.location.href='bookingManagementPage.php?StudentNumber=$studentNumberToCarry'\">Click to manage this booking</button></h3>";
  }
}
 echo "<h3><button class='label' onclick=\"window.location.href='studentDashboard.php?StudentNumber=$studentNumberToCarry'\">Go Back To Student Dashboard</button></h3>";
?>
</div>
</div>