<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- IMPORTANT: This page is used to display that the parent account was successfully created c-->
    <title>Parent User account successfully created</title>
<!-- Again the CSS Styles bellow are the same on most pages and copied over to here c-->
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
         /* This is a Flex container for buttons c */
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
            border-radius: 15px; /* roundness of buttons c */
            transition: background-color 500ms, transform 1s;
        }
        
        .label:hover {
            background-color: rgb(0, 226, 226);
            transform: translate(-10%) scale(1.1); /* Scales the button to 110% (10% larger) c */
        }
    </style>
</head>
<body>
    <!-- below is the background video for style  c -->
<div class='section'>
<video autoplay loop muted id="bgvid">
<source src="bgvidchems.mp4">
</video>
</div>

<!-- The bellow PHP script saves the $ParentIDNumber variable as the ID entered by the user on the previouse pages session to ensure its the correct person logged in c --> 
<?php
session_start();
 $ParentIdNumber = $_SESSION['ParentIdNumber'];
$AdminID = $_SESSION['AdminID'];
?>

    <!-- the below is just for text on the screen showing the parent the account was created successfully c-->
    <h1><?php echo $ParentIdNumber;?> ID number Successfully created a user account</h1>


<!-- Below you can see the button container, this class="button-container is making all the buttons center and under eachother c-->
<div class="button-container">

<!-- Navigation Button to go to the parent login page section for the website c-->
<button class="label" onclick="window.location.href='adminDashboard.php'">
Go to Admin Dashboard
</button>
<br>
   </div>
</body>
</html>