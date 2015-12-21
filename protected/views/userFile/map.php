<script src="http://maps.google.com/maps/api/js?sensor=true&libraries=places" type="text/javascript"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />
<?php
/* @var $this UserFileController */
/* @var $model UserFile */

$this->breadcrumbs = array(
    'My Files' => array('manage'),
    $model->id,
);
?>

<h1><?php echo $model->file_name; ?></h1>
<a class="new_btn" style="border: 2px outset gray; padding: 4px; text-decoration: none" href="index.php?r=userFile/setup&id=<?php echo $model->id ?>">Map setup</a>    
<a class="new_btn" style="border: 2px outset gray; padding: 4px; text-decoration: none" href="index.php?r=userFile/update&id=<?php echo $model->id ?>">Change this file</a>
<a class="new_btn" style="border: 2px outset gray; padding: 4px; text-decoration: none" href="index.php?r=userFile/upload">Upload another file</a>

<input type="text" placeholder="Search..." class="searchBox" id="searchBox" autocomplete="off"/>
<div id="map" style="height: 600px; width: 900px;  border: 1px solid gray; margin-top: 10px"></div>
<script type="text/javascript">
    $("#searchBox").hide();
    var locations = [
<?php
$row = 1;
$Index = 0;
$length = count($model->csv_data);
if ($length > 1) {
    foreach ($model->csv_data as $data) {
        if ($row !== 1) {
            ?>
                    [
            <?php
            if (strlen(trim($model->name_index)) > 0) {
                echo "'" . $data[$model->name_index] . "'";
            } else {
                echo "'NULL'";
            }
            ?>,
            <?php
            if (strlen(trim($model->address_index)) > 0) {
                echo "'" . $data[$model->address_index] . "'";
            } else {
                echo "'NULL'";
            }
            ?>,
            <?php
            if (strlen(trim($model->city_index)) > 0) {
                echo "'" . $data[$model->city_index] . "'";
            } else {
                echo "'NULL'";
            }
            ?>,
            <?php
            if (strlen(trim($model->state_index)) > 0) {
                echo "'" . $data[$model->state_index] . "'";
            } else {
                echo "'NULL'";
            }
            ?>,
            <?php
            if (strlen(trim($model->zipcode_index)) > 0) {
                echo "'" . $data[$model->zipcode_index] . "'";
            } else {
                echo "'NULL'";
            }
            ?>,
            <?php
            if (strlen(trim($model->phone_index)) > 0) {
                echo "'" . $data[$model->phone_index] . "'";
            } else {
                echo "'NULL'";
            }
            ?>,
            <?php
            if (strlen(trim($model->field1_index)) > 0) {
                echo "'" . $data[$model->field1_index] . "'";
            } else {
                echo "'NULL'";
            }
            ?>,
            <?php
            if (strlen(trim($model->field2_index)) > 0) {
                echo "'" . $data[$model->field2_index] . "'";
            } else {
                echo "'NULL'";
            }
            ?>,
            <?php
            if (strlen(trim($model->field3_index)) > 0) {
                echo "'" . $data[$model->field3_index] . "'";
            } else {
                echo "'NULL'";
            }
            ?>,
            <?php
            if (strlen(trim($model->field4_index)) > 0) {
                echo "'" . $data[$model->field4_index] . "'";
            } else {
                echo "'NULL'";
            }
            ?>,
            <?php
            if (strlen(trim($model->field5_index)) > 0) {
                echo "'" . $data[$model->field5_index] . "'";
            } else {
                echo "'NULL'";
            }
            ?>,
                            "'<?php echo $Index; ?>'",
                    ],
            <?php
            $Index++;
        }
        $row++;
    }
}
?>
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 7,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var infowindowlist = new Array();
    var markerlist = new Array();
    function initMap() {
        google.maps.Map.prototype.clearInfoWindow = function () {
            for (var i = 0; i < infowindowlist.length; i++) {
                if (infowindowlist[i])
                    infowindowlist[i].close();
            }
        };

        var geocoder = new google.maps.Geocoder();
        var i;
        for (i = 0; i < locations.length; i++) {
            var address = locations[i][1].split(">");
            if (address.length <= 1) {
                geocode(geocoder, locations[i], i, function (results, i) {
                    addMarker(map, results[0].geometry.location, locations[i], i);
                });
            } else {
                var latLng = new google.maps.LatLng(parseFloat(address[1]), parseFloat(address[2]));
                addMarker(map, latLng, locations[i], i);
            }
        }

        //search box
        // Create the search box and link it to the UI element.
        var input = document.getElementById('searchBox');
        //var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.RIGHT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function () {
            //searchBox.setBounds(map.getBounds());
        });

    }

    function addMarker(map, latlon, locations, i) {
        map.setCenter(latlon);
        var marker = new google.maps.Marker({
            map: map,
            position: latlon,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var contentString = '<div id="content">' +
                '<div style="width: 16px; float: right"><img src="<?php echo Yii::app()->request->baseUrl; ?>/css/ajax-loader.gif" id="ntf" class="ntf"></img></div>' +
                '<img onclick="editRequest()" src="<?php echo Yii::app()->request->baseUrl; ?>/css/edit.png" class="edit"></img>' +
                '<input class="<?php echo $model->name_index; ?> 0 map-text-box firstHeading" id="firstHeading" value="' + locations[0] + '"></input>' +
                '<div id="bodyContent" class="bodyContent">' +
                '<b>Address:</b> <input class="<?php echo $model->address_index; ?> 1 map-text-box" value="' + locations[1].split(">")[0] + '"></input>' +
                '<br/><b>City:</b> <input class="<?php echo $model->city_index; ?> 2 map-text-box" value="' + locations[2] + '"></input>' +
                '<br/><b>State:</b> <input class="<?php echo $model->state_index; ?> 3 map-text-box margin-right-3" value="' + locations[3] + '"></input>' +
                '<br/><b>Zipcode:</b> <input class="<?php echo $model->zipcode_index; ?> 4 map-text-box" value="' + locations[4] + '"></input>';
        if (locations[5] !== 'NULL') {
            contentString = contentString + '<br/><b>Phone:</b><input class="<?php echo $model->phone_index; ?> 5 map-text-box" value="' + locations[5] + '"></input>';
        }
        if (locations[6] !== 'NULL') {
            contentString = contentString + '<br/><b><?php echo $model->field1_label; ?>:</b><input class="<?php echo $model->field1_index; ?> 6 map-text-box" value="' + locations[6] + '"></input>';
        }
        if (locations[7] !== 'NULL') {
            contentString = contentString + '<br/><b><?php echo $model->field2_label; ?>:</b><input class="<?php echo $model->field2_index; ?> 7 map-text-box" value="' + locations[7] + '"></input>';
        }
        if (locations[8] !== 'NULL') {
            contentString = contentString + '<br/><b><?php echo $model->field3_label; ?>:</b><input class="<?php echo $model->field3_index; ?> 8 map-text-box" value="' + locations[8] + '"></input>';
        }
        if (locations[9] !== 'NULL') {
            contentString = contentString + '<br/><b><?php echo $model->field4_label; ?>:</b><input class="<?php echo $model->field4_index; ?> 9 map-text-box" value="' + locations[9] + '"></input>';
        }
        if (locations[10] !== 'NULL') {
            contentString = contentString + '<br/><b><?php echo $model->field5_label; ?>:</b><input class="<?php echo $model->field5_index; ?> 10 map-text-box" value="' + locations[10] + '"></input>';
        }
        contentString = contentString + '</div>';
        contentString = contentString + '<div class="button-section">';
        contentString = contentString + '<span id="info" style="float: left"></span>';
        contentString = contentString + '<input type="button" class="new_btn bottom_btn" value="Cancel" onclick="cancelEditRequest()"></input>';
        contentString = contentString + '<input class="new_btn bottom_btn" type="button" value="Save" onclick="saveRequest(' + i + ')"></input>';
        contentString = contentString + '</div>' + '</div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        infowindowlist[infowindowlist.length] = infowindow;
        markerlist[markerlist.length] = marker;
        marker.addListener('click', function () {
            map.clearInfoWindow();
            infowindow.open(map, marker);
            $("#ntf").hide();
            //$(".map-text-box").attr('disabled', 'disabled');
            cancelEditRequest();
        });
    }

    function geocode(geocoder, location, i, callback) {
        geocoder.geocode({'address': location[1] + "," + location[2] + "," + location[3] + " " + location[4]}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (typeof callback === "function") {
                    callServer(results[0].geometry.location.lat(), results[0].geometry.location.lng(), i + 2);
                    callback(results, i);
                }
            } else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                setTimeout(function () {
                    geocode(geocoder, location, i, callback);
                }, 20);
            } else {
                console.error('Geocode for: ' + i + location[1] + ' was not successful for the following reason: ' + status);
            }
        });
    }
    initMap();

    function callServer(lat, lon, row_id) {
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('userFile/locationUpdate'); ?>",
            data: {
                file_name: "<?php echo $model->physical_file_name; ?>",
                lat: lat,
                lon: lon,
                row_id: row_id,
                column_id: "<?php echo $model->address_index; ?>"
            },
            success: function (msg) {
                console.log("Sucess");
            },
            error: function (xhr) {
                console.log("failure" + xhr.readyState + this.url);
            }
        });
    }
</script>

<script>
    var element = $("#searchBox");
    element.autocomplete({
        source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response($.grep(locations, function (value) {
                return matcher.test(value[0]);
            }));
        },
        select: function (event, ui) {
            element.val(ui.item[0]);
            infowindow = new google.maps.InfoWindow();
            infowindow = infowindowlist[ui.item[11]];
            marker = new google.maps.Marker();
            marker = markerlist[ui.item[11]];
            var latLng = new google.maps.LatLng(parseFloat(ui.item[1].split(">")[1]), parseFloat(ui.item[1].split(">")[2]));
            map.setCenter(latLng);
            map.setZoom(15);
//            infowindow.open(map, marker);
//            google.maps.event.trigger(marker, 'click');
            return false;
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<p class='searchBoxHeader'>" + item[0] + "</p><p class='searchBoxDetails'>" + item[1].split(">")[0] + ", " + item[2] + ", " + item[3] + ", " + item[4] + "</p>")
                .appendTo(ul);
    };
    //$("#searchBox").show();
    function editRequest() {
        clearInfoMessage();
        $(".map-text-box").removeAttr('disabled', 'disabled');
        $(".map-text-box").addClass("map-text-box-edit");
        $(".bottom_btn").show();
    }

    function cancelEditRequest() {
        $(".map-text-box").attr('disabled', 'disabled');
        $(".map-text-box").removeClass("map-text-box-edit");
        $(".bottom_btn").hide();
    }

    function setInfoMessage(msg) {
        $("#info").html(msg);
    }

    function clearInfoMessage() {
        $("#info").html("");
    }

    function saveRequest(row_id) {
        jsonObj = [];
        $('.map-text-box').each(function () {
            classes = $(this).attr('class').split(' ');
            item = {}
            item ["row_id"] = row_id;
            item ["column_id"] = classes[0];
            item ["column_value"] = $(this).val();
            item ["local_column_id"] = classes[1];
            jsonObj.push(item);
        });
        //console.log(jsonObj);

        $("#ntf").show();
        $.ajax({
            type: "POST",
            url: "<?php echo Yii::app()->createUrl('userFile/dataUpdate'); ?>",
            data: {
                data: jsonObj,
                row_id: row_id,
                id: "<?php echo $model->id; ?>",
            },
            success: function (msg) {
                setInfoMessage("Successful!");
                cancelEditRequest();
                $("#ntf").hide();
            },
            error: function (xhr) {
                setInfoMessage("Failed!");
                console.log("failure" + xhr.readyState + this.url);
                $("#ntf").hide();
            }
        });
    }
    clearInfoMessage();
</script>
