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
						'id' => 'categories_editor_form',
						'class' => 'recipes-editor-form',
						'enctype' => 'multipart/form-data'
					);
					$fAction = ($action == 'new' ? 'insert' : 'update');
					echo form_open("/recipes/update_category/{$categories_id}/{$fAction}{$pager}", $_attrs);
					echo form_hidden('MAX_FILE_SIZE', '50331648');
					
					echo form_hidden('categories_id', $categories_id);
					
				?>
				<div class="recipe-row recipe-intro top row">
					
					<div class="recipes-editor-info col-xs-12 col-sm-6">
						<div class="recipes-name">
							<?php
								// Recipe's Name
								$_val = (isset($editorInfo->categories_name) ? trim($editorInfo->categories_name) : '');
								$_attrs = array(
									'name' => 'categories_name',
									'id' => 'categories_name',
									'value' => $_val,
									'placeholder' => 'Category Name',
									'class' => $baseFldClass
								);
								$_attrs = array_merge($_attrs, $baseFldParams);
								$_label = form_label('Category Name:', 'categories_name', array('class' => $baseLblClass));
								$_fld = (in_array($action, array('new', 'edit')) ? form_input($_attrs) : $_val);
								echo draw_form_set($_label, $_fld);
							?>
						</div>
						<div class="recipes-notes">
							<?php
								$_val = (isset($editorInfo->categories_keywords) ? trim($editorInfo->categories_keywords) : '');
								$_attrs = array(
									'name' => 'categories_keywords',
									'id' => 'categories_keywords',
									'value' => $_val,
									'placeholder' => 'Category Keywords',
									'rows' => 3,
									'class' => $baseFldClass
								);
								$_attrs = array_merge($_attrs, $baseFldParams);
								$_label = form_label('Category Keywords:', 'notes', array('class' => $baseLblClass));
								$_fld = (in_array($action, array('new', 'edit')) ? form_textarea($_attrs) : $_val);
								echo draw_form_set($_label, $_fld, true);
								
							?>
						</div>
					</div>
					
					<div class="recipe-editor-image main hidden-xs col-sm-6">
						<?php
							// Recipe's Images
							$_val = (isset($editorInfo->categories_images) ? trim($editorInfo->categories_images) : '');
							
							if($action == 'edit' && cb_not_null($_val)) {
								$_filePath = DIR_WS_IMAGES . "recipes/" . $editorInfo->categories_images;
								$_flipped = (strpos($_val, 'category_') !== false ? ' flipped' : '');
								
								$_attrs = array(
									'src' => $_filePath,
									'alt' => $editorInfo->categories_name,
									'class' => "responsive{$_flipped}",
									'rel' => 'lightbox');
								echo "<div class=\"editor-image-preview\">" . img($_attrs) . "</div>\n";
							}
							
							$_attrs = array(
								'type' => 'hidden',
								'name' => 'categories_image_start',
								'id' => 'categories_image_start',
								'value' => $_val
							);
							echo form_input($_attrs);
							
							/*
							$_attrs = array(
								'type' => 'hidden',
								'name' => 'categories_image',
								'id' => 'categories_image',
								'value' => $_val
							);
							echo form_input($_attrs);
							*/
							
							$_attrs = array(
								'name' => 'categories_image',
								'id' => 'categories_image',
								'value' => $_val,
								'placeholder' => 'Category Image',
								'class' => "editor-{$action}-field recipe-form-control"
							);
							$_attrs = array_merge($_attrs, $baseFldParams);
							$_label = form_label('Category Image:', 'categories_image', array('class' => $baseLblClass));
							$_fld = (in_array($action, array('new', 'edit')) ? form_upload($_attrs) : $_val);
							echo draw_form_set($_label, $_fld);
						?>
					</div>
					
					<div class="clearfix"></div>
				</div>
				
				<div class="recipe-row description row">
					<div class="section-content">
						<?php
							$_val = (isset($editorInfo->categories_description) ? trim($editorInfo->categories_description) : '');
							$_attrs = array(
								'name' => 'categories_description',
								'id' => 'categories_description',
								'value' => $_val,
								'placeholder' => 'Category Description',
								'rows' => 4,
								'class' => $baseFldClass
							);
							$_attrs = array_merge($_attrs, $baseFldParams);
							$_label = form_label('Category Description:', 'categories_description', array('class' => $baseLblClass));
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
								$_url = ($action == 'new' ? '/recipes' : "/recipes/category_viewer/{$categories_id}{$pager}");
								echo cb_draw_button('Cancel', 'undo', $_url, null) . "&nbsp;&nbsp;";
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
	
	<?php
	
	