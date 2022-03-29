<?php 
    include "php/head.php"; 
    include "php/login_required.php"; 
?>
<script src="scripts/main.js" type="text/javascript"></script>
<?php include "php/header.php"; ?>

<div class="text-center load-save">
    
    <div id="btnGenerate" class="div-button">
        <img src="images/btn-generate.png" />
    </div>
    <div id="btnList" class="div-button margin-left-5">
        <img src="images/btn-list.png" />
    </div>
    <?php if($_SESSION["admin"] == 1){ ?>
        <div id="btnCheck" class="div-button margin-left-5">
            <img src="images/btn-check.png" />
        </div>
    <?php } ?>
    <div id="btnLogout" class="div-button margin-left-5">
        <img src="images/btn-exit.png" />
    </div>
</div>

<div id="divName" class="div-center">
    <input type="text" id="txtName" class="jqx load-save"/>
</div>
<div id="divAuthor" class="div-center display-none load-save">
    <label class="label-author"></label>
</div>

<div class="div-center large">
    <div class="div-side float-left text-center load-save">
        <div class="text-center margin-top-10">
            SELECT PART
        </div>
        <div id="btnHead" class="div-button sel button-body-part margin-top-10">
            <img src="images/btn-face.png" />
        </div>
        <br/>
        <div id="btnRightArmMirror" class="div-button button-mirror">
            <img src="images/btn-mirror-right.png" />
        </div>
        <div id="btnRightArm" class="div-button button-body-part">
            <img src="images/btn-right-arm.png" />
        </div>
        <div id="btnBody" class="div-button button-body-part">
            <img src="images/btn-body.png" />
        </div>
        <div id="btnLeftArm" class="div-button button-body-part disabled">
            <img src="images/btn-left-arm.png" />
        </div>
        <div id="btnLeftArmMirror" class="div-button button-mirror sel-blue">
            <img src="images/btn-mirror-left.png" />
        </div>
        <br/>
        <div id="btnRightLegMirror" class="div-button button-mirror">
            <img src="images/btn-mirror-right.png" />
        </div>
        <div id="btnRightLeg" class="div-button button-body-part">
            <img src="images/btn-right-leg.png" />
        </div>
        <div id="btnLeftLeg" class="div-button button-body-part disabled">
            <img src="images/btn-left-leg.png" />
        </div>
        <div id="btnLeftLegMirror" class="div-button button-mirror sel-blue">
            <img src="images/btn-mirror-left.png" />
        </div>
    </div>
    <div class="load-save">
        <canvas id="cnvMain" class="canvas-draw float-left"></canvas> 
    </div>
    <div class="div-side float-right load-save">
        <div class="text-center margin-top-10">
            PREVIEW
        </div>
        <div class="div-preview margin-top-10">
            <canvas id="cnvPreview" class="canvas-preview"></canvas>
        </div>
        <div class="text-center margin-top-10">
            <div id="btnFrameBack" class="div-button disabled">
                <img src="images/btn-back.png" />
            </div>
            <div id="btnFramePause" class="div-button margin-left-5">
                <img id="imgPause" src="images/btn-pause.png" />
                <img id="imgPlay" src="images/btn-play.png" class="display-none" />
            </div>
            <div id="btnFrameFwd" class="div-button disabled margin-left-5">
                <img src="images/btn-fwd.png" />
            </div>
        </div>
        <div class="text-center margin-top-10">
            <div id="btnSave" class="div-button">
                SAVE
            </div>
            <?php if($_SESSION["admin"] == 1){ ?>
                <div id="btnApprove" class="div-button display-none">
                    APPROVE
                </div>
                <div id="btnDisapprove" class="div-button display-none">
                    REJECT
                </div>
            <?php } ?>
        </div>
        <div id="lblError" class="text-center margin-top-10 error">
        </div>
        <div id="divExisting" class="div-preview small margin-top-10 display-none">
            <canvas id="cnvExisting" class="canvas-preview small"></canvas>
        </div>
    </div>
</div>

<div class="div-center load-save" id="divColorSquares">
</div>
<div class="div-center load-save">
    <div id="divToolArea" class="div-tool-area margin-top-3">
        <div id="btnToolEyedrop" class="tool div-button">
            <img src="images/btn-eyedrop.png" />
        </div>
        <div id="btnToolBucket" class="tool div-button margin-left-5">
            <img src="images/btn-bucket.png" />
        </div>
        <div id="btnToolSelect" class="tool div-button margin-left-5">
            <img src="images/btn-select.png" />
        </div>
        <div id="btnToolUndo" class="tool disabled div-button margin-left-5">
            <img src="images/btn-undo.png" />
        </div>
    </div>
    <div id="ddbColor" class="display-none">
        <div style="padding: 3px;">
            <div id="colorPicker">
            </div>
        </div>
    </div>
</div>
  
<?php include "php/footer.php"; ?>