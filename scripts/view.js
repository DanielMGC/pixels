
var animParams = {
    angle: 0,
    dir: 1,
    continue: 1
};

var creature = null;

var interval = null;

var partLoadedCount = 0;
var searchLoadedCount = 0;

var searchTotal = 0;

var creaturesParts = [null, null, null, null];

var creaturesSearch = [null, null, null, null];

var selectedCreature = null;

var infoTextTimer = null;

$(function() {

    $("#txtSearch").jqxInput({placeHolder: "Search...", height: 30, width: 165, minLength: 5,  theme: 'metro' });
    $("#txtName").jqxInput({placeHolder: "Enter a name", height: 30, width: 250, minLength: 5,  theme: 'metro' });
    $("#txtName").on("change", function () {
        if($("#txtName").val().trim() == "") {
            $("#txtName").val($("#hidOriginal").val());
        }
    });

    $("#txtUrl, #txtUrlPage").jqxInput({height:1, width: 1,  theme: 'metro' });

    $("#btnDraw").on('click', (e) => {
        window.location = "index.php";
    });

    $("#btnList").on('click', (e) => {
        window.location = "list.php";
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

    $("#btnSave").on('click', (e) => {
        saveGif(true);
    });

    $("#btnNew").on('click', (e) => {
        generate();
    });

    $("#btnSearch").on('click', (e) => {
        search();
    });

    $('#txtSearch').on('keydown', (e) => {
        searchKeyDown(e);					   
    });

    var code = getUrlParameter("c");

    generate(code);
});

var searchKeyDown = function (e) {
    e = e || window.event;
    var keyCode = (e.which || e.keyCode);
    if(keyCode == 13) {
        search();
    }
};

var saveGif = function (download = true) {
    
    stopTimer();

    var canvas = document.getElementById("cnvMain");

    var images = [];
    var zeroCount = 0;

    while(zeroCount < 3) {
        if(animParams.angle == 0) {
            zeroCount++;
        }
        if(zeroCount < 3) {
            images.push(canvas.toDataURL("image/png"));
            updatePreview();
        }
    }

    var name = $("#txtName").val().trim();
    if(name == "") {
        name = $("#hidOriginal").val();
    }

    startTimer();

    if(download === true) {
        $.redirect("export/save_gif.php", { images: images, id: creature.id, name: name, mode: 'download'}, "POST", "_blank");
    } else {
        $.ajax({
            type: "POST",
            url: "export/save_gif.php",
            data: { images: images, id: creature.id, name: name, mode: 'link'},
            success: function(result) {
                $("#txtUrl").val(encodeURI(result.url));
                $("#btnShareGif").removeClass("disabled");

            },
            dataType: "json"
        });
    }
};

var generate = function (code = null, decrypted = false, updatePartList = true) {

    stopTimer();

    showLoader("load-view", "divView");

    var data = {id: 'rand'};
    if(code !== null) {
        data["code"] = code;
        data["decrypted"] = decrypted;
    }

    $.ajax({
        type: "POST",
        url: "controller/creature/get.php",
        data: data,
        success: function(creatureData) {
            
            creature = creatureData;
            if($("#txtName").val() == $("#hidOriginal").val() || $("#txtName").val().trim() == "") {
                $("#txtName").val(creature.name);
            }
            $("#hidOriginal").val(creature.name);

            //$("#txtUrlPage").val('http://entelodonte/daniel/teste/pixels/view.php?c=' + creatureData.code);
            $("#txtUrlPage").val('https://thebob.com.br/pixels/view.php?c=' + creatureData.code);
            $("#btnSharePage").removeClass("disabled");

            startTimer();

            hideLoader("load-view", "divView");

            saveGif(false);

            if(updatePartList === true) {

                $("divPartListArea .div-view-part").html("");

                showLoader(null, "divPartListArea");

                partLoadedCount = 0;

                for (var i = 0; i < creatureData.creaturesIds.length; i++) {
                    var id = creatureData.creaturesIds[i];

                    $.ajax({
                        type: "POST",
                        url: "controller/creature/get.php",
                        data: {id: id, index: i},
                        success: function(creatureData) {
                            
                            if(creatureData.id > 0) {
                                addPart(creatureData.index, creatureData);
                            } 
                            
                        },
                        dataType: "json"
                    });
                    
                }
            }
            
        },
        dataType: "json"
    });
};

var updatePreview = function () {
    if(creature != null) {
        createPreview($("#cnvMain"), creature.parts, animParams);
    }
};

var startTimer = function () {

    interval = setInterval(function() {
        updatePreview();
    }, 50);
};

var stopTimer = function () {

    animParams = {
        angle: 0,
        dir: 1,
        continue: 1
    };

    updatePreview();

    animParams = {
        angle: 0,
        dir: 1,
        continue: 1
    };

    clearInterval(interval);
};

var copyUrl = function () {
    if(!$("#btnShareGif").hasClass("disabled")) {
        var copyText = document.getElementById("txtUrl");

        copyText.select();

        document.execCommand("copy");

        deselectAll();

        showInfoText("Gif URL copied to clipboard");
    }
};

var copyUrlPage = function () {
    if(!$("#btnSharePage").hasClass("disabled")) {
        var copyText = document.getElementById("txtUrlPage");

        copyText.select();

        document.execCommand("copy");

        deselectAll();

        showInfoText("Page URL copied to clipboard");
    }
};

var showInfoText = function (text) {

    if(infoTextTimer != null) {
        clearTimeout(infoTextTimer);
    }
    $("#divInfoText").html("");
    $("#divInfoText").css("opacity", 1);

    $("#divInfoText").html(text);

    infoTextTimer = setTimeout(function () {
        $("#divInfoText").animate({
            opacity: 0
        }, 400);
    }, 5000);
};

var addPart = function (index, creature, search = false) {
    if(search === false) {
        creaturesParts[index] = creature;
    } else {
        creaturesSearch[index] = creature;
    }

    var templateHtml = $("#divItemTemplate").html();

    var item = $(templateHtml);
    $(item).find(".span-name").html(creature.name);
    $(item).find(".span-author").html(creature.author);
    $(item).find(".div-item-select-area").on("click", function() {
        selectPart($(this), index, search);
    });
    $(item).find(".div-item-button").on("click", function() {
        switchPart(index);
    });

    var canvasId = (search === false ? "cnvCreature" : "cnvCreatureSearch") + index;
    $(item).find(".canvas-preview").attr("id", canvasId);

    var divId = search === false ? "#divPart" : "#divSearch";
    
    $(divId + index).html($(item));

    createPreview($("#" + canvasId), creature.parts);

    if(search === false) {
        partLoadedCount++;
        if(partLoadedCount >=4) {
            hideLoader(null, "divPartListArea");
        }
    } 
};

var switchPart = function(index) {
    if(selectedCreature != null) {
        creaturesParts[index] = selectedCreature;
        addPart(index, selectedCreature);
        var code = "";
        for (var i = 0; i < creaturesParts.length; i++) {
            code += creaturesParts[i].id + ";";
        }
        code = code.substr(0, code.length-1);
        generate(code, true, false);
    }
};

var selectPart = function(divPart, index, search = false) {
    if(search === false) {
        //if(selectedCreature == null) {
            $(".div-item-select-area").removeClass("sel");
            $(divPart).addClass("sel");
            selectedCreature = creaturesParts[index];
        /*} else if(selectedCreature.id == creaturesParts[index].id) {
            $(".div-item-select-area.border").removeClass("sel");
            selectedCreature = null;
        } else {
            creaturesParts[index] = selectedCreature;
            addPart(index, selectedCreature);
            var code = "";
            for (var i = 0; i < creaturesParts.length; i++) {
                code += creaturesParts[i].id + ";";
            }
            code = code.substr(0, code.length-1);
            generate(code, true, false);
        }*/
    } else {
        /*if(selectedCreature != null && selectedCreature.id == creaturesSearch[index].id) {
            $(".div-item-select-area.border").removeClass("sel");
            selectedCreature = null;
        } else {*/
            $(".div-item-select-area").removeClass("sel");
            $(divPart).addClass("sel");
            selectedCreature = creaturesSearch[index];
        //}
    }
};

var search = function () {
    var text = $("#txtSearch").val().trim();

    $("#divSearch .div-view-part").html("");
    creaturesSearch = [null, null, null, null]

    showLoader("load-search", "divSearch");
    
    $.ajax({
        type: "POST",
        url: "controller/creature/read.php",
        data: {approved: 1, nameLike: text, maxResults: 4},
        success: function(result) {

            for(var i = 0; i < result.records.length; i++) {
                
                addPart(i, result.records[i], true);

            } 

            hideLoader("load-search", "divSearch");

            $([document.documentElement, document.body]).animate({
                scrollTop: $("#divSearch").offset().top
            }, 500);
        },
        dataType: "json"
    });
};