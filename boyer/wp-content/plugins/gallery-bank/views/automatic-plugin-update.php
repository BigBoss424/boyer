<?php
switch($gb_role)
{
	case "administrator":
		$user_role_permission = "manage_options";
	break;
	case "editor":
		$user_role_permission = "publish_pages";
	break;
	case "author":
		$user_role_permission = "publish_posts";
	break;
}
if (!current_user_can($user_role_permission))
{
	return;
}
else
{
	?>
	<form id="frm_auto_update" class="layout-form">
		<div id="poststuff" style="width: 99% !important;">
			<div id="post-body" class="metabox-holder">
				<div id="postbox-container-2" class="postbox-container">
					<div id="advanced" class="meta-box-sortables">
						<div id="gallery_bank_get_started" class="postbox" >
							<h3 class="hndle"><span><?php _e("Plugin Updates", gallery_bank); ?></span></h3>
							<div class="inside">
								<div id="ux_dashboard" class="gallery_bank_layout">
									<div class="layout-control-group" style="margin: 10px 0 0 0 ;">
										<label class="layout-control-label"><?php _e("Plugin Updates", gallery_bank); ?> :</label>
										<div class="layout-controls-radio">
											<?php $gallery_updates = get_option("gallery-bank-automatic_update");?>
											<input type="radio" name="ux_gallery_update" id="ux_enable_update" onclick="gallery_bank_autoupdate(this);" <?php echo $gallery_updates == "1" ? "checked=\"checked\"" : "";?> value="1"><label style="vertical-align: baseline;"><?php _e("Enable", gallery_bank); ?></label>
											<input type="radio" name="ux_gallery_update" id="ux_disable_update" onclick="gallery_bank_autoupdate(this);" <?php echo $gallery_updates == "0" ? "checked=\"checked\"" : "";?> style="margin-left: 10px;" value="0"><label style="vertical-align: baseline;"><?php _e("Disable", gallery_bank); ?></label>
										</div>
									</div>
									<div class="layout-control-group" style="margin:10px 0 10px 0 ;">
										<strong><i>This feature allows the plugin to update itself automatically when a new version is available on WordPress Repository.<br/>This allows to stay updated to the latest features. If you would like to disable automatic updates, choose  the disable option above.</i></strong>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script type="text/javascript">
		function gallery_bank_autoupdate(control)
		{
			var gallery_updates = jQuery(control).val();
			jQuery.post(ajaxurl, "gallery_updates="+gallery_updates+"&param=gallery_plugin_updates&action=add_new_album_library", function(data)
			{
			});
		}
		
	</script>
<?php 
}
?>