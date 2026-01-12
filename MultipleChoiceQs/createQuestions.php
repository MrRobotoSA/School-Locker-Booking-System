<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "assessment2lockers";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//THE BELOW LINES OF CODE FIND THE LAST ROWS ID SO THAT THE QUESTION ADDED NEXT IS ALWAYS THE NEXT ID NUMBER AND KEEPS THE SEQUENCE OF QUESTIONS GAP FREE
$message = "";
$result = $conn->query("SELECT QuestionID FROM QuestionsTrial ORDER BY QuestionID DESC LIMIT 1");
$row = $result->fetch_assoc();
$lastQuestionID = $row['QuestionID'];
echo $lastQuestionID;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $QuestionText   = $_POST['QuestionText'];
    $answerA        = $_POST['answerA'] ?: null;
    $answerB        = $_POST['answerB'] ?: null;
    $answerC        = $_POST['answerC'] ?: null;
    $answerD        = $_POST['answerD'] ?: null;
    $correctAnswer  = $_POST['correctAnswer'];
    $videoURL       = $_POST['videoURL'] ?: null;

    $stmt = $conn->prepare("
        INSERT INTO QuestionsTrial 
        (QuestionText, answerA, answerB, answerC, answerD, correctAnswer, videoURL)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssssss",
        $QuestionText,
        $answerA,
        $answerB,
        $answerC,
        $answerD,
        $correctAnswer,
        $videoURL
    );

    if ($stmt->execute()) {
        $message = "Question added successfully.";
    } else {
        $message = "Error adding question.";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
</head>
<body>

<h1>Add New Question</h1>

<?php if ($message) echo "<p>$message</p>"; ?>

<form method="post">
    <label>Question Text</label><br>
    <textarea name="QuestionText" required></textarea><br><br>

    <label>Answer A</label><br>
    <input type="text" name="answerA"><br><br>

    <label>Answer B</label><br>
    <input type="text" name="answerB"><br><br>

    <label>Answer C</label><br>
    <input type="text" name="answerC"><br><br>

    <label>Answer D</label><br>
    <input type="text" name="answerD"><br><br>

    <label>Correct Answer</label><br>
    <select name="correctAnswer" required>
        <option value="answerA">Answer A</option>
        <option value="answerB">Answer B</option>
        <option value="answerC">Answer C</option>
        <option value="answerD">Answer D</option>
    </select><br><br>

    <label>Video URL</label><br>
    <input type="text" name="videoURL"><br><br>

    <button type="submit">Add Question</button>
</form>

</body>
</html>