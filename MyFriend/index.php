<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <style type="text/css">
            html { height: 100% }
            body { height: 100%; margin: 0; padding: 0 }
            #map_canvas { height: 100% }
        </style>
        <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBpaqfxnXG_SvJwUmIZlHssibG9j8d4cTw&sensor=false">
        </script>
        <script  type="text/javascript">
            

            var markers = new Array();
            var map;



            //bot exist element given in the array
            Array.prototype.notexist = function(title){
                for (i=0;i<this.length;i++)
                {
                    if(this[i].title === title)
                        return false;
                }
                return true;
    
            }

            // get element by Title
            Array.prototype.getByTitle = function(title){
                var ret = null;
                for (i=0;i<this.length;i++)
                {
                    if(this[i].title === title){
                        ret = this[i];
                        i = this.length;
                    }
                }
                return ret;
            }


            //initialize
            function initialize(markersarray) {
                var latlngmap ;
                alert("change");
        
                if(markersarray.length>0){
                    latlngmap = new google.maps.LatLng(markersarray[0].lat,markersarray[0].lng);
                } else {
                    latlngmap = new google.maps.LatLng(0,0);
                }
                var mapOptions = {
                    center: latlngmap,
                    zoom: 6,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById("map_canvas"),
                mapOptions);
        
                for(i=0;i<markersarray.length;i++){
                    var cur = markersarray[i];
                    markers.push(drawMarker(cur.who, cur.lat, cur.lng));
                }
            }

            //draw Marker
            function drawMarker( who, lat,lng){
    
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat,lng),
                    map: map,
                    title: who
                });
                return marker;
            }
      
            //change marker postion
            function changeMarker(who, lat, lng){
                markers.getByTitle(who).setPosition(new google.maps.LatLng(lat, lng));
            }      
        </script>
    </head>
    <?php
        $markers = "[";
        if(isset($_GET['markers'])){
            $markers = $markers.$_GET['markers'];
        }
        $markers = $markers."]";
        //{who:'rabah',lat:36.752175,lng:3.042026}, {who:'rabah2',lat:-23,lng:135},{who:'rabah',lat:-20,lng:135}
    ?>
    <body onload="initialize(<?php echo $markers; ?>)">
        <div id="map_canvas" style="width:100%; height:100%"></div>
        <br/>
    </body>
</html>