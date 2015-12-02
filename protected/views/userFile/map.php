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

<script type="text/javascript">
    var locations = [
<?php
$path = Yii::app()->runtimePath . '/temp/' . $model->physical_file_name;
$row = 1;
if (($handle = fopen($path, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if ($row !== 1) {
            $num = count($data);
            ?>
                    [<?php echo "'" . $data[0] . "'"; ?>, <?php echo "'" . $data[1] . "'"; ?>, <?php echo "'" . $data[2] . "'"; ?>,
            <?php echo "'" . $data[3] . "'"; ?>, <?php echo "'" . $data[4] . "'"; ?>, <?php echo "'" . $data[5] . "'"; ?>,
            <?php echo "'" . $data[6] . "'"; ?>, <?php echo "'" . $data[7] . "'"; ?>, <?php echo "'" . $data[8] . "'"; ?>, <?php echo "'" . $data[9] . "'"; ?>],
            <?php
        }
        $row = 2;
    }
    fclose($handle);
}
?>
    ];



    function initMap() {
        var infowindowlist = new Array();
        google.maps.Map.prototype.clearInfoWindow = function () {
            for (var i = 0; i < infowindowlist.length; i++) {
                if (infowindowlist[i]) infowindowlist[i].close();
            }
        };
        
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6
        });
        var geocoder = new google.maps.Geocoder();
        var i;
        //console.log(locations.length);
        for (i = 0; i < locations.length; i++) {
            geocode(geocoder, locations[i], i, function (results, i) {
                //console.log(i);
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                var contentString = '<div id="content">' +
                        '<div id="siteNotice">' +
                        '</div>' +
                        '<h1 id="firstHeading" class="firstHeading">' + locations[i][0] + '</h1>' +
                        '<div id="bodyContent" class="bodyContent">' +
                        '<p>' + locations[i][1] + '<br/>' +
                        '' + locations[i][2] + ' ' + locations[i][3] + ', ' + locations[i][4] + '<br/>' +
                        '' + locations[i][5] + '<br/>' +
                        '<b> Contact:</b> ' + locations[i][6] + '<br/>' +
                        '<b> Rank:</b> ' + locations[i][7] + '<br/>' +
                        '<b> Cor A/C:</b> ' + locations[i][8] + '<br/>' +
                        '<b> Grand Total:</b> ' + locations[i][9] + '<br/>' +
                        '</p>' +
                        '</div>' +
                        '</div>';

                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });
                
                infowindowlist[infowindowlist.length] = infowindow;
                marker.addListener('click', function () {
                    map.clearInfoWindow();
                    infowindow.open(map, marker);
                });

            });
        }
    }

    function geocode(geocoder, location, i, callback) {
        geocoder.geocode({'address': location[1]}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                //console.error('Geocode for: '+ location[1]+' was not successful for the following reason: ' + status)
                if (typeof callback === "function") {
                    callback(results, i);
                }
            } else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                //console.error('Geocode for: '+ location[1]+' was not successful for the following reason: ' + status)
                setTimeout(function () {
                    geocode(geocoder, location, i, callback);
                }, 20);
            } else {
                //alert('Geocode was not successful for the following reason: ' + status);
                //console.error('Geocode for: '+ i + location[1]+' was not successful for the following reason: ' + status)
            }
        });
    }
    initMap();
</script>
<?php
?>
