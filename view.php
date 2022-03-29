<?php 
    include "php/head.php"; 
    //include "php/login_required.php"; 
?>
<script src="scripts/view.js" type="text/javascript"></script>
<?php include "php/header.php"; ?>

<div class="text-center load-save">
    
    <?php if(isset($_SESSION["username"])){ ?>
        <div id="btnDraw" class="div-button" title="Draw">
            <img src="images/btn-draw.png" />
        </div>
        <div id="btnList" class="div-button margin-left-5" title="Your creatures">
            <img src="images/btn-list.png"/>
        </div>
    <?php } ?>
    <div id="btnWhat" class="div-button margin-left-5" title="What is this??">
        <img src="images/btn-question.png" />
    </div>
    <div id="btnLogout" class="div-button margin-left-5" title="Logout">
        <img src="images/btn-exit.png" />
    </div>
</div>

<div id="divView" class="div-center large margin-top-10">
    <div class="div-view-middle">
        <div class="load-view div-center text-center margin-top-10">
            <div id="btnNew" class="div-button">
                REGENERATE CREATURE
            </div>
        </div>

        <div id="divName" class="load-view margin-top-10 text-center">
            <input type="hidden" id="hidOriginal" />
            <input type="text" id="txtName" class="jqx text-center font-big"/>
            <div class="text-center font-small margin-top-8">
                Use the auto name, or come up with one! (clear text to go back to auto)
            </div>
        </div>
        
        <div class="div-view load-view margin-top-10">
            <canvas id="cnvMain" class="canvas-view medium"></canvas> 
        </div>

        <div class="load-view div-center text-center margin-top-10">
            <div id="btnSave" class="div-button width-80">
                SAVE
            </div>
            <div id="btnShareGif" class="div-button width-80 disabled" onclick="copyUrl();">
                SHARE GIF
            </div>
            <div id="btnSharePage" class="div-button width-90 disabled" onclick="copyUrlPage();">
                SHARE PAGE
            </div>
        </div>

        <div class="text-center font-small margin-top-8">
            Use the buttons to save the gif file, share a url to it, or a url to this page for this creature
        </div>

        <div id="divInfoText" class="load-view div-center text-center margin-top-10 font-med text-green">
        </div>

        <input type="text" id="txtUrl" class="jqx invisible" />
        <input type="text" id="txtUrlPage" class="jqx invisible" />
    </div>

    <div id="divPartListArea" class="div-side float-left-desktop text-center load-view">
        <div class="text-center margin-top-10">
            PARTS
        </div>
        <div class="text-center margin-top-8">
            Click a creature to select, click on the body part icons to use that creature for that part
        </div>
        <div id="divPart0" class="div-view-part head margin-top-10"> 
        </div>
        <div id="divPart1" class="div-view-part body margin-top-10"> 
        </div>
        <div id="divPart2" class="div-view-part arm margin-top-10"> 
        </div>
        <div id="divPart3" class="div-view-part leg margin-top-10"> 
        </div>
    </div>
    
    <div id="divSearch" class="div-side float-right-desktop load-view load-search">
        <div class="div-area-search">
            <div class="text-center float-left">
                <input type="text" id="txtSearch" class="jqx text-right"/>
            </div>
            <div id="btnSearch" class="div-button margin-top-7 float-right" title="Search">
                <img src="images/btn-search.png" />
            </div>
        </div>
        <div class="text-center margin-top-2">
            Search the creature database. Then click the creature you want and the icon of the body part you want to place it.
        </div>
        <div id="divSearch0" class="div-view-part margin-top-10"> 
        </div>
        <div id="divSearch1" class="div-view-part margin-top-10"> 
        </div>
        <div id="divSearch2" class="div-view-part margin-top-10"> 
        </div>
        <div id="divSearch3" class="div-view-part margin-top-10"> 
        </div>
    </div>
</div>

<div id="divItemTemplate" class="display-none">
    <div class="div-list-item small">
        <div class="div-item-button small cursor-pointer">
            
        </div>
        <div class="div-item-select-area">
            <div class="div-item-canvas">
                <canvas class="canvas-preview small"></canvas>
            </div>
            <div class="div-item-name small">
                <span class="span-name">Name</span>
                <span class="span-author">Author</span>
            </div>
        </div>
    </div>
</div>
  
<?php include "php/footer.php"; ?>