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
				<div class="row-cell row-left col-xs-12 col-sm-9">
					
					<?php
						echo form_open('/cooking_info/manager', array('id' => 'cooking_info_finder_form', 'class' => 'micro-search-form', 'method' => 'get'));
						
						$_params = array(
							'name' => 'find_keywords',
							'value' => $keywords,
							'placeholder' => 'Search Cooking Info',
							'class' => 'find-keywords search-form-fld form-control',
							'size' => 36
						);
						$_fld = form_input($_params);
						$_button = cb_draw_button('Find Cooking Info', 'search', null, "form-control admin-form-control", array());
						
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
						echo cb_draw_button('Add New Cooking Info', 'plus', '/cooking_info/editor/0', null, array('type' => 'button'));
					?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="section-sep-row"></div>
			<div class="body-main row">
				<div id="recipes_listing" class="recipes-listing">
					<?php
						echo $listing;
						// echo "<div class=\"listing-pagination\">" . $pagination . "</div>\n";
					?>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var baseURL = '<?php echo $baseURL; ?>';
		
		function deleteCookingInfo(ciid) {
			var dConfirm = confirm('Are You Sure You Want To Delete This Cooking Info');
			if(dConfirm == true) {
				$.get(
					'/cooking_info/delete/' + ciid,
					function(data) {
						window.location.reload();
					},
					"json"
				);
			}
		}
	</script>
<?php
