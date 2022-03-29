

var pixelRatio = 0;

var c = null;
var ctx = null;

var cellSize = {
    w: 0,
    h: 0
};

var creature = null;

var currentPart = "Head";

var pallete = [
    "-",
    "-",
    "-",
    "-",
    "-",
    "B",
    "#05fec1",
    "#2a5219",
    "#00b716",
    "#50fe34",
    "#cdfff1",
    "#499faa",
    "#2f6d82",
    "#3894d7",
    "#78cef8",
    "#bbc6ec",
    "#8e8cfd",
    "#1f64f4",
    "#72629f",
    "#f5b8f4",
    "#df6ff1",
    "#7f2387",
    "#93274e",
    "#dd385a",
    "#f28071",
    "#ee2911",
    "#663300",
    "#f8d0c0",
    "#f8cb1a",
    "#3d3d3d",
    "#000000",
    "#ffffff"
];

var currentColor = "-";
var isDragging = false;

var previewOk = false;

var animParams = {
    angle: 0,
    dir: 1,
    continue: 1
};

var errorMessageTimer = null;

var saveOk = true;

var selectedTool = null;

var undoArray = [];
var undoMax = 10;

var selection = {
    p1: {
        x: -1,
        y: -1
    },
    p2: {
        x: -1,
        y: -1
    }
};

var selectionDragP1 = null;

var checkName = true;

$(function() {
    newCreature();

    setWidgets();
    setColorSquares();

    initParams();
  
    changePart("Head");

    var editId = getUrlParameter("c");
    if(editId != null) {
        loadEdit(editId);
    }

    setInterval(function() {
        updatePreview();
    }, 50);
});

var setCurrentColor = function (color) {
    if($('.div-color-square.sel').html() == '') {
        currentColor = color;
        $('.div-color-square.sel').css('background-color', currentColor);
    }
};

var setWidgets = function () {
    $("#txtName").jqxInput({placeHolder: "Enter a name", height: 30, width: 250, minLength: 5,  theme: 'metro' });
    $("#colorPicker").jqxColorPicker({ color: "ffaabb", colorMode: 'hue', width: 150, height: 150,  theme: 'metrodark' });
    $("#ddbColor").jqxDropDownButton({ width: 145, height: 22,animationType: 'none', dropDownVerticalAlignment: 'top', enableBrowserBoundsDetection: true, theme: 'metrodark'});
    $("#ddbColor").jqxDropDownButton('setContent', getTextElementByColor(new $.jqx.color({ hex: "ffaabb" })));

    $('#ddbColor').on('open', function () { 
        var posy = $("#ddbColor").offset().top;
        posy -= 160;
        $("#dropDownButtonPopupddbColor").css("top", posy + "px");    

        var posx = $("#ddbColor").offset().left;
        posx -= 10;
        $("#dropDownButtonPopupddbColor").css("left", posx + "px"); 
    }); 
    
    $("#colorPicker").on('colorchange', function (event) {
        $("#ddbColor").jqxDropDownButton('setContent', getTextElementByColor(event.args.color));
        setCurrentColor("#" + event.args.color.hex);
    });

    $("#txtName").on('change', function (event) {
        if(checkName) {
            saveOk = false;
            checkNameExists($("#txtName").val());
        }
    });
};

var newCreature = function () {
    $("#txtName").val("");
    creature = {
        parts: {
            Head: {colors: [], anchors: {}},
            Body: {colors: [], anchors: {}},
            RightArm: {colors: [], anchors: {}},
            LeftArm: {colors: [], anchors: {}},
            RightLeg: {colors: [], anchors: {}},
            LeftLeg: {colors: [], anchors: {}}
        },
        author: {
            id: 0
        },
        name: "---",
        id: -1
    };

    for (var i = 0; i < gridSize.columns; i++) {
        var columnHead = [];
        var columnBody = [];
        var columnRightArm = [];
        var columnLeftArm = [];
        var columnRightLeg = [];
        var columnLeftLeg = [];
        for (var j = 0; j < gridSize.rows; j++) {
            columnHead.push("-");
            columnBody.push("-");
            columnRightArm.push("-");
            columnLeftArm.push("-");
            columnRightLeg.push("-");
            columnLeftLeg.push("-");
        }
        creature.parts.Head.colors.push(columnHead);
        creature.parts.Body.colors.push(columnBody);
        creature.parts.RightArm.colors.push(columnRightArm);
        creature.parts.LeftArm.colors.push(columnLeftArm);
        creature.parts.RightLeg.colors.push(columnRightLeg);
        creature.parts.LeftLeg.colors.push(columnLeftLeg);
    }

    creature.parts.Head.anchors["B"] = {row: 15, column: 7};
    creature.parts.Body.anchors["H"] = {row: 1, column: 7};
    creature.parts.Body.anchors["RA"] = {row: 3, column: 4};
    creature.parts.Body.anchors["LA"] = {row: 3, column: 11};
    creature.parts.Body.anchors["RL"] = {row: 12, column: 5};
    creature.parts.Body.anchors["LL"] = {row: 12, column: 10};
    creature.parts.RightArm.anchors["B"] = {row: 4, column: 10};
    creature.parts.LeftArm.anchors["B"] = {row: 4, column: 10};
    creature.parts.RightLeg.anchors["B"] = {row: 4, column: 10};
    creature.parts.LeftLeg.anchors["B"] = {row: 4, column: 10};
};

var loadEdit = function (id) {

    showLoader("load-save", "divName");

    $.ajax({
        type: "POST",
        url: "controller/creature/get.php",
        data: {id: id},
        success: function(creatureData) {
            
            if(creatureData.id > 0) {

                loadCreature(creatureData);
            } 
            hideLoader("load-save", "divName");
            
        },
        dataType: "json"
    });
};

var loadCreature = function (creatureToLoad)  {
    checkName = false;

    creature = creatureToLoad;
    console.log(creature.approved);
    if(creature.approved == 0) {
        $(".show-approve").removeClass("display-none");
    }
    $("#txtName").val(creature.name);
    changePart("Head");

    checkName = true;
};

var setColorSquares = function () {
    $("#divColorSquares").html();

    for (var i = 0; i < pallete.length; i++) {
        var color = pallete[i].indexOf("#") == -1 ? "transparent" : pallete[i];
        var style = " style=\"background-color: " + color + "\" ";
        var square = "<div class='div-color-square " + (i == 0 ? "sel" : "") +"' " + style + ">" + (pallete[i].indexOf("#") == -1 ? pallete[i] : "") + "</div>";
        
        $("#divColorSquares").append($(square));
        
    }

    $(".div-color-square").each(function() {
        $(this).on("click", function () {
            selectColorSquare($(this));
        });
    });
};

var getTextElementByColor = function (color) {
    if (color == 'transparent' || color.hex == "") {
        return $("<div style='text-shadow: none; position: relative; padding-bottom: 2px; margin-top: 2px;'>transparent</div>");
    }
    var element = $("<div style='text-shadow: none; position: relative; padding-bottom: 2px; margin-top: 2px;'>#" + color.hex + "</div>");
    var nThreshold = 105;
    var bgDelta = (color.r * 0.299) + (color.g * 0.587) + (color.b * 0.114);
    var foreColor = (255 - bgDelta < nThreshold) ? 'Black' : 'White';
    element.css('color', foreColor);
    element.css('background', "#" + color.hex);
    element.addClass('jqx-rc-all');
    return element;
};

var initParams = function() {
    pixelRatio = Math.round(window.devicePixelRatio) || 1;
    
    c = document.getElementById("cnvMain");
    ctx = c.getContext("2d");

    var width = $("#cnvMain").width() * pixelRatio;
    var height = $("#cnvMain").height() * pixelRatio;
    c.width = width;
    c.height = height;

    cellSize.w = width / gridSize.columns;
    cellSize.h = height / gridSize.rows;  

    ctx.lineWidth = 1;

    c.addEventListener('mousedown', (e) => {
        if(selectedTool == null || selectedTool == "select") {
            isDragging = true;
        } 
        setPixelFromClientPos(e, true);
    });

    c.addEventListener('mouseup', (e) => {
        isDragging = false;
        selectionDragP1 = null;
    });

    c.addEventListener('mouseleave', (e) => {
        isDragging = false;
        selectionDragP1 = null;
    });
    
    c.addEventListener('mousemove', (e) => {
        
        if(isDragging) {
            setPixelFromClientPos(e, false);
        }
    });

    $(".button-body-part").on('click', (e) => {
        if(!$(e.currentTarget).hasClass("disabled")) {
            var part = $(e.currentTarget).attr("id").replace("btn","");
            changePart(part);
        }
    });

    $(".button-mirror").on('click', (e) => {
        changeMirror($(e.currentTarget));
    });

    $("#btnFramePause").on('click', (e) => {
        if(animParams.continue == 1) {
            animParams.continue = 0;
            $("#btnFrameBack").removeClass("disabled");
            $("#btnFrameFwd").removeClass("disabled");
            $("#imgPause").addClass("display-none");
            $("#imgPlay").removeClass("display-none");
        } else {
            animParams.continue = 1;
            $("#btnFrameBack").addClass("disabled");
            $("#btnFrameFwd").addClass("disabled");
            $("#imgPause").removeClass("display-none");
            $("#imgPlay").addClass("display-none");
        }
    });
    $("#btnFrameBack").on('click', (e) => {
        if(animParams.continue == 0) {
            if(animParams.angle >= 45) {
                animParams.dir = 1;
            }
            if(animParams.angle <= -45) {
                animParams.dir = -1;
            }
            animParams.angle += (5 * animDir * -1);    
        }    
    });
    $("#btnFrameFwd").on('click', (e) => {
        if(animParams.continue == 0) {
            if(animParams.angle >= 45) {
                animParams.dir = -1;
            }
            if(animParams.angle <= -45) {
                animParams.dir = 1;
            }
            animParams.angle += (5 * animParams.dir);
        }
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

    $("#btnGenerate").on('click', (e) => {
        window.location = "view.php";
    });

    $("#btnList").on('click', (e) => {
        window.location = "list.php";
    });

    if($("#btnCheck").length > 0) {
        $("#btnCheck").on('click', (e) => {
            loadForApproval();
        });

        $("#btnFlip").on('click', (e) => {
            flip();
        });

        $("#btnApprove").on('click', (e) => {
            save(1);
        });

        $("#btnReject").on('click', (e) => {
            save(-1);
        });

        $("#btnRejectQuality").on('click', (e) => {
            save(-2);
        });

        $("#btnRejectDupe").on('click', (e) => {
            save(-3);
        });

        $("#btnRejectInappropriate").on('click', (e) => {
            save(-4);
        });
    }

    $("#btnSave").on('click', (e) => {
        save();
    });

    $("#btnToolEyedrop").on('click', (e) => {
        
        if($("#btnToolEyedrop").hasClass("sel")) {
            $("#btnToolEyedrop").removeClass("sel");
            selectedTool = null;
        } else {
            clearSelection();

            $(".tool").removeClass("sel");
            $("#btnToolEyedrop").addClass("sel");
            selectedTool = "eyedrop";
        }
    });

    $("#btnToolBucket").on('click', (e) => {
        if($("#btnToolBucket").hasClass("sel")) {
            $("#btnToolBucket").removeClass("sel");
            selectedTool = null;
        } else {
            clearSelection();

            $(".tool").removeClass("sel");
            $("#btnToolBucket").addClass("sel");
            selectedTool = "bucket";
        }
    });

    $("#btnToolSelect").on('click', (e) => {
        clearSelection();
            
        if($("#btnToolSelect").hasClass("sel")) {
            $("#btnToolSelect").removeClass("sel");
            selectedTool = null;
        } else {
            $(".tool").removeClass("sel");
            $("#btnToolSelect").addClass("sel");
            selectedTool = "select";
        }
        
    });

    $("#btnToolUndo").on('click', (e) => {
        undo();
    });

    $("#btnToolClear").on('click', (e) => {
        clearPart();
    });
};

var flip = function () {
    
    for(part in creature.parts) {

        for (var i = 0; i < creature.parts[part].colors.length; i++) {
            creature.parts[part].colors[i].reverse();
        }   
        for(anchor in creature.parts[part].anchors) {    
            creature.parts[part].anchors[anchor].column = gridSize.columns - creature.parts[part].anchors[anchor].column;
        }
    }

    updateDraw();
};

var loadForApproval = function () {
    $(".show-approve").addClass("display-none");
    $("#lblError").html("");
    $("#divAuthor").addClass("display-none");
    $("#divAuthor label").html("");

    showLoader("load-save", "divName");
    $.ajax({
        type: "POST",
        url: "controller/creature/read.php",
        data: {approved: 0},
        success: function(creatureData) {
            
            if(creatureData.records.length > 0) {
                $("#divAuthor").removeClass("display-none");
                $("#divAuthor label").html("By " + creatureData.records[0].author);

                $(".show-approve").removeClass("display-none");

                loadCreature(creatureData.records[0]);
            } else {
                newCreature();
                changePart("Head");

                showError("Nothing to approve");
            }

            hideLoader("load-save", "divName");
            
        },
        dataType: "json"
    });
};

var clearPart = function () {
    for (var i = 0; i < gridSize.columns; i++) {
        for (var j = 0; j < gridSize.rows; j++) {
            creature.parts[currentPart].colors[j][i] = "-";
        }
    }
    updateDraw();
};

var setPixelFromClientPos = function(pos, firstClick = false) {
    var rect = c.getBoundingClientRect();
    const mousePos = {
        //x: pos.clientX - rect.left,
        //y: pos.clientY - rect.top
        x: pos.offsetX * c.width / c.clientWidth | 0,
        y: pos.offsetY * c.height / c.clientHeight | 0
    };
    const pixel = {
        x: Math.floor(mousePos.x / cellSize.w),
        y: Math.floor(mousePos.y / cellSize.h)
    };

    if(selectedTool == null) {
        if(currentColor == "-" || currentColor.indexOf("#") == 0) {
            addUndo();
        }
        setPixel(pixel.x, pixel.y, currentColor, currentPart);
        updateDraw();
    } else if (selectedTool == "eyedrop") {
        var color = creature.parts[currentPart].colors[pixel.y][pixel.x];

        var exists = false;

        $(".div-color-square").each(function() {
            var html = $(this).html().trim();
            if(html == "") {
                var squareColor = $(this).css("background-color");
                squareColor = rgb2hex(squareColor);
                if(color.toLowerCase() == squareColor.toLowerCase()) {
                    exists = true;
                    selectColorSquare($(this));
                }
            }
            
        });

        if(!exists) {
            if(currentColor.indexOf("#") == -1) {
                selectColorSquare($(".div-color-square").slice(6,7));
                setCurrentColor(color);
                selectColorSquare($(".div-color-square.sel"));
            }
            else {
                setCurrentColor(color);
                selectColorSquare($(".div-color-square.sel"));
            }
        }

        $(".tool").removeClass("sel");
        selectedTool = null;
    } else if (selectedTool == "bucket") {
        var oldColor = creature.parts[currentPart].colors[pixel.y][pixel.x];
        if(oldColor != currentColor && (currentColor == "-" || currentColor.indexOf("#") == 0)) {
            addUndo();
            
            paintSurrounding(pixel.x, pixel.y, oldColor, currentColor, currentPart);

            var mirror = checkMirror(currentPart);
            if(mirror != null) {
                paintSurrounding(pixel.x, pixel.y, oldColor, currentColor, mirror);
            }
            updateDraw();
        }
    } else if(selectedTool == "select") {
        if (firstClick === true) {
            if(isInsideSelection(pixel.x, pixel.y)) {
                selectionDragP1 = {
                    x: pixel.x,
                    y: pixel.y
                };
                addUndo();
            } else {
                selection.p1.x = pixel.x;
                selection.p1.y = pixel.y;
    
                selection.p2.x = selection.p1.x;
                selection.p2.y = selection.p1.y;
            }
            
            
        } else {
            if(selectionDragP1 == null) {
                selection.p2.x = pixel.x;
                selection.p2.y = pixel.y;
            } else {
                var diff = {
                    x: pixel.x - selectionDragP1.x,
                    y: pixel.y - selectionDragP1.y
                };
                if(selection.p1.x + diff.x < 0 || selection.p1.x + diff.x >= gridSize.columns
                    && selection.p2.x + diff.x < 0 || selection.p2.x + diff.x >= gridSize.columns) {
                    diff.x = 0;
                }
                if(selection.p1.y + diff.y < 0 || selection.p1.y + diff.y >= gridSize.rows
                    && selection.p2.y + diff.y < 0 || selection.p2.y + diff.y >= gridSize.rows) {
                    diff.y = 0;
                }

                if(diff.x != 0 || diff.y != 0) {
                    var area = normalizePoints(selection.p1, selection.p2);
                    shiftPixels(area, diff, currentPart);
                    var mirror = checkMirror(currentPart);
                    if(mirror != null) {
                        shiftPixels(area, diff, mirror);
                    }

                    selection.p1.x += diff.x;
                    selection.p2.x += diff.x;
                    selection.p1.y += diff.y;
                    selection.p2.y += diff.y;
                }

                selectionDragP1 = {
                    x: pixel.x,
                    y: pixel.y
                };

            }
        }

        updateDraw();
    }

};

var shiftPixels = function (area, diff, part) {
    var copy = [];

    for (var i = area.start.x; i <= area.end.x; i++) {
        var column = [];
        for (var j = area.start.y; j <= area.end.y; j++) {
            //console.log(j + ' (+ ' + diff.y + ') x ' + i + ' (+ ' + diff.x + ')');
            column.push(creature.parts[part].colors[j][i]);
            creature.parts[part].colors[j][i] = "-";
            //creature.parts[part].colors[j + diff.y][i + diff.x] = copy[j][i];
        }
        copy.push(column);
    }

    var copyJ = 0;

    for (var i = area.start.x + diff.x; i <= area.end.x + diff.x; i++) {
        var copyI = 0;
        for (var j = area.start.y + diff.y; j <= area.end.y + diff.y; j++) {
            if(copy[copyJ][copyI] != "-") {
                creature.parts[part].colors[j][i] = copy[copyJ][copyI];
            }
            copyI++;
        }
        copyJ++;
    }
};

var normalizePoints = function (p1, p2) {
    var startPoint = {
        x: (p1.x < p2.x ? p1.x : p2.x),
        y: (p1.y < p2.y ? p1.y : p2.y)
    }
    var endPoint = {
        x: (p1.x > p2.x ? p1.x : p2.x),
        y: (p1.y > p2.y ? p1.y : p2.y)
    }
    return {
        start: startPoint,
        end: endPoint
    };
};

var isInsideSelection = function (column, row) {
    if(selection.p1.x > -1) {
        
        var area = normalizePoints(selection.p1, selection.p2);

        return column >= area.start.x && column <= area.end.x && row >= area.start.y && row <= area.end.y;
    }
    return false;
};

var paintSurrounding = function(column, row, oldColor, newColor, part) {
    if(column >= 0 && column < gridSize.columns && row >= 0 && row < gridSize.rows) {
        if(creature.parts[part].colors[row][column] == oldColor) {
            creature.parts[part].colors[row][column] = newColor;

            paintSurrounding(column - 1, row, oldColor, newColor, part);
            paintSurrounding(column + 1, row, oldColor, newColor, part);
            paintSurrounding(column, row - 1, oldColor, newColor, part);
            paintSurrounding(column, row + 1, oldColor, newColor, part);
        }
    }
};

var checkMirror = function (part) {
    if(part.indexOf("Right") > -1 || part.indexOf("Left") > -1) {
        var current = part.indexOf("Right") > -1 ? "Right" : "Left";
        var opposite = part.indexOf("Right") > -1 ? "Left" : "Right";
        if($("#btn" + part.replace(current, opposite) + "Mirror").hasClass("sel-blue")) {
            return part.replace(current, opposite);
        }
    }
    return null;
};

var setPixel = function (column, row, color, part) {

    if(color == "-" || color.indexOf("#") == 0) {
        creature.parts[part].colors[row][column] = color;
    } else {
        creature.parts[part].anchors[color].row = row;
        creature.parts[part].anchors[color].column = column;
    }
    var mirror = checkMirror(part);
    if(mirror != null) {
        setPixel(column, row, color, mirror);
    }
    /*if(part.indexOf("Right") > -1 || part.indexOf("Left") > -1) {
        var current = part.indexOf("Right") > -1 ? "Right" : "Left";
        var opposite = part.indexOf("Right") > -1 ? "Left" : "Right";
        if($("#btn" + part.replace(current, opposite) + "Mirror").hasClass("sel-blue")) {
            setPixel(column, row, color, part.replace(current, opposite));
        }
    }*/
};

var selectColorSquare = function(div) {
    $(".div-color-square").removeClass("sel");
    $(div).addClass("sel");

    var html = $(div).html().trim();

    currentColor = (html != "") ? html : $(div).css("background-color");
    currentColor = rgb2hex(currentColor)
    
    if(currentColor.indexOf("#") == -1) {
        $("#ddbColor").addClass('display-none');    
    } else {
        $("#ddbColor").removeClass('display-none');
        $("#colorPicker").jqxColorPicker('setColor', currentColor);
    }
};

var prepareGrid = function () {

    for (var i = 0; i < gridSize.rows; i++) {
        for (var j = 0; j < gridSize.columns; j++) {
            setPixel(i, j, "-", currentPart);
        }
    }
    updateDraw();
    
};

var rgb2hex = function(orig){
    var rgb = orig.replace(/\s/g,'').match(/^rgba?\((\d+),(\d+),(\d+)/i);
    return (rgb && rgb.length === 4) ? '#' +
        ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : orig;
};

var changePart = function(part) {
    $(".button-body-part").removeClass("sel");
    $("#btn" + part).addClass("sel");

    currentPart = part;

    $("#divColorSquares .div-color-square").slice(0,5).html("-");
    $("#divColorSquares .div-color-square").slice(5,6).html("B");

    if(part == "Body") {
        $("#divColorSquares .div-color-square").slice(1,2).html("H");
        $("#divColorSquares .div-color-square").slice(2,3).html("RA");
        $("#divColorSquares .div-color-square").slice(3,4).html("LA");
        $("#divColorSquares .div-color-square").slice(4,5).html("RL");
        $("#divColorSquares .div-color-square").slice(5,6).html("LL");
    }

    for (var i = 0; i < gridSize.columns; i++) {
        for (var j = 0; j < gridSize.rows; j++) {
            
            setPixel(j, i, creature.parts[currentPart].colors[i][j], currentPart);
        }
    }
    updateDraw();

    previewOk = true;

    //selectColorSquare($(".div-color-square").first());
    selectColorSquare($(".div-color-square.sel"));
};

var clearSelection = function() {
    selection = {
        p1: {
            x: -1,
            y: -1
        },
        p2: {
            x: -1,
            y: -1
        }
    };
    updateDraw();
};

var changeMirror = function(mirrorDiv) {
    var active = $(mirrorDiv).hasClass("sel-blue");
    var current = $(mirrorDiv).attr("id");
    var opposite = current.indexOf("Left") > -1 ? current.replace("Left","Right") : current.replace("Right","Left");
    var currentPart = current.replace("Mirror","");
    var oppositePart = opposite.replace("Mirror","");

    if(!active) {
        $(mirrorDiv).addClass("sel-blue");
        $("#" + opposite).removeClass("sel-blue");
        $("#" + currentPart).addClass("disabled");
        $("#" + oppositePart).removeClass("disabled");
    } else {
        $(mirrorDiv).removeClass("sel-blue");
        $("#" + currentPart).removeClass("disabled");
    }
};

var undo = function () {
    if(undoArray.length > 0) {

        var last = undoArray.pop();
        while(checkSameArrays(last, creature.parts[currentPart].colors)) {
            last = undoArray.pop();
        }
        copyColorArray(last, creature.parts[currentPart].colors);
        var mirror = checkMirror(currentPart);
        if(mirror != null) {
            copyColorArray(last, creature.parts[mirror].colors);
        }
        updateDraw();

        if(undoArray.length == 0) {
            $("#btnToolUndo").addClass("disabled");
        }
    }
};

var addUndo = function () {
    var copy = [];
    copyColorArray(creature.parts[currentPart].colors, copy);
    if(undoArray.length == 0 || !checkSameArrays(undoArray[undoArray.length - 1], copy)) {
        undoArray.push(copy);
        if(undoArray.length > undoMax) {
            undoArray.splice(0,1);
        }
    }
    if(undoArray.length > 0) {
        $("#btnToolUndo").removeClass("disabled");
    }
};

var copyColorArray = function (source, destination) {
    if(destination.length == 0) {
        for (var i = 0; i < gridSize.columns; i++) {
            var column = [];
            for (var j = 0; j < gridSize.rows; j++) {
                column.push("-");
            }
            destination.push(column);
        }
    }
    for (var i = 0; i < source.length; i++) {
        for (var j = 0; j < source[i].length; j++) {
            destination[i][j] = source[i][j];
        }
    }
};

var checkSameArrays = function (array1, array2) {
    if(array1.length != array2.length) {
        return false;
    }
    for (var i = 0; i < array1.length; i++) {
        if(array1[i].length != array2[i].length) {
            return false;
        }
        for (var j = 0; j < array1[i].length; j++) {
            if(array1[i][j] != array2[i][j]) {
                return false;
            }
        }
    }
    return true;
};

var updateDraw = function () {

    c.width = c.width;

    for (var i = 0; i < gridSize.columns; i++) {
        for (var j = 0; j < gridSize.rows; j++) {

            const start = {
                x: j * cellSize.w,
                y: i * cellSize.h
            };

            var color = creature.parts[currentPart].colors[i][j];
 
            ctx.fillStyle = color.indexOf("#") == -1 ? "#171717" : color;
            
            ctx.fillRect(start.x + 0.5, start.y + 0.5, cellSize.w, cellSize.h);
        
            ctx.strokeStyle = "#444444";
            ctx.strokeRect(start.x + 0.5, start.y + 0.5, cellSize.w, cellSize.h); 
        }
    } 

    for(anchor in creature.parts[currentPart].anchors) {
        var textPos = {
            x: (creature.parts[currentPart].anchors[anchor].column * cellSize.w) + (cellSize.w / 8),
            y: (creature.parts[currentPart].anchors[anchor].row * cellSize.h) + (2* (cellSize.h / 3))
        };
        var textPad = (anchor.toString().length == 1) ? 5 : 0;

        var circlePos = {
            x: (creature.parts[currentPart].anchors[anchor].column * cellSize.w) + (cellSize.w/2) + 0.5,
            y: (creature.parts[currentPart].anchors[anchor].row * cellSize.h) + (cellSize.h/2) + 0.5
        };
        //console.log(cellSize.w);
    
        ctx.beginPath();
        ctx.arc(circlePos.x, circlePos.y, (cellSize.w/2), 0, 2 * Math.PI, false);
        ctx.fillStyle = '#000000';
        ctx.fill();
    
        ctx.fillStyle="#ffffff";
        ctx.font='normal normal bold ' + (cellSize.w / 2) + 'pt Lucida Console';
        ctx.fillText(anchor.toString(),textPos.x + textPad,textPos.y);
    }

    if(selection.p1.x > -1) {
        ctx.setLineDash([5, 3]);
        ctx.strokeStyle = "#ffffff";

        var area = normalizePoints(selection.p1, selection.p2);

        ctx.strokeRect((area.start.x * cellSize.w) + 0.5, (area.start.y * cellSize.h) + 0.5, ((area.end.x - area.start.x + 1) * cellSize.w) - 1, ((area.end.y - area.start.y + 1) * cellSize.h) - 1);
    }

};

var checkAllPartsOk = function () {
    for(part in creature.parts) {
        var partOk = false;
        for (var i = 0; i < creature.parts[part].colors.length; i++) {
            for (var j = 0; j < creature.parts[part].colors[i].length; j++) {
                if(creature.parts[part].colors[i][j] != "-") {
                    partOk = true;
                    break;
                }
            }
        }   
        if(!partOk) {
            return false;
        }
    }
    return true;
};

var showError = function (message) {

    if(errorMessageTimer != null) {
        clearTimeout(errorMessageTimer);
    }

    $("#lblError").css("opacity", 1);
    $("#lblError").html(message);

    errorMessageTimer = setTimeout(function () {
        $("#lblError").animate({
            opacity: 0
        }, 400);
    }, 5000);

}

var save = function (approved = 0) {
    if(saveOk) {
            
        var name = $("#txtName").val().trim();
        if(name == "") {
            showError("Please type a name");
            $("#txtName").jqxInput('focus'); 
        } else if (!checkAllPartsOk()) {
            showError("All parts must have some content");
        } else {

            showLoader("load-save", "divName");

            creature.newApproved = approved == 1 && (creature.approved != approved);

            creature.name = name;
            creature.author.id = parseInt($("#hidUserId").val());
            creature.approved = approved;

            var createUpdate = (creature.id != null && creature.id > 0) ? "update" : "create";

            $.ajax({
                type: "POST",
                url: "controller/creature/"+createUpdate+".php",
                data: {creature: creature},
                success: function() {
                    
                    if(approved !== 0) {
                        hideLoader("load-save", "divName");

                        loadForApproval();
                    } else {
                        window.location = "list.php";
                    }
                },
                dataType: "json"
            });
        }
    }
};

var checkNameExists = function (name) {

    showError("Checking if a creature with this name exists...");
    $("#btnSave").addClass("disabled");
    $("#divExisting").addClass("display-none");

    $.ajax({
        type: "POST",
        url: "controller/creature/read.php",
        data: {name: name, notId: creature.id},
        success: function(result) {
            $("#btnSave").removeClass("disabled");

            if(result.records.length > 0) {
                showError("A creature with this name already exists");

                $("#divExisting").removeClass("display-none");
                createPreview($("#cnvExisting"), result.records[0].parts);

                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#cnvExisting").offset().top
                }, 500);

            } else {
                $("#lblError").html("");
                $("#divExisting").addClass("display-none");
                saveOk = true;
            }
        },
        dataType: "json"
    });
};

var updatePreview = function () {

    if(previewOk) {
        createPreview($("#cnvPreview"), creature.parts, animParams);
    }
};