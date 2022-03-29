<?php 
    include "php/head.php"; 
    include "php/login_required.php"; 
?>
<script src="scripts/list.js" type="text/javascript"></script>
<?php include "php/header.php"; ?>

<div class="text-center load-save">
    
    <div id="btnDraw" class="div-button" title="Draw">
        <img src="images/btn-draw.png" />
    </div>
    <div id="btnGenerate" class="div-button margin-left-5" title="Combine">
        <img src="images/btn-generate.png" />
    </div>
    <div id="btnWhat" class="div-button margin-left-5" title="What is this??">
        <img src="images/btn-question.png" />
    </div>
    <div id="btnLogout" class="div-button margin-left-5" title="Logout">
        <img src="images/btn-exit.png" />
    </div>
</div>

<div class="text-center font-med-small margin-top-10 margin-auto width-80pct ">
    Here you can view all creatures you created. New creatures will be in a pending state until approval. Rejected creatures can be edited, for atempting a new approval.
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