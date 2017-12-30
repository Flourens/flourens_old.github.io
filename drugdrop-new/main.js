'use strict';


var socket = io();

$(document).ready(function () {


    var canvas = document.getElementsByClassName('whiteboard')[0];
    var context = canvas.getContext('2d');
    var currentImg, cImg, cPage, gPDF, pdfRendering = false, xOffset = 0, yOffset = 20;

    var fname = $('#fname').val();
    var ftype = $('#ftype').val();

    if (ftype === 'application/pdf') {
        loadPDF(fname);
    } else {
        loadJPEG(fname);
    }

    //init();

    var current = {
        color: 'blue'
    };

    canvas.addEventListener('mousemove', throttle(onMouseMove, 10), false);
    document.getElementById("the-drawin-canvas").addEventListener('mousemove', throttle(onMouseMove, 10), false);

    socket.on('cursor', onCursorEvent);

    socket.on('cremove', function (data) {
        $('.' + data.color).hide();
    });

	//TODO: Investigate here
    socket.on('draw', function (data) {

        if (data.lastY > 1 || data.lastX > 1)
            return;

        var w = cImg.width;
        var h = cImg.height;
        var lastX = data.lastX * w;
        var lastY = data.lastY * h - yOffset;

        var mouseX = data.mouseX * w;
        var mouseY = data.mouseY * h - yOffset;


        var ctx = document.getElementById("the-drawin-canvas").getContext("2d");
        ctx.beginPath();
        ctx.globalCompositeOperation = "source-over";
        ctx.strokeStyle = "#" + data.color;
        ctx.lineWidth = 5;
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(mouseX, mouseY);

        ctx.stroke();
    });

	//TODO: Investigate here
    socket.on('erase', function (data) {
        if (data.lastY > 1 || data.lastX > 1)
            return;

        var w = cImg.width;
        var h = cImg.height;
        var lastX = data.lastX * w;
        var lastY = data.lastY * h - yOffset;

        var ctx = document.getElementById("the-drawin-canvas").getContext("2d");
        ctx.beginPath();
        ctx.globalCompositeOperation = "destination-out";
        ctx.arc(lastX, lastY, 8, 0, Math.PI * 2, false);

        ctx.fill();
    });


    var room = window.location.pathname;

    socket.on('connect', function () {
        socket.emit('room', room);
    });

    window.addEventListener('resize', onResize, false);
    onResize();

    function onMouseMove(e) {
        if (!cImg) return;
        var w = cImg.width;
        var h = cImg.height;
        var x = (e.clientX - xOffset) / w;
        var y = e.clientY / h;
        var data = {x: x, y: y, color: '0000ff'};
        socket.emit('cursor', data);


        //mouseX = parseInt(e.clientX - offsetX);
        //mouseY = parseInt(e.clientY - offsetY);
        mouseX = x;
        mouseY = y;

        if (isMouseDown) {
            ctx.beginPath();
            if (mode === "pen") {
                socket.emit("draw", {lastX: lastX, lastY: lastY, mouseX: mouseX, mouseY: mouseY});

            } else {
                socket.emit("erase", {lastX: lastX, lastY: lastY});
            }
            lastX = mouseX;
            lastY = mouseY;
        }

        return;
    }

    // limit the number of events per second
    function throttle(callback, delay) {
        var previousCall = new Date().getTime();
        return function () {
            var time = new Date().getTime();

            if ((time - previousCall) >= delay) {
                previousCall = time;
                callback.apply(null, arguments);
            }
        };
    }

    function onCursorEvent(data) {
        color = data.color;

        var w = cImg.width;
        var h = cImg.height;

        var da_div = $('.' + data.color);
        if (da_div.length == 0) {
            da_div = $('<div>').addClass('client').addClass(data.color).css('background', "#" + data.color).appendTo('body');
        }
        var left = data.x * w + xOffset;
        da_div.css({top: data.y * h, left: left});
    }

    // make the canvas fill its parent
    function onResize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        if (ftype === 'application/pdf') {
//      redrawPDF(fname);
            loadPDF(fname);
        } else {
            redrawJPEG(fname);
        }


        if (drawn) {
            $('#the-drawin-canvas').css("width", $("#the-canvas").width(), 'important');
            $('#the-drawin-canvas').css("height", $("#the-canvas").height(), 'important');
        }

    }

    function redrawJPEG() {
        var canvas = document.getElementById('the-canvas');
        var context = canvas.getContext('2d');
        var hRatio = canvas.width / currentImg.width;
        var vRatio = canvas.height / currentImg.height;
        var ratio = Math.min(hRatio, vRatio);


        canvas.height = currentImg.height * ratio;
        canvas.width = currentImg.width * ratio;
        xOffset = (window.innerWidth - canvas.width) / 2;
        var m = (xOffset > 0) ? xOffset : 0;
        $('#the-canvas').css({'margin': '0 ' + m + 'px'});
        $('#the-drawin-canvas').css({'margin': '0 ' + m + 'px'});

        context.drawImage(currentImg, 0, 0, currentImg.width, currentImg.height,
            0, 0, currentImg.width * ratio, currentImg.height * ratio
        );
        cImg = {width: currentImg.width * ratio, height: currentImg.height * ratio};

    }

    function redrawPDF() {
        var pdf = gPDF;
        var pageNumber = 1;
        if (!pdf) return;
        //    if (pdfRendering) return;
        //    pdfRendering = true;
        pdf.getPage(pageNumber).then(function (page) {
            // console.log('Page loaded');

            var canvas = document.getElementById('the-canvas');
            var context = canvas.getContext('2d');

            var scale = 1; //1.5;
            var viewport = page.getViewport(scale);

            // Prepare canvas using PDF page dimensions
            var hRatio = window.innerWidth / viewport.width;
            var vRatio = window.innerHeight / viewport.height;
            var ratio = Math.min(hRatio, vRatio);

            canvas.height = viewport.height * ratio;
            canvas.width = viewport.width * ratio;

            var viewport = page.getViewport(ratio);

            xOffset = (window.innerWidth - canvas.width) / 2;
            var m = (xOffset > 0) ? xOffset : 0;
            $('#the-canvas').css({'margin': '0 ' + m + 'px'});
            $('#the-drawin-canvas').css({'margin': '0 ' + m + 'px'});
            var renderContext = {
                canvasContext: context,
                viewport: viewport
            };
//console.log(renderContext);
            var renderTask = page.render(renderContext);
            renderTask.then(function () {
                // console.log('Page rendered');
                cImg = {width: canvas.width, height: canvas.height};
                pdfRendering = false;
            });
        });

    }

    function loadJPEG() {
        var img1 = new Image();

        img1.onload = function () {
            currentImg = img1;
            redrawJPEG();
            init();
        };

        img1.src = 'uploads/' + fname;
    }

    function loadPDF(fname) {
        var url = '/uploads/' + fname;
        if (pdfRendering) return;
        pdfRendering = true;

        // The workerSrc property shall be specified.
        PDFJS.workerSrc = 'http://mozilla.github.io/pdf.js/build/pdf.worker.js';

        // Asynchronous download of PDF
        var loadingTask = PDFJS.getDocument(url);
        loadingTask.promise.then(function (pdf) {
            gPDF = pdf;
            redrawPDF();
            init();

        }, function (reason) {
            // PDF loading error
            console.error(reason);
        });

    }

    init();
});
