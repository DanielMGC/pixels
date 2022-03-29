<?php 
    include "php/head.php"; 
    include "php/not_login_required.php"; 
?>
<script src="scripts/login.js" type="text/javascript"></script>
<?php include "php/header.php"; ?>

<div class="div-center" id="divForm">
    <div class="div-center text-center">
        <input type="text" id="txtUsername" class="jqx form-field show-login show-register text-center"/>
    </div>
    <div class="div-center text-center">
        <input type="password" id="txtPassword" class="jqx form-field show-login show-register text-center"/>
    </div>
    <div class="div-center text-center show-forgot show-register display-none">
        <input type="text" id="txtEmail" class="jqx form-field text-center"/>
    </div>
</div>
<div class="show-login div-center text-center margin-top-10 load-login">
    <div id="btnLogin" class="div-button width-90">
        LOGIN
    </div>
</div>
<div class="show-login div-center text-center margin-top-10 load-login">
    <div id="btnRegister" class="div-button width-90">
        REGISTER
    </div>
</div>
<div class="show-login div-center text-center margin-top-10 load-login">
    <div id="btnForgot" class="div-button width-90">
        FORGOT
    </div>
</div>
<div class="show-login div-center text-center margin-top-10 load-login">
    <div id="btnVisit" class="div-button width-90">
        VISIT
    </div>
</div>
<div class="show-login div-center text-center margin-top-10 load-login">
    <div id="btnWhat" class="div-button width-90">
        WHAT?
    </div>
</div>
<div class="show-register show-forgot div-center text-center margin-top-10 display-none load-register">
    <div id="btnOk" class="div-button width-90">
        OK
    </div>
</div>
<div class="show-register show-forgot div-center text-center margin-top-10 display-none load-register">
    <div id="btnBack" class="div-button width-90">
        BACK
    </div>
</div>
<div class="div-center text-center margin-top-10">
    <div id="divError" class="text-center margin-top-10 error">
    </div>
</div>

<?php include "php/footer.php"; ?>