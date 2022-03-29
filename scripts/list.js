
$(function() {
    loadCreatureList();

    $("#btnDraw").on('click', (e) => {
        window.location = "index.php";
    });

    $("#btnGenerate").on('click', (e) => {
        window.location = "view.php";
    });

    $("#btnWhat").on('click', (e) => {
        window.location = "what.php";
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

var loadCreatureList = function() {

    showLoader(null, "divLoad");

    $.ajax({
        type: "POST",
        url: "controller/creature/read.php",
        data: {author: parseInt($("#hidUserId").val())},
        success: function(result) {
            var templateHtml = $("#divItemTemplate").html();

            for(var i = 0; i < result.records.length; i++) {
                
                var item = $(templateHtml);
                $(item).find(".span-name").html(result.records[i].name);

                if(result.records[i].approved == -1) {
                    $(item).find(".span-approved").html("REJECTED");
                    $(item).find(".span-approved").addClass("rejected");
                } else if(result.records[i].approved == -2) {
                    $(item).find(".span-approved").html("REJECTED (LOW QUALITY)");
                    $(item).find(".span-approved").addClass("rejected");
                } else if(result.records[i].approved == -3) {
                    $(item).find(".span-approved").html("REJECTED (DUPLICATE)");
                    $(item).find(".span-approved").addClass("rejected");
                } else if(result.records[i].approved == -4) {
                    $(item).find(".span-approved").html("REJECTED (INAPPROPRIATE)");
                    $(item).find(".span-approved").addClass("rejected");
                } else if(result.records[i].approved == 1) {
                    $(item).find(".span-approved").html("APPROVED");
                    $(item).find(".span-approved").addClass("approved");
                } else {
                    $(item).find(".span-approved").html("PENDING");
                    $(item).find(".span-approved").addClass("pending");
                }

                var canvasId = "cnvCreature" + result.records[i].id;
                $(item).find(".canvas-preview").attr("id", canvasId);
                if(result.records[i].approved <= 0) {
                    $(item).find(".div-button").attr("onclick", "javascript:window.location='index.php?c="+result.records[i].id+"';")
                } else {
                    $(item).find(".div-item-button").remove();
                }
                
                
                $("#divList").append($(item));

                createPreview($("#" + canvasId), result.records[i].parts);

            } 

            hideLoader(null, "divLoad");
        },
        dataType: "json"
    });
};