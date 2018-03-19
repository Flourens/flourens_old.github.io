/* Crate report */
function setUpBulkSearchForm() {
    setDateRangePicker("input[name='bulk_search_date_range']");

    $("#bulk-search-report-form button[type=submit]").on('click', function() {
        convertDateRangeToUtc("input[name='bulk_search_date_range']");
    });
}
/****************/

/* View report */
function buildBulkSearchResults(results) {
    console.log("Bulk search");

    const resultsTableSelector = "#report-data-table";
    const resultsTableTimeRangeSelector = "#results-time-range";

    var table = $(resultsTableSelector).on('init.dt', function() {
            $("#report-data-table_filter").append(
                '<label>Date range: <input id="results-time-range" class="form-control input-sm" type="text"></label>'
            );
            addDateRangePicker(resultsTableTimeRangeSelector);

        }).DataTable({ 
            data:results,
            columns: [
                {
                    data: "plate",
                    render: function(data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        return getPlateNode(row);
                    },
                    title: "Plate",
                },
                {
                    data: "timestamp",
                    render: function(data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        return convertTimestamp(row['timestamp']);
                    },
                    title: "Date",
                },
                {data: "site", title: "Site"},
                {data: "camera", title: "Camera"},
            ],
            order: [[1, 'desc']],
    });

    table.on("draw", function() {
        console.log("Draw bulk search table");
        $("span.platecrop").hover(function() {
            togglePlatePreview(this);
        },
        function() {
            togglePlatePreview();
        });

        $(".croplink").colorbox({iframe:true, width:"90%", height:"90%"});
    });

    $.fn.dataTable.ext.search.push(dateRangeSearch);

    addDatePickerEvents(resultsTableTimeRangeSelector, table);

    table.draw();
}
/***************/
