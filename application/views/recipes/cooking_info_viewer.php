<?php
	/**
	 * Created by PhpStorm.
	 * User: dan
	 * Date: 10/14/2016
	 * Time: 10:28 PM
	 */
	
	?>
	<div id="container">
		<div class="page-top-panel">
			<h1><?php echo $page_title; ?></h1>
		</div>
		<div id="body">
			<div id="recipe_viewer">
				<div class="recipe-row recipe-intro top row">
					<?php
						$_has_images = false;
						$_class = "col-xs-12";
						$_filePath = DIR_WS_IMAGES . "recipes/" . $viewerInfo->cooking_info_image;
						
						if(cb_not_null($viewerInfo->cooking_info_image) && is_file($_filePath)) {
							$_has_images = true;
							$_class = "col-xs-12 col-sm-6";
						}
					
					?>
					<div class="recipes-viewer-info <?php echo $_class; ?>">
						<div class="recipes-name"><?php echo $viewerInfo->cooking_info_image; ?></div>
						<?php if(cb_not_null($viewerInfo->cooking_info_notes)) { ?>
							<div class="recipes-notes"><?php echo $viewerInfo->cooking_info_notes; ?></div>
						<?php } ?>
					</div>
					<?php
						$_attrs = array(
							'src' => $_filePath,
							'alt' => $viewerInfo->cooking_info_name,
							'class' => 'flipped responsive'
						);
						if($_has_images) {
							echo "<div class=\"recipe-viewer-image main hidden-xs col-sm-6\">" . anchor($_filePath, img($_attrs), array('data-lightbox' => 'cooking-info-img', 'data-title' => $viewerInfo->cooking_info_name)) . "</div>\n";
						}
					?>
					<div class="clearfix"></div>
				</div>
				
				<div class="recipe-row-h-sep row"></div>
				
				<div class="recipe-row directions row">
					<div class="section-label">Cooking Info</div>
					<div class="section-content">
						<?php echo $viewerInfo->cooking_info; ?>
					</div>
				</div>
				
				<div class="recipe-row row">
					<ul class="viewer-controls form-ul">
						<li class="recipe-row-cell col-xs-12 col-sm-4 first"></li>
						<li class="recipe-row-cell viewer-buttons col-xs-12 col-sm-4 noprint">
							<?php
								echo cb_draw_button('Back', 'undo', '/cooking_info/manager/') . "&nbsp;&nbsp;";
								echo cb_draw_button('Edit', 'edit', '/cooking_info/editor/' . $cooking_info_id) . "&nbsp;&nbsp;";
								echo cb_draw_button('Delete', 'trash', 'javascript:void(0);', 'delete-btn', array('params' => 'onclick="deleteCookingInfo(' . $cooking_info_id . ')"'))
							?>
						</li>
						<li class="recipe-row-cell last-mod col-xs-12 col-sm-4 last">
							Last modified <?php echo date('n-j-Y', $viewerInfo->last_mod); ?>
						</li>
					</ul>
					
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function deleteCookingInfo(ciid) {
			var infoName = '<?php echo $viewerInfo->cooking_info_name; ?>';
			if(infoName.length < 1) {
				infoName = 'This Cooking Info';
			} else {
				infoName = 'The Cooking Info ' + infoName;
			}
			var dConfirm = confirm('Are You Sure You Want To Delete ' + infoName);
			if(dConfirm == true) {
				$.get(
					'/cooking_info/delete_info/' + ciid,
					function(data) {
						window.location.assign('/cooking_info/manager');
					},
					"json"
				);
			}
		}
	</script>
	
	<?php
	
	