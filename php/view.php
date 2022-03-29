<?php 
    include "php/head.php"; 
    include "php/login_required.php"; 
?>
<script src="scripts/view.js" type="text/javascript"></script>
<?php include "php/header.php"; ?>

<div class="text-center load-save">
    
    <div id="btnDraw" class="div-button">
        <img src="images/btn-draw.png" />
    </div>
    <div id="btnList" class="div-button margin-left-5">
        <img src="images/btn-list.png" />
    </div>
    <div id="btnLogout" class="div-button margin-left-5">
        <img src="images/btn-exit.png" />
    </div>
</div>

<div id="divView" class="div-center large margin-top-10">
    <div id="divName" class="load-view margin-top-10 text-center font-big">
    </div>
    
    <div class="div-view load-view">
        <canvas id="cnvMain" class="canvas-view"></canvas> 
    </div>

    <div class="load-view div-center text-center margin-top-10">
        <div id="btnSave" class="div-button width-90">
            SAVE
        </div>
    </div>
    <div class="load-view div-center text-center margin-top-10">
        <div id="btnShare" class="div-button width-90">
            SHARE
        </div>
    </div>

    
</div>

  
<?php include "php/footer.php"; ?>