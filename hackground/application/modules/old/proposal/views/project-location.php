<div class="row">
	<div class="col-sm-6">
		<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
				<input type="hidden" name="ID" value="<?php echo $project_id;?>"/>
				<input type="hidden" name="page" value="<?php echo $page;?>"/>
				<div class="box-body">
					<div class="form-group">
						<label for="locality">Location </label>
						<input type="text" class="form-control" placeholder="Landmark" name="locality" id="locality" autocomplete="off" value="<?php echo $detail['location']['location_locality'];?>" />
						<input type="hidden" name="lat" id="location_lat" value="<?php echo $detail['location']['location_lat']; ?>"/>
						<input type="hidden" name="lng" id="location_lng" value="<?php echo $detail['location']['location_long'];  ?>"/>
					
					</div>
					<div id="map" style="height: 400px; display:none;"></div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn-block btn btn-primary">Save</button>
				</div>
		</form>
	</div>
	
</div>
</div>

<script>
function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

</script>

<script>

  var placeSearch, autocomplete;
  var componentForm = {
	locality: 'long_name',
	administrative_area_level_1: 'short_name',
	country: 'long_name'
  };

function initAutocomplete() {
	var options = {
		types: ['geocode'],
	};
	autocomplete = new google.maps.places.Autocomplete((document.getElementById('locality')), options);
	autocomplete.addListener('place_changed', fillInAddress);
	
	<?php if($detail['location']['location_lat'] || $detail['location']['location_long']){ ?>
	loadMap({lat: <?php echo $detail['location']['location_lat']; ?>, lng: <?php echo $detail['location']['location_long']; ?>});
	<?php } ?>
}
   

function fillInAddress() {
	var place = autocomplete.getPlace();

	var addr = {};
	for (var i = 0; i < place.address_components.length; i++) {
	  var addressType = place.address_components[i].types[0];
	  if (componentForm[addressType]) {
		addr[addressType] = place.address_components[i][componentForm[addressType]];
	  }
	 
	}
	
	addr['place_id'] =  place.place_id;
	addr['latitude'] = place.geometry.location.lat();
	addr['longitude'] = place.geometry.location.lng();
	$('#location_lat').val(addr['latitude']);
	$('#location_lng').val(addr['longitude']);
	loadMap({lat: addr['latitude'], lng: addr['longitude']});
}
 
function loadMap(pos){
	var map, marker;
	
	$('#map').show();
	map = new google.maps.Map(document.getElementById('map'), {
	  center: pos,
	  zoom: 17
	});
	
	marker = new google.maps.Marker({
		position: pos, 
		map: map,
		draggable: true,
		animation: google.maps.Animation.DROP,
	});
	
	google.maps.event.addListener(marker, "dragend", function(event) { 
      var pos = {};
	  pos.lat = event.latLng.lat(); 
	  pos.lng = event.latLng.lng(); 
	  map.setCenter(marker.getPosition());
	  $('#location_lat').val( pos.lat);
	  $('#location_lng').val(pos.lng);
	}); 
	
}

</script>
	
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuZsZjk-oi_W_c9j-eslyO_LkTwU-8X8U&libraries=places&callback=initAutocomplete" async defer></script>