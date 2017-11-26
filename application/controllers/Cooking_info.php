<?php
	
	/**
	 * Created by PhpStorm.
	 * User: dan
	 * Date: 6/5/2016
	 * Time: 5:57 PM
	 */

	defined('BASEPATH') OR exit('Access Denied Here');

	class Cooking_info extends CI_Controller {
		
		
		public $rID = 0;
		
		protected $pageData = array();
		protected $uInput = array();

		public function __construct() {

			parent::__construct();
			
			if(isset($_GET) && is_array($_GET) && cb_not_null($_GET)) {
				$this->uInput = $this->input->get(NULL, FALSE);
			}
			if(isset($_POST) && is_array($_POST) && cb_not_null($_POST)) {
				$this->uInput = array_merge($this->uInput, $this->input->post(NULL, FALSE));
			}
			
			// Load recipes model
			$this->load->model('recipes_model');

		}


		public function view($page = 'cooking_info_manager') {
			
			if (!file_exists(APPPATH.'views/recipes/'.$page.'.php')) {
				// Whoops, we don't have a page for that!
				show_404();
			}

			if(!(isset($this->pageData['page_title']) && cb_not_null($this->pageData['page_title']))) {
				$this->pageData['page_title'] = ucwords(str_replace(array("_", "-"), " ", $page)); // Capitalize the first letter
			}
			
			$this->load->view('templates/header', $this->pageData);
			$this->load->view('recipes/'.$page, $this->pageData);
			$this->load->view('templates/footer', $this->pageData);
		}
		
		public function cooking_info_ajax($tFunc) {
			if(cb_not_null($tFunc)) {
				$this->$tFunc();
			}
		}
		
		// Image upload
		public function upload_images($fFld = 'cooking_info_image') {
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
				// $this->uInput[$fFld] = $_FILES[$fFld]['name'];
				$this->uInput[$fFld] = $this->upload->data('file_name');
			}
		}
		
		public function manager($page = 1) {
			$_start_time = microtime(true);
			
			$this->pageData['bodyTag'] = '<body class="recipes-manager">';
			
			$this->load->library('table');
			
			$this->uInput['page'] = $page;
			$cooking_info = $this->recipes_model->find_cooking_info($this->uInput, false);
			
			// die("DBG: <pre>" . print_r($cooking_info, true) . "</pre>");
			
			// Setup table data array
			// 'Recipes ID',
			$tData = array(
				array('Cooking Info Name', 'Last Modified', 'Actions'),
			);
			
			foreach($cooking_info['find_results'] as $iInfo) {
				$_buttons = array(
					'view' => cb_draw_button('Details', 'info', "/cooking_info/viewer/{$iInfo->cooking_info_id}", null, array('icon_only' => true, 'type' => 'button')),
					'edit' => cb_draw_button('Edit', 'edit', "/cooking_info/editor/{$iInfo->cooking_info_id}", 'edit-btn', array('icon_only' => true, 'type' => 'button')),
					'delete' => cb_draw_button('Delete', 'trash', null, 'delete-btn', array('icon_only' => true, 'type' => 'button', 'params' => 'onclick="deleteCookingInfo(' . $iInfo->cooking_info_id . ')"'))
				);
				// $iInfo->cooking_info_id,
				// $iInfo->cooking_info_name
				$tData[] = array(
					anchor("cooking_info/viewer/{$iInfo->cooking_info_id}", $iInfo->cooking_info_name, "title=\"{$iInfo->cooking_info_name}\""),
					date('n-j-Y', $iInfo->last_mod),
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
			
			$this->pageData['keywords'] = (isset($this->uInput['find_keywords']) ? $this->uInput['find_keywords'] : '');
			$this->pageData['page'] = (isset($this->uInput['page']) && (int)$this->uInput['page'] ? (int)$this->uInput['page'] : 1);
			$this->pageData['listing'] = $this->table->generate($tData);
			$this->pageData['elapsed_time'] = number_format((microtime(true) - $_start_time), 4);
			$this->pageData['foundCt'] = $cooking_info['foundCt'];
			
			$this->view('cooking_info_manager');
		}
		
		public function finder($page = 1, $ajax = true) {
			$_start_time = microtime(true);
			
			$this->load->library('table');
			
			// Setup table data array
			// 'Recipes ID',
			$tData = array(
				array('Cooking Info Name', 'Last Modified', 'Actions'),
			);
			
			$this->uInput['page'] = $page;
			
			$params = array(
				'find_keywords' => $this->uInput['find_keywords'],
				'find_method' => 'before',
				'page' => $this->uInput['page']
			);
			$cooking_info = $this->recipes_model->find($params, true);
			
			if($cooking_info['status'] == 'success' && $cooking_info['foundCt'] > 0) {
				foreach($cooking_info['find_results'] as $iInfo) {
					$_buttons = array('view' => cb_draw_button('Details', 'info', "/cooking_info/viewer/{$iInfo->cooking_info_id}", null, array('icon_only' => true, 'type' => 'button')), 'edit' => cb_draw_button('Edit', 'edit', "/cooking_info/editor/{$iInfo->cooking_info_id}", 'edit-btn', array('icon_only' => true, 'type' => 'button')), 'delete' => cb_draw_button('Delete', 'trash', null, 'delete-btn', array('icon_only' => true, 'type' => 'button', 'params' => 'onclick="deleteRecipe(' . $iInfo->cooking_info_id . ')"')));
					
					// $iInfo->cooking_info_id,
					$_name = anchor("cooking_info/viewer/{$iInfo->cooking_info_id}", $iInfo->cooking_info_name, "title=\"{$iInfo->cooking_info_name}\"");
					$tData[] = array(
						$_name,
						date('n-j-Y', $iInfo->last_mod),
						implode("&nbsp;", $_buttons)
					);
				}
			}
			
			$_tpl = array(
				'table_open' => '<table border="0" cellpadding="4" cellspacing="0" id="admin_recipes_listing" class="admin-listing-table col-xs-12">',
				'heading_row_start' => '<tr class="admin-listing-heading">',
				'row_start' => '<tr class="listing-row">',
				'row_alt_start' => '<tr class="listing-row alt">'
			);
			
			$this->table->set_template($_tpl);
			
			$this->pageData['keywords'] = (isset($this->uInput['find_keywords']) ? $this->uInput['find_keywords'] : '');
			$this->pageData['page'] = (isset($this->uInput['page']) && (int)$this->uInput['page'] ? (int)$this->uInput['page'] : 1);
			$this->pageData['listing'] = $this->table->generate($tData);
			$this->pageData['elapsed_time'] = number_format((microtime(true) - $_start_time), 4);
			$this->pageData['foundCt'] = $cooking_info['foundCt'];
			
			if($ajax) {
				$this->pageData['is_ajax_req'] = true;
				echo json_encode($this->pageData);
			} else {
				// $this->view('cooking_info_manager');
				$this->load->view('recipes/cooking_info_manager', $this->pageData);
			}
		}
		
		public function viewer($ciid, $ajax = false) {
			if($ciid < 1) {
				if($ajax) {
					$vOut = array(
						'status' => 'fail',
						'listing' => 'No Cooking Info ID submitted',
						'dbg' => 'No Cooking Info ID submitted'
					);
					echo json_encode($vOut);
					exit;
				}
				redirect("cooking_info");
			}
			
			$this->pageData['cooking_info_id'] = $ciid;
			if(!(isset($this->pageData['action']) && cb_not_null($this->pageData['action']))) {
				$this->pageData['action'] = 'view';
				if(isset($this->uInput['action']) && cb_not_null($this->uInput['action'])) {
					$this->pageData['action'] = $this->uInput['action'];
				}
			}
			
			$cooking_info = $this->recipes_model->get_cooking_info($ciid);
			
			if(cb_not_null($cooking_info->cooking_info)) {
				// str_replace("\n", "<br />", $cooking_info->cooking_info);
				$cooking_info->cooking_info = $cooking_info->cooking_info;
			}
			
			$this->pageData['viewerInfo'] = $cooking_info;
			$this->pageData['page_title'] = "{$cooking_info->cooking_info_name} Info";
			
			$this->pageData['is_ajax_req'] = false;
			if($ajax) {
				$this->pageData['is_ajax_req'] = true;
				$vOut = array(
					'listing' => $this->load->view('recipes/cooking_info_viewer', $this->pageData, TRUE),
					'dbg' => http_build_query($cooking_info->cooking_info)
				);
				
				echo json_encode($vOut);
				
			} else {
				$this->view('cooking_info_viewer');
			}
		}
		
		public function editor($ciid = 0, $ajax = false) {
			
			$this->pageData['cooking_info_id'] = $ciid;
			$this->pageData['action'] = 'new';
			if($ciid > 0) {
				$this->pageData['action'] = 'edit';
			}
			
			$cooking_info = $this->recipes_model->get_cooking_info($ciid);
			
			$this->pageData['editorInfo'] = $cooking_info;
			$_cat = (isset($cooking_info->categories_name) && cb_not_null($cooking_info->categories_name) ? "{$cooking_info->categories_name} | " : '');
			$this->pageData['page_title'] = $_cat . ($this->pageData['action'] == 'new' ? "New Cooking Info" : "{$cooking_info->cooking_info_name} Info");
			
			// $this->pageData['categories_id'] = $rid;
			
			$this->pageData['is_ajax_req'] = false;
			if($ajax && 1 == 2) {
				$this->pageData['is_ajax_req'] = true;
				$vOut = array(
					'listing' => $this->load->view('recipes/cooking_info_editor', $this->pageData, TRUE),
					'dbg' => http_build_query($cooking_info)
				);
				
				echo json_encode($vOut);
				
			} else {
				$this->view('cooking_info_editor');
			}
		}
		
		public function update_info($ciid, $action = 'update', $ajax = false) {
			if(cb_not_null($_FILES['cooking_info_image']) && cb_not_null($_FILES['cooking_info_image']['name'])) {
				$this->upload_images('cooking_info_image');
			}
			
			$ciid = $this->recipes_model->update_cooking_info($this->uInput, $ciid, $action);
			
			if(isset($this->uInput['returnTo']) && cb_not_null($this->uInput['returnTo'])) {
				redirect($this->uInput['returnTo']);
			} elseif(!$ajax) {
				redirect("/cooking_info/viewer/{$ciid}");
			}
			echo json_encode(array('ciid' => $ciid));
		}
		
		
		
		public function delete_info($ciid) {
			$iDel = $this->recipes_model->delete_cooking_info($ciid);
			$dOut = array(
				'status' => $iDel,
				'msg' => "{$ciid} successfully Deleted"
			);
			echo json_encode($dOut);
			exit;
		}

	}