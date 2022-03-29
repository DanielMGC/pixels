
$(function() {

    

    $("#btnDraw").on('click', (e) => {
        window.location = "index.php";
    });

    $("#btnList").on('click', (e) => {
        window.location = "list.php";
    });

    $("#btnGenerate").on('click', (e) => {
        window.location = "view.php";
    });

    $("#btnLogout").on('click', (e) => {
        $.ajax({
            type: "POST",
            url: "controller/user/logout.php",
            success: function() {
                window.location = "login.php";
            },
            dataType: "json"
        });
    });

});
