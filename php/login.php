<?php 
    include "php/head.php"; 
    include "php/not_login_required.php"; 
?>
<script src="scripts/login.js" type="text/javascript"></script>
<?php include "php/header.php"; ?>

<div class="div-center" id="divForm">
    <input type="text" id="txtUsername" class="jqx form-field"/>
    <input type="password" id="txtPassword" class="jqx form-field"/>
    <div class="hid-register display-none">
        <input type="text" id="txtEmail" class="jqx form-field"/>
    </div>
</div>
<div class="hid-login div-center text-center margin-top-10 load-login">
    <div id="btnLogin" class="div-button width-90">
        LOGIN
    </div>
</div>
<div class="hid-login div-center text-center margin-top-10 load-login">
    <div id="btnRegister" class="div-button width-90">
        REGISTER
    </div>
</div>
<div class="hid-register div-center text-center margin-top-10 display-none load-register">
    <div id="btnOk" class="div-button width-90">
        OK
    </div>
</div>
<div class="hid-register div-center text-center margin-top-10 display-none load-register">
    <div id="btnBack" class="div-button width-90">
        BACK
    </div>
</div>
<div class="div-center text-center margin-top-10">
    <div id="divError" class="text-center margin-top-10 error">
    </div>
</div>

<?php include "php/footer.php"; ?>