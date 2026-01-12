<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "assessment2lockers";

//This line below is to keep track of if all the questions in the database have been answered
$quizComplete = false;
if (isset($_POST['resetTries'])) {
    $_SESSION['triesLeft'] = 2;
}
// The lines below are to keep track of the number of tries left
if (!isset($_SESSION['triesLeft'])) {
    $_SESSION['triesLeft'] = 2;
}

$triesLeft = $_SESSION['triesLeft'];

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the first question ID that appears in the database
$sql1 = "SELECT QuestionID, QuestionText, answerA, answerB, answerC, answerD, correctAnswer, videoURL 
        FROM QuestionsTrial 
        ORDER BY QuestionID ASC
        LIMIT 1";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    $row1 = $result1->fetch_assoc();

    $firstQuestion = $row1['QuestionID'];
}


// Determine which question to show
$currentID = intval($_POST['currentID'] ?? $firstQuestion);
$message = "";

// If user clicked "Next Question"
if (isset($_POST['nextQuestion'])) {

    // Reset tries for new question
    $_SESSION['triesLeft'] = 2;

    // Fetch the question from DB
$sql = "SELECT QuestionID, QuestionText, answerA, answerB, answerC, answerD, correctAnswer, videoURL 
        FROM QuestionsTrial 
        WHERE QuestionID > $currentID
        ORDER BY QuestionID ASC
        LIMIT 1";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $QuestionID     = $row['QuestionID'];
    $currentID = $QuestionID;
}
else{
    $quizComplete = true;
}
    
}

// If user submitted an answer
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['answer'])) {
    if ($_POST['answer'] === ($_POST['correctAnswer'] ?? '')) {
        $message = "<p style='color:green;'>Correct! Well done.</p>";
    } else {
        $message = "<p style='color:red;'>Incorrect. You only have 1 more try.</p>";

        //The 2 lines below are to decrease the number of tries left by 1 if the answer is incorrect
        $_SESSION['triesLeft']--;
        $triesLeft = $_SESSION['triesLeft'];

        // stay on same question
        $currentID = intval($_POST['currentID']);
    }
}

echo "<h3>Tries Left: " . $triesLeft . "</h3>";

// Fetch the question from DB
$sql = "SELECT QuestionID, QuestionText, answerA, answerB, answerC, answerD, correctAnswer, videoURL 
        FROM QuestionsTrial 
        WHERE QuestionID = $currentID
        LIMIT 1";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0 && $quizComplete === false && $triesLeft > 0) {
    $row = $result->fetch_assoc();

    $QuestionID     = $row['QuestionID'];
    $QuestionText   = $row['QuestionText'];
    $answerA        = $row['answerA'];
    $answerB        = $row['answerB'];
    $answerC        = $row['answerC'];
    $answerD        = $row['answerD'];
    $correctAnswer  = $row['correctAnswer'];
    $videoURL       = $row['videoURL'];

    // Convert YouTube URL to embed URL
    $embedURL = "";
    if (preg_match("/v=([a-zA-Z0-9_-]+)/", $videoURL, $matches)) {
        $embedURL = "https://www.youtube.com/embed/" . $matches[1];
    }
} 
elseif ($triesLeft === 0) {
    $message = "<H1 style='color:red'>Game Over! You've run out of tries.</p>";
    $QuestionText = $answerA = $answerB = $answerC = $answerD = $correctAnswer = $embedURL = "";
    echo '
<form method="post">
    <button type="submit" name="resetTries">Reset Tries</button>
</form>';
}
else {
    $message = "<H1 style='color: green;'>All questions completed!</H1>";
    $QuestionText = $answerA = $answerB = $answerC = $answerD = $correctAnswer = $embedURL = "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Page</title>
</head>
<body>



<!-- Question Form -->
<?php if ($QuestionText): ?>
<form method="post">
    <h1>Question: </h1>
    <h2><?php echo htmlspecialchars($QuestionText); ?></h2>

    <label>
        <input type="radio" name="answer" value="answerA" required>
        <?php echo htmlspecialchars($answerA); ?>
    </label><br>

    <label>
        <input type="radio" name="answer" value="answerB">
        <?php echo htmlspecialchars($answerB); ?>
    </label><br>

    <label>
        <input type="radio" name="answer" value="answerC">
        <?php echo htmlspecialchars($answerC); ?>
    </label><br>

    <label>
        <input type="radio" name="answer" value="answerD">
        <?php echo htmlspecialchars($answerD); ?>
    </label><br><br>

    <!-- Hidden inputs to remember current question and correct answer -->
    <input type="hidden" name="currentID" value="<?php echo $QuestionID; ?>">
    <input type="hidden" name="correctAnswer" value="<?php echo htmlspecialchars($correctAnswer); ?>">

    <button type="submit">Submit</button>
</form>

<?php endif; 
echo "<br>"?>

<?php 
echo $message;
if ($message && $message === "<p style='color:green;'>Correct! Well done.</p>") {
    echo '<form method="post">
        <input type="hidden" name="currentID" value="' . $currentID . '">
        <button type="submit" name="nextQuestion">Next Question</button>
    </form>';
    $_SESSION['triesLeft'] = 2;
}
echo "<br>";
?>
<!-- Video -->
<?php if ($embedURL): ?>
<iframe width="100%" height="400" src="<?php echo htmlspecialchars($embedURL); ?>" frameborder="0" allowfullscreen></iframe>
<?php endif; ?>

<!-- Remove tgis comment to make a debugging reset tries button appear


<form method="post">
    <button type="submit" name="resetTries">Reset Tries</button>
</form>


-->
</body>
</html>