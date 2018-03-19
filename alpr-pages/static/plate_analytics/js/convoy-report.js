/* Crate report */
function setUpConvoyReportForm() {
    const camerasSelectCss = "#convoy_camera_selector";
    const siteSelectCss = "#convoy_site_selector";

    setDateRangePicker("input[name='convoy_report_date_range']");

    addNumSpinner("#convoy-period");

    $("#convoy_report_submit").on("click", function() {
        convertDateRangeToUtc("input[name=convoy_report_date_range]");
        $("#convoy-report-form").submit();
    });

    $("select[name=convoy_report_cameras]").selectpicker('selectAll');
}
/****************/

/** View report */
function occurencesDetailsNode(data, type, row) {
    if (type === "sort" || type === "type") {
        return data;
    }

    var details = $("<div></div>");

    $.each(row.occurences_details.details, function(i, detailObj) {
        var detailsList = $("<ul></ul>");
        var occurenceTime = detailObj.timestamp;
        occurenceTime = convertTimestamp(occurenceTime);

        details.append(`<p><strong>Time:</strong> ${occurenceTime}`);

        detailsList.append(`
            <li>Camera: ${detailObj.camera}</li>
            <li>Time diff: ${detailObj.time_diff}</li>
        `);

        details.append(detailsList);
    });

    details = details.prop("outerHTML");

    return `<a data-toggle="popover" title="Details" data-content="${details}">Details</a>`;
}

function buildConvoyResults(results) {
    console.log("Convoy");

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
                    return getPlateNode(row)
                },
                title: "Plate"
            },
            {
                data: "occurences_number", title: "Occurences"
            },
            {
                data: "occurences_details",
                title: "Details",
                render: occurencesDetailsNode,
            }
        ],
        order: [[1, 'desc']],
    });

    table.on("draw", function() {
        console.log("Draw convoy table.");

        $("span.platecrop").hover(function() {
            togglePlatePreview(this);
        },
        function() {
            togglePlatePreview();
        });

        initPopovers();

        $("a.croplink").colorbox({iframe:true, width:"90%", height:"90%"});
    });

    table.draw();
}
/****************/
