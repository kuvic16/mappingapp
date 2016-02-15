/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var infowindowlist = new Array();
var markerlist = new Array();
var gm = google.maps;
var searchBoxElement = $("#searchBox");
var filterBoxElement = $("#filterBox");
var map;
var oms;

map = new gm.Map(document.getElementById('map'), {
        mapTypeId: gm.MapTypeId.ROADMAP,
        center: new gm.LatLng(50, 0),
        zoom: 6,
});
var input = document.getElementById('searchBox');
map.controls[google.maps.ControlPosition.RIGHT].push(input);
    
var filterInput = document.getElementById('filterBox');
map.controls[google.maps.ControlPosition.RIGHT].push(filterInput);

window.onload = function () {
    infowindowlist = new Array();
    markerlist = new Array();
    oms = null;
    
//    map = new gm.Map(document.getElementById('map'), {
//        mapTypeId: gm.MapTypeId.ROADMAP,
//        center: new gm.LatLng(50, 0),
//        zoom: 6,
//    });
    var iw = new gm.InfoWindow();
    oms = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: true});

//    var usualColor = '';
//    if(defaultColor){
//        usualColor = defaultColor;
//    }else{
//        usualColor = 'eebb22';
//    }
        
    //var usualColor = 'FE7569';
    var spiderfiedColor = 'ffee22';
    //var spiderfiedColor = 'F78181';
    oms.addListener('click', function (marker) {
        iw.setContent(getContentString(marker.rowId));
        iw.open(map, marker);
        $("#ntf").hide();
        cancelEditRequest();
        setMarkerTooltip(marker);
    });
    oms.addListener('spiderfy', function (markers) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setIcon(iconWithColor(markers[i].usualColor));
            markers[i].setShadow(null);
        }
        iw.close();
    });
    oms.addListener('unspiderfy', function (markers) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setIcon(iconWithColor(markers[i].usualColor));
            markers[i].setShadow(shadow);
        }
    });
        
    var bounds = new gm.LatLngBounds();
    var geocoder = new google.maps.Geocoder();
    for (i = 0; i < locations.length; i++) {
        if(isFiltered(i)){
            var address = locations[i][1].split(">");
            if (address.length <= 1) {
                geocode(geocoder, locations[i], i, function (results, i) {
                    bounds.extend(results[0].geometry.location);
                    addMarker(map, results[0].geometry.location, locations[i], i, oms, "", shadow);
                });
            } else {
                var loc = new gm.LatLng(parseFloat(address[1]), parseFloat(address[2]));
                bounds.extend(loc);
                addMarker(map, loc, locations[i], i, oms, "", shadow);
            }
        }
    }
    map.fitBounds(bounds);

//    var input = document.getElementById('searchBox');
//    map.controls[google.maps.ControlPosition.RIGHT].push(input);
//    
//    var filterInput = document.getElementById('filterBox');
//    map.controls[google.maps.ControlPosition.RIGHT].push(filterInput);
    
    map.addListener('bounds_changed', function () {
        //searchBox.setBounds(map.getBounds());
    });

    window.map = map;
    window.oms = oms;
}


var iconWithColor = function (color) {
    color = color.replace("#", ""); 
    return 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|' + color + '|000000|ffff00';
}

var shadow = new gm.MarkerImage(
        'https://www.google.com/intl/en_ALL/mapfiles/shadow50.png',
        new gm.Size(37, 34), // size   - for sprite clipping
        new gm.Point(0, 0), // origin - ditto
        new gm.Point(10, 34)  // anchor - where to meet map location
        );

google.maps.Map.prototype.clearInfoWindow = function () {
    for (var i = 0; i < infowindowlist.length; i++) {
        if (infowindowlist[i])
            infowindowlist[i].close();
    }
};

function addMarker(map, latlon, locations, i, oms, usualColor, shadow) {
    usualColor = getMarkerColorFromFiltering(i);
    map.setCenter(latlon);
    var marker = new gm.Marker({
        map: map,
        position: latlon,
//        title: locations[0],
        icon: iconWithColor(usualColor),
        shadow: shadow,
        tooltip: locations[0]
    });
    setMarkerTooltip(marker);
    marker.rowId = i;
    marker.usualColor = usualColor;
    
    markerlist[markerlist.length] = marker;
    oms.addMarker(marker);
}

function getMarkerColorFromFiltering(i){
    var data = fileData[i];
    var usualColor = '';
    if(defaultColor){
        usualColor = defaultColor;
    }else{
        usualColor = 'eebb22';
    }
    
    try{
        if(filterColumn){
            var columnValue = data[parseInt(filterColumn)];
            var filters = filter.split(",");

            for (jj = 0; jj < filters.length; jj++) {
                var items = filters[jj].split(":");

                if(items[0].trim() === columnValue){
                    usualColor = items[1].trim();
                    break;
                }else{
                    var maxMin = items[0].trim().split("-");
                    //console.log(maxMin + "___" + columnValue);
                    //console.log(maxMin.length);
                    if(maxMin.length > 1){
                        var max = maxMin[1].trim();
                        var min = maxMin[0].trim();
                        if(parseInt(columnValue) >= parseInt(min) && parseInt(columnValue) <= parseInt(max)){
                            usualColor = items[1].trim();
                            break;
                        }
                    }
                }
            }
        }    
    }catch (err){
        console.log(err);
    }
    
    return usualColor;
}

function setMarkerTooltip(marker){
    var tooltip = new Tooltip({map: map}, marker);
    tooltip.bindTo("text", marker, "tooltip");
    google.maps.event.addListener(marker, 'mouseover', function() {
        tooltip.addTip();
        tooltip.getPos2(marker.getPosition());
    });

    google.maps.event.addListener(marker, 'mouseout', function() {
        tooltip.removeTip();
    });
}

function getContentString(i) {
    var location = locations[i];
    //console.log(location);
    var contentString = '<div id="content">' +
            '<div style="width: 16px; float: right"><img src="' + baseUrl + '/css/ajax-loader.gif" id="ntf" class="ntf"></img></div>' +
            '<img onclick="editRequest()" src="' + baseUrl + '/css/edit.png" class="edit"></img>' +
            '<input class="' + nameIndex + ' 0 map-text-box firstHeading" id="firstHeading" value="' + location[0] + '"></input>' +
            '<div id="bodyContent" class="bodyContent">' +
            '<b>Address:</b> <input class="' + addressIndex + ' 1 map-text-box" value="' + location[1].split(">")[0] + '"></input>' +
            '<br/><b>City:</b> <input class="' + cityIndex + ' 2 map-text-box" value="' + location[2] + '"></input>' +
            '<br/><b>State:</b> <input class="' + stateIndex + ' 3 map-text-box margin-right-3" value="' + location[3] + '"></input>' +
            '<br/><b>Zipcode:</b> <input class="' + zipcodeIndex + ' 4 map-text-box" value="' + location[4] + '"></input>';
    if (location[5] !== 'NULL') {
        contentString = contentString + '<br/><b>Phone:</b><input class="' + phoneIndex + ' 5 map-text-box" value="' + location[5] + '"></input>';
    }
    if (location[6] !== 'NULL') {
        contentString = contentString + '<br/><b>' + field1Label + ':</b><input class="' + field1Index + ' 6 map-text-box" value="' + location[6] + '"></input>';
    }
    if (location[7] !== 'NULL') {
        contentString = contentString + '<br/><b>' + field2Label + ':</b><input class="' + field2Index + ' 7 map-text-box" value="' + location[7] + '"></input>';
    }
    if (location[8] !== 'NULL') {
        contentString = contentString + '<br/><b>' + field3Label + ':</b><input class="' + field3Index + ' 8 map-text-box" value="' + location[8] + '"></input>';
    }
    if (location[9] !== 'NULL') {
        contentString = contentString + '<br/><b>' + field4Label + ':</b><input class="' + field4Index + ' 9 map-text-box" value="' + location[9] + '"></input>';
    }
    if (location[10] !== 'NULL') {
        contentString = contentString + '<br/><b>' + field5Label + ':</b><input class="' + field5Index + ' 10 map-text-box" value="' + location[10] + '"></input>';
    }
    contentString = contentString + '</div>';
    contentString = contentString + '<div class="button-section">';
    contentString = contentString + '<span id="info" style="float: left"></span>';
    contentString = contentString + '<input type="button" class="new_btn bottom_btn" value="Cancel" onclick="cancelEditRequest()"></input>';
    contentString = contentString + '<input class="new_btn bottom_btn" type="button" value="Save" onclick="saveRequest(' + i + ')"></input>';
    contentString = contentString + '</div>' + '</div>';
    
    return contentString;
}

// call geocode for lat and lon
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

searchBoxElement.autocomplete({
    source: function (request, response) {
        var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
        response($.grep(locations, function (value) {
            return matcher.test(value[0]);
        }));
    },
    select: function (event, ui) {
        searchBoxElement.val(ui.item[0]);
        var i = ui.item[11].replace(/'/g,"");
        marker = markerlist[i];
        var latLng = new google.maps.LatLng(parseFloat(ui.item[1].split(">")[1]), parseFloat(ui.item[1].split(">")[2]));
        map.setCenter(latLng);
        map.setZoom(15);
        
        var iw = new gm.InfoWindow();
        iw.setContent(getContentString(marker.rowId));
        iw.open(map, marker);
        
        $("#ntf").hide();
        cancelEditRequest();
        
        return false;
    }
}).autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
            .append("<p class='searchBoxHeader'>" + item[0] + "</p><p class='searchBoxDetails'>" + item[1].split(">")[0] + ", " + item[2] + ", " + item[3] + ", " + item[4] + "</p>")
            .appendTo(ul);
};

function clearOverlays() {
  for (var i = 0; i < markerlist.length; i++ ) {
    markerlist[i].setMap(null);
  }
  markerlist.length = 0;
}

var filterText = "";
$('#filterBox').keypress(function (e) {
    var key = e.which;
    if(key == 13){
        filterText = this.value;
        clearOverlays();
        onload();
    }
});


function isFiltered(i){
    //console.log(filterText);
    if(typeof filterText != 'undefined' && filterText.length > 0){
        var data = fileData[i];
        try{

            for(fi=0; fi<data.length; fi++){
                if (data[fi].toLowerCase().indexOf(filterText.toLowerCase()) >= 0){
                    return true;
                }
            } 
        }catch (err){
            console.log(err);
        }
        return false;
    }else{
        return true;
    }
}