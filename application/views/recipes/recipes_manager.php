<?php
	/**
	 * Created by Danola Digital.
	 * User: dan
	 * Date: 6/5/2016
	 * Time: 6:16 PM
	 */
	
	$baseURL = base_url();
	
	// echo "<pre>" . print_r($this->db->list_fields('recipes'), true) . "</pre>";

	// echo "{$total_found} -- {$results2display}";

    $results2display_array = array(
        10 => 10,
        20 => 20,
        25 => 25,
        30 => 30,
        40 => 40,
        50 => 50
    );

    $pager = ($page > 1 ? "/{$page}" : "");
    
    $results2display_js = "onChange=\"updateResults2Display(this)\"";

?>
	<div id="container">
		<div id="body">
			<div class="body-top row">
				<div class="row-cell row-left col-xs-12 col-sm-9">
					
					<?php
						echo form_open('/recipes/manager' . ($cat_id > 0 ? "?cat_id={$cat_id}" : ""), array('id' => 'recipes_finder_form', 'class' => 'micro-search-form', 'method' => 'post'));
						
						$_params = array(
							'name' => 'find_keywords',
							'value' => $keywords,
							'autocomplete' => "off",
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
				<div class="row-cell row-right col-xs-12 col-sm-3">
					<?php
						echo cb_draw_button('Add New Recipe', 'plus', '/recipes/editor/0', null, array('type' => 'button'));
					?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="section-sep-row"></div>
			<div class="body-main row">
				<div id="recipes_listing" class="recipes-listing">
					<?php
					    echo "<div class=\"listing-count clearFloats\">Display " . form_dropdown('results2display', $results2display_array, $results2display, $results2display_js) . " Results</div>\n";
						echo $listing;
						echo "<div class=\"listing-pagination clearFloats\">" . $pagination . "</div>\n";
					?>
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
		
		function updateResults2Display(r2dObj) {
		    window.location.assign('/recipes/manager/?results2display=' + $(r2dObj).val());
        }

        var catID = <?php echo $cat_id; ?>;
        $('input.typeahead').typeahead({
            minLength: 2,
            items: 12,
            autoSelect: false,
            source:  function (query, process) {
                return $.get('/recipes/autocomplete_finder', { query: query, cat_id: catID }, function (data) {
                    // console.log(data);
                    data = $.parseJSON(data);
                    return process(data);
                });
            }
        });
		
	</script>
<?php
