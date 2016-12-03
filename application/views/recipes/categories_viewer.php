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
				<div class="recipe-row categories-intro top row">
					<?php
						$_has_images = false;
						$_class = "col-xs-12";
						$_filePath = DIR_WS_IMAGES . "recipes/" . $viewerInfo->categories_image;
						
						if(cb_not_null($viewerInfo->categories_image) && is_file($_filePath)) {
							$_has_images = true;
							$_class = "col-xs-12 col-sm-6";
						}
					?>
					<div class="recipes-viewer-info <?php echo $_class; ?>">
						<div class="categories-name viewer-name"><label>Category Name:</label> <?php echo $viewerInfo->categories_name; ?></div>
						<div class="categories-keywords viewer-keywords"><label>Category Keywords <span class="label-field-info">(Used for Search)</span>:</label><br />
							<?php echo $viewerInfo->categories_keywords; ?>
						</div>
						<?php if(cb_not_null($viewerInfo->categories_description)) { ?>
							<div class="categories-description viewer-description"><label>Description:</label><br />
								<?php echo $viewerInfo->categories_description; ?>
							</div>
						<?php } ?>
					</div>
					<?php
						$_attrs = array(
							'src' => $_filePath,
							'alt' => $viewerInfo->categories_name,
							'class' => 'flipped responsive',
							'rel' => 'lightbox'
						);
						if($_has_images) {
							echo "<div class=\"recipe-viewer-image main hidden-xs col-sm-6\">" . img($_attrs) . "</div>\n";
						}
					?>
					<div class="clearfix"></div>
				</div>
				
				<div class="recipe-row row">
					<ul class="viewer-controls form-ul">
						<li class="recipe-row-cell col-xs-12 col-sm-4 first"></li>
						<li class="recipe-row-cell viewer-buttons col-xs-12 col-sm-4 noprint">
							<?php
								echo cb_draw_button('Back', 'undo', '/recipes/categories_manager/') . "&nbsp;&nbsp;";
								echo cb_draw_button('Edit', 'edit', '/recipes/category_editor/' . $categories_id) . "&nbsp;&nbsp;";
								echo cb_draw_button('Delete', 'trash', 'javascript:void(0);', 'delete-btn', array('params' => 'onclick="deleteCategory(' . $categories_id . ')"'))
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
		function deleteCategory(cid) {
			var categoriesName = '<?php echo $viewerInfo->categories_name; ?>';
			if(categoriesName.length < 1) {
				categoriesName = 'This Category';
			} else {
				categoriesName = 'The Category ' + categoriesName;
			}
			var dConfirm = confirm('Are You Sure You Want To Delete ' + categoriesName);
			if(dConfirm == true) {
				$.get(
					'/recipes/delete_category/' + cid,
					function(data) {
						window.location.assign('/recipes/categories_manager');
					},
					"json"
				);
			}
		}
	</script>
	
	<?php
	
	