<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- THIS PAGE IS HOW THE PARENT CREATES A STUDENT OR CHILD
adapted the code from the create a parent account page to make this page c -->
<?php session_start();
// The bellow 1 line saves the $ParentIDNumber variable as the ID entered by the user on the previouse pages session to ensure its the correct person logged in c
$AdminID = $_SESSION['AdminID'];
?>
    <title>Add a child/student</title>
    <!-- IMPORTANT all these LINES ARE ALL JUST CSS STYLES AS ON PREVIOUSE PAGE c-->
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
          transform: translate(-10%) scale(1.1); /* this Scales the button to 110% (10% larger) for style rc*/
        }
              .infoWindows2{
            background-color: lightpink;
            padding: 1%;
            text-align: center;
        }
    /*END of CSS styles c */
    </style>
</head>
<body>
      <!-- Below is a div fir the background video c -->
<div class='section'>
<video autoplay loop muted id="bgvid">
<source src="bgvidchems.mp4">
</video>
</div>
<!-- BELOW CODE IS just the text on the screen instructing the parent what to do c -->
<div class="button-container">
<h2>Add a child/student</h2>
<h3>Please enter your childs details</h3>
<!-- Below CODE IS the FORM to gather child info inserted by the parent into the form c -->
 <form method="POST">
1 Student Number: <input type="text" name="StudentSchoolNumber"><br><br>
2 Student Name: <input type="text" name="StudentName"><br><br>
3 Student Surname: <input type="text" name="StudentSurname"><br><br>
4 <label for="students">Student Grade:</label>
<select id="StudentGrade" name="StudentGrade">
  <option value="Grade 8">Grade 8</option>
  <option value="Grade 9">Grade 9</option>
  <option value="Grade 10">Grade 10</option>
  <option value="Grade 11">Grade 11</option>
  <option value="Grade 12">Grade 12</option>
</select><br><br>
<input type="submit" value="Add child">
 </form>
</div>
<br>
<br>
<br>
<br>
<?php
// this if checks if the form was submitted and then posts the form c
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Gets the values from the input the parent entered about the student/child and enters the info into variables to use later c
$StudentSchoolNumber = $_POST['StudentSchoolNumber'];
  $StudentName = $_POST['StudentName'];
  $StudentSurname = $_POST['StudentSurname'];
  $StudentGrade = $_POST['StudentGrade'];

// The line below uses the global session variabl $ParentIDNumber for local scope here so the parent does not have to enter this manually c
 $ParentIdNumber = $_GET['ParentIdNumber'];


//BELOW IS WHERE FORM VALIDATION STARTS---------------------------------------------------------
echo "<div class=infoWindows2>";

/*DEBUGGING---------------------------------------------------------------------------------------
echo "$StudentSchoolNumber";//sorted
echo "<br>";
echo "$StudentName";
echo "<br>";
echo "$StudentSurname";  
echo "<br>";
echo "$StudentGrade";
echo "<br>";
echo "$ParentIdNumber";
------------------------------------------------------------------------------------------------------*/


//THE BELOW IS FOR THE FORM VALIDATION
$StudentSchoolNumberSTATUS= false;
$StudentNameSTATUS= false; 
$StudentSurnameSTATUS= false; 


//Student number validation bellow
$StudentSchoolNumberLength = strlen($StudentSchoolNumber);
$StudentSchoolNumberIsNumber = is_numeric($StudentSchoolNumber);
if ($StudentSchoolNumberIsNumber) {
    if ($StudentSchoolNumberLength == 6 && $StudentSchoolNumberIsNumber) {
    $StudentSchoolNumberSTATUS = true;
    }
    else {
  echo "Student number NOT VALID";
  echo "<br>";
  echo "<br>";
    }
}
else
{
echo "Student Number is NOT A NUMBER";
  echo "<br>";
  echo "<br>";
}


//Student First Name validation bellow 
if (empty($StudentName)) {
 echo "Student First Name is Empty";
 $StudentNameSTATUS = false;
  echo "<br>";
  echo "<br>";
}
else
{
      if (!preg_match("/^[a-zA-Z-' ]*$/",$StudentName)) {
      echo "Only letters and white space allowed in first name";
      $StudentNameSTATUS = false;
      echo "<br>";
      echo "<br>";
        }
      else {
      $StudentNameSTATUS = true;
    }
}


//Student Surname validation bellow 
if (empty($StudentSurname)) {
 echo "Student Surname is Empty";
 $StudentSurnameSTATUS = false;
  echo "<br>";
  echo "<br>";
}
else
{
      if (!preg_match("/^[a-zA-Z-' ]*$/",$StudentSurname)) {
      echo "Only letters and white space allowed in Surname";
      $StudentSurnameSTATUS = false;
      echo "<br>";
      echo "<br>";
        }
      else {
      $StudentSurnameSTATUS = true;
    }
}


/*DEBUGGING---------------------------------------------------------------------------------------
//REMOVE IF ALL GOOD-------------------------------
echo "<br>";
echo "Student School Number STATUS: ";
var_dump($StudentSchoolNumberSTATUS);
echo "<br>";
//--------------------------------------------------
//REMOVE IF ALL GOOD-------------------------------
echo "<br>";
echo "Student First Name STATUS: ";
var_dump($StudentNameSTATUS);
echo "<br>";
//--------------------------------------------------
//REMOVE IF ALL GOOD-------------------------------
echo "<br>";
echo "Student Surname STATUS: ";
var_dump($StudentSurnameSTATUS);
//--------------------------------------------------
-----------------------------------------------------------------------------------------------------------*/


if ($StudentSchoolNumberSTATUS && $StudentNameSTATUS && $StudentSurnameSTATUS) {

  // the below variables provide the credentials for the PDO connection to the database c
  $host = 'localhost';
  $dbname = 'assessment2lockers';
  $username = 'root';
  $dbpassword = '';      

  try {
    // The line below is to create a new instance of the PDO c
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $dbpassword);
    // The below line of code is to set the error mode to: exception c
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //the bellow code inserts the student/child info into the correct studentchild database table c
    $sql = "INSERT INTO studentchild (StudentSchoolNumber, ParentIdNumber, StudentName, StudentSurname, StudentGrade) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    //This executes the data into the studentchild table c
    $stmt->execute([$StudentSchoolNumber, $ParentIdNumber, $StudentName, $StudentSurname, $StudentGrade]);
    
    // If all is a success the next line automatically takes the parent BACK TO the parent dashboard and the child child they added should display c
    header("Location: adminOneParentDashboard.php?ParentIdNumber=$ParentIdNumber");
  } catch (PDOException $e) {
    // This last line displays an error message if the connection to the database fails c
    echo "This student number already exists<br>";
    echo "Database connection failed: " . $e->getMessage();
  }

//Below triggers if form data not valid
} else {
  echo "<br>";
  echo "Check for User input errors";
}
echo "</div>";

}
?>
<div class="button-container">
<?php  
$ParentIdNumber = $_GET['ParentIdNumber'];
echo "<button class='label' onclick=\"window.location.href='adminOneParentDashboard.php?ParentIdNumber=$ParentIdNumber'\">Back to Parent Dashboard</button>";
?>
</div>
</body>
</html>