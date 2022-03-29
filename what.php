<?php 
    include "php/head.php"; 
?>
<script src="scripts/what.js" type="text/javascript"></script>
<?php include "php/header.php"; ?>

<div class="text-center load-save">
    
    <div id="btnGenerate" class="div-button margin-left-5" title="Combine">
        <img src="images/btn-generate.png" />
    </div>
    <?php if(isset($_SESSION["username"])){ ?>
        <div id="btnDraw" class="div-button" title="Draw">
            <img src="images/btn-draw.png" />
        </div>
        <div id="btnList" class="div-button margin-left-5" title="Your creatures">
            <img src="images/btn-list.png" />
        </div>
    <?php } ?>
    <div id="btnLogout" class="div-button margin-left-5" title="Logout">
        <img src="images/btn-exit.png" />
    </div>
</div>

<div class="margin-top-10 div-center text-center font-big">
    WHAT IS THIS???
</div>

<div class="margin-top-10 font-med-small width-80pct margin-auto">
    Pixel Creatures is a small web app developed with:
    <div class="text-green font-med-small ">
        > HTML5 and CSS with Responsive Design
        <br/>
        > JavaScript
        <br/>
        > JQuery
        <br/>
        > REST API in PHP
        <br/>
        > MySQL Database
        <br/>
    </div>
    It was mainly created to demonstrate some of my programming and design skills, but
    ended up growing to become a fun (hopefully!) little page where you can create... well, pixel creatures!
</div>
<div class="margin-top-10 font-med-small width-80pct margin-auto">
    By <a href="login.php">registering and logging in</a>, you can use the drawing grid to create your creatures. By drawing each part of the creature
    and placing the anchors accordingly, a walking animation loop will be applied to the creatue. Cute!
</div>
<div class="margin-top-10 font-med-small width-80pct margin-auto">
    But the fun doesn't stop there! Using all the creatures created by all the users, you can <a href="view.php">mix them up and create new and hilarious creatures</a>.
    Do it at random, or searching and picking the ones you want, and then share the result with your friends! 
</div>
<div class="margin-top-10 font-med-small width-80pct margin-auto">
    If you have any feedback, suggestions or bug/error reports, please contact me: <a href="mailto:daniel.moises.gc@gmail.com">daniel.moises.gc@gmail.com</a>.
</div>
  
<?php include "php/footer.php"; ?>