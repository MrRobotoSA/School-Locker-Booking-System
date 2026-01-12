<!DOCTYPE html>
<html>
<head>
<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden;
    }

    /* Fullscreen video */
    #bg-video {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1; /* Behind page content */
    }


  .center-box {
    position: fixed;                  
    top: 50%;                         
    left: 50%;                        
    transform: translate(-50%, -75%); 

    width: 50vw;     /* 50% of screen width */
    height: 50vh;    /* 50% of screen height */

    background-color: lightcyan;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);

    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    box-shadow: 
    0 4px 8px rgba(0, 0, 0, 0.2),     /* soft shadow */
    0 10px 20px rgba(0, 0, 0, 0.15),  /* deeper shadow */
    inset 0 2px 4px rgba(255, 255, 255, 0.6), /* top shine (3D effect) */
    inset 0 -2px 6px rgba(0, 0, 0, 0.2);      /* bottom depth (3D effect) */
  }
</style>
</head>
<body>

<!-- Background Video -->
<video id="bg-video" autoplay loop muted playsinline>
    <source src="ocean.mp4" type="video/mp4">
</video>

<!-- Page Content -->
<div class="center-box">
    Your Website Text Here
</div>

</body>
</html>