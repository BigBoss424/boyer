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
		$show_gallery_demo = get_option("gallery-bank-demo");
		if($show_gallery_demo == "")
		{
			/******************************************Code for Thumbnails Creation**********************/
			if(!function_exists("uploading_gallery_image"))
			{
				function uploading_gallery_image($image, $width, $height)
				{
					$temp_image_path = GALLERY_MAIN_UPLOAD_DIR . $image;
					$temp_image_name = $image;
					list(, , $temp_image_type) = getimagesize($temp_image_path);
					if ($temp_image_type === NULL) {
						return false;
					}
					$uploaded_image_path = GALLERY_MAIN_UPLOAD_DIR . $temp_image_name;
					move_uploaded_file($temp_image_path, $uploaded_image_path);
					$type = explode(".", $image);
					$thumbnail_image_path = GALLERY_MAIN_THUMB_DIR . preg_replace('{\\.[^\\.]+$}', '.'.$type[1], $temp_image_name);
			
					$result = generating_gallery_thumbnail($uploaded_image_path, $thumbnail_image_path, $width, $height);
					return $result ? array($uploaded_image_path, $thumbnail_image_path) : false;
				}
			}
			if(!function_exists("uploading_gallery_album"))
			{
				function uploading_gallery_album($album_image, $width, $height)
				{
					$temp_image_path = GALLERY_MAIN_UPLOAD_DIR . $album_image;
					$temp_image_name = $album_image;
					list(, , $temp_image_type) = getimagesize($temp_image_path);
					if ($temp_image_type === NULL) {
						return false;
					}
					$uploaded_image_path = GALLERY_MAIN_UPLOAD_DIR . $temp_image_name;
					move_uploaded_file($temp_image_path, $uploaded_image_path);
					$type = explode(".", $album_image);
					$thumbnail_image_path = GALLERY_MAIN_ALB_THUMB_DIR . preg_replace("{\\.[^\\.]+$}", ".".$type[1], $temp_image_name);
			
					$result = generating_gallery_thumbnail($uploaded_image_path, $thumbnail_image_path, $width, $height);
					return $result ? array($uploaded_image_path, $thumbnail_image_path) : false;
				}
			}
			/****************************** COMMON FUNCTION TO GENERATE THUMBNAILS********************************/
			if(!function_exists("generating_gallery_thumbnail"))
			{
				function generating_gallery_thumbnail($source_image_path, $thumbnail_image_path, $imageWidth, $imageHeight)
				{
					list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
					$source_gd_image = false;
					switch ($source_image_type) {
			
						case IMAGETYPE_GIF:
							$source_gd_image = imagecreatefromgif($source_image_path);
							break;
						case IMAGETYPE_JPEG:
							$source_gd_image = imagecreatefromjpeg($source_image_path);
							break;
						case IMAGETYPE_PNG:
							$source_gd_image = imagecreatefrompng($source_image_path);
							break;
					}
					if ($source_gd_image === false) {
						return false;
					}
					$source_aspect_ratio = $source_image_width / $source_image_height;
					if ($source_image_width > $source_image_height) {
						(int)$real_height = $imageHeight;
						(int)$real_width = $imageHeight * $source_aspect_ratio;
					} else if ($source_image_height > $source_image_width) {
						(int)$real_height = $imageWidth / $source_aspect_ratio;
						(int)$real_width = $imageWidth;
			
					} else {
			
						(int)$real_height = $imageHeight > $imageWidth ? $imageHeight : $imageWidth;
						(int)$real_width = $imageWidth > $imageHeight ? $imageWidth : $imageHeight;
					}
					$thumbnail_gd_image = imagecreatetruecolor($real_width, $real_height);
					$bg_color = imagecolorallocate($thumbnail_gd_image, 255, 255, 255);
					imagefilledrectangle($thumbnail_gd_image, 0, 0, $real_width, $real_height, $bg_color);
					imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $real_width, $real_height, $source_image_width, $source_image_height);
			
					imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 100);
					imagedestroy($source_gd_image);
					imagedestroy($thumbnail_gd_image);
					return true;
				}
			}
			/******************************************End of Code for Thumbnails Creation **********************/
			$total_albums = $wpdb->get_var
			(
				"SELECT count(album_id) FROM ".gallery_bank_albums()
			);
			if($total_albums == 0)
			{
				$wpdb->query
				(
					$wpdb->prepare
					(
						"INSERT INTO " . gallery_bank_albums() . "(album_name, description, album_date, author)
						VALUES(%s, %s, CURDATE(), %s)",
						"Demo Album",
						"",
						$current_user->display_name
					)
				);
				$demo_albumid = $wpdb->insert_id;
				$wpdb->query
				(
					$wpdb->prepare
					(
						"UPDATE " . gallery_bank_albums() . " SET album_order = %d WHERE album_id = %d",
						$demo_albumid,
						$demo_albumid
					)
				);
				$cover_pic_id = 0;
				$images_array = array("coffee.jpg","hunter.jpg","ice-cream.jpg","like.jpg","pawns.jpg","wallpaper.jpg");
				
				foreach ($images_array as $image)
				{
					$src_image = GALLERY_BK_PLUGIN_DIR."images/".$image;
					$destination_image = GALLERY_MAIN_UPLOAD_DIR.$image;
					if (PHP_VERSION > 5)
					{
						copy($src_image, $destination_image);
					}
					else
					{
						$content = file_get_contents($src_image);
						$fp = fopen($destination_image, "w");
						fwrite($fp, $content);
						fclose($fp);
					}
					if(file_exists($destination_image))
					{
						uploading_gallery_image($image, 160, 120);
					}
					
					$wpdb->query
					(
						$wpdb->prepare
						(
							"INSERT INTO " . gallery_bank_pics() . " (album_id,thumbnail_url,title,description,url,video,date,tags,pic_name,album_cover)
							VALUES(%d,%s,%s,%s,%s,%d,CURDATE(),%s,%s,%d)",
							$demo_albumid,
							$image,
							"",
							"",
							"http://",
							0,
							"",
							$image,
							($cover_pic_id == 0 ? 1 : 0)
						)
					);
					$image_id = $wpdb->insert_id;
					$wpdb->query
					(
						$wpdb->prepare
						(
							"UPDATE " . gallery_bank_pics() . " SET sorting_order = %d WHERE pic_id = %d",
							$image_id,
							$image_id
						)
					);
					if($cover_pic_id == 0)
					{
						$cover_pic_id = 1;
						uploading_gallery_album($image, 160, 120);
					}
				}
			}
			update_option("gallery-bank-demo", "1");
		}
		
		$last_album_id = $wpdb->get_var
		(
			"SELECT album_id FROM " .gallery_bank_albums(). " order by album_id desc limit 1"
		);
		
		$album = $wpdb->get_results
		(
			"SELECT * FROM ".gallery_bank_albums()." order by album_order asc "
		);
		$album_css = $wpdb->get_results
		(
			"SELECT * FROM ".gallery_bank_settings()
		);
		if(count($album_css) != 0)
		{
			$setting_keys= array();
			for($flag=0;$flag<count($album_css);$flag++)
			{
				array_push($setting_keys,$album_css[$flag]->setting_key);
			}
			$index = array_search("cover_thumbnail_width", $setting_keys);
			$cover_thumbnail_width = $album_css[$index]->setting_value;
			
			$index = array_search("cover_thumbnail_height", $setting_keys);
			$cover_thumbnail_height = $album_css[$index]->setting_value;
			
			$index = array_search("cover_thumbnail_opacity", $setting_keys);
			$cover_thumbnail_opacity = $album_css[$index]->setting_value;
			
			$index = array_search("cover_thumbnail_border_size", $setting_keys);
			$cover_thumbnail_border_size = $album_css[$index]->setting_value;
			
			$index = array_search("cover_thumbnail_border_radius", $setting_keys);
			$cover_thumbnail_border_radius = $album_css[$index]->setting_value;
			
			$index = array_search("cover_thumbnail_border_color", $setting_keys);
			$cover_thumbnail_border_color = $album_css[$index]->setting_value;
		}
		
	?>
	<!--suppress ALL -->
	
	        <style type="text/css">
		.dynamic_cover_css{
			border:<?php echo $cover_thumbnail_border_size;?>px solid <?php echo $cover_thumbnail_border_color;?> ;
			-moz-border-radius:<?php echo $cover_thumbnail_border_radius; ?>px;
			-webkit-border-radius:<?php echo $cover_thumbnail_border_radius; ?>px;
			-khtml-border-radius:<?php echo $cover_thumbnail_border_radius; ?>px;
			-o-border-radius:<?php echo $cover_thumbnail_border_radius; ?>px;
			border-radius:<?php echo $cover_thumbnail_border_radius;?>px;
			opacity:<?php echo $cover_thumbnail_opacity;?>;
			-moz-opacity:<?php echo $cover_thumbnail_opacity;?>;
			-khtml-opacity:<?php echo $cover_thumbnail_opacity;?>;
		}
		.imgLiquidFill
		{
			width:<?php echo $cover_thumbnail_width;?>px;
			height:<?php echo $cover_thumbnail_height;?>px;
		}
		div.pp_default .pp_top .pp_middle {
	    background-color: #ffffff;
	    }
		.pp_pic_holder.pp_default {
		    background-color: #ffffff;
	    }
	    div.pp_default .pp_content_container .pp_left {
	        background-color: #ffffff;
	        padding-left: 16px;
	    }
	
	    div.pp_default .pp_content_container .pp_right {
	        background-color: #ffffff;
	        padding-right: 13px;
	    }
	
	    div.pp_default .pp_bottom .pp_middle {
	        background-color: #ffffff;
	    }
	
	    div.pp_default .pp_content, div.light_rounded .pp_content {
	        background-color: #ffffff;
	    }
	
	    .pp_details {
	        background-color: #ffffff;
	    }
	
	    .ppt {
	        display: none !important;
	    }
	</style>
	<div class="custom-message red">
		<span style="line-height: inherit;">
			<?php echo( sprintf( __( "<b>Notice:</b> Your server allows you to upload <b>%s</b> files of maximum total <b>%s</b> bytes and allows <b>%s</b> seconds to complete.", gallery_bank ), $max_files_upload, $max_files_size, $max_files_time));?>
			<?php _e( "<br />If your request exceeds these limitations, it will fail, probably without an errormessage.", gallery_bank); ?>
			<?php _e( "<br />Additionally your hosting provider may have set other limitations on uploading files.", gallery_bank); ?>
			<?php echo check_server_configuration();?>
		</span>
	</div>
	<form id="frmdashboard" class="layout-form">
		<div id="poststuff" style="width: 99% !important;">
			<div id="post-body" class="metabox-holder">
				<div id="postbox-container-2" class="postbox-container">
					<div id="advanced" class="meta-box-sortables">
						<div id="gallery_bank_get_started" class="postbox" >
							<div class="handlediv" data-target="#ux_shortcode" title="Click to toggle" data-toggle="collapse"><br></div>
							<h3 class="hndle"><span><?php _e("Gallery Bank Dashboard", gallery_bank); ?></span></h3>
							<div class="inside">
								<div id="ux_dashboard" class="gallery_bank_layout">
									<?php
									$album_count = $wpdb->get_var
									(
										"SELECT count(album_id) FROM ".gallery_bank_albums()
									);
									if($album_count < 3)
									{
										?>
										<a class="btn btn-info" href="admin.php?page=save_album&album_id=<?php echo count($last_album_id) == 0 ? 1 : $last_album_id + 1; ?>"><?php _e("Add New Album", gallery_bank);?></a>
										<?php
									}
									?>
									
												
									<a class="btn btn-danger" href="#" onclick="delete_all_albums();"><?php _e("Delete All Albums", gallery_bank);?></a>
									<a class="btn btn-danger" href="#" onclick="purge_all_images();"><?php _e("Purge Images & Albums", gallery_bank);?></a>
									<a class="btn btn-danger" href="#" onclick="restore_factory_settings();"><?php _e("Restore Factory Settings", gallery_bank);?></a>
									<div class="separator-doubled"></div>
									<a rel="prettyPhoto[gallery]" href="<?php echo plugins_url("/assets/images/how-to-setup-short-code.png",dirname(__FILE__));?>">How to setup Short-Codes for Gallery Bank into your WordPress Page/Post?</a>
									<div class="fluid-layout">
										<div class="layout-span12">
											<div class="widget-layout">
												<div class="widget-layout-title">
													<h4><?php _e( "Existing Albums Overview", gallery_bank ); ?></h4>
												</div>
												<div class="widget-layout-body">
													<table class="table table-striped " id="data-table-album">
														<thead>
															<tr>
																<th style="width:24%"><?php _e( "Thumbnail", gallery_bank ); ?></th>
																<th style="width:13%"><?php _e( "Title", gallery_bank ); ?></th>
																<th style="width:14%"><?php _e( "Total Images", gallery_bank ); ?></th>
																<th style="width:14%"><?php _e( "Date", gallery_bank ); ?></th>
																<th style="width:14%"><?php _e( "Short-Codes", gallery_bank ); ?></th>
																<th style="width:20%"></th>
															</tr>
														</thead>
														<tbody>
															<?php
															for($flag=0; $flag <count($album); $flag++)
															{
																$count_pic = $wpdb->get_var
																(
																	$wpdb->prepare
																	(
																		"SELECT count(".gallery_bank_albums().".album_id) FROM ".gallery_bank_albums()." join ".gallery_bank_pics()." on ".gallery_bank_albums().".album_id =  ".gallery_bank_pics().".album_id where ".gallery_bank_albums().".album_id = %d ",
																		$album[$flag]->album_id
																	)
																);
																$albumCover = $wpdb->get_row
																(
																	$wpdb->prepare
																	(
																		"SELECT album_cover,thumbnail_url,video FROM ".gallery_bank_pics()." WHERE album_cover=1 and album_id = %d",
																		$album[$flag]->album_id
																	)
																);
																?>
																	<tr>
																		<td>
																			<a href="admin.php?page=save_album&album_id=<?php echo $album[$flag]->album_id;?>" title="<?php echo stripcslashes(htmlspecialchars_decode($album[$flag] -> album_name));?>" >
																				<div class="imgLiquidFill dynamic_cover_css">
																					<?php
																					if(count($albumCover) != 0)
																					{
																						if($albumCover->album_cover == 0)
																						{
																							?>
																							<img src="<?php echo stripcslashes(plugins_url("/assets/images/album-cover.png",dirname(__FILE__))); ?>"  />
																							<?php
																						}
																						else
																						{
																							?> 
																							<img src="<?php echo stripcslashes(GALLERY_BK_ALBUM_THUMB_URL.$albumCover->thumbnail_url); ?>"   />
																							<?php
																						}
																					}
																					else 
																					{
																						?> 
																						<img src="<?php echo stripcslashes(plugins_url("/assets/images/album-cover.png",dirname(__FILE__))); ?>"   />	
																						<?php
																					}
																					?>
																				</div>
																			</a>
																		</td>
																		<td><?php echo stripcslashes(htmlspecialchars_decode($album[$flag] -> album_name));?></td>
																		<td><?php echo $count_pic;?></td>
																		<td><?php echo date("d-M-Y", strtotime($album[$flag] -> album_date));?></td>
																		<td>
																			<a rel="prettyPhoto[gallery]" href="<?php echo plugins_url("/assets/images/how-to-setup-short-code.png",dirname(__FILE__));?>">Short Codes</a>
																		</td>
																		<td>
																			<ul class="layout-table-controls">
																				<li>
																					<a href="admin.php?page=save_album&album_id=<?php echo $album[$flag]->album_id;?>" class="btn hovertip" data-original-title="<?php _e( "Edit Album", gallery_bank ); ?>">
																						<i class="icon-pencil" ></i>
																					</a>
																				</li>
																				<li>
																					<a href="admin.php?page=images_sorting&album_id=<?php echo $album[$flag]->album_id;?>&row=3" class="btn hovertip" data-original-title="<?php _e( "Re-Order Images", gallery_bank ); ?>">
																						<i class="icon-th"></i>
																					</a>
																				</li>
																				<li>
																					<a href="admin.php?page=album_preview&album_id=<?php echo $album[$flag]->album_id;?>" class="btn hovertip" data-original-title="<?php _e( "Preview Album", gallery_bank ); ?>">
																						<i class="icon-eye-open"></i>
																					</a>
																				</li>
																				<li>
																					<a class="btn hovertip "  style="cursor: pointer;" data-original-title="<?php _e( "Delete Album", gallery_bank)?>" onclick="delete_album(<?php echo $album[$flag]->album_id;?>);" >
																						<i class="icon-trash"></i>
																					</a>
																				</li>
																			</ul>
																		</td>
																	</tr>
																<?php
															}
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
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
		jQuery(".hovertip").tooltip_tip();
		jQuery(document).ready(function() 
		{
			jQuery(".imgLiquidFill").imgLiquid({fill:true});
			var oTable = jQuery("#data-table-album").dataTable
			({
				"bJQueryUI": false,
				"bAutoWidth": true,
				"sPaginationType": "full_numbers",
				"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
				"oLanguage": 
				{
					"sLengthMenu": "<span>Show entries:</span> _MENU_"
				},
				"aaSorting": [[ 0, "asc" ]],
				"aoColumnDefs": [{ "bSortable": false, "aTargets": [5] }]
			});
			jQuery("a[rel^=\"prettyPhoto\"]").prettyPhoto
			({
				animation_speed: 1000, 
				slideshow: 4000, 
				autoplay_slideshow: false,
				opacity: 0.80, 
				show_title: false,
				allow_resize: true
			});
		});
	
		function delete_album(album_id) 
		{
			var r = confirm("<?php _e( "Are you sure you want to delete this Album?", gallery_bank ); ?>");
			if(r == true)
			{
				//noinspection JSUnresolvedVariable
				jQuery.post(ajaxurl, "album_id="+album_id+"&param=Delete_album&action=add_new_album_library", function()
				{
					var check_page = "<?php echo $_REQUEST["page"]; ?>";
					window.location.href = "admin.php?page="+check_page;
				});
			}
		}
		function delete_all_albums()
		{
			alert("<?php _e( "This feature is only available in Paid Premium Version!", gallery_bank ); ?>");
		}
		function restore_factory_settings()
		{
			alert("<?php _e( "This feature is only available in Paid Premium Version!", gallery_bank ); ?>");
		}
		function purge_all_images()
		{
			alert("<?php _e( "This feature is only available in Paid Premium Version!", gallery_bank ); ?>");
		}
	</script>
	<?php 
	}
	?>
