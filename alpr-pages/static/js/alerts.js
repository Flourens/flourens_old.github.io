$( document ).ready(function(){

    $("#loading_spinner").hide();

    var alerts = [{

    }];

    var currentGroup = {};
    var sites = [];
    var recipients = [];
    var csrf = $('.csrf_token').val();
    var strategies = ["Exact", "Lenient"];

    function start_alert_spinner()
    {
        $("#loading_spinner").show(); $('#delete-alert-button').addClass('loading');
        $('#change-alert-button').addClass('loading');
        $('#delete-alert-group').addClass('loading');
        $('#add-alert-button').addClass('loading');
        $('#new-alert-group').addClass('loading');
        $('#export-csv-link').addClass('loading');
        $('#import-csv-link').addClass('loading');
        $("#alert-group").attr('disabled', true);
        $("#alerts").attr('disabled', true);

    }

    function end_alert_spinner(success, message)
    {
        $("#loading_spinner").hide();
        $('#delete-alert-button').removeClass('loading');
        $('#change-alert-button').removeClass('loading');
        $('#delete-alert-group').removeClass('loading');
        $('#add-alert-button').removeClass('loading');
        $('#new-alert-group').removeClass('loading');
        $('#export-csv-link').removeClass('loading');
        $('#import-csv-link').removeClass('loading');
        $("#alert-group").attr('disabled', false);
        $("#alerts").attr('disabled', false);
    }

    function rebuildTitle(id, title)
    {
        $('#group-main-title').editable('destroy');
        $('.group-main-title').remove();

        var $editableTitle = $('<a id="group-main-title" token="' + csrf + '" data-id="' + id + '" href="#" title="' + title + '">' + title + '</a>');

        $('.main-box-header').html($editableTitle);

        $('#group-main-title').editable({
           type:  'text',
           pk:    1,
           name:  'name',
           url:   '/api/alert-group/update/'+id+'/',
           title: 'Enter Alert Group Name',
           send: 'always',
           ajaxOptions: {
               type: 'PUT',
               headers:
                {
                    'X-CSRFToken':  csrf,
                    'Content-Type': 'application/json'
                }
           },
           params: function(params) {
               params.pk = $(this).data('id');
               params.name = params.value;
               delete params.value;
               params = JSON.stringify(params);
               return params;
           },
           success: function(result, newValue) {
             $('#alert-group option[value='+id+']').text(newValue);
           },
           error: function(jqXHR, textStatus, errorThrown) {
             const response = JSON.parse(jqXHR.responseText).name[0];
             $(".editable-error-block").html(response);
           }
        });

        $("#group-main-title").on('save', function(e, params) {
            $(this).attr("title", params.newValue);
        });
    }

    function rebuildAlerts(data)
    {
        var alerts_select = $("select.alerts-list");
        alerts_select.empty();
        for(var i = 0; i<data.length; i++ ){
            // Title case the match strategy
            //match_strategy_friendly = data[i].match_strategy[0].toUpperCase() + data[i].match_strategy.substring(1);
            var friendly_description = '';
            if (data[i].description.length > 0)
                friendly_description = ' (' + data[i].description + ')'
            alerts_select.append($('<option>', {value:data[i].id, text: data[i].plate_number + friendly_description}));
        }
    }

    function fillModalWindowAlert(alertData)
    {
        var strategyStr = '';
        for(var i=0; i<strategies.length; i++){
            if(strategies[i].toLowerCase() === alertData.match_strategy){
                strategyStr += '<option value="' + strategies[i].toLowerCase() + '" selected>' + strategies[i] + '</option>';
            } else {
                strategyStr += '<option value="' + strategies[i].toLowerCase() + '">' + strategies[i] + '</option>';
            }
        }

        $('#alert-strategy').empty().append(strategyStr);

        $('.alert-plate-number').val(alertData.plate_number);
        $('.alert-description').val(alertData.description);
        $('.alert-alert-group').val(currentGroup.name);
        $('.alert-id').val(alertData.id);
    }

    function checkAlertButtons()
    {
        if(alerts.length === 0){
            $('#delete-alert-button').prop('disabled', true);
            $('#change-alert-button').prop('disabled', true);
        } else {
            $('#delete-alert-button').prop('disabled', false);
            $('#change-alert-button').prop('disabled', false);
        }
    }


    function rebuildRecipientsData(alertRecipients)
    {
        $('#email-recipients').multiselect('destroy');

        for(var i=0; i<alertRecipients.length; i++){
            alertRecipients[i] = parseInt(alertRecipients[i]);
        }

        var siteOptionStr = '';

        $("#email-recipients option").each(function() {
            var value = $(this).val();
            var text = $(this).text();
            if( jQuery.inArray( parseInt(value), alertRecipients ) !== -1 ){
                siteOptionStr += '<option value="' + value + '" selected>' + text + '</option>';
            } else {
                siteOptionStr += '<option value="' + value + '">' + text + '</option>';
            }
        });

        $('#email-recipients').empty().append(siteOptionStr);

        $('#email-recipients').multiselect({
            enableHTML: true,
            // includeSelectAllOption: true,
            buttonWidth: '100%',
            onChange: function(element, checked) {

                var selecteditems = '';

                $('#email-recipients option:selected').each(function () {
                    selecteditems = selecteditems + $(this).val() + ',';
                });

                if (selecteditems.indexOf(',', this.length - ','.length) !== -1)
                    selecteditems = selecteditems.substring(0, selecteditems.length - 1);

                var dataArray = selecteditems.split(',');
                if(selecteditems.length === 0){
                    dataArray = [];
                }

                var id = $('#alert-group').val();

                $.ajax({
                    method: 'PUT',
                    url: '/api/alert-group/update/' + id + '/',
                    dataType: 'json',
                    data:JSON.stringify({
                        "pk": id[0],
                        "recipients": dataArray
                    }),
                    headers: {
                        'X-CSRFToken':  csrf,
                        'Content-Type': 'application/json'
                    },
                    success: function(result){
                        currentGroup.recipients = dataArray;
                        currentGroup.recipients_full_data = result.recipients_full_data;
                    }
                });

            }
        });

    }

    function rebuildManagersData(managersSites)
    {
        $("#alert-group-managers").multiselect('destroy');
        for(var i=0; i<managersSites.length; i++){
            managersSites[i] = parseInt(managersSites[i]);
        }

        var managersOptionStr = '';

        $("#alert-group-managers option").each(function() {
            var value = $(this).val();
            var text = $(this).text();
            if( jQuery.inArray( parseInt(value), managersSites ) !== -1 ){
                managersOptionStr += '<option value="' + value + '" selected>' + text + '</option>';
            } else {
                managersOptionStr += '<option value="' + value + '">' + text + '</option>';
            }
        });

        $('#alert-group-managers').empty().append(managersOptionStr);
        $("#alert-group-managers").multiselect({
            enableHTML: true,
            buttonWidth: '100%',
            onChange: function(element, checked) {
                var selecteditems = '';
                $('#alert-group-managers option:selected').each(function() {
                    selecteditems = selecteditems + $(this).val() + ',';
                });
                if (selecteditems.indexOf(',', this.length - ','.length) !== -1)
                    selecteditems = selecteditems.substring(0, selecteditems.length - 1);

                var dataArray = selecteditems.split(',');
                var id = $('#alert-group').val();

                if(selecteditems.length === 0){
                    dataArray = [];
                }

                $.ajax({
                    method: 'PUT',
                    url: '/api/alert-group/update/' + id + '/',
                    dataType: 'json',
                    data:JSON.stringify({
                        "pk": id[0],
                        "managers": dataArray
                    }),
                    headers: {
                        'X-CSRFToken':  csrf,
                        'Content-Type': 'application/json'
                    },
                    success: function(result){
                        currentGroup.managers = dataArray;
                        currentGroup.managers_full_data = result.managers_full_data;
                    }
                });
            }
        });
    }

    function rebuildSitesData(currentGroup)
    {
        $('#sites').multiselect('destroy');
        for(var i=0; i<currentGroup.sites.length; i++){
            currentGroup.sites[i] = parseInt(currentGroup.sites[i]);
        }

        var siteOptionStr = '';

        $("#sites option").each(function() {
            var value = $(this).val();
            var text = $(this).text();
            if( currentGroup.all_sites === true || jQuery.inArray( parseInt(value), currentGroup.sites ) !== -1 ){
                siteOptionStr += '<option value="' + value + '" selected>' + text + '</option>';
            } else {
                siteOptionStr += '<option value="' + value + '">' + text + '</option>';
            }
        });

        $('#sites').empty().append(siteOptionStr);

        $('#sites').multiselect({
            enableHTML: true,
            includeSelectAllOption: true,
            buttonWidth: '100%',
            onChange: function(element, checked) {

                var selecteditems = '';

                var allSelected = $("#sites option:not(:selected)").length;

                $('#sites option:selected').each(function() {
                    selecteditems = selecteditems + $(this).val() + ',';
                });

                if (selecteditems.indexOf(',', this.length - ','.length) !== -1)
                    selecteditems = selecteditems.substring(0, selecteditems.length - 1);

                var dataArray = selecteditems.split(',');
                var id = $('#alert-group').val();

                if(selecteditems.length === 0){
                    dataArray = [];
                }


                $.ajax({
                    method: 'PUT',
                    url: '/api/alert-group/update/' + id + '/',
                    dataType: 'json',
                    data:JSON.stringify({
                        "pk": id[0],
                        "all_sites": allSelected === 0,
                        "sites": dataArray
                    }),
                    headers: {
                        'X-CSRFToken':  csrf,
                        'Content-Type': 'application/json'
                    },
                    success: function(result){
                        currentGroup.sites = dataArray;
                        currentGroup.sites_full_data = result.sites_full_data;
                    }
                });

            }
        });

    }
    function setListTypeLabel(data) {
        if (data.list_type === "black") {
            $("#list-type-symbol").html("<span data-toggle='tooltip' title='Black list'><i class='fa fa-user-secret' aria-hidden='true'></i></span> ");
        } else {
            $("#list-type-symbol").html("<span data-toggle='tooltip' title='White list'><i class='fa fa-user-o' aria-hidden='true'></i></span>  ");
        }
        $("#list-type-symbol span").tooltip();
    }
    function setLastModified(data) {
        const dateString = moment(data.last_modified).fromNow();
        $("#last-modified").html("<strong>Last modified: </strong>");
        $("#last-modified").append(dateString);
    }

    function updateSearchLink(group_id)
    {
        $("#search_link").attr('href', '/search/#search_type=alert&alertlist=' + group_id)
    }
    function loadAlertGroupData(group_id)
    {
        $('.alert-group-panel-body').css('visibility', 'hidden');
        updateSearchLink(group_id);

        $.get('/api/get-groups/' + group_id + '/', function(data){

            console.log(data);

            if(typeof data.definitions !== "undefined"){
                alerts = data.definitions;
                server_alert_count = data.definitions_count

                if (alerts.length < server_alert_count)
                {
                    // The Web UI can only handle 1000 or so alerts, so we clipped it.
                    // tell the user that they need to manage this via CSV import/export
                    $(".alert-group-panel-body .alerts-count").text(alerts.length + " / " + server_alert_count + " alerts")
                }
                else if (alerts.length > 0)
                {
                    $(".alert-group-panel-body .alerts-count").text(alerts.length + " alerts")
                }
                else
                {
                    $(".alert-group-panel-body .alerts-count").text("")
                }

                alerts.sort(function(a, b){
                    if(a.plate_number < b.plate_number) return -1;
                    if(a.plate_number > b.plate_number) return 1;
                    return 0;
                });

                checkAlertButtons();
                rebuildAlerts(alerts);
                currentGroup = data;
                var sitesStr = '';
                if(currentGroup.sites_full_data.length !==0){
                    sitesStr = '<div class="current-group-title">Current sites</div><div class="current-group-data">';
                    for(var i=0; i<currentGroup.sites_full_data.length; i++){
                        sitesStr += '<div>' + currentGroup.sites_full_data[i].site_id + '</div>';
                    }

                    sitesStr += '</div>';
                }

                $('.current-sites').html(sitesStr);
                var recipientsStr = '';
                if(currentGroup.recipients_full_data.length !==0){
                    recipientsStr = '<div class="current-group-title">Current Recipients</div><div class="current-group-data">';
                    for(var i=0; i<currentGroup.recipients_full_data.length; i++){
                        recipientsStr += '<div>' + currentGroup.recipients_full_data[i].email + '</div>';
                    }

                    recipientsStr += '</div>';
                }
                $('.current-recipients').html(recipientsStr);

                rebuildSitesData(currentGroup);
                rebuildRecipientsData(currentGroup.recipients);
                rebuildManagersData(currentGroup.managers);

                rebuildTitle(data.id, data.name);
                $('#delete-alert-button').prop('disabled', true);
                $('#change-alert-button').prop('disabled', true);
                $('.alert-group-panel-body').css('visibility', 'visible');

                setListTypeLabel(data);
                setLastModified(data);
            }
        });
    }

    function showErrors(elem, text)
    {
        $(elem).find('.custom-alert').html(text);
        $(elem).fadeIn();
        setTimeout(function(){$(elem).fadeOut();}, 5000);

    }

    function setTooltip(message) {
        $('.help-strategy')
            .attr('data-original-title', message)
            .tooltip('show');
    }

    function hideTooltip() {
        $('.help-strategy').tooltip('hide');
    }

    function init()
    {

        $('#alert-group').on('change', function(){
            $('#delete-alert-group').prop('disabled', false);
            var id = $(this).val();
            loadAlertGroupData(id);
        });

        $('body').on('change', '.alerts-list', function(){
            $('#delete-alert-button').prop('disabled', false);
            $('#change-alert-button').prop('disabled', false);
        });

        $('#alert-group-save').on('click', function(){
            var data = {};
            data.name = $('.alert-group-name').val();
            if(data.name.trim() === ''){
                showErrors('.add-alert-group-error', 'Alert list name cannot be empty');
                return;
            }
            if (data.name.length > 128)
            {
                showErrors('.add-alert-group-error', 'Alert list name is too long');
                return;
            }
            data.csrfmiddlewaretoken = csrf;

            data.list_type = $("#list-type").val();

            $.post('/api/alert-group/create', data, function(data){
                $('#alert-group').append('<option value="' + data.id + '">' + data.name + '</option>');
                $('.alert-group-name').val('');
                $('#myAddGroupModal').modal('hide');
            }).fail(function(response){
                $('#myAddGroupModal').modal('hide');
                $('.alert-group-name').val('');
                bootbox.alert(response.statusText);
            });
        });

        $('.export-csv').on('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            var data = {};
            data.pk = currentGroup.id;
            data.name = currentGroup.name;
            $('#download-url').html('<a id="load" style="display:none" href="/api/alert-group-export-csv/?pk='+data.pk+'&name='+data.name+'">Click</a>');
            $('#download-url').find('a')[0].click();
        });

        $('#delete-alert-button').on('click', function(){
            bootbox.confirm({
                message: "Are you sure that you wish to delete this alert?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-default'
                    }
                },
                callback: function(result) {
                    if(result){
                        start_alert_spinner();
                        var id = $('.alerts-list').val();
                        $.ajax({
                            type: 'DELETE',
                            url: '/api/alert/delete/'+id+'/',
                            headers: {
                                'X-CSRFToken':  csrf
                            },
                            success: function(result){
                                $('#alerts option[value='+id+']').remove();
                                $('#delete-alert-button').prop('disabled', true);
                                $('#change-alert-button').prop('disabled', true);
                                end_alert_spinner(true, "");

                                const alertGroupId = $("#alert-group").val();
                                loadAlertGroupData(alertGroupId);
                            },
                            fail: function(result){
                                end_alert_spinner(false, "Failed to delete alert");

                            }
                        });

                    }
                }
            });
        });

        $('#delete-alert-group').on('click', function(){
            bootbox.confirm({
                message: "Deleting a Alert List will remove ALL alerts that are associated with it. Are you sure?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-default'
                    }
                },
                callback: function(result) {
                    if(result){
                        var id = $('#alert-group').val();
                        $.ajax({
                            type: 'DELETE',
                            url: '/api/alert-group/delete/' + id + '/',
                            headers: {
                                'X-CSRFToken':  csrf
                            },
                            success: function(result){
                                $('#alert-group option[value='+id+']').remove();
                                $('#alert-group').val($("#alert-group option:first").val()).change();
                            }
                        });
                    }
                }
            })
        });

        $('.edit-alert-data').on('click', function(){
            $(".add-edit-alert-header").text('Edit Alert');
            var alert_id = $('#alerts').val();
            var alertData = {};

            for(var i=0; i<alerts.length; i++){
                if(alerts[i].id === parseInt(alert_id)){
                    alertData = alerts[i];
                }
            }

            fillModalWindowAlert(alertData);
            $('#addAlertModal').modal('show');
        });

        $('.add-alert-data').on('click', function(){
            $(".add-edit-alert-header").text('Add Alert');
            $('.alert-plate-number').val('');
            $('.alert-description').val('');
            $('.alert-alert-group').val(currentGroup.name);
            $('.alert-id').val('');

            $('#addAlertModal').modal('show');
        });

        $('#alert-save').on('click', function(){
            var data = {};
            var url = '/api/alert/create';
            var method = 'POST';

            if($('.alert-plate-number').val() === ''){
                showErrors('.add-alert-error', 'Missing Plate Number');
                return;
            }


            data.plate_number = $('.alert-plate-number').val();
            data.description = $('.alert-description').val();
            data.alert_group = currentGroup.id;
            data.match_strategy = $('#alert-strategy').val();
            data.csrfmiddlewaretoken = csrf;

            if($('.alert-id').val() !== ''){
                data.pk = $('.alert-id').val();
                method = 'PUT';
                url = '/api/alert/update/'+data.pk+'/';
            }

            $.ajax({
                url:url,
                method:method,
                data:data,
                headers:
                {
                    'X-CSRFToken':  csrf
                },
                success: function(result){
                    loadAlertGroupData(currentGroup.id);
                    $('#addAlertModal').modal('hide');
                },
                error: function(data) {
                    if (data.hasOwnProperty('responseJSON') && data.responseJSON.hasOwnProperty('non_field_errors'))
                        showErrors('.add-alert-error', data.responseJSON.non_field_errors[0]);
                }
            });

        });

        $("#btnSubmit").click(function (event) {

            event.preventDefault();
            $(this).button("loading");

            var form = $('#fileUploadForm')[0];
            var data = new FormData(form);

            data.append("pk", currentGroup.id);
            data.append("import", "import");

            $("#btnSubmit").attr('disabled', true);
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "/api/alert-group-import-csv/",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                headers:
                {
                    'X-CSRFToken':  csrf
                },
                success: function (result){
                    $('#addCsvModal').modal('hide');

                    // In case if the button was in error state.
                    $("#btnSubmit").removeClass('btn-danger');
                    $("#btnSubmit").addClass('btn-primary');

                    if (result.success)
                    {
                        //added, deleted, modified, unmodified
                        var str = '<h3>Import Successful</h3>' +
                        '<div style="margin-top:10px;"><table class="table">' +
                        '<tr><td>Alerts Added</td><td>' + result.added + '</td></tr>' +
                        '<tr><td>Alerts Updated</td><td>' + result.modified + '</td></tr>' +
                        '<tr><td>Alerts Unmodified</td><td>' + result.unmodified + '</td></tr>' +
                        '<tr><td>Alerts Deleted</td><td>' + result.deleted + '</td></tr>' +
                        '</table></div>';
                    }
                    else
                    {
                        var str = '<h3>Import Failed</h3>';
                        str += '<div>' + result.errors.length + ' errors:</div>';
                        for(var i=0; i < result.errors.length; i++){
                            str += '<div>' + result.errors[i] + '</div>';
                        }
                    }

                    $('.errors-count').html(str);
                    $('#alert-group').val(currentGroup.id).change();
                    $("#myModal").modal('show');

                },

                error: function() {
                    $("#btnSubmit").removeClass('btn-primary');
                    $("#btnSubmit").addClass('btn-danger');
                },

                complete: function(rq, status) {
                    $("#btnSubmit").button('reset');
                    $("#btnSubmit").attr('disabled', false);

                    if (status === "error") {
                        $("#btnSubmit").html('Upload Error');
                    }
                },
            });

        });

        $('#group-main-title').on('init', function(e, edt){
            edt.options.url = '/api/alert-group/update/' + edt.options.id + '/'
        });

        $('.help-strategy').tooltip({
          trigger: 'click',
          html: true,
          placement: 'right'
        });

        var showTooltip = false;

        $('.help-strategy').on('click', function(e) {
            showTooltip = !showTooltip;
            var toolTipStr = '<div><b>Exact</b>: Matches the best result for a plate group</div>';
            toolTipStr += '<div><b>Lenient</b>: Matches the best result for a plate number</div>';
            toolTipStr += '<div>Adjusting the match strategy provides a higher chance of catching </div>';
            toolTipStr += '<div>the plate, but increases the chance of a false positive alert.</div>';
            if(showTooltip){
                setTooltip(toolTipStr);
            } else {
                hideTooltip();
            }
        });

        $('#group-main-title').editable({
           type:  'text',
           pk:    1,
           name:  'name',
           title: 'Enter Camera Name',
           ajaxOptions: {
               type: 'PUT',
               headers:
                {
                    'X-CSRFToken':  csrf,
                    'Content-Type': 'application/json'
                }
           },
           params: function(params) {
               params.pk = $(this).data('id');
               params.name = params.value;
               params.csrfmiddlewaretoken = $(this).data('token');
               delete params.value;
               params = JSON.stringify(params);
               return params;
           },
           success: function(result, newValue) {
               console.log(result, newValue);
           }
        });

        if($('.no-groups').length){
            $("#topdrawer_errortext").html('You are not authorized to manage any alert groups');
            $("#topdrawer_error").slideDown();
        } else {
            if(typeof $("#alert-group option:first").val() !== "undefined"){
                $('#alert-group').val($("#alert-group option:first").val()).change();
            } else {
                $("#topdrawer_errortext").html('Create At least One alert group');
                $("#topdrawer_error").slideDown();
            }
        }

    }

    init();

    const listTypeHelp = "<p><strong>Black List</strong> - Any plate number on this list will trigger an alert.  This is useful if you want to be notified when specific plates are seen.</p><p><strong>White List</strong> - Any plate number that is NOT on this list will trigger an alert.  This is useful if you have a list of authorized vehicle plates and you want to know if anyone who is not on your list drives past your cameras.</p>";

    $("#list-type-help").attr("title", listTypeHelp);
    $("#list-type-help").tooltip({
        trigger: 'hover',
        html: true,
        placement: 'right'
    });

    $("#new-alert-group").on("click", function() {
        $("#list-type").val("black");
    });

});


function checkUploadedFile() {
    const fileNameRegex = /\.csv$/i
    const fileName = $("#csv-data").val();

    if (fileNameRegex.test(fileName)) {
        $("#btnSubmit").attr("disabled", false);
        $("#fileUploadGroup").removeClass("has-error");
        return true;
    } 

    $("#btnSubmit").attr("disabled", true);

    if (fileName !== "") {
        $("#fileUploadGroup").addClass("has-error");
    }

    return false
}


$(document).ready(function() {
    checkUploadedFile();

    $("#csv-data").on("change", function() {
        checkUploadedFile();
    });

});
