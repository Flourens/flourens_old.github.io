ALPR_DATETIME_FORMAT = "YYYY-MM-DD HH:mm:ss";
MAX_CROP_WIDTH = 225;

    function get_plate_crop_url(agent_uid, uuid, plate_index)
    {
        var crop_url = '/crop/' + agent_uid + '/' + uuid + '/' + plate_index + '?max_width=' + MAX_CROP_WIDTH;
        return crop_url;
    }
    function get_vehicle_crop_url(agent_uid, uuid, plate_index, x, y, w, h)
    {
        var crop_url = '/crop/' + agent_uid + '/' + uuid + '/' + plate_index + '?x=' + x + '&y=' + y + '&width=' + w + '&height=' + h + '&max_width=' + MAX_CROP_WIDTH;
        return crop_url;
    }
    // Scripts for the plate table
    function plate_crop_enter(popup_description, plate_crop_url)
    {
        $("#floating_crop_img strong.floating_title").text(popup_description);


        $("#floating_crop_img img.cropimage").error(function() {
            $(this).hide();
            $("#floating_crop_img .croperror").text("image not available").show();
        });

        $("#floating_crop_img .croperror").hide();
        $("#floating_crop_img img.cropimage").show();

        $("#floating_crop_img img.cropimage").attr("src",plate_crop_url);

    }
    function plate_crop_mousemove(e)
    {
        x=e.clientX;
        y=e.clientY;
        $("#floating_crop_img").css('left', x).css('top', y);
        $("#floating_crop_img").show();

        var float_crop_width = $("#floating_crop_img > span").outerWidth(true);
        var right_edge_px = x + float_crop_width;

        if (right_edge_px > window.innerWidth)
        {
            $("#floating_crop_img").css('left', x - float_crop_width - 15)
            $("#floating_crop_img .callout").hide();
        }
        else
        {
            $("#floating_crop_img .callout").show();
        }
    }
    function plate_crop_mouseout()
    {

        $("#floating_crop_img").hide();

        // Set it to a 1 pixel transparent image so that this image doesn't show up again when we mouse over another crop (while the next crop loads)
        $("#floating_crop_img img.cropimage").attr("src",'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
    }

    String.prototype.endsWith = function(suffix) {
        return this.indexOf(suffix, this.length - suffix.length) !== -1;
    };

    if (typeof String.prototype.startsWith != 'function') {
      // see below for better implementation!
      String.prototype.startsWith = function (str){
        return this.indexOf(str) == 0;
      };
    }


    function format_alpr_date(epochtime)
    {
        var now = moment();
        event_time = moment(epochtime);
        if (event_time.isSame(now, "day"))
            return event_time.format("h:mm:ss a");
        else if (event_time.isSame(now, "year"))
            return event_time.format("MMM D h:mm:ss a");
        else
            return event_time.format("MMM D, YYYY h:mm:ss a");


    }

    function get_search_link(start_time, end_time, search_type)
    {
        //tz=America%2FNew_York&search_choice=3&start_time=2015-01-26+19%3A39&end_time=2015-02-02+19%3A39&site=&plate_number=

        return '/search/#search_type=' + search_type + '&site=&plate_number=' +
        '&start_time=' + encodeURIComponent(start_time.format(ALPR_DATETIME_FORMAT)) +
        '&end_time=' + encodeURIComponent(end_time.format(ALPR_DATETIME_FORMAT));
    }


    var error_list = []

    function add_error_message(message)
    {
        error_list.push(message);

        // Only show the most recent 5 error messages
        // If more than that, remove the oldest message
        if (error_list.length > 5)
            error_list.splice(0, 1);

        var all_messages = "";
        for (var i = 0; i < error_list.length; i++)
            all_messages += error_list[i] + "<br />"

        $("#topdrawer_errortext").html(all_messages);
        //$("#topdrawer_error").removeClass('hide');
        $("#topdrawer_error").slideDown();
    }

    function close_drawer()
    {
        error_list = [];
        $("#topdrawer_error").slideUp();
    }


    function SortPlateGroupByTime(a, b){
      var aVal = moment(a.fields.epoch_time_end);
      var bVal = moment(b.fields.epoch_time_end);
      return ((aVal > bVal) ? -1 : ((aVal < bVal) ? 1 : 0));
    }
    function SortPlatesByTime(a, b){
      var aVal = moment(a.fields.epoch_time);
      var bVal = moment(b.fields.epoch_time);
      return ((aVal > bVal) ? -1 : ((aVal < bVal) ? 1 : 0));
    }
    function get_plate_url(uuid) { return '/show/plate/' + uuid; }
    function get_plate_link(agent_uid, agent_type, uuid, plate_number, plate_index)
    {
        if (agent_type == "cloudprocessor")
            return plate_number;

        var crop_url = get_plate_crop_url(agent_uid, uuid, plate_index);

        var hoverfunctions = 'onmouseover="plate_crop_enter(\'' + plate_number + '\', \'' + crop_url + '\')" onmousemove="plate_crop_mousemove(event)" onmouseout="plate_crop_mouseout()"';

        var crop_link = '<a class="croplink" href="' + get_plate_url(uuid) + '" ' + hoverfunctions + '>' + plate_number + '</a>';


        return crop_link;
    }

    function get_group_url(id, uuid) { return '/show/group/' + id + '/' + uuid; }
    function get_group_link(agent_uid, id, uuid, plate_number, plate_index)
    {
        var crop_url = get_plate_crop_url(agent_uid, uuid, plate_index);

        var hoverfunctions = 'onmouseover="plate_crop_enter(\'' + plate_number + '\', \'' + crop_url + '\')" onmousemove="plate_crop_mousemove(event)" onmouseout="plate_crop_mouseout()"';

        var crop_link = '<a class="croplink" href="' + get_group_url(id, uuid) + '" ' + hoverfunctions + '>' + plate_number + '</a>';
        return crop_link;
    }

    function build_search_csvurl(search_type, topn, site_id, start_time, end_time, sort_desc, plate_number, vehicle_make, vehicle_body, vehicle_color, alertlist)
    {
        search_url = build_search_url(search_type, topn, site_id, start_time, end_time, sort_desc, plate_number, vehicle_make, vehicle_body, vehicle_color, alertlist);
        search_url += "&format=csv";

        return search_url;
    }
    function build_search_url(search_type, topn, site_id, start_time, end_time, sort_desc, plate_number, vehicle_make, vehicle_body, vehicle_color, alertlist, region_tag, camera_id)
    {
        url = '/api/search/';

        if (search_type == "group")
            url += 'group'
        else if (search_type == "plate")
            url += 'plate';
        else if (search_type == "alert")
            url += 'alert';
        else if (search_type == "platecandidate")
            url += 'platecandidate';

        url += '?topn=' + topn;

        if (start_time != "")
            url += '&start=' + encodeURIComponent(start_time);

        if (end_time!= "")
            url += '&end=' + encodeURIComponent(end_time);

        if (plate_number != "")
            url += "&plate=" + encodeURIComponent(plate_number);

        if (search_type == "group")
        {
            if (vehicle_make !== undefined && vehicle_make.length > 0)
                url += "&make=" +encodeURIComponent(vehicle_make);
            if (vehicle_body !== undefined && vehicle_body.length > 0)
                url += "&body=" +encodeURIComponent(vehicle_body);
            if (vehicle_color !== undefined && vehicle_color.length > 0)
                url += "&color=" +encodeURIComponent(vehicle_color);
            if (region_tag !== undefined && region_tag !== "") {
                url += "&region=" + encodeURIComponent(region_tag);
            }
        }
        else if (search_type == "alert")
        {
            if (alertlist !== undefined && alertlist.length > 0)
                url += "&alertlist=" + encodeURIComponent(alertlist);
        }
        if (sort_desc)
            url += '&order=desc';
        else
            url += '&order=asc';

        if (site_id !== undefined && site_id !== "0")
            url += "&site=" + encodeURIComponent(site_id);

        if (camera_id) {
            url += "&camera_id=" + encodeURIComponent(camera_id);
        }

        return url;
    }

    function titleCase(str) {
      str = str.toLowerCase().split(' ');
      for (var i = 0; i < str.length; i++) {
        str[i] = str[i].charAt(0).toUpperCase() + str[i].slice(1);
      }
      return str.join(' ');
    }

    function get_vehicle_string(group_obj)
    {

        var MIN_CONFIDENCE = 40.0
        body_type_mapping = {
            "antique": "Antique",
            "motorcycle": "Motorcycle",
            "tractor-trailer": "Tractor/Trailer",
            "sedan-sports": "Sedan",
            "sedan-compact": "Compact Sedan",
            "sedan-convertible": "Sedan",
            "sedan-sport": "Sedan",
            "sedan-sports": "Sedan",
            "sedan-standard": "Sedan",
            "sedan-wagon": "Sedan",
            "suv-crossover": "SUV",
            "suv-standard": "SUV",
            "suv-wagon": "SUV",
            "truck-standard": "Truck",
            "van-full": "Van",
            "van-mini": "Minivan"
        }

        if (group_obj.fields.vehicle_color === undefined || group_obj.fields.vehicle_color === null || group_obj.fields.vehicle_color === "")
            return "-";

        var vehicle_string = "";
        if (group_obj.fields.vehicle_color_confidence > MIN_CONFIDENCE)
            vehicle_string += titleCase(group_obj.fields.vehicle_color) + " "

        if (group_obj.fields.vehicle_make_confidence > MIN_CONFIDENCE)
            vehicle_string += titleCase(group_obj.fields.vehicle_make) + " "

        if (group_obj.fields.vehicle_body_type_confidence > MIN_CONFIDENCE)
            vehicle_string += body_type_mapping[group_obj.fields.vehicle_body_type]

        return vehicle_string;

    }

   function get_vehicle_croplink(agent_uid, id, uuid, vehicle_description, plate_index, x, y, w, h)
    {
        var crop_url = get_vehicle_crop_url(agent_uid, uuid, plate_index, x, y, w, h);

        var hoverfunctions = 'onmouseover="plate_crop_enter(\'' + vehicle_description + '\', \'' + crop_url + '\')" onmousemove="plate_crop_mousemove(event)" onmouseout="plate_crop_mouseout()"';

        var crop_link = '<a class="croplink" href="' + get_group_url(id, uuid) + '" ' + hoverfunctions + '>' + vehicle_description + '</a>';

        return crop_link;

    }

    function populate_search_table($platetable, search_type, data)
    {
        var $tablerows = $('<tbody></tbody>');


        $platetable.removeData('latest_time');
        $platetable.removeData('earliest_time');
        $platetable.empty();

        var $tablehead = $("<thead></thead>")

        if (search_type == "group")
        {
            $tablehead.append('             <tr>                        \
                                                <th>Site</th>           \
                                                <th>Camera</th>         \
                                                <th>Plate Number</th>   \
                                                <th>Vehicle</th>\
                                                <th>Confidence</th>     \
                                                <th>Time</th>           \
                                            </tr>');
        }
        else if (search_type == "plate")
        {
            $tablehead.append('             <tr>                        \
                                                <th>Site</th>           \
                                                <th>Camera</th>         \
                                                <th>Plate Number</th>   \
                                                <th>Confidence</th>     \
                                                <th>Time</th>           \
                                            </tr>                       \
                                        </thead>');
        }
        else if (search_type == "platecandidate")
        {
            $tablehead.append('             <tr>                        \
                                                <th>Site</th>           \
                                                <th>Camera</th>         \
                                                <th>Plate Number</th>   \
                                                <th>Confidence</th>     \
                                                <th>Time</th>           \
                                            </tr>                       \
                                        </thead>');
        }
        else if (search_type == "alert")
        {
            $tablehead.append('            <tr>                         \
                                                <th>Alert List</th>     \
                                                <th>Site</th>           \
                                                <th>Plate Number</th>   \
                                                <th>Description</th>    \
                                                <th>Confidence</th>     \
                                                <th>Time</th>           \
                                            </tr>                       \
                                        </thead>');
        }

        if (data.length == 0)
        {
            var columns = $tablehead.find('th').length;

            $tablerows.append($('<td align="center" height="100px" colspan="' + columns + '"><strong style="font-size: 1.3em;">No results found</strong></td>'));
        }

        $.each(data, function (index, plate) {
            var rowtime;
            if (search_type == "group")
            {


                $tablerow = $('<tr>' +
                '<td>' + plate.fields.site + '</td>' +
                '<td>' + plate.fields.camera + '</td>' +
                '<td>' + get_group_link(plate.fields.agent_uid, plate.pk, plate.fields.best_uuid, plate.fields.best_plate, plate.fields.best_index) + '</td>' +
                '<td>' + get_vehicle_croplink(plate.fields.agent_uid, plate.pk, plate.fields.best_uuid, get_vehicle_string(plate),
                                plate.fields.best_index, plate.fields.vehicle_region_x, plate.fields.vehicle_region_y,
                                plate.fields.vehicle_region_width, plate.fields.vehicle_region_height) + '</td>' +
                '<td>' + plate.fields.best_confidence + '</td>' +
                '<td>' + format_alpr_date(plate.fields.epoch_time_end) + '</td>' +
                '</tr>');
                rowtime = plate.fields.epoch_time_end;
            }
            else if (search_type == "plate")
            {
                $tablerow = $('<tr>' +
                '<td>' + plate.fields.site + '</td>' +
                '<td>' + plate.fields.camera + '</td>' +
                '<td>' + get_plate_link(plate.fields.agent_uid, plate.fields.agent_type, plate.fields.uuid, plate.fields.best_plate, plate.fields.plate_index) + '</td>' +
                '<td>' + plate.fields.best_confidence + '</td>' +
                '<td>' + format_alpr_date(plate.fields.epoch_time) + '</td>' +
                '</tr>');
                rowtime = plate.fields.epoch_time;
            }
            else if (search_type == "platecandidate")
            {
                $tablerow = $('<tr>' +
                '<td>' + plate.fields.site + '</td>' +
                '<td>' + plate.fields.camera + '</td>' +
                '<td><a class="croplink" href="' + get_plate_url(plate.fields.plate) + '">' + plate.fields.plate_number + '</a></td>' +
                '<td>' + plate.fields.confidence + '</td>' +
                '<td>' + format_alpr_date(plate.fields.epoch_time) + '</td>' +
                '</tr>');
                rowtime = plate.fields.epoch_time;
            }
            else if (search_type == "alert")
            {
                var plate_group = plate.fields.alert_group_name;
                if (plate_group === undefined) {
                    plate_group = "Does not exist"
                }

                $tablerow = $('<tr>' +
                '<td>' + plate_group + '</td>' +
                '<td>' + plate.fields.site + '</td>' +
                '<td><a class="croplink" href="/show/alert/' + plate.pk + '">' + plate.fields.plate_number + '</a></td>' +
                '<td>' + plate.fields.description + '</td>' +
                '<td>' + plate.fields.confidence + '</td>' +
                '<td>' + format_alpr_date(plate.fields.epoch_time) + '</td>' +
                '</tr>');
                rowtime = plate.fields.epoch_time;
            }

            if (index == 0)
                $platetable.data('latest_time', rowtime);
            else if (index == data.length - 1)
                $platetable.data('earliest_time', rowtime);

            $tablerows.append($tablerow);
        });

        $platetable.append($tablehead);
        $platetable.append($tablerows);


        $(".croplink").colorbox({iframe:true, width:"90%", height:"90%"});
    }
