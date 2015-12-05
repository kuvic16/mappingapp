<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<?php
/* @var $this UserFileController */
/* @var $model UserFile */

$this->breadcrumbs = array(
    'My Files' => array('manage'),
    $model->id,
);

//$this->menu=array(
//        array('label'=>'Update this File', 'url'=>array('update', 'id'=>$model->id)),
//    	array('label'=>'Upload File', 'url'=>array('upload')),
//        array('label'=>'My Files', 'url'=>array('manage')),
//);
?>

<h1><?php echo $model->file_name; ?></h1>
<div id="map" style="height: 600px; width: 900px;  border: 1px solid gray"></div>

<script type='text/javascript' src='<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js'></script>
<script type="text/javascript">
    var locations = [
<?php
$row = 1;
$length = count($model->csv_data);
if ($length > 1) {
    foreach ($model->csv_data as $data) {
        if ($row !== 1) {
            ?>
            [
                <?php echo "'" . $data[0] . "'"; ?>, <?php echo "'" . $data[1] . "'"; ?>, <?php echo "'" . $data[2] . "'"; ?>,
                <?php echo "'" . $data[3] . "'"; ?>, <?php echo "'" . $data[4] . "'"; ?>, <?php echo "'" . $data[5] . "'"; ?>,
                <?php echo "'" . $data[6] . "'"; ?>, <?php echo "'" . $data[7] . "'"; ?>, <?php echo "'" . $data[8] . "'"; ?>, <?php echo "'" . $data[9] . "'"; ?>
            ],
            <?php
        }
        $row++;
    }
}
?>
    ];


    var infowindowlist = new Array();        
    function initMap() {
        google.maps.Map.prototype.clearInfoWindow = function () {
            for (var i = 0; i < infowindowlist.length; i++) {
                if (infowindowlist[i])
                    infowindowlist[i].close();
            }
        };

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6
        });
        var geocoder = new google.maps.Geocoder();
        var i;
        for (i = 0; i < locations.length; i++) {
            var address = locations[i][1].split(">");
            if(address.length <= 1){
                geocode(geocoder, locations[i], i, function (results, i) {
                    addMarker(map, results[0].geometry.location, locations[i], i);
                });
            }else{
                var latLng = new google.maps.LatLng(parseFloat(address[1]),parseFloat(address[2]));
                addMarker(map, latLng, locations[i], i);
            }
        }
    }

    function addMarker(map, latlon, locations, i) {
        map.setCenter(latlon);
        var marker = new google.maps.Marker({
            map: map,
            position: latlon,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var contentString = '<div id="content">' +
                '<img src="<?php echo Yii::app()->request->baseUrl; ?>/css/ajax-loader.gif" id="ntf">' +
                '</img>' +
                '<input class="map-text-box firstHeading" onblur="changeRequest(\''+ i +'\',\'0\', this);" id="firstHeading" value="' + locations[0] + '"></input>' +
                '<div id="bodyContent" class="bodyContent">' +
                '<input class="map-text-box hundred" onblur="changeRequest(\''+ i +'\',\'1\', this);" value="' + locations[1].split(">")[0] + '"></input>' +
                '<br/><input class="map-text-box margin-right-3" onblur="changeRequest(\''+ i +'\',\'2\', this);" value="' + locations[2] + '"></input>' +
                '<input style="float:right" class="map-text-box margin-right-3" onblur="changeRequest(\''+ i +'\',\'3\', this);" value="' + locations[3] + '"></input>' +
                '<br/><input class="map-text-box" onblur="changeRequest(\''+ i +'\',\'4\', this);" value="' + locations[4] + '"></input>' +
                '<br/><input class="map-text-box hundred" onblur="changeRequest(\''+ i +'\',\'5\', this);" value="' + locations[5] + '"></input>' +
                '<b> Contact:</b> <input class="map-text-box " onblur="changeRequest(\''+ i +'\',\'6\', this);" value="' + locations[6] + '"></input><br/>' +
                '<b> Rank:</b> <input class="map-text-box" onblur="changeRequest(\''+ i +'\',\'7\', this);" value="' + locations[7] + '"></input><br/>' +
                '<b> Cor A/C:</b> <input class="map-text-box" onblur="changeRequest(\''+ i +'\',\'8\', this);" value="' + locations[8] + '"></input><br/>' +
                '<b> Grand Total:</b> <input class="map-text-box" onblur="changeRequest(\''+ i +'\',\'9\', this);" value="' + locations[9] + '"></input><br/>' +
                '</div>' +
                '</div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        infowindowlist[infowindowlist.length] = infowindow;
        marker.addListener('click', function () {
            map.clearInfoWindow();
            infowindow.open(map, marker);
            $("#ntf").hide();
        });
    }

    function geocode(geocoder, location, i, callback) {
        geocoder.geocode({'address': location[1]}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (typeof callback === "function") {
                    callServer(results[0].geometry.location.lat(), results[0].geometry.location.lng(), i+2);
                    callback(results, i);
                }
            } else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                setTimeout(function () {
                    geocode(geocoder, location, i, callback);
                }, 20);
            } else {
                console.error('Geocode for: '+ i + location[1]+' was not successful for the following reason: ' + status);
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
                row_id: row_id
            },
            success: function (msg) {
                console.log("Sucess");
            },
            error: function (xhr) {
                console.log("failure" + xhr.readyState + this.url);
            }
        });
    }
    
    function changeRequest(row_id, column_id, element){
        var prev_value = locations[row_id][column_id];
        var new_value = $(element).val();
        if(column_id === 1){
            prev_value = locations[row_id][1].split(">")[0];
        }
        if(prev_value !== new_value){
            $("#ntf").show();
            $.ajax({
                type: "POST",
                url: "<?php echo Yii::app()->createUrl('userFile/dataUpdate'); ?>",
                data: {
                    file_name: "<?php echo $model->physical_file_name; ?>",
                    row_id: row_id,
                    column_id: column_id,
                    column_value: $(element).val()
                },
                success: function (msg) {
                    locations[row_id][column_id] = $(element).val();
                    $("#ntf").hide();
                },
                error: function (xhr) {
                    console.log("failure" + xhr.readyState + this.url);
                    $("#ntf").hide();
                }
            });
        }
    }
</script>
