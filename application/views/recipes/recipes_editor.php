<?php
	/**
	 * Created by PhpStorm.
	 * User: dan
	 * Date: 10/14/2016
	 * Time: 10:28 PM
	 */
	
	
	$baseFldClass = "editor-{$action}-field form-control recipe-form-control";
	$baseLblClass = "";
	$baseFldParams = array();
	if(!in_array($action, array('new', 'edit'))) {
		$baseFldParams['disabled'] = "true";
		$baseFldParams['readonly'] = "true";
	}

    $pager = ($page > 1 ? "/{$page}" : "");
	
	// die("<pre>" . print_r($editorInfo, true) . "</pre>");
	
	?>
	<div id="container">
		<div class="page-top-panel">
			<h1><?php echo $page_title; ?></h1>
		</div>
		<div id="body">
			<div id="recipe_editor">
				<?php
					$_attrs = array(
						'id' => 'recipes_editor_form',
						'class' => 'recipes-editor-form',
						'enctype' => 'multipart/form-data'
					);
					$fAction = ($action == 'new' ? 'insert' : 'update');
					echo form_open("/recipes/update_recipe/{$recipes_id}/{$fAction}{$pager}{$_gets}", $_attrs);
					echo form_hidden('MAX_FILE_SIZE', '50331648');
					
					if(isset($editorInfo->categories_id)) {
						echo form_hidden('categories_id', $editorInfo->categories_id);
					}
					
				?>
				<div class="recipe-row recipe-intro top row">
					
					<div class="recipes-editor-info col-xs-12 col-sm-6">
						<div class="recipes-name">
							<?php
								// Category
								$_val = (isset($editorInfo->categories_id) ? $editorInfo->categories_id : '');
								$_params = array(
									'id' => 'categories_id',
									'class' => $baseFldClass
								);
								$_params = array_merge($_params, $baseFldParams);
								$_label = form_label('Category:', 'categories_id', array('class' => $baseLblClass));
								$_fld = (in_array($action, array('new', 'edit')) ? form_dropdown('categories_id', $categoriesArray, $_val, $_params) : $_val);
								echo draw_form_set($_label, $_fld);
								
								// Recipe's Name
								$_val = (isset($editorInfo->recipes_name) ? trim($editorInfo->recipes_name) : '');
								$_attrs = array(
									'name' => 'recipes_name',
									'id' => 'recipes_name',
									'value' => $_val,
									'placeholder' => 'Recipe Name',
									'class' => $baseFldClass,
                                    'onchange' => 'trimInput(this)'
								);
								$_attrs = array_merge($_attrs, $baseFldParams);
								$_label = form_label('Recipe Name:', 'recipes_name', array('class' => $baseLblClass));
								$_fld = (in_array($action, array('new', 'edit')) ? form_input($_attrs) : $_val);
								echo draw_form_set($_label, $_fld);
							?>
						</div>
						<div class="recipes-notes">
							<?php
								$_val = (isset($editorInfo->notes) ? trim($editorInfo->notes) : '');
								$_attrs = array(
									'name' => 'notes',
									'id' => 'notes',
									'value' => $_val,
									'placeholder' => 'Recipe Notes',
									'rows' => 7,
									'class' => $baseFldClass
								);
								$_attrs = array_merge($_attrs, $baseFldParams);
								$_label = form_label('Recipe Notes:', 'notes', array('class' => $baseLblClass));
								$_fld = (in_array($action, array('new', 'edit')) ? form_textarea($_attrs) : $_val);
								echo draw_form_set($_label, $_fld, true);
								
							?>
						</div>
					</div>
					
					<div class="recipe-editor-image main hidden-xs col-sm-6">
						<?php
							// Recipe's Images
							$_val = (isset($editorInfo->recipes_images) ? trim($editorInfo->recipes_images) : '');
							
							if($action == 'edit' && cb_not_null($_val)) {
								$_filePath = DIR_WS_IMAGES . "recipes/" . $editorInfo->recipes_images;
								$_flipped = (strpos($_val, 'category_') !== false ? ' flipped' : '');
								
								$_attrs = array(
									'src' => $_filePath,
									'alt' => $editorInfo->recipes_name,
									'class' => "responsive{$_flipped}",
									'rel' => 'lightbox');
								echo "<div class=\"editor-image-preview\">" . img($_attrs) . "</div>\n";
							}
							
							$_attrs = array(
								'type' => 'hidden',
								'name' => 'recipes_images_start',
								'id' => 'recipes_images_start',
								'value' => $_val
							);
							echo form_input($_attrs);
							
							/*
							$_attrs = array(
								'type' => 'hidden',
								'name' => 'recipes_images',
								'id' => 'recipes_images',
								'value' => $_val
							);
							echo form_input($_attrs);
							*/
							
							$_attrs = array(
								'name' => 'recipes_images',
								'id' => 'recipes_images',
								'value' => $_val,
								'placeholder' => 'Recipe Image',
								'class' => "editor-{$action}-field recipe-form-control"
							);
							$_attrs = array_merge($_attrs, $baseFldParams);
							$_label = form_label('Recipe Image:', 'recipes_images', array('class' => $baseLblClass));
							$_fld = (in_array($action, array('new', 'edit')) ? form_upload($_attrs) : $_val);
							echo draw_form_set($_label, $_fld);
						?>
					</div>
					
					<div class="clearfix"></div>
				</div>
				
				<div class="recipe-row ingredients row">
					<div class="recipe-col ingredients left col-xs-12 col-sm-6 first">
						<?php
							$_val = (isset($editorInfo->ingredients_left) ? trim($editorInfo->ingredients_left) : '');
							$_attrs = array(
								'name' => 'ingredients_left',
								'id' => 'ingredients_left',
								'value' => $_val,
								'placeholder' => "Ingredients (One per line):",
								'rows' => 8,
								'class' => $baseFldClass
							);
							$_attrs = array_merge($_attrs, $baseFldParams);
							$_label = form_label("Ingredients:<br /><span class=\"field-info\">(One per line)</span>", 'ingredients_left', array('class' => $baseLblClass));
							$_fld = (in_array($action, array('new', 'edit')) ? form_textarea($_attrs) : $_val);
							echo draw_form_set($_label, $_fld, true);
							
						?>
					</div>
					<div class="recipe-col ingredients right col-xs-12 col-sm-6 last">
						<?php
							$_val = (isset($editorInfo->ingredients_right) ? trim($editorInfo->ingredients_right) : '');
							$_attrs = array(
								'name' => 'ingredients_right',
								'id' => 'ingredients_right',
								'value' => $_val,
								'placeholder' => "Ingredients Column 2 (One per line):",
								'rows' => 8,
								'class' => $baseFldClass
							);
							$_attrs = array_merge($_attrs, $baseFldParams);
							$_label = form_label("Ingredients Column 2:<br /><span class=\"field-info\">(One per line)</span>", 'ingredients_right', array('class' => $baseLblClass));
							$_fld = (in_array($action, array('new', 'edit')) ? form_textarea($_attrs) : $_val);
							echo draw_form_set($_label, $_fld, true);
						?>
					</div>
				</div>
				
				<div class="recipe-row-h-sep row"></div>
				
				<div class="recipe-row directions row">
					<div class="section-content">
						<?php
							$_val = (isset($editorInfo->directions) ? trim($editorInfo->directions) : '');
							$_attrs = array(
								'name' => 'directions',
								'id' => 'directions',
								'value' => $_val,
								'placeholder' => 'Directions',
								'rows' => 10,
								'class' => $baseFldClass
							);
							$_attrs = array_merge($_attrs, $baseFldParams);
							$_label = form_label('Directions:', 'directions', array('class' => $baseLblClass));
							$_fld = (in_array($action, array('new', 'edit')) ? form_textarea($_attrs) : $_val);
							echo draw_form_set($_label, $_fld, true);
						?>
					</div>
				</div>
				
				<div class="recipe-row row">
					<ul class="viewer-controls form-ul">
						<li class="recipe-row-cell col-xs-12 col-sm-4 first"></li>
						<li class="recipe-row-cell  editor-buttons col-xs-12 col-sm-4">
							<?php
								$_url = ($action == 'new' ? '/recipes/manager' : "/recipes/viewer/{$recipes_id}") . $pager . $_gets;
								echo cb_draw_button('Cancel', 'undo', $_url, null) . "&nbsp;&nbsp;";
								echo cb_draw_button('Apply', 'save', null, null, array('params' => 'name="apply_updates" value="apply"')) . "&nbsp;&nbsp;";
								echo cb_draw_button('Submit', 'save', null, null);
							?>
						</li>
						<li class="recipe-row-cell last-mod col-xs-12 col-sm-4 last">
							Last modified <?php echo date('n-j-Y', $editorInfo->last_mod); ?>
						</li>
					</ul>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
    <script type="text/javascript">
        function trimInput(tObj) {
            var tVal = $(tObj).val().trim();
            $(tObj).val(tVal);
        }
        
        $(function() {
            CKEDITOR.replace("directions", {
                height: '350px',
                width: '100%',
                customConfig: '/javascript/ckeditor/config.js'
            });
        })
    </script>
	
	<?php
	
	