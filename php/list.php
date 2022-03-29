<?php 
    include "php/head.php"; 
    include "php/login_required.php"; 
?>
<script src="scripts/list.js" type="text/javascript"></script>
<?php include "php/header.php"; ?>

<div class="text-center load-save">
    
    <div id="btnDraw" class="div-button">
        <img src="images/btn-draw.png" />
    </div>
    <div id="btnGenerate" class="div-button margin-left-5">
        <img src="images/btn-generate.png" />
    </div>
    <div id="btnLogout" class="div-button margin-left-5">
        <img src="images/btn-exit.png" />
    </div>
</div>

<div class="div-center large margin-top-10">
    
    <div id="divList" class="div-center margin-top-10">
        
    </div>

    <div id="divLoad" class="div-center margin-top-10">
    </div>
    
</div>

<div id="divItemTemplate" class="display-none">
    <div class="div-list-item">
        <div class="div-item-canvas">
            <canvas class="canvas-preview small"></canvas>
        </div>
        <div class="div-item-name text-center">
            <span class="span-name">Name</span>
            <span class="span-approved">Approved</span>
        </div>
        <div class="div-item-button">
            <div class="div-button">
                EDIT
            </div>
        </div>
    </div>
</div>
  
<?php include "php/footer.php"; ?>