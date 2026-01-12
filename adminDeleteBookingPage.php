<!-- The following file is the Delete booking page, to delete students from the waiting list -->
 <!-- The following are css styles as used before-->
<style>
h1 {
    text-align: center;
    padding: 0;
    font-size: 300%;
    }
        
    h3, h2, p, button, form {
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
</style>
</head>
<body>
<!-- This is the video that plays in the background of the page on loop c -->
<div class='section'>
 <video autoplay loop muted id="bgvid">
 <source src="bgvidchems.mp4">
</video>
</div>
<h1>Delete your booking</h1>
<h2>Are you sure you want to delete your locker booking</h2>
<form method="post">
<button class='label' type="submit" name="buttonDeleteBooking">Yes Delete</button>
</form>
</body>
<!-- The following php scrip runs is the button if clicked if deletes the booking for the student in the waitinglist table 
        This code was adapted from the create booking page-->
<?php 
session_start();
$studentNumberToCarry = $_GET['StudentNumber'];
//Following 3 lines to see if the variables were carried over correctly 
echo "<h3>For Student number: " . $studentNumberToCarry . " </h3>";
echo "<br>";
echo "<h3><button class='label' onclick=\"window.location.href='adminBookingManagementPage.php?StudentNumber=$studentNumberToCarry'\">Go back</button></h3>";
//following code will execute when the user clicks the button, it deletes the student's booking from the waiting list
if(isset($_POST['buttonDeleteBooking'])) {
    // the below variables provide the credentials for the PDO connection to the database c
  $host = 'localhost';
  $dbname = 'assessment2lockers';
  $username = 'root';
  $dbpassword = '';      

  try {
    // The line below is to create a new instance of the PDO c
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $dbpassword);
    // The below line of code is to set the error mode c
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* following sql code was adapted from a SQL statemnt i got in php my admin when i clicked the delete row button
DELETE FROM waitinglistparents WHERE `waitinglistparents`.`WaitingListNumber` = 45" */
    $sql = "DELETE FROM waitinglistparents WHERE `waitinglistparents`.`StudentSchoolNumber` = $studentNumberToCarry";
    $stmt = $pdo->prepare($sql);

    //This executes the data into the waitinglistparents table c
    $stmt->execute();
    
//If all is a success the next line creates a button that if clicked takes the USER BACK TO the bookingManagementPage c
    echo "<h2>Booking deleted, click the Go Back button</h2>";
} catch (PDOException $e) {
    // This last line displays an error message if the connection fails to give helpfull information to the user c
    echo "Database connection failed: " . $e->getMessage();
  }
}
?>