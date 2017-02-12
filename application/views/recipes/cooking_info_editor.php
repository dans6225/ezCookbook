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
	
	// die("<pre>" . print_r($editorInfo, true) . "</pre>");
	
	?>
	<div id="container">
		<div class="page-top-panel">
			<h1><?php echo $page_title; ?></h1>
		</div>
		<div id="body">
			<div id="cooking_info_editor">
				<?php
					$_attrs = array(
						'id' => 'cooking_info_editor_form',
						'class' => 'cooking-info-editor-form',
						'enctype' => 'multipart/form-data'
					);
					$fAction = ($action == 'new' ? 'insert' : 'update');
					echo form_open("/cooking_info/update_info/{$cooking_info_id}/{$fAction}", $_attrs);
					echo form_hidden('MAX_FILE_SIZE', '50331648');
					
					if(isset($editorInfo->cooking_info_id)) {
						echo form_hidden('cooking_info_id', $editorInfo->cooking_info_id);
					}
					
				?>
				<div class="recipe-row recipe-intro top row">
					
					<div class="recipes-editor-info col-xs-12 col-sm-6">
						<div class="recipes-name">
							<?php
								// Cooking Info's Name
								$_val = (isset($editorInfo->cooking_info_name) ? trim($editorInfo->cooking_info_name) : '');
								$_attrs = array(
									'name' => 'cooking_info_name',
									'id' => 'cooking_info_name',
									'value' => $_val,
									'placeholder' => 'Cooking Info Name',
									'class' => $baseFldClass
								);
								$_attrs = array_merge($_attrs, $baseFldParams);
								$_label = form_label('Cooking Info Name:', 'cooking_info_name', array('class' => $baseLblClass));
								$_fld = (in_array($action, array('new', 'edit')) ? form_input($_attrs) : $_val);
								echo draw_form_set($_label, $_fld);
							?>
						</div>
						<div class="recipes-notes">
							<?php
								$_val = (isset($editorInfo->cooking_info_notes) ? trim($editorInfo->cooking_info_notes) : '');
								$_attrs = array(
									'name' => 'cooking_info_notes',
									'id' => 'cooking_info_notes',
									'value' => $_val,
									'placeholder' => 'Cooking Info Notes',
									'rows' => 3,
									'class' => $baseFldClass
								);
								$_attrs = array_merge($_attrs, $baseFldParams);
								$_label = form_label('Cooking Info Notes:', 'notes', array('class' => $baseLblClass));
								$_fld = (in_array($action, array('new', 'edit')) ? form_textarea($_attrs) : $_val);
								echo draw_form_set($_label, $_fld, true);
								
							?>
						</div>
					</div>
					
					<div class="recipe-editor-image main hidden-xs col-sm-6">
						<?php
							// Recipe's Images
							$_val = (isset($editorInfo->cooking_info_image) ? trim($editorInfo->cooking_info_image) : '');
							if($action == 'edit' && cb_not_null($_val)) {
								$_filePath = DIR_WS_IMAGES . "recipes/" . $editorInfo->cooking_info_image;
								
								$_attrs = array(
									'src' => $_filePath,
									'alt' => $editorInfo->cooking_info_name,
									'class' => "responsive",
									'rel' => 'lightbox');
								echo "<div class=\"editor-image-preview\">" . img($_attrs) . "</div>\n";
							}
							
							$_attrs = array(
								'type' => 'hidden',
								'name' => 'cooking_info_image_start',
								'id' => 'cooking_info_image_start',
								'value' => $_val
							);
							echo form_input($_attrs);
							
							
							$_attrs = array(
								'name' => 'cooking_info_image',
								'id' => 'cooking_info_image',
								'value' => $_val,
								'placeholder' => 'Cooking Info Image',
								'class' => "editor-{$action}-field recipe-form-control"
							);
							$_attrs = array_merge($_attrs, $baseFldParams);
							$_label = form_label('Cooking Info Image:', 'cooking_info_image', array('class' => $baseLblClass));
							$_fld = (in_array($action, array('new', 'edit')) ? form_upload($_attrs) : $_val);
							echo draw_form_set($_label, $_fld);
						?>
					</div>
					
					<div class="clearfix"></div>
				</div>
				
				<div class="recipe-row-h-sep row"></div>
				
				<div class="recipe-row cooking-info row">
					<div class="section-content">
						<?php
							$_val = (isset($editorInfo->cooking_info) ? trim($editorInfo->cooking_info) : '');
							$_attrs = array(
								'name' => 'cooking_info',
								'id' => 'cooking_info',
								'value' => $_val,
								'placeholder' => 'Cooking Info',
								'rows' => 6,
								'class' => $baseFldClass
							);
							$_attrs = array_merge($_attrs, $baseFldParams);
							$_label = form_label('Cooking Info:', 'cooking_info', array('class' => $baseLblClass));
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
								$_url = ($action == 'new' ? '/cooking_info/manager' : "/cooking_info/viewer/{$cooking_info_id}");
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
	
	