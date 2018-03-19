DATE_TIME_FORMAT = "ddd MMM D H:mm:ss Y";
DATE_RANGE_DELIMITER = " - ";


function writeReportDescription(description) {
    $("#report-type-description p").text(description);
}

function toggleSpinner(selector) {
    if ($(selector).hasClass("fa")) {
        $(selector).removeClass("fa fa-refresh fa-spin");
    } else {
        $(selector).text("");
        $(selector).addClass("fa fa-refresh fa-spin");
    }
}

function displayForm(reportType) {
    if (reportType === "INTERDICTION") {
        $("#interdiction-report-form").css("display", "block");

    } else if (reportType === "BULK_SEARCH") {
        $("#bulk-search-report-form").css("display", "block");

    } else if (reportType === "CONVOY") {
        $("#convoy-report-form").css("display", "block");
    }
}

function setReportAttributes(reportType, reportUrl) {
    $.ajax({
        url: `/plate_analytics/ajax/report-type-info?report_type=${reportType}`,
        beforeSend: function() {
            toggleSpinner("#report-type-description p");
        },
        success: function(resp) {
            toggleSpinner("#report-type-description p");
            writeReportDescription(resp.report_description);
        },
        fail: function(resp) {
            console.error("Cannot get report type details");
        },
    });

    displayForm(reportType);

    $("#report-type-selector").val(reportType);
    console.log(reportType);
}

function initReport(initReportType) {
    if (initReportType.length !== 0) {
        setReportAttributes(initReportType);
        return true;
    }

    return false;
}

function convertToUTC(timestamp) {
    var ts = timestamp.trim();
    return moment(ts, DATE_TIME_FORMAT).utc().format(DATE_TIME_FORMAT);
}

function convertDateRangeToUtc(selector) {
    const curRange = $(selector).val();
    console.log(curRange);

    var utcRanges = [];

    curRange.split(DATE_RANGE_DELIMITER).map(function(timestamp, i) {
        var utcTimestamp = convertToUTC(timestamp)
        utcRanges.push(utcTimestamp);
    });

    const utcDateRange = utcRanges.join(DATE_RANGE_DELIMITER);
    console.log(utcDateRange);

    $(selector).val(utcDateRange);
}

function setDateRangePicker(selector) {
    $(selector).daterangepicker({

        /* Default last 7 days */
        startDate: moment().subtract(7, 'days'),
        endDate: moment(),
        /***********************/ 

        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: DATE_TIME_FORMAT
        },
        ranges: {
            'Today': [moment().startOf('day'), moment().endOf('day')],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(7, 'days'), moment()],
            'Last Week': [
                moment().subtract(1, 'week').startOf('week'),
                moment().subtract(1, 'week').endOf('week'),
            ],
            'Last 30 Days': [moment().subtract(30, 'days'), moment()],
            'Last Month': [
                moment().subtract(1, 'month').startOf('month'),
                moment().subtract(1, 'month').endOf('month')
            ]
        },
    });
}

function addNumSpinner(selector) {
    const incrementSelector = "a.num-spinner-increment";
    const decrementSelector = "a.num-spinner-decrement";
    const inputSelector = "input.spinner-value";

    $(selector).find(incrementSelector).on("click", function() {
        var num = $(selector).find(inputSelector).val();
        num = parseInt(num) + 1;
        $(selector).find(inputSelector).val(num);
    });

    $(selector).find(decrementSelector).on("click", function() {
        var num = $(selector).find(inputSelector).val();
        num = parseInt(num);
        if (num > 1) {
            $(selector).find(inputSelector).val(num - 1);
        }
    });

}

function loadCameras(siteId, selector) {
    const url = `/analytics/ajax/get-cameras/${siteId}`;
    console.log(url);

    $(selector).html("");

    $.getJSON(url, function(data) {
        $.each(data.results, function(i, obj) {
            $(selector).append(`
                <option value="${obj.id}">${obj.camera_name}</option>
            `);
        });
    });
}

var addCamera = (function() {
    var cameras = {};

    return function(siteSelector, cameraSelector, resultsSelector) {

        if (cameras[resultsSelector] === undefined) {
            cameras[resultsSelector] = [];
        }

        $(".site-camera-group").removeClass("has-error");

        const siteId = $(siteSelector).val();
        const cameraId = $(cameraSelector).val();

        if (siteId === "" || cameraId === "") {
            $(".site-camera-group").addClass("has-error");
            console.error("Cannot add empty site/camera");
            return false;
        }

        if (cameras[resultsSelector].indexOf(cameraId) !== -1) {
            $(".site-camera-group").addClass("has-error");
            console.error("Cannot add, already exist.")
            return false;
        }

        const siteName = $(`${siteSelector} option:selected`).text();
        const cameraName = $(`${cameraSelector} option:selected`).text();

        $(resultsSelector).append(`
            <option selected value="${cameraId}">${siteName} / ${cameraName}</option>
        `);

        cameras[resultsSelector].push(cameraId);

        return true;
    }
})();
