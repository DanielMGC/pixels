var errorMessageTimer = null;

var mode = "login";

var redirect = null;

$(function() {

    redirect = getUrlParameterNoDecode("r");
    setWidgets();
});

var setWidgets = function () {
    $("#txtUsername").jqxInput({placeHolder: "Username", height: 30, width: 200, minLength: 5,  theme: 'metro' });

    $("#txtPassword").jqxPasswordInput({placeHolder: "Password", width: 200, height: 30,  theme: 'metro' });

    $("#txtEmail").jqxInput({placeHolder: "E-mail", height: 30, width: 200, minLength: 5,  theme: 'metro' });

    $("#btnLogin").on('click', (e) => {
        login();
    });

    $("#btnOk").on('click', (e) => {
        if(mode=="register") {
            save();
        } else {
            remindPassword();
        }
    });

    $("#btnRegister").on('click', (e) => {
        $(".show-login").addClass("display-none");
        $(".show-forgot").addClass("display-none");
        $(".show-register").removeClass("display-none");
        mode = "register";
    });

    $("#btnForgot").on('click', (e) => {
        $(".show-login").addClass("display-none");
        $(".show-register").addClass("display-none");
        $(".show-forgot").removeClass("display-none");
        mode = "forgot";
    });

    $("#btnWhat").on('click', (e) => {
        window.location = "what.php";
    });

    $("#btnVisit").on('click', (e) => {
        window.location = "view.php";
    });

    $("#btnBack").on('click', (e) => {
        $(".show-register").addClass("display-none");
        $(".show-forgot").addClass("display-none");
        $(".show-login").removeClass("display-none");
        mode = "login";
    });

    $('.form-field').on('keydown', (e) => {
		formFieldKeyDown(e);					   
	});
};

var formFieldKeyDown = function (e) {
	e = e || window.event;
    var keyCode = (e.which || e.keyCode);
	if(keyCode == 13) {
		if(mode == "login") {
            login();
        } else if(mode == "register") {
            save();
        } else {
            remindPassword();
        }
	}
};

var validateEmail = function (email){
    var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return emailReg.test(email);
};

var showError = function (message) {
    $("#divError").html(message);

    errorMessageTimer = setTimeout(function () {
        $("#divError").animate({
            opacity: 0
        }, 400);
    }, 5000);

}

var save = function () {

    if(errorMessageTimer != null) {
        clearTimeout(errorMessageTimer);
    }
    $("#divError").html("");
    $("#divError").css("opacity", 1);

    var username = $("#txtUsername").val().trim();
    var password = $("#txtPassword").val().trim();
    var email = $("#txtEmail").val().trim();

    var error = true;

    if(username.length < 5) {
        showError("Username must have at least 5 characters");
    } else if (password.length < 8){
        showError("Password must have at least 8 characters");
    } else if (!validateEmail(email)){
        showError("Please enter a valid e-mail");
    } else {
        showLoader("load-register", "divForm");
        
        $.ajax({
            type: "POST",
            url: "controller/user/read.php",
            data: {username: username},
            success: function(result) {
                if(result.records.length > 0) {
                    hideLoader("load-register", "divForm");

                    showError("This username already exists");
    
                } else {
                    
                    $.ajax({
                        type: "POST",
                        url: "controller/user/create.php",
                        data: {username: username, password: password, email: email},
                        success: function(result) {
                            window.location = "index.php";
                        },
                        dataType: "json"
                    });

                }
            },
            dataType: "json"
        });

    }

};

var remindPassword = function () {
    var email = $("#txtEmail").val().trim();

    if (!validateEmail(email)){
        showError("Please enter a valid e-mail");
    } else {
        showLoader("load-register", "divForm");

        $.ajax({
            type: "POST",
            url: "controller/user/password.php",
            data: {email: email},
            success: function(result) {
                hideLoader("load-register", "divForm");
                if(result.user != null) {
                    showError("The login information was sent to your e-mail");
                } else {
                    showError("There is no user registered with this e-mail");
                }
            },
            dataType: "json"
        });
    }
};

var login = function () {

    showLoader("load-login", "divForm");
    
    if(errorMessageTimer != null) {
        clearTimeout(errorMessageTimer);
    }
    $("#divError").html("");
    $("#divError").css("opacity","1");

    var username = $("#txtUsername").val().trim();
    var password = $("#txtPassword").val().trim();

    $.ajax({
        type: "POST",
        url: "controller/user/login.php",
        data: {username: username, password: password},
        success: function(result) {

            if(result.result) {

                window.location = redirect == null ? "list.php" : decodeURIComponent(redirect);
            } else {

                hideLoader("load-login", "divForm");

                $("#divError").html("Login/Password is incorrect");

                errorMessageTimer = setTimeout(function () {
                    $("#divError").animate({
                        opacity: 0
                    }, 400);
                }, 5000);
            }
        },
        dataType: "json"
    });
};