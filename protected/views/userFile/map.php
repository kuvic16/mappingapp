<script src="http://maps.google.com/maps/api/js?sensor=true&libraries=places" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.10.2.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/oms.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/custom_map_tooltip.js"></script>
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
    // creating location data in JSON format
    var baseUrl = "<?php echo Yii::app()->request->baseUrl; ?>";
    var nameIndex = "<?php echo $model->name_index; ?>";
    var addressIndex = "<?php echo $model->address_index; ?>";
    var cityIndex = "<?php echo $model->city_index; ?>";
    var stateIndex = "<?php echo $model->state_index; ?>";
    var zipcodeIndex = "<?php echo $model->zipcode_index; ?>";
    var phoneIndex = "<?php echo $model->phone_index; ?>";
    var field1Index = "<?php echo $model->field1_index; ?>";
    var field2Index = "<?php echo $model->field2_index; ?>";
    var field3Index = "<?php echo $model->field3_index; ?>";
    var field4Index = "<?php echo $model->field4_index; ?>";
    var field5Index = "<?php echo $model->field5_index; ?>";
    var field1Label = "<?php echo $model->field1_label; ?>";
    var field2Label = "<?php echo $model->field2_label; ?>";
    var field3Label = "<?php echo $model->field3_label; ?>";
    var field4Label = "<?php echo $model->field4_label; ?>";
    var field5Label = "<?php echo $model->field5_label; ?>";
    var locations = [];
    function loadLocation() {
        locations = [
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
        //console.log(locations[0][0] + locations[1][0]);
    }
</script>



<script>
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
                loadLocation();
            },
            error: function (xhr) {
                setInfoMessage("Failed!");
                console.log("failure" + xhr.readyState + this.url);
                $("#ntf").hide();
            }
        });
    }

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

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/mapping.js"></script>
<script>
    loadLocation();
    clearInfoMessage();
</script>

