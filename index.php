<?php 
    include "php/head.php"; 
    include "php/login_required.php"; 
?>
<script src="scripts/main.js" type="text/javascript"></script>
<?php include "php/header.php"; ?>

<div class="text-center load-save">
    
    <div id="btnGenerate" class="div-button" title="Combine">
        <img src="images/btn-generate.png" />
    </div>
    <div id="btnList" class="div-button margin-left-5" title="Your creatures">
        <img src="images/btn-list.png" />
    </div>
    <?php if(isset($_SESSION["admin"]) && $_SESSION["admin"] == 1){ ?>
        <div id="btnCheck" class="div-button margin-left-5">
            <img src="images/btn-check.png" />
        </div>
    <?php } ?>
    <div id="btnWhat" class="div-button margin-left-5" title="What is this??">
        <img src="images/btn-question.png" />
    </div>
    <div id="btnLogout" class="div-button margin-left-5" title="Logout">
        <img src="images/btn-exit.png" />
    </div>
</div>

<div id="divName" class="div-center text-center margin-top-10">
    <input type="text" id="txtName" class="jqx load-save text-center"/>
</div>
<div id="divAuthor" class="div-center display-none load-save">
    <label class="label-author"></label>
</div>
<div class="text-center font-small margin-top-8">
    Write the name first, to see if a creature with this name already exists!
</div>

<div class="div-center large">
    <div class="div-side float-left-desktop text-center load-save">
        <div class="text-center margin-top-10">
            Click the part you want to work on. If the arm or leg mirrors are selected, the corresponding part will be mirrored
            from the one on the other side. Unselect the mirror if you wish to make them different.
        </div>
        <div class="text-center margin-top-10">
            The dash ("-") will clear pixels, and the letters will position the anchor points. These will depend on the 
            current body part. "B" is Body, "H" is Head, "RA" is Right Arm, "LA" is Left Arm, "RL" is Right Leg and "LL" is Left Leg.
        </div>
        <div class="text-center margin-top-10">
            SELECT PART
        </div>
        <div id="btnHead" class="div-button sel button-body-part margin-top-10" title="Head">
            <img src="images/btn-face.png" />
        </div>
        <br/>
        <div id="btnRightArmMirror" class="div-button button-mirror" title="Mirror from left arm">
            <img src="images/btn-mirror-right.png" />
        </div>
        <div id="btnRightArm" class="div-button button-body-part" title="Right arm">
            <img src="images/btn-right-arm.png" />
        </div>
        <div id="btnBody" class="div-button button-body-part" title="Body">
            <img src="images/btn-body.png" />
        </div>
        <div id="btnLeftArm" class="div-button button-body-part disabled" title="Left arm">
            <img src="images/btn-left-arm.png" />
        </div>
        <div id="btnLeftArmMirror" class="div-button button-mirror sel-blue" title="Mirror from right arm">
            <img src="images/btn-mirror-left.png" />
        </div>
        <br/>
        <div id="btnRightLegMirror" class="div-button button-mirror" title="Mirror from left leg">
            <img src="images/btn-mirror-right.png" />
        </div>
        <div id="btnRightLeg" class="div-button button-body-part" title="Right leg">
            <img src="images/btn-right-leg.png" />
        </div>
        <div id="btnLeftLeg" class="div-button button-body-part disabled" title="Left leg">
            <img src="images/btn-left-leg.png" />
        </div>
        <div id="btnLeftLegMirror" class="div-button button-mirror sel-blue" title="Mirror from right leg">
            <img src="images/btn-mirror-left.png" />
        </div>

    </div>
    <div class="div-draw-middle display-inline-block-desktop load-save">
        <canvas id="cnvMain" class="canvas-draw float-left-desktop"></canvas> 

        <div class="div-center load-save" id="divColorSquares">
        </div>
        <div class="div-center load-save">
            <div id="divToolArea" class="div-tool-area margin-top-3">
                <div id="btnToolEyedrop" class="tool div-button" title="Pick color">
                    <img src="images/btn-eyedrop.png" />
                </div>
                <div id="btnToolBucket" class="tool div-button margin-left-2" title="Fill">
                    <img src="images/btn-bucket.png" />
                </div>
                <div id="btnToolSelect" class="tool div-button margin-left-2 display-none-mobile" title="Select">
                    <img src="images/btn-select.png" />
                </div>
                <div id="btnToolUndo" class="tool disabled div-button margin-left-2" title="Undo">
                    <img src="images/btn-undo.png" />
                </div>
                <div id="btnToolClear" class="tool div-button margin-left-2" title="Clear">
                    <img src="images/btn-clear.png" />
                </div>
            </div>
            <div id="ddbColor" class="display-none">
                <div style="padding: 3px;">
                    <div id="colorPicker">
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <div class="div-side float-right-desktop load-save">
        <div class="text-center margin-top-10">
            PREVIEW
        </div>
        <div class="div-preview margin-top-10">
            <canvas id="cnvPreview" class="canvas-preview"></canvas>
        </div>
        <div class="text-center margin-top-10">
            <div id="btnFrameBack" class="div-button disabled" title="Previous frame">
                <img src="images/btn-back.png" />
            </div>
            <div id="btnFramePause" class="div-button margin-left-5" title="Pause/Play">
                <img id="imgPause" src="images/btn-pause.png" />
                <img id="imgPlay" src="images/btn-play.png" class="display-none" />
            </div>
            <div id="btnFrameFwd" class="div-button disabled margin-left-5" title="Next frame">
                <img src="images/btn-fwd.png" />
            </div>
        </div>
        <div class="text-center margin-top-10">
            <div id="btnSave" class="div-button">
                SAVE
            </div>
            <?php if(isset($_SESSION["admin"]) && $_SESSION["admin"] == 1){ ?>
                <div id="btnFlip" class="show-approve div-button display-none">
                    FLIP
                </div>
                <div id="btnApprove" class="show-approve div-button display-none">
                    APPROVE
                </div>
                <div id="btnReject" class="show-approve div-button display-none">
                    REJECT
                </div>
                <div id="btnRejectQuality" class="show-approve div-button display-none">
                    QUALITY
                </div>
                <div id="btnRejectDupe" class="show-approve div-button display-none">
                    DUPLICATE
                </div>
                <div id="btnRejectInappropriate" class="show-approve div-button display-none">
                    INAPPROPRIATE
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
  
<?php include "php/footer.php"; ?>