<?php
	/**
	 * Created by Danola Digital.
	 * User: dan
	 * Date: 6/5/2016
	 * Time: 6:16 PM
	 */
	
	$baseURL = base_url();
	// echo CI_VERSION;
?>
	<div id="container">
		<div id="body">
			<div class="body-top row">
				<h1><?php echo APPLICATION_NAME; ?></h1>
			</div>
			<div class="body-main row">
				<div class="block-left col-xs-12 col-sm-6 col-md-4">
					<div id="favorites_box">
						<div class="box-heading">Favorites</div>
						<div class="box-content"><?php echo $favorites; ?></div>
					</div>
				</div>
				<div class="block-right col-xs-12 col-sm-6 col-md-8">
					<div id="recipe_search">
						<?php
							echo form_open('recipes/finder', array('id' => 'recipes_finder_form', 'class' => 'micro-search-form', 'method' => 'get'));
							$_params = array(
								'name' => 'find_keywords',
								'value' => $keywords,
								'autocomplete' => 'off',
								'placeholder' => 'Search Recipes',
								'class' => 'find-keywords search-form-fld typeahead form-control',
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
				baseURL + "recipes/toggle_favorite/" + sVal + "/" + rid + "/" + 1,
				function(data) {
					// alert(data);
					if(data.status == true) {
						$("#status_" + rid).html(data.favorite_html);
						$("#favorites_box .box-content").html(data.favorites);
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
                        // console.log(data);
					    if(data.ajaxRedirId != undefined && data.ajaxRedirId > 0) {
					        var rArgs = (data.cat_id > 0 ? "?cat_id=" + data.cat_id : "");
					        window.location.assign("recipes/viewer/" + data.ajaxRedirId + rArgs);
                        } else {
                            $("#recipes_found").html(data.listing);
                        }
					},
					"json"
				);
				return false;
			});

            $('input.typeahead').typeahead({
                minLength: 2,
                items: 12,
                autoSelect: false,
                source:  function (query, process) {
                    return $.get('/recipes/autocomplete_finder', { query: query }, function (data) {
                        // console.log(data);
                        data = $.parseJSON(data);
                        return process(data);
                    });
                }
            });
		})
	
	</script>
<?php
