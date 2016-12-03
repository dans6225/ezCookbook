<?php
	/**
	 * Created by Danola Digital.
	 * User: dan
	 * Date: 6/5/2016
	 * Time: 6:16 PM
	 */
	
	$baseURL = base_url();
	
	// echo "<pre>" . print_r($this->db->list_fields('recipes'), true) . "</pre>";
	
?>
	<div id="container">
		<div id="body">
			<div class="body-top row">
				<h1>Nola's Recipe Box</h1>
			</div>
			<div class="body-main row">
				<div class="block-left col-xs-12 col-sm-6 col-md-4">
					<div id="favorites_box">
						<div class="box-heading">Favorites</div>
						<?php
							if(cb_not_null($favorites)) {
								$fList = array();
								foreach($favorites as $fid => $favorite) {
									$_row = "<div class=\"favorite-row listing-flex-row\">";
									if(cb_not_null($favorite->recipes_images)) {
										$_row .= "	<div class=\"favorite-image listing-flex-row-cell\">";
										$_row .= anchor("recipes/viewer/{$fid}", img(DIR_WS_IMAGES . "recipes/{$favorite->recipes_images}"), "title=\"{$favorite->recipes_name}\"") . "&nbsp;&nbsp;";
										$_row .= "	</div>\n";
									}
									$_row .= "	<div class=\"favorite-info listing-flex-row-cell\">";
									$_row .= anchor("recipes/viewer/{$fid}", $favorite->recipes_name, "title=\"{$favorite->recipes_name}\"") . "<br />\n";
									$_row .= "Category: {$favorite->categories_name}";
									$_row .= "	</div>\n";
									
									$_row .= "</div>\n";
									
									$fList[$fid] = $_row;
								}
								$_attrs = array(
									'class' => 'listing-ul',
									'id' => 'favorites_list'
								);
								echo ul($fList, $_attrs);
							}
						?>
					</div>
				</div>
				<div class="block-right col-xs-12 col-sm-6 col-md-8">
					<div id="recipe_search">
						<?php
							// echo form_open('/recipes/manager', array('id' => 'recipes_finder_form', 'class' => 'micro-search-form', 'method' => 'get'));
							echo form_open('recipes/finder', array('id' => 'recipes_finder_form', 'class' => 'micro-search-form', 'method' => 'get'));
							$_params = array(
								'name' => 'find_keywords',
								'value' => $keywords,
								'placeholder' => 'Search Recipes',
								'class' => 'find-keywords search-form-fld form-control',
								'size' => 36
							);
							$_fld = form_input($_params);
							$_button = cb_draw_button('Find Recipe', 'search', null, "form-control admin-form-control", array());
							
							echo <<<FFLD
							<div class="recipes-micro-form-field-set">
								<div class="field-set-cell field">{$_fld}</div>
								<div class="field-set-cell button">{$_button}</div>
							</div>
FFLD;
							
							echo form_close();
						?>
					</div>
					<div id="recipes_found" class="recipes-found recipes-listing"></div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var baseURL = '<?php echo $baseURL; ?>';
		
		function deleteRecipe(rid) {
			var dConfirm = confirm('Are You Sure You Want To Delete This Recipe');
			if(dConfirm == true) {
				$.get(
					'/recipes/delete_recipe/' + rid,
					function(data) {
						window.location.reload();
					},
					"json"
				);
			}
		}
		
		// Set Admin Status
		function toggleFavorite(sVal, rid) {
			$.get(
				baseURL + "recipes/toggle_favorite/" + sVal + "/" + rid,
				function(data) {
					// alert(data);
					if(data.status == true) {
						$("#status_" + rid).html(data.favorite_html);
					}
				},
				"json"
			);
		}
		
		$(function() {
			$("#recipes_finder_form").on("submit", function(event) {
				event.preventDefault();
				$.post(
					$(this).attr("action"),
					$(this).serialize(),
					function(data) {
						// alert(data);
						$("#recipes_found").html(data.listing);
					},
					"json"
				);
				return false;
			});
		})
	
	</script>
<?php
