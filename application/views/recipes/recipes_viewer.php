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
						$_filePath = DIR_WS_IMAGES . "recipes/" . $viewerInfo->recipes_images;
						
						if(cb_not_null($viewerInfo->recipes_images) && is_file($_filePath)) {
							$_has_images = true;
							$_class = "col-xs-12 col-sm-6";
						}
					
					?>
					<div class="recipes-viewer-info <?php echo $_class; ?>">
						<div class="recipes-name"><?php echo $viewerInfo->recipes_name; ?></div>
						<?php if(cb_not_null($viewerInfo->notes)) { ?>
							<div class="recipes-notes"><?php echo $viewerInfo->notes; ?></div>
						<?php } ?>
					</div>
					<?php
						$_attrs = array(
							'src' => $_filePath,
							'alt' => $viewerInfo->recipes_name,
							// 'class' => 'flipped responsive'
						);
						// anchor($_filePath, img($_attrs), array('data-lightbox' => 'recipe-img', 'data-title' => $viewerInfo->recipes_name))
						// img($_attrs)
						if($_has_images) {
							echo "<div class=\"recipe-viewer-image main hidden-xs col-sm-6\">" . anchor($_filePath, img($_attrs), array('data-lightbox' => 'recipe-img', 'data-title' => $viewerInfo->recipes_name)) . "</div>\n";
						}
					?>
					<div class="clearfix"></div>
				</div>
				
				<div class="recipe-row ingredients row">
					<div class="section-label">Ingredients</div>
					<div class="recipe-col ingredients left col-xs-12 col-sm-6 first">
						<?php echo $viewerInfo->ingredients_left; ?>
					</div>
					<?php if(cb_not_null($viewerInfo->ingredients_right)) { ?>
					<div class="recipe-col ingredients right col-xs-12 col-sm-6 last">
						<?php echo $viewerInfo->ingredients_right; ?>
					</div>
					<?php } ?>
				</div>
				
				<div class="recipe-row-h-sep row"></div>
				
				<div class="recipe-row directions row">
					<div class="section-label">Directions</div>
					<div class="section-content">
						<?php echo $viewerInfo->directions; ?>
					</div>
				</div>
				
				<div class="recipe-row row">
					<ul class="viewer-controls form-ul">
						<li class="recipe-row-cell col-xs-12 col-sm-4 first"></li>
						<li class="recipe-row-cell viewer-buttons col-xs-12 col-sm-4 noprint">
							<?php
							    $pager = ($page > 1 ? "/{$page}" : "");
								echo cb_draw_button('Back', 'undo', '/recipes/manager' . $pager . $_gets) . "&nbsp;&nbsp;";
								echo cb_draw_button('Edit', 'edit', '/recipes/editor/' . $recipes_id . $pager . $_gets) . "&nbsp;&nbsp;";
								echo cb_draw_button('Delete', 'trash', 'javascript:void(0);', 'delete-btn', array('params' => 'onclick="deleteRecipe(' . $recipes_id . ')"'))
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
		function deleteRecipe(rid) {
			var recipesName = '<?php echo $viewerInfo->recipes_name; ?>';
			if(recipesName.length < 1) {
				recipesName = 'This Recipe';
			} else {
				recipesName = 'The Recipe ' + recipesName;
			}
			var dConfirm = confirm('Are You Sure You Want To Delete ' + recipesName);
			if(dConfirm == true) {
				$.get(
					'/recipes/delete_recipe/' + rid,
					function(data) {
						window.location.assign('/recipes/manager');
					},
					"json"
				);
			}
		}
	</script>
	
	<?php
	
	