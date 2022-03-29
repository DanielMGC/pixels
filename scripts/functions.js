const gridSize = {
    rows: 16,
    columns: 16
};

$(function() {
	FastClick.attach(document.body);
});

var createPreview = function (canvas, parts, animParams = null) {
    var show = false;
if(animParams == null){
    //console.log(parts);
    show = true;
}
    if(!animParams) {
        animParams = {
            angle: 40,
            dir: 1,
            continue: 1
        };
    }

    var pixelRatio = Math.round(window.devicePixelRatio) || 1;
    
    var cv = document.getElementById($(canvas).attr("id"));
    var cvCtx = cv.getContext("2d");

    var width = $(canvas).width() * pixelRatio;
    var height = $(canvas).height() * pixelRatio;
    cv.width = width;
    cv.height = height;

    var cells = {
        w: (width  / 3) / gridSize.columns,
        h: (height / 3) / gridSize.rows
    };

    cvCtx.lineWidth = 1;

    animParams.angle += (5 * animParams.dir * animParams.continue);
    if(animParams.angle >= 45) {
        animParams.dir = -1;
    }
    if(animParams.angle <= -45) {
        animParams.dir = 1;
    }

    const start = {
        x: Math.round(cv.width / 3),
        y: Math.round(cv.height / 3)
    };

    var startingPositions = {
        LeftArm: {
            x: start.x + (parts.Body.anchors.LA.column * cells.w),
            y: start.y + (parts.Body.anchors.LA.row * cells.w),
            difX: (parts.LeftArm.anchors.B.column * cells.w),
            difY: (parts.LeftArm.anchors.B.row * cells.w)
        },
        LeftLeg: {
            x: start.x + (parts.Body.anchors.LL.column * cells.w),
            y: start.y + (parts.Body.anchors.LL.row * cells.w),
            difX: (parts.LeftLeg.anchors.B.column * cells.w),
            difY: (parts.LeftLeg.anchors.B.row * cells.w)
        },
        Body: {
            x: start.x,
            y: start.y,
            difX: 0,
            difY: 0
        },
        RightLeg: {
            x: start.x + (parts.Body.anchors.RL.column * cells.w),
            y: start.y + (parts.Body.anchors.RL.row * cells.w),
            difX: (parts.RightLeg.anchors.B.column * cells.w),
            difY: (parts.RightLeg.anchors.B.row * cells.w)
        },
        Head: {
            x: start.x + (parts.Body.anchors.H.column * cells.w) - (parts.Head.anchors.B.column * cells.w),
            y: start.y + (parts.Body.anchors.H.row * cells.w) - (parts.Head.anchors.B.row * cells.w),
            difX: 0,
            difY: 0
        },
        RightArm: {
            x: start.x + (parts.Body.anchors.RA.column * cells.w),
            y: start.y + (parts.Body.anchors.RA.row * cells.w),
            difX: (parts.RightArm.anchors.B.column * cells.w),
            difY: (parts.RightArm.anchors.B.row * cells.w)
        }
        
    }

    for(part in startingPositions) {
        if(show){
            //console.log(part);
        }
        cvCtx.save();
        cvCtx.translate(startingPositions[part].x, startingPositions[part].y);
        if(part == "RightArm" || part == "LeftLeg") {
            cvCtx.rotate(animParams.angle*Math.PI/180);
        } else if(part == "LeftArm" || part == "RightLeg") {
            cvCtx.rotate((-animParams.angle)*Math.PI/180);
        }

        for (var i = 0; i < gridSize.columns; i++) {
            if(show){
                //console.log(i);
            }
            for (var j = 0; j < gridSize.rows; j++) {
                var color = "-";
                if(parts[part].colors[i] && parts[part].colors[i][j]) {
                    color = parts[part].colors[i][j];
                }
                if(color && color.indexOf("#") > -1) {
                    cvCtx.fillStyle = cvCtx.strokeStyle = color;
                    var pos = {
                        x: (j * cells.w) + 0.5 - startingPositions[part].difX,
                        y: (i * cells.h) + 0.5 - startingPositions[part].difY
                    }

                    cvCtx.fillRect(pos.x, pos.y, cells.w, cells.h);
                    cvCtx.strokeRect(pos.x, pos.y, cells.w, cells.h);
                    
                }
            }
        } 

        cvCtx.restore();
    }
    
};

var showLoader = function (classHide, area) {
    if(classHide != null) {
        $("." + classHide).addClass("display-none");
    }
    $("#" + area).append("<div class='div-load-area'><div class='div-loader'></div></div>");
};

var hideLoader = function (classShow, area) {
    if(classShow != null) {
        $("." + classShow).removeClass("display-none");
    }
    $("#" + area).find(".div-load-area").remove();
};

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }

    return null;
};

var getUrlParameterNoDecode = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }

    return null;
};

function deselectAll() {
    var element = document.activeElement;

    if (element && /INPUT|TEXTAREA/i.test(element.tagName)) {
        if ('selectionStart' in element) {
            element.selectionEnd = element.selectionStart;
        }
        element.blur();
    }

    if (window.getSelection) { // All browsers, except IE <=8
        window.getSelection().removeAllRanges();
    } else if (document.selection) { // IE <=8
        document.selection.empty();
    }
};