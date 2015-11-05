<style>
#TB_ajaxContent { width: 752px !important; }
</style>
<div id="my-gallery-content-id" style="display:none;">
	<div style="padding:15px 15px 0 0;">
		<div class="layout-control-group">
			<label class="custom-layout-label" for="ux_gallery"><?php _e("Gallery Type", gallery_bank); ?> : </label>
			<input type="radio" name="ux_gallery" value="1" onclick="check_gallery_type();"/>
			<label><?php _e("Albums with Images", gallery_bank); ?></label>
			<input type="radio" style="margin-left: 10px;" checked="checked" name="ux_gallery" value="0" onclick="check_gallery_type();"/> <label><?php _e("Only Images", gallery_bank); ?> </label>
		</div>
		<div class="layout-control-group">
			<div id="album_format" style="display: none;">
				<label class="custom-layout-label" for="ux_album_format"><?php _e("Album Format", gallery_bank); ?> : </label>
				<select id="ux_album_format" class="layout-span3" onclick="check_gallery_type();" onchange="select_album();">
					<option value="grid">Grid Albums</option>
					<option value="list">List Albums</option>
					<option value="individual">Individual Album</option>
				</select>
			</div>
			<div id="ux_show_multiple_albums" style="display: none;margin-left: 10px;">
				<label class="custom-layout-label"><?php _e("Choose Type", gallery_bank); ?> : </label>
				<select id="ddl_show_albums" class="layout-span3" onchange="show_gallery_albums();" onchange="select_album();">
					<option value="all">All Albums</option>
					<option value="selected">Only Selected Albums</option>
				</select>
			</div>
		</div>
		<div class="layout-control-group" id="ux_select_multiple_albums"><!-- style="display: none;" -->
			<label class="custom-layout-label"><?php _e("Choose Albums", gallery_bank); ?> : </label>
			<select id="ddl_add_multi_album" class="layout-span7" multiple="multiple">
				<?php
				global $wpdb;
				$albums = $wpdb->get_results
				(
					"SELECT * FROM ".gallery_bank_albums()." order by album_order asc "
				);
				for ($flag = 0; $flag < count($albums); $flag++) {
					?>
					<option value="<?php echo intval($albums[$flag]->album_id); ?>"><?php echo esc_html($albums[$flag]->album_name) ?></option>
				<?php
				}
				?>
			</select>
		</div>
		<div class="layout-control-group" id="ux_select_album" style="display: block;">
			<label class="custom-layout-label"><?php _e("Choose Album", gallery_bank); ?> : </label>
			<select id="ux_ddl_select_album" class="layout-span7">
				<?php
				global $wpdb,$current_user;
				
				$gb_role = $wpdb->prefix . "capabilities";
				$current_user->role = array_keys($current_user->$gb_role);
				$gb_role = $current_user->role[0];
				if($gb_role == "administrator")
				{
					$albums = $wpdb->get_results
					(
						"SELECT * FROM ".gallery_bank_albums()." order by album_order asc "
					);
				}
				else 
				{
					$albums = $wpdb->get_results
					(
						$wpdb->prepare
						(
							"SELECT * FROM ".gallery_bank_albums()." where author = %s order by album_order asc ",
							$current_user->display_name
						)
					);
				}
				for ($flag = 0; $flag < count($albums); $flag++) {
					?>
					<option value="<?php echo intval($albums[$flag]->album_id); ?>"><?php echo esc_html($albums[$flag]->album_name) ?></option>
				<?php
				}
				?>
			</select>
		</div>
		<div class="layout-control-group">
			<label class="custom-layout-label"><?php _e("Gallery Format", gallery_bank); ?> : </label>
			<select id="ux_gallery_format" class="layout-span3" onchange="select_images_in_row();">
				<option value="thumbnail">Thumbnail Gallery</option>
				<option value="masonry">Masonry Gallery</option>
				<option value="filmstrip" disabled="disabled" style="color: #FF0000;">Filmstrip Gallery (Available only in Premium Versions)</option>
				<option value="blog" disabled="disabled" style="color: #FF0000;">Blog Style Gallery (Available only in Premium Versions)</option>
				<option id="slide_show" disabled="disabled" value="slideshow" style="color: #FF0000;">Slideshow Gallery (Available only in Premium Versions)</option>
				
			</select>
			<div id="gb_gallery_format" style="display: inline-block; margin-left: 10px;">
				<label class="custom-layout-label"><?php _e("Text Format", gallery_bank); ?> : </label>
				<select id="ux_text_format" class="layout-span3" onchange="show_special_effect();">
					<option value="title_only">With Title only</option>
					<option value="title_desc">With Title and Description</option>
					<option value="no_text">Without Title and Description</option>
				</select>
			</div>
		</div>
		<div class="layout-control-group">
			<div id="div_albums_in_row" style="display: none;margin-right:10px;">
				<label class="custom-layout-label"><?php _e("Albums in Row", gallery_bank); ?> : </label>
				<input type="text" class="layout-span3" name="ux_album_in_row" id="ux_album_in_row" onkeyup="set_text_value(\"album_in_row\");" onkeypress="return OnlyNumbers(event);" value="3"/>
			</div>
			<div id="div_img_in_row" style="display: none;">
				<label class="custom-layout-label"><?php _e("Images in Row", gallery_bank); ?> : </label>
				<input type="text" class="layout-span3" name="ux_img_in_row" id="ux_img_in_row" onkeyup="set_text_value(\"img_in_row\");" onkeypress="return OnlyNumbers(event);" value="3"/>
			</div>
		</div>
		<div class="layout-control-group" id="div_animation_effects">
			<label class="custom-layout-label"><?php _e("Animation Effects", gallery_bank); ?> : </label>
			<select id="ux_animation_effects" class="layout-span3" disabled= "disabled">
				<option value="bounce">Bounce</option>
			</select>
			<div id="div_special_effects" style="display: none;margin-left: 10px;">
				<label class="custom-layout-label"><?php _e("Special Effects", gallery_bank); ?> : </label>
				<select id="ux_special_effects" class="layout-span3" disabled= "disabled">
					<option value="grayscale">Grayscale</option>
				</select>
			</div>
		</div>
		<div class="layout-control-group">
			<label class="custom-layout-label"></label>
			<i class="widget_premium_feature">(Available only in Premium Versions)</i>
		</div>
		<div class="layout-control-group">
			<label class="custom-layout-label"><?php _e("Display Images", gallery_bank); ?> : </label>
			<select name="ddl_display_images" onchange="gb_display_images();" id="ddl_display_images" class="layout-span3">
				<option value="all">All</option>
				<option value="selected">Selected</option>
			</select>
			<div style="display: inline-block;margin-left: 10px;">
				<label class="custom-layout-label"><?php _e("Sort By", gallery_bank); ?> : </label>
				<select name="ddl_sort_order" id="ddl_sort_order" class="layout-span3">
					<option value="random">Random</option>
					<option value="pic_id">Image Id</option>
					<option value="pic_name">File Name</option>
					<option value="title">Title Text</option>
					<option value="date">Date</option>
					<option value="sort_order">Sorting Order</option>
				</select>
			</div>
		</div>
		<div class="layout-control-group" id="div_no_of_images" style="display: none;">
			<label class="custom-layout-label"><?php _e("No. of Images", gallery_bank); ?> : </label>
			<input type="text" class="layout-span3" onkeypress="return OnlyNumbers(event);" name="ux_show_no_of_images" id="ux_show_no_of_images" value="10" />
		</div>
		<div class="layout-control-group">
			<label class="custom-layout-label"><?php _e("Show Responsive Gallery", gallery_bank); ?> : </label>
			<input type="checkbox" checked="checked" onclick="show_images_in_row();" name="ux_responsive_gallery" id="ux_responsive_gallery"/>
			<label class="custom-layout-label" style="margin-left:7%;"><?php _e("Show Album Title", gallery_bank); ?> : </label>
			<input type="checkbox" checked="checked" name="ux_album_title" id="ux_album_title" style="vertical-align: -webkit-baseline-middle;"/>
		</div>
		<div class="layout-control-group">
			<label class="custom-layout-label"></label>
			<input type="button" class="button-primary" value="<?php _e("Insert Album", gallery_bank); ?>" onclick="InsertGallery();"/>&nbsp;&nbsp;&nbsp;
			<a class="button" style="color:#bbb;" href="#" onclick="tb_remove(); return false;"><?php _e("Cancel", gallery_bank); ?></a>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function () {
	check_gallery_type();
	select_images_in_row();
	show_special_effect();
	show_images_in_row();
	gb_display_images();
});
function gb_display_images()
{
	var show_images = jQuery("#ddl_display_images").val();
	if(show_images == "selected")
	{
		jQuery("#div_no_of_images").css("display","block");
	}
	else
	{
		jQuery("#div_no_of_images").css("display","none");
	}
}
function show_images_in_row()
{
	var responsive = jQuery("#ux_responsive_gallery").prop("checked");
	var gallery_format = jQuery("#ux_gallery_format").val();
	if(responsive == true && (gallery_format == "thumbnail" || gallery_format == "masonry" || gallery_format == "slideshow" ))
	{
		jQuery("#div_img_in_row").css("display","none");
	}
	else if(gallery_format != "blog" && gallery_format != "slideshow")
	{
		jQuery("#div_img_in_row").css("display","inline-block");
	}
}
function select_images_in_row() {
	var gallery_format = jQuery("#ux_gallery_format").val();
	switch(gallery_format)
	{
		case "thumbnail":
			jQuery("#div_img_in_row").css("display", "inline-block");
			jQuery("#gb_gallery_format").css("display", "inline-block");
			jQuery("#div_special_effects").css("display", "inline-block");
			jQuery("#div_animation_effects").css("display", "block");
		break;
		case "masonry":
			jQuery("#div_img_in_row").css("display", "inline-block");
			jQuery("#gb_gallery_format").css("display", "inline-block");
			jQuery("#div_special_effects").css("display", "inline-block");
			jQuery("#div_animation_effects").css("display", "block");
			jQuery("#ux_special_effects").val("grayscale");
		break;
		default:
			jQuery("#gb_gallery_format").css("display", "inline-block");
			jQuery("#div_img_in_row").css("display", "none");
			jQuery("#div_special_effects").css("display", "inline-block");
			jQuery("#div_animation_effects").css("display", "block");
		break;
	}
	show_images_in_row();
}
function show_special_effect() {
	var text_format = jQuery("#ux_text_format").val();
	if (text_format == "no_text") {
		jQuery("#div_special_effects").css("display", "inline-block");
	}
	else 
	{
		jQuery("#div_special_effects").css("display", "none");
	}
}
function show_gallery_albums()
{
	var show_albums = jQuery("#ddl_show_albums").val();
	if(show_albums == "all")
	{
		jQuery("#ux_select_multiple_albums").css("display", "none");
	}
	else
	{
		jQuery("#ux_select_multiple_albums").css("display", "block");
	}
}
function check_gallery_type() {
	var gallery_type = jQuery("input:radio[name=ux_gallery]:checked").val();
	var album_format = jQuery("#ux_album_format").val();
	if (gallery_type == 0) {
		jQuery("#album_format").css("display", "none");
		jQuery("#div_albums_in_row").css("display", "none");
		jQuery("#ux_select_album").css("display", "block");
		jQuery("#slide_show").css("display", "none");
		jQuery("#ux_show_multiple_albums").css("display", "none");
		jQuery("#ux_select_multiple_albums").css("display", "none");
	}
	else {
		jQuery("#album_format").css("display", "inline-block");
		if (album_format != "individual") {
			jQuery("#ux_select_album").css("display", "none");
			if (album_format == "grid") {
				jQuery("#div_albums_in_row").css("display", "inline-block");
				jQuery("#slide_show").css("display", "block");
				jQuery("#ux_show_multiple_albums").css("display", "inline-block");
			}
			else {
				jQuery("#div_albums_in_row").css("display", "none");
				jQuery("#slide_show").css("display", "block");
				jQuery("#ux_show_multiple_albums").css("display", "inline-block");
			}
			show_gallery_albums();
		}
		else {
			jQuery("#div_albums_in_row").css("display", "none");
			jQuery("#slide_show").css("display", "block");
			jQuery("#ux_show_multiple_albums").css("display", "none");
			jQuery("#ux_select_multiple_albums").css("display", "none");
		}
	}
}
function select_album() {
	var album_format = jQuery("#ux_album_format").val();
	if (album_format == "individual") {
		jQuery("#ux_select_album").css("display", "block");
	}
	else {
		jQuery("#ux_select_album").css("display", "none");
	}
}
function InsertGallery() {
	var gallery_effect;
	var album_id = jQuery("#ux_ddl_select_album").val();
	var album_format = jQuery("#ux_album_format").val();
	var gallery_format = jQuery("#ux_gallery_format").val();
	var text_format = jQuery("#ux_text_format").val();
	var images_in_row = jQuery("#ux_img_in_row").val();
	var album_in_row = jQuery("#ux_album_in_row").val();
	var gallery_type = jQuery("input:radio[name=ux_gallery]:checked").val();
	var album_type = jQuery("#ddl_show_albums").val();
	var selected_albums = jQuery("#ddl_add_multi_album").val();
	var display_images = jQuery("#ddl_display_images").val();
	var no_of_images = jQuery("#ux_show_no_of_images").val();
	var sort_order = jQuery("#ddl_sort_order").val();

	var displayAlbumTitle = jQuery("#ux_album_title").prop("checked");
	var responsiveGallery = jQuery("#ux_responsive_gallery").prop("checked");
	var responsive;

	if(no_of_images == "")
	{
		alert("<?php _e("Enter number of images you want to display.", gallery_bank) ?>");
		return;
	}
	
	if(responsiveGallery == true)
	{
		responsive = "responsive=\""+ responsiveGallery+"\"";
	}
	else
	{
		responsive = "img_in_row=\""+ images_in_row+"\"";
	}
	
	if(album_type == "all")
	{
		var display_selected_albums = "show_albums=\"all\"";
	}
	else
	{
		var display_selected_albums = "show_albums=\""+ encodeURIComponent(selected_albums)+"\"";
	}

	if(display_images == "all")
	{
		var show_no_of_images = "display=\"all\" sort_by=\""+sort_order+"\"";
	}
	else
	{
		var show_no_of_images = "display=\"selected\" no_of_images=\""+no_of_images+"\" sort_by=\""+sort_order+"\"";
	}
	
	if (gallery_type == 1) {
		if (album_format == "individual") {
			if (gallery_format == "thumbnail" || gallery_format == "masonry") {
				if (text_format == "title_only") {
					window.send_to_editor("[gallery_bank type=\"" + album_format + "\" format=\"" + gallery_format + "\" title=\"true\" desc=\"false\" "+responsive+" "+show_no_of_images+" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\" album_id=\"" + album_id + "\"]");
				}
				else if (text_format == "title_desc") {
					window.send_to_editor("[gallery_bank type=\"" + album_format + "\" format=\"" + gallery_format + "\" title=\"true\" desc=\"true\" "+responsive+" "+show_no_of_images+" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\" album_id=\"" + album_id + "\"]");
				}
				else if (text_format == "no_text") {
					window.send_to_editor("[gallery_bank type=\"" + album_format + "\" format=\"" + gallery_format + "\" title=\"false\" desc=\"false\" "+responsive+" "+show_no_of_images+" special_effect=\"\" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\" album_id=\"" + album_id + "\"]");
				}
			}
		}
		else if (album_format == "grid") {
			if (gallery_format == "thumbnail" || gallery_format == "masonry") {
				if (text_format == "title_only") {
					window.send_to_editor("[gallery_bank type=\"" + album_format + "\" format=\"" + gallery_format + "\" "+display_selected_albums+" title=\"true\" desc=\"false\" "+responsive+" "+show_no_of_images+" albums_in_row=\"" + album_in_row + "\" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\"]");
				}
				else if (text_format == "title_desc") {
					window.send_to_editor("[gallery_bank type=\"" + album_format + "\" format=\"" + gallery_format + "\" "+display_selected_albums+" title=\"true\" desc=\"true\" "+responsive+" "+show_no_of_images+" albums_in_row=\"" + album_in_row + "\" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\"]");
				}
				else if (text_format == "no_text") {
					window.send_to_editor("[gallery_bank type=\"" + album_format + "\" format=\"" + gallery_format + "\" "+display_selected_albums+" title=\"false\" desc=\"false\" "+responsive+" "+show_no_of_images+" albums_in_row=\"" + album_in_row + "\" special_effect=\"\" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\"]");
				}
			}
		}
		else {
			if (gallery_format == "thumbnail" || gallery_format == "masonry") {
				if (text_format == "title_only") {
					window.send_to_editor("[gallery_bank type=\"" + album_format + "\" format=\"" + gallery_format + "\" "+display_selected_albums+" title=\"true\" desc=\"false\" "+responsive+" "+show_no_of_images+" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\"]");
				}
				else if (text_format == "title_desc") {
					window.send_to_editor("[gallery_bank type=\"" + album_format + "\" format=\"" + gallery_format + "\" "+display_selected_albums+" title=\"true\" desc=\"true\" "+responsive+" "+show_no_of_images+" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\"]");
				}
				else if (text_format == "no_text") {
					window.send_to_editor("[gallery_bank type=\"" + album_format + "\" format=\"" + gallery_format + "\" "+display_selected_albums+" title=\"false\" desc=\"false\" "+responsive+" "+show_no_of_images+" special_effect=\"\" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\"]");
				}
			}
		}
	}
	else {
		if (gallery_format == "thumbnail" || gallery_format == "masonry") {
			if (text_format == "title_only") {
				window.send_to_editor("[gallery_bank type=\"images\" format=\"" + gallery_format + "\" title=\"true\" desc=\"false\" "+responsive+" "+show_no_of_images+" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\" album_id=\"" + album_id + "\"]");
			}
			else if (text_format == "title_desc") {
				window.send_to_editor("[gallery_bank type=\"images\" format=\"" + gallery_format + "\" title=\"true\" desc=\"true\" "+responsive+" "+show_no_of_images+" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\" album_id=\"" + album_id + "\"]");
			}
			else if (text_format == "no_text") {
				window.send_to_editor("[gallery_bank type=\"images\" format=\"" + gallery_format + "\" title=\"false\" desc=\"false\" "+responsive+" "+show_no_of_images+" special_effect=\"\" animation_effect=\"\" album_title=\"" + displayAlbumTitle + "\" album_id=\"" + album_id + "\"]");
			}
		}
	}
}
/**
 * @return {boolean}
 */
function OnlyNumbers(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode;
	return (charCode > 47 && charCode < 58) || charCode == 127 || charCode == 8;
}
function set_text_value(text_type) {
	var val = "";
	switch (text_type) {
		case "img_in_row":
			val = jQuery("#ux_img_in_row").val();
			if (val < 1)
				jQuery("#ux_img_in_row").val(1);
			break;
		case  "album_in_row":
			val = jQuery("#ux_album_in_row").val();
			if (val < 1)
				jQuery("#ux_album_in_row").val(1);
			break;
	}
}
</script>