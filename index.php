<!DOCTYPE html>
<html lang="en">
<head>
<!-- The whole page here is the HOME PAGE OF THE WHOLE SYSTEM (the first screen all users need to see) c-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amandla highschool Home</title>
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
<!-- THE BELOW div is to have the background video for style purposes c-->
<div class='section'>
<video autoplay loop muted id="bgvid">
<source src="bgvidchems.mp4">
</video>
</div>
    <!-- The rest of the code on this page is: 
        1) Just the text on the screen
        2) The buttons that link to the different pages needed by the application c -->
<h1>Welcome to the Amandla Highschool Locker Booking system</h1>

<!-- Below you can see the button container, this class="button-container is making all the buttons center and under eachother linking with above css style" c-->
 <div class="button-container">
<!-- Navigation Button to go to the main login page for the website c-->
 <button class="label" onclick="window.location.href='loginParentPage.php'">
 Go to Parent Login Page
 </button>
<br>
</div>
<br>

<div class="button-container">
<!-- Navigation Button to go to the CREATE ACCOUNT page for the APP c-->
<button class="label" onclick="window.location.href='createParentAccountWithValidation.php'">
Create Valid Parent Account 
</button>
<br>
</div>
<br>

<div class="button-container">
<!-- Navigation Button to go to the ADMIN LOGIN page for the APP c-->
<button class="label" onclick="window.location.href='loginAdminPage.php'">
Admin Login
</button>
<br>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
</div>
</body>
</html>

