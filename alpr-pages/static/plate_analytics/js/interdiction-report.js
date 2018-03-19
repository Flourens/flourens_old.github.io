/* Create report */
function updatePassThrough() {
    const numOfCameras = $(
        "select[name=interdiction_report_cameras]"
    ).val().length;

    $("select[name=interdiction_must_pass_through]").html(
        `<option value='${numOfCameras}'>All sites</option>`
    );
    $("select[name=interdiction_must_pass_through]").attr("disabled", false);

    for (var i = 2; i < numOfCameras; i++) {
        $("select[name=interdiction_must_pass_through]").append(`
            <option value="${i}">At least ${i}</option>
        `);
    }
}

function interdictionFormIsValid() {
    var formState = true;
    if ($("select[name=interdiction_report_cameras]").val() === null) {
        $("#site-camera-group").addClass("has-error");
        formState = false;
    }

    if ($("input[name='interdiction_report_title']").val() == "") {
        $("#interdiction-title-group").addClass("has-error");
        formState = false;
    }

    return formState;
}

function setUpInterdictionReportForm() {
    const camerasSelectCss = "#interdiction_camera_selector";

    setDateRangePicker("input[name=interdiction_report_date_range]");

    loadCameras(
        siteId=$("#interdiction_site_selector").val(),
        selector=camerasSelectCss
    );

    $("#interdiction_site_selector").on("change", function() {
        loadCameras(
            siteId=$("#interdiction_site_selector").val(),
            selector=camerasSelectCss
        );
    });

    $("#interdition_add_camera").on("click", function() {
        addCamera(
            siteSelector="#interdiction_site_selector",
            cameraSelector="#interdiction_camera_selector",
            resultsSelector="select[name=interdiction_report_cameras]"
        );
        updatePassThrough();
    });

    $("#interdiction_report_submit").on("click", function() {
        if (interdictionFormIsValid()) {
            convertDateRangeToUtc("input[name=interdiction_report_date_range]");
            $("#interdiction-report-form").submit();
        } else {
            console.error("Form error");
        }
    });
}

/*****************/

/* View report */
function getPopOverContent(rawJson) {
    function makeCameraNode(cameraId, data) {
        console.log(data[cameraId]);

        var node = $("<div></div>");
        node.append($(`<p>Camera: <strong>${data[cameraId].name}</strong></p>`));

        var tsList = $("<ul></ul>");
        
        data[cameraId].occurences.forEach(function(timestamp, index) {
            const date = convertTimestamp(timestamp);
            tsList.append($(`<li>${date}</li>`));
        });

        node.append(tsList);

        return node.prop("outerHTML");
    }

    try {
        const data = JSON.parse(rawJson);
        var node = $("<div></div");

        for (var i in data) {
            const cameraNode = makeCameraNode(i, data);
            node.append(cameraNode);
        }
        return node.prop("outerHTML");

    } catch(error) {
        console.error(error);
        return "<p>Cannot load details.</p>";
    }
}

function getInterdictionNode(row) {
    const content = getPopOverContent(row['interdiction_details']);
    const interdictionNode = `
        <a data-toggle="popover" title="Details" data-content='${content}'>${row['interdiction']}</a>
    `;

    return interdictionNode;
}

function buildInterdictionResults(results) {
    console.log("Interdiction");

    const resultsTableSelector = "#report-data-table";

    var table = $(resultsTableSelector).DataTable({
        data: results,
        columns: [
            {
                data: "plate", 
                render: function(data, type, row) {
                    if (type === "sort" || type === "type") {
                        return data;
                    }
                    return getPlateNode(row);
                },
                title: "Plate"
            },
            {
                data: "first_seen", 
                title: "First seen",
                render: function(data, type, row) {
                    if (type === "sort" || type === "type") {
                        return data;
                    }
                    return convertTimestamp(row.first_seen);
                }
            },
            {
                data: "last_seen", 
                title: "Last seen",
                render: function(data, type, row) {
                    if (type === "sort" || type === "type") {
                        return data;
                    }
                    return convertTimestamp(row.last_seen);
                }
            },
            {
                data: "interdiction", 
                title: "Interdiction",
                render: function(data, type, row) {
                    if(type === "sort" || type === "type") {
                        return data;
                    }
                    return getInterdictionNode(row);
                }
            },
        ]
    });

    table.on("draw", function() {
        console.log("Draw interdiction table");

        $("span.platecrop").hover(function() {
            togglePlatePreview(this);
        },
        function() {
            togglePlatePreview();
        });

        initPopovers();

        $(".croplink").colorbox({iframe:true, width:"90%", height:"90%"});
    });

    table.draw();
}
/***************/
