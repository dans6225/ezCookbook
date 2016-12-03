<?php
	
	/**
	 * Created by PhpStorm.
	 * User: dan
	 * Date: 6/5/2016
	 * Time: 5:57 PM
	 */

	defined('BASEPATH') OR exit('Access Denied Here');

	class Recipes extends CI_Controller{
		
		
		public $rID = 0;
		
		protected $pageData = array();
		protected $uInput = array();

		public function __construct() {

			parent::__construct();
			
			if(isset($_GET) && is_array($_GET) && cb_not_null($_GET)) {
				$this->uInput = $this->input->get(NULL, TRUE);
			}
			if(isset($_POST) && is_array($_POST) && cb_not_null($_POST)) {
				$this->uInput = array_merge($this->uInput, $this->input->post(NULL, TRUE));
			}
			
			// Load recipes model
			$this->load->model('recipes_model');

		}


		public function view($page = 'home') {
			// die('Got Here');

			if (!file_exists(APPPATH.'views/recipes/'.$page.'.php')) {
				// Whoops, we don't have a page for that!
				show_404();
			}

			if(!(isset($this->pageData['page_title']) && cb_not_null($this->pageData['page_title']))) {
				$this->pageData['page_title'] = ucwords(str_replace(array("_", "-"), " ", $page)); // Capitalize the first letter
			}
			
			if($page == 'home') {
				$this->init_dashboard();
			}
			
			$this->load->view('templates/header', $this->pageData);
			$this->load->view('recipes/'.$page, $this->pageData);
			$this->load->view('templates/footer', $this->pageData);
		}
		
		public function recipes_ajax($tFunc) {
			if(cb_not_null($tFunc)) {
				$this->$tFunc();
			}
		}
		
		// Image upload
		public function upload_images($fFld = 'recipes_images') {
			$config['upload_path']          = DOC_ROOT . '/images/recipes/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 100;
			$config['max_width']            = 1024;
			$config['max_height']           = 768;
			
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload($fFld)) {
				$this->uInput[$fFld] = '';
				// echo $this->upload->display_errors('<p>', '</p>');
				// die('Failed<pre>' . print_r($_FILES[$fFld], true) . "</pre>");
			} else {
				$this->uInput[$fFld] = $_FILES[$fFld]['name'];
			}
		}
		
		public function manager($page = 1) {
			$_start_time = microtime(true);
			
			$this->pageData['bodyTag'] = '<body class="recipes-manager">';
			
			$this->load->library('table');
			
			$this->uInput['page'] = $page;
			$recipes = $this->recipes_model->find($this->uInput, false);
			
			// die("DBG: <pre>" . print_r($recipes, true) . "</pre>");
			
			// Setup table data array
			$tData = array(
				array('Recipes ID', 'Recipes Name', 'Category', 'Favorites', 'Last Modified', 'Actions'),
			);
			
			foreach($recipes['find_results'] as $rInfo) {
				$_buttons = array(
					'view' => cb_draw_button('Details', 'info', "/recipes/viewer/{$rInfo->recipes_id}", null, array('icon_only' => true, 'type' => 'button')),
					'edit' => cb_draw_button('Edit', 'edit', "/recipes/editor/{$rInfo->recipes_id}", 'edit-btn', array('icon_only' => true, 'type' => 'button')),
					'delete' => cb_draw_button('Delete', 'trash', null, 'delete-btn', array('icon_only' => true, 'type' => 'button', 'params' => 'onclick="deleteRecipe(' . $rInfo->recipes_id . ')"'))
				);
				
				$tData[] = array(
					$rInfo->recipes_id,
					$rInfo->recipes_name,
					$rInfo->categories_name,
					cb_draw_status_set($rInfo->recipes_id, $rInfo->favorite, 'toggleFavorite'),
					date('n-j-Y', $rInfo->last_mod),
					implode("&nbsp;", $_buttons)
				);
			}
			
			$_tpl = array(
				'table_open' => '<table border="0" cellpadding="4" cellspacing="0" id="admin_recipes_listing" class="admin-listing-table col-xs-12">',
				'heading_row_start' => '<tr class="admin-listing-heading">',
				'row_start' => '<tr class="listing-row">',
				'row_alt_start' => '<tr class="listing-row alt">'
			);
			$this->table->set_template($_tpl);
			
			$this->pageData['keywords'] = (isset($this->uInput['keywords']) ? $this->uInput['keywords'] : '');
			$this->pageData['page'] = (isset($this->uInput['page']) && (int)$this->uInput['page'] ? (int)$this->uInput['page'] : 1);
			$this->pageData['listing'] = $this->table->generate($tData);
			$this->pageData['elapsed_time'] = number_format((microtime(true) - $_start_time), 4);
			$this->pageData['foundCt'] = $recipes['foundCt'];
			
			$this->view('recipes_manager');
			
		}
		
		public function finder($page = 1, $ajax = true) {
			$_start_time = microtime(true);
			
			$this->load->library('table');
			
			// Setup table data array
			$tData = array(
				array('Recipes ID', 'Recipes Name', 'Category', 'Favorites', 'Last Modified', 'Actions'),
			);
			
			$this->uInput['page'] = $page;
			
			$params = array(
				'find_keywords' => $this->uInput['find_keywords'],
				'find_method' => 'before',
				'page' => $this->uInput['page']
			);
			$recipes = $this->recipes_model->find($params, true);
			
			if($recipes['status'] == 'success' && $recipes['foundCt'] > 0) {
				foreach($recipes['find_results'] as $rInfo) {
					$_buttons = array('view' => cb_draw_button('Details', 'info', "/recipes/viewer/{$rInfo->recipes_id}", null, array('icon_only' => true, 'type' => 'button')), 'edit' => cb_draw_button('Edit', 'edit', "/recipes/editor/{$rInfo->recipes_id}", 'edit-btn', array('icon_only' => true, 'type' => 'button')), 'delete' => cb_draw_button('Delete', 'trash', null, 'delete-btn', array('icon_only' => true, 'type' => 'button', 'params' => 'onclick="deleteRecipe(' . $rInfo->recipes_id . ')"')));
					
					$tData[] = array($rInfo->recipes_id, $rInfo->recipes_name, $rInfo->categories_name, cb_draw_status_set($rInfo->recipes_id, $rInfo->favorite, 'toggleFavorite'), date('n-j-Y', $rInfo->last_mod), implode("&nbsp;", $_buttons));
				}
			}
			
			$_tpl = array(
				'table_open' => '<table border="0" cellpadding="4" cellspacing="0" id="admin_recipes_listing" class="admin-listing-table col-xs-12">',
				'heading_row_start' => '<tr class="admin-listing-heading">',
				'row_start' => '<tr class="listing-row">',
				'row_alt_start' => '<tr class="listing-row alt">'
			);
			
			$this->table->set_template($_tpl);
			
			$this->pageData['keywords'] = (isset($this->uInput['keywords']) ? $this->uInput['keywords'] : '');
			$this->pageData['page'] = (isset($this->uInput['page']) && (int)$this->uInput['page'] ? (int)$this->uInput['page'] : 1);
			$this->pageData['listing'] = $this->table->generate($tData);
			$this->pageData['elapsed_time'] = number_format((microtime(true) - $_start_time), 4);
			$this->pageData['foundCt'] = $recipes['foundCt'];
			
			if($ajax) {
				$this->pageData['is_ajax_req'] = true;
				$vOut = array(
					'listing' => $this->pageData['listing'],
					'dbg' => http_build_query($recipes)
				);
				
				echo json_encode($vOut);
			} else {
				// $this->view('recipes_manager');
				$this->load->view('recipes/recipes_manager', $this->pageData);
			}
			
			
		}
		
		public function viewer($rid, $ajax = false) {
			if($rid < 1) {
				if($ajax) {
					$vOut = array(
						'status' => 'fail',
						'listing' => 'No Recipe ID submitted',
						'dbg' => 'No Recipe ID submitted'
					);
					echo json_encode($vOut);
					exit;
				}
				redirect("recipes");
			}
			
			$this->pageData['recipes_id'] = $rid;
			if(!(isset($this->pageData['action']) && cb_not_null($this->pageData['action']))) {
				$this->pageData['action'] = 'view';
				if(isset($this->uInput['action']) && cb_not_null($this->uInput['action'])) {
					$this->pageData['action'] = $this->uInput['action'];
				}
			}
			
			$recipe = $this->recipes_model->get_recipe($rid);
			
			if(cb_not_null($recipe->ingredients_left)) {
				$recipe->ingredients_left = str_replace("\n", "<br />", $recipe->ingredients_left);
			}
			if(cb_not_null($recipe->ingredients_right)) {
				$recipe->ingredients_right = str_replace("\n", "<br />", $recipe->ingredients_right);
			}
			if(cb_not_null($recipe->directions)) {
				$recipe->directions = str_replace("\n", "<br />", $recipe->directions);
			}
			
			$this->pageData['viewerInfo'] = $recipe;
			$this->pageData['page_title'] = "{$recipe->categories_name} | {$recipe->recipes_name} Info";
			
			$this->pageData['is_ajax_req'] = false;
			if($ajax) {
				$this->pageData['is_ajax_req'] = true;
				$vOut = array(
					'listing' => $this->load->view('recipes/recipes_viewer', $this->pageData, TRUE),
					'dbg' => http_build_query($recipe)
				);
				
				echo json_encode($vOut);
				
			} else {
				$this->view('recipes_viewer');
			}
			
		}
		
		public function editor($rid = 0, $ajax = false) {
			
			$this->pageData['recipes_id'] = $rid;
			$this->pageData['action'] = 'new';
			if($rid > 0) {
				$this->pageData['action'] = 'edit';
			}
			
			$recipe = $this->recipes_model->get_recipe($rid);
			
			$this->pageData['editorInfo'] = $recipe;
			$_cat = (isset($recipe->categories_name) && cb_not_null($recipe->categories_name) ? "{$recipe->categories_name} | " : '');
			$this->pageData['page_title'] = $_cat . ($this->pageData['action'] == 'new' ? "New Recipe" : "{$recipe->recipes_name} Info");
			
			$this->pageData['categoriesArray'] = $this->recipes_model->get_categories_array();
			
			$this->pageData['is_ajax_req'] = false;
			if($ajax && 1 == 2) {
				$this->pageData['is_ajax_req'] = true;
				$vOut = array(
					'listing' => $this->load->view('recipes/recipes_editor', $this->pageData, TRUE),
					'dbg' => http_build_query($recipe)
				);
				
				echo json_encode($vOut);
				
			} else {
				$this->view('recipes_editor');
			}
			
		}
		
		public function update_recipe($rid, $action = 'update', $ajax = false) {
			if(cb_not_null($_FILES['recipes_images']) && cb_not_null($_FILES['recipes_images']['name'])) {
				$this->upload_images('recipes_images');
			}
			
			$rid = $this->recipes_model->update_recipe($this->uInput, $rid, $action);
			
			if(isset($this->uInput['returnTo']) && cb_not_null($this->uInput['returnTo'])) {
				redirect($this->uInput['returnTo']);
			} elseif(!$ajax) {
				redirect("/recipes/viewer/{$rid}");
			}
			echo json_encode(array('rid' => $rid));
		}
		
		public function toggle_favorite($flag, $rid) {
			$fOut = array();
			$fOut['status'] = $this->recipes_model->update_favorite($rid, $flag);
			$fOut['favorite_html'] = cb_draw_status_set($rid, $flag, 'toggleFavorite');
			
			echo json_encode($fOut);
			exit;
		}
		
		public function delete_recipe($rid) {
			$rDel = $this->recipes_model->delete_recipe($rid);
			$dOut = array(
				'status' => $rDel,
				'msg' => "{$rid} successfully Deleted"
			);
			echo json_encode($dOut);
			exit;
		}
		
		
		// Categories Controls
		public function categories_manager($page = 1) {
			$_start_time = microtime(true);
			
			$this->pageData['bodyTag'] = '<body class="recipes-manager">';
			
			$this->load->library('table');
			
			$this->uInput['page'] = $page;
			$category = $this->recipes_model->find_categories($this->uInput, false);
			
			// die("DBG: <pre>" . print_r($recipes, true) . "</pre>");
			
			// Setup table data array
			$tData = array(
				array('Categories ID', 'Categories Name', 'Last Modified', 'Actions'),
			);
			
			foreach($category['find_results'] as $rInfo) {
				$_buttons = array(
					'view' => cb_draw_button('Details', 'info', "/recipes/category_viewer/{$rInfo->categories_id}", null, array('icon_only' => true, 'type' => 'button')),
					'edit' => cb_draw_button('Edit', 'edit', "/recipes/category_editor/{$rInfo->categories_id}", 'edit-btn', array('icon_only' => true, 'type' => 'button')),
					'delete' => cb_draw_button('Delete', 'trash', null, 'delete-btn', array('icon_only' => true, 'type' => 'button', 'params' => 'onclick="deleteCategory(' . $rInfo->categories_id . ')"'))
				);
				
				$tData[] = array(
					$rInfo->categories_id,
					$rInfo->categories_name,
					date('n-j-Y', $rInfo->last_mod),
					implode("&nbsp;", $_buttons)
				);
			}
			
			$_tpl = array(
				'table_open' => '<table border="0" cellpadding="4" cellspacing="0" id="recipes_categories_listing" class="admin-listing-table col-xs-12">',
				'heading_row_start' => '<tr class="admin-listing-heading">',
				'row_start' => '<tr class="listing-row">',
				'row_alt_start' => '<tr class="listing-row alt">'
			);
			$this->table->set_template($_tpl);
			
			$this->pageData['keywords'] = (isset($this->uInput['keywords']) ? $this->uInput['keywords'] : '');
			$this->pageData['page'] = (isset($this->uInput['page']) && (int)$this->uInput['page'] ? (int)$this->uInput['page'] : 1);
			$this->pageData['listing'] = $this->table->generate($tData);
			$this->pageData['elapsed_time'] = number_format((microtime(true) - $_start_time), 4);
			$this->pageData['foundCt'] = $category['foundCt'];
			
			$this->view('categories_manager');
			
		}
		
		public function category_viewer($cid, $ajax = false) {
			if($cid < 1) {
				if($ajax) {
					$vOut = array(
						'status' => 'fail',
						'listing' => 'No Category ID submitted',
						'dbg' => 'No Category ID submitted'
					);
					echo json_encode($vOut);
					exit;
				}
				redirect("recipes");
			}
			
			$this->pageData['categories_id'] = $cid;
			if(!(isset($this->pageData['action']) && cb_not_null($this->pageData['action']))) {
				$this->pageData['action'] = 'view';
				if(isset($this->uInput['action']) && cb_not_null($this->uInput['action'])) {
					$this->pageData['action'] = $this->uInput['action'];
				}
			}
			
			$category = $this->recipes_model->get_category($cid);
			
			if(cb_not_null($category->categories_keywords)) {
				$category->categories_keywords = str_replace("\n", "<br />", $category->categories_keywords);
			}
			if(cb_not_null($category->categories_description)) {
				$category->categories_description = str_replace("\n", "<br />", $category->categories_description);
			}
			
			$this->pageData['viewerInfo'] = $category;
			$this->pageData['page_title'] = "{$category->categories_name} Info";
			
			$this->pageData['is_ajax_req'] = false;
			if($ajax) {
				$this->pageData['is_ajax_req'] = true;
				$vOut = array(
					'listing' => $this->load->view('recipes/categories_viewer', $this->pageData, TRUE),
					'dbg' => http_build_query($category)
				);
				
				echo json_encode($vOut);
				
			} else {
				$this->view('categories_viewer');
			}
			
		}
		
		public function category_editor($cid = 0, $ajax = false) {
			
			$this->pageData['categories_id'] = $cid;
			$this->pageData['action'] = 'new';
			if($cid > 0) {
				$this->pageData['action'] = 'edit';
			}
			
			$category = $this->recipes_model->get_category($cid);
			
			$this->pageData['editorInfo'] = $category;
			$this->pageData['page_title'] = ($this->pageData['action'] == 'new' ? "New Category" : "{$category->categories_name} Info");
			
			
			$this->pageData['is_ajax_req'] = false;
			if($ajax && 1 == 2) {
				$this->pageData['is_ajax_req'] = true;
				$vOut = array(
					'listing' => $this->load->view('recipes/categories_editor', $this->pageData, TRUE),
					'dbg' => http_build_query($category)
				);
				
				echo json_encode($vOut);
				
			} else {
				$this->view('categories_editor');
			}
			
		}
		
		public function update_category($cid, $action = 'update', $ajax = false) {
			if(cb_not_null($_FILES['categories_image']) && cb_not_null($_FILES['categories_image']['name'])) {
				$this->upload_images('categories_image');
			}
			
			$cid = $this->recipes_model->update_category($this->uInput, $cid, $action);
			
			if(isset($this->uInput['returnTo']) && cb_not_null($this->uInput['returnTo'])) {
				redirect($this->uInput['returnTo']);
			} elseif(!$ajax) {
				redirect("/recipes/category_viewer/{$cid}");
			}
			echo json_encode(array('cid' => $cid));
		}
		
		public function delete_category($cid) {
			$rDel = $this->recipes_model->delete_category($cid);
			$dOut = array(
				'status' => $rDel,
				'msg' => "{$cid} successfully Deleted"
			);
			echo json_encode($dOut);
			exit;
		}
		
		
		// Dashboard functions
		public function init_dashboard() {
			$this->pageData['favorites'] = $this->recipes_model->get_favorites();
			$this->pageData['keywords'] = (isset($this->uInput['find_keywords']) ? $this->uInput['find_keywords'] : '');
			$this->pageData[''] = '';
		}

	}