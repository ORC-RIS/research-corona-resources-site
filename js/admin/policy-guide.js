if(!$)$=jQuery;
jQuery(document).ready(function(){

	//Adds new location row to "Policy Guide Locations" table.
	jQuery("#add_new_location_a").click(function(e){
		
		e.preventDefault();
		
		var location_row = "<tr>";
		location_row += "<td><input type='text' class='wide' name='locations_links_names[]' value=''/></td>";
		location_row += "<td><input type='text' class='wide' name='locations_links[]' value=''/></td>";
		location_row += "<td><a href='#' class='delete_location_row'>Delete</a></td>";
		location_row += "</tr>";

		jQuery("#policy_guide_locations_table tbody").append(location_row);

	});

	jQuery(document.body).on('click','.delete_location_row',function(e){
		e.preventDefault();
		jQuery(this).parents("tr").remove();
	});
});