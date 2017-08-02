<?php
	
	/**
	 * Created by PhpStorm.
	 * User: dan
	 * Date: 10/14/2016
	 * Time: 8:11 PM
	 */
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Recipes_model extends CI_Model {
		
		public $recipeID, $recipeInfo;
		public $maxListingDisplay = 20;
		
		
		protected $dbTables = array();
		protected $dbTableCols = array();
		
		
		public function __construct() {
			parent::__construct();
			
			
			// Set up our database info
			$this->dbTables['recipes'] = 'recipes';
			$this->dbTableCols['recipes']['all'] = $this->db->list_fields($this->dbTables['recipes']);
			$this->dbTableCols['recipes']['forms_exclude'] = array(
				'recipes_id',
				'last_mod'
			);
			$this->dbTableCols['recipes']['edit_form'] = array();
			foreach($this->dbTableCols['recipes']['all'] as $col) {
				if(!in_array($col, $this->dbTableCols['recipes']['forms_exclude'])) {
					$this->dbTableCols['recipes']['edit_form'][] = $col;
				}
			}
			
			/*
			$this->dbTables['recipes_ingredients'] = 'recipes_ingredients';
			$this->dbTableCols['recipes_ingredients']['all'] = $this->db->list_fields($this->dbTables['recipes_ingredients']);
			$this->dbTableCols['recipes_ingredients']['forms_exclude'] = array(
				'ingredients_id'
			);
			$this->dbTableCols['recipes_ingredients']['edit_form'] = array();
			foreach($this->dbTableCols['recipes_ingredients']['all'] as $col) {
				if(!in_array($col, $this->dbTableCols['recipes_ingredients']['forms_exclude'])) {
					$this->dbTableCols['recipes_ingredients']['edit_form'][] = $col;
				}
			}
			*/
			
			$this->dbTables['categories'] = 'categories';
			$this->dbTableCols['categories']['all'] = $this->db->list_fields($this->dbTables['categories']);
			$this->dbTableCols['categories']['forms_exclude'] = array(
				'categories_id',
				'last_mod'
			);
			$this->dbTableCols['categories']['edit_form'] = array();
			foreach($this->dbTableCols['categories']['all'] as $col) {
				if(!in_array($col, $this->dbTableCols['categories']['forms_exclude'])) {
					$this->dbTableCols['categories']['edit_form'][] = $col;
				}
			}
			
			$this->dbTables['recipes_categories'] = 'recipes_categories';
			/*$this->dbTableCols['recipes_categories']['all'] = $this->db->list_fields($this->dbTables['recipes_categories']);
			$this->dbTableCols['recipes_categories']['forms_exclude'] = array();
			$this->dbTableCols['recipes_categories']['edit_form'] = array();
			foreach($this->dbTableCols['recipes_categories']['all'] as $col) {
				if(!in_array($col, $this->dbTableCols['recipes_categories']['forms_exclude'])) {
					$this->dbTableCols['recipes_categories']['edit_form'][] = $col;
				}
			}*/
			
			$this->dbTables['cooking_info'] = 'cooking_info';
			$this->dbTableCols['cooking_info']['all'] = $this->db->list_fields($this->dbTables['cooking_info']);
			$this->dbTableCols['cooking_info']['forms_exclude'] = array(
				'cooking_info_id',
				'last_mod'
			);
			$this->dbTableCols['cooking_info']['edit_form'] = array();
			foreach($this->dbTableCols['cooking_info']['all'] as $col) {
				if(!in_array($col, $this->dbTableCols['cooking_info']['forms_exclude'])) {
					$this->dbTableCols['cooking_info']['edit_form'][] = $col;
				}
			}
			
		}
		
		public function getProp($name) {
			return $this->$name;
		}
		
		
		public function get_recipe($rid, $edit = false) {
			if(!is_int($rid)) {
				$rid = (int)$rid;
			}
			if($rid < 1) {
				$new = array();
				foreach($this->dbTableCols['recipes']['all'] as $fld) {
					$new[$fld] = '';
					if($fld == 'last_mod') {
						$new[$fld] = time();
					} elseif($fld == 'recipes_id') {
						$new[$fld] = 0;
					}
				}
				return (object)$new;
			}
			
			$_select = implode(',', $this->dbTableCols['recipes']['edit_form']);
			if(!$edit) {
				$_select .= ", {$this->dbTables['recipes']}.last_mod";
			}
			$_select .= ", {$this->dbTables['categories']}.categories_id, {$this->dbTables['categories']}.categories_name";
			
			$recipe = new stdClass();
			$this->db->select($_select);
			$this->db->from($this->dbTables['recipes']);
			$this->db->join($this->dbTables['recipes_categories'], "{$this->dbTables['recipes']}.recipes_id = {$this->dbTables['recipes_categories']}.recipes_id", 'inner');
			$this->db->join($this->dbTables['categories'], "{$this->dbTables['recipes_categories']}.categories_id = {$this->dbTables['categories']}.categories_id", 'inner');
			$this->db->where("{$this->dbTables['recipes']}.recipes_id", $rid);
			$qry = $this->db->get();
			
			if($qry) {
				$recipe = $qry->row();
			}
			
			return $recipe;
		}
		
		public function get_favorites() {
			$favs = array();
			$_pre_r = $this->dbTables['recipes'];
			$_pre_c = $this->dbTables['categories'];
			$this->db->select("{$_pre_r}.recipes_id, {$_pre_r}.recipes_name, {$_pre_r}.recipes_images, {$_pre_c}.categories_id, {$_pre_c}.categories_name");
			$this->db->from($this->dbTables['recipes']);
			$this->db->join($this->dbTables['recipes_categories'], "{$this->dbTables['recipes']}.recipes_id = {$this->dbTables['recipes_categories']}.recipes_id", 'inner');
			$this->db->join($this->dbTables['categories'], "{$this->dbTables['recipes_categories']}.categories_id = {$this->dbTables['categories']}.categories_id", 'inner');
			$this->db->where("{$_pre_r}.favorite > 0");
			$this->db->order_by("{$_pre_r}.favorite ASC, {$_pre_r}.recipes_name ASC");
			
			$qry = $this->db->get();
			if($qry) {
				foreach($qry->result() as $fav) {
					$favs[$fav->recipes_id] = $fav;
				}
			}
			return $favs;
		}
		
		public function find($sData = array(), $ajax = false) {
			$fOut = array(
				'status' => 'fail',
				'foundCt' => 0,
				'find_results' => array(),
				'msg' => ''
			);
			$cat_id = false;
			
			$_select = "{$this->dbTables['recipes']}.recipes_id, 
						{$this->dbTables['recipes']}.recipes_name, 
						{$this->dbTables['recipes']}.favorite, 
						{$this->dbTables['recipes']}.recipes_images, 
						{$this->dbTables['recipes']}.last_mod,
						{$this->dbTables['categories']}.categories_id,
						{$this->dbTables['categories']}.categories_name";
			
			if(isset($sData['cat_id']) && cb_not_null($sData['cat_id'])) {
				$cat_id = $sData['cat_id'];
				$_select .= ", '1' AS score";
			} else {
				$_keywords = '';
				if(isset($sData['find_keywords']) && cb_not_null($sData['find_keywords'])) {
					$_keywords = $sData['find_keywords'];
				}
				$_select .= ", ((MATCH({$this->dbTables['recipes']}.recipes_name, {$this->dbTables['recipes']}.ingredients_left, {$this->dbTables['recipes']}.ingredients_right,
							    {$this->dbTables['recipes']}.directions, {$this->dbTables['recipes']}.notes)
						  AGAINST('{$_keywords}'))
						  	+ IF({$this->dbTables['recipes']}.recipes_name LIKE '%{$_keywords}%', 2, 0)
							+ IF({$this->dbTables['categories']}.categories_name LIKE '%{$_keywords}%', 1, 0)
							+ IF({$this->dbTables['categories']}.categories_keywords LIKE '%{$_keywords}%', (0.5), 0)
						  ) AS score";
			}
			
			$this->db->select($_select);
			$this->db->from($this->dbTables['recipes']);
			$this->db->join($this->dbTables['recipes_categories'], "{$this->dbTables['recipes']}.recipes_id = {$this->dbTables['recipes_categories']}.recipes_id", 'inner');
			$this->db->join($this->dbTables['categories'], "{$this->dbTables['recipes_categories']}.categories_id = {$this->dbTables['categories']}.categories_id", 'inner');
			
			if($cat_id && (int)$cat_id > 0) {
				$this->db->where("{$this->dbTables['categories']}.categories_id", $cat_id);
			} elseif(cb_not_null($_keywords)) {
				if($_keywords == "favorites") {
					$this->db->where("favorite = 1");
				} else {
					$_likePos = (isset($sData['find_method']) && cb_not_null($sData['find_method']) ? $sData['find_method'] : 'both');
					$this->db->group_start();
					$this->db->where("MATCH({$this->dbTables['recipes']}.recipes_name, {$this->dbTables['recipes']}.ingredients_left, {$this->dbTables['recipes']}.ingredients_right,
										   {$this->dbTables['recipes']}.directions, {$this->dbTables['recipes']}.notes) 
									 AGAINST('{$_keywords}')");
					
					/* // Not sure about this one
					 * $this->db->or_where("MATCH({$this->dbTables['categories']}.categories_name, {$this->dbTables['categories']}.categories_description, {$this->dbTables['categories']}.categories_keywords)
										 AGAINST('{$_keywords}')"); */
					$this->db->or_like("{$this->dbTables['categories']}.categories_name", trim($_keywords, $_likePos));
					// $this->db->or_like("{$this->dbTables['recipes']}.recipes_name", trim($_keywords, $_likePos)); // Pretty sure not needed
					
					$this->db->group_end();
					$this->db->having("score >= 1");
				}
			}
			
			// $this->db->order_by("score", 'DESC');
			$this->db->order_by("{$this->dbTables['recipes']}.recipes_name", 'ASC');
			
			$ctSql = $this->db->get_compiled_select(null, FALSE);
			$ctQry = $this->db->query($ctSql);
			$foundTotal = $ctQry->num_rows();
			
			if($foundTotal > $this->maxListingDisplay || (isset($sData['page']) && (int)$sData['page'] > 1)) {
				$_start = 0;
				if((int)$sData['page'] > 1) {
					$_start = (($sData['page'] -1) * $this->maxListingDisplay);
				}
				$this->db->limit($this->maxListingDisplay, $_start);
			}
			// die($this->db->get_compiled_select());
			$qry = $this->db->get();
			
			if($qry) {
				$fOut['status'] = 'success';
				$fOut['foundCt'] = $qry->num_rows();
				$fOut['find_results'] = $qry->result();
				$fOut['foundTotal'] = $foundTotal;
			} else {
				$fOut['msg'] = 'None Found';
			}
			return $fOut;
			
		}
		
		public function update_recipe($data, $rid = 0, $action = 'insert') {
			$_data = array('last_mod' => time());
			
			if($rid > 0) {
				$_recipe = $this->get_recipe($rid);
			}
			
			foreach($this->dbTableCols['recipes']['edit_form'] as $fld) {
				if(isset($data[$fld])) {
					$_data[$fld] = $data[$fld];
				}
			}
			
			if($action == 'insert') {
				$this->db->insert($this->dbTables['recipes'], $_data);
				$rid = $this->db->insert_id();
			} else {
				$this->db->update($this->dbTables['recipes'], $_data, array('recipes_id' => $rid));
			}
			
			if(!(isset($data['categories_id']) && (int)$data['categories_id'] > 0)) {
				$data['categories_id'] = 1;
			}
			
			if($action == 'insert') {
				$rc_sql = "INSERT IGNORE INTO {$this->dbTables['recipes_categories']} SET recipes_id = '{$rid}', categories_id = '{$data['categories_id']}'";
				$this->db->query($rc_sql);
			} else {
				if($_recipe->categories_id != $data['categories_id']) {
					$rc = array(
						'categories_id' => $data['categories_id']
					);
					$this->db->where('recipes_id', $rid);
					$this->db->update($this->dbTables['recipes_categories'], $rc);
				}
			}
			
			return $rid;
		}
		
		public function update_favorite($rid, $flag = 0) {
			$this->db->set('favorite', $flag);
			$this->db->where('recipes_id', $rid);
			return $this->db->update($this->dbTables['recipes']);
		}
		
		
		public function delete_recipe($rid) {
			$tables = array($this->dbTables['recipes'], $this->dbTables['recipes_categories']);
			$this->db->where('recipes_id', $rid);
			return $this->db->delete($tables);
		}
		
		
		// Recipes Categories
		public function find_categories($sData = array(), $ajax = false) {
			$fOut = array(
				'status' => 'fail',
				'foundCt' => 0,
				'find_results' => array(),
				'msg' => ''
			);
			
			$_select = "categories_id,
						categories_name,
						last_mod";
			
			$this->db->select($_select);
			$this->db->from($this->dbTables['categories']);
			
			if(isset($sData['find_keywords']) && cb_not_null($sData['find_keywords'])) {
				$_likePos = (isset($sData['find_method']) && cb_not_null($sData['find_method']) ? $sData['find_method'] : 'both');
				$_keywords = trim($sData['find_keywords'], $_likePos);
				$this->db->or_like(array("categories_name" => $_keywords, "categories_keywords" => $_keywords));
			}
			$this->db->order_by("categories_name", 'ASC');
			
			if(isset($sData['page']) && (int)$sData['page'] > 1) {
				$_start = ($sData['page'] * $this->maxListingDisplay) + 1;
				$_end = $_start + $this->maxListingDisplay;
				$this->db->limit($_start, $_end);
			}
			// die($this->db->get_compiled_select());
			$qry = $this->db->get();
			
			if($qry) {
				$fOut['status'] = 'success';
				$fOut['foundCt'] = $qry->num_rows();
				$fOut['find_results'] = $qry->result();
			} else {
				$fOut['msg'] = 'None Found';
			}
			return $fOut;
			
		}
		
		public function get_categories_array() {
			$this->db->select("categories_id, categories_name");
			$this->db->from($this->dbTables['categories']);
			$qry = $this->db->get();
			$rows = array();
			if($qry) {
				foreach($qry->result() as $row) {
					$rows[$row->categories_id] = $row->categories_name;
				}
			}
			return $rows;
		}
		
		public function get_category($cid, $edit = false) {
			$category = new stdClass();
			
			if(!is_int($cid)) {
				$cid = (int)$cid;
			}
			if($cid < 1) {
				$new = array();
				foreach($this->dbTableCols['categories']['all'] as $fld) {
					$category->$fld = '';
					if($fld == 'last_mod') {
						$category->$fld = time();
					} elseif($fld == 'categories_id') {
						$category->$fld = 0;
					}
				}
				return $category;
			}
			
			$_select = implode(',', $this->dbTableCols['categories']['edit_form']);
			if(!$edit) {
				$_select .= ", last_mod";
			}
			
			$this->db->select($_select);
			$this->db->from($this->dbTables['categories']);
			$this->db->where("categories_id", $cid);
			$qry = $this->db->get();
			
			if($qry) {
				$category = $qry->row();
			}
			
			return $category;
		}
		
		public function update_category($data, $cid = 0, $action = 'insert') {
			$_data = array('last_mod' => time());
			
			foreach($this->dbTableCols['categories']['edit_form'] as $fld) {
				if(isset($data[$fld])) {
					if(strpos($fld, 'name') !== false) {
						$data[$fld] = trim($data[$fld]);
					}
					$_data[$fld] = $data[$fld];
				}
			}
			
			if($action == 'insert') {
				$this->db->insert($this->dbTables['categories'], $_data);
				$cid = $this->db->insert_id();
			} else {
				$this->db->update($this->dbTables['categories'], $_data, array('categories_id' => $cid));
			}
			
			return $cid;
		}
		
		public function delete_category($cid) {
			$tables = array($this->dbTables['categories'], $this->dbTables['recipes_categories']);
			$this->db->where('categories_id', $cid);
			return $this->db->delete($tables);
		}
		
		
		// Cooking Tips, Tricks and Notes
		public function find_cooking_info($sData = array(), $ajax = false) {
			$fOut = array(
				'status' => 'fail',
				'foundCt' => 0,
				'find_results' => array(),
				'msg' => ''
			);
			
			$_select = "cooking_info_id,
						cooking_info_name,
						last_mod";
			
			$this->db->select($_select);
			$this->db->from($this->dbTables['cooking_info']);
			
			if(isset($sData['find_keywords']) && cb_not_null($sData['find_keywords'])) {
				$_likePos = (isset($sData['find_method']) && cb_not_null($sData['find_method']) ? $sData['find_method'] : 'both');
				$_keywords = trim($sData['find_keywords'], $_likePos);
				$this->db->or_like(array("cooking_info_name" => $_keywords, "cooking_info_keywords" => $_keywords));
			}
			$this->db->order_by("cooking_info_name", 'ASC');
			
			if(isset($sData['page']) && (int)$sData['page'] > 1) {
				$_start = ($sData['page'] * $this->maxListingDisplay) + 1;
				$_end = $_start + $this->maxListingDisplay;
				$this->db->limit($_start, $_end);
			}
			// die($this->db->get_compiled_select());
			$qry = $this->db->get();
			
			if($qry) {
				$fOut['status'] = 'success';
				$fOut['foundCt'] = $qry->num_rows();
				$fOut['find_results'] = $qry->result();
			} else {
				$fOut['msg'] = 'None Found';
			}
			return $fOut;
			
		}
		
		public function get_cooking_info($cid, $edit = false) {
			$category = new stdClass();
			
			if(!is_int($cid)) {
				$cid = (int)$cid;
			}
			if($cid < 1) {
				$new = array();
				foreach($this->dbTableCols['cooking_info']['all'] as $fld) {
					$category->$fld = '';
					if($fld == 'last_mod') {
						$category->$fld = time();
					} elseif($fld == 'cooking_info_id') {
						$category->$fld = 0;
					}
				}
				return $category;
			}
			
			$_select = implode(',', $this->dbTableCols['cooking_info']['edit_form']);
			if(!$edit) {
				$_select .= ", last_mod";
			}
			
			$this->db->select($_select);
			$this->db->from($this->dbTables['cooking_info']);
			$this->db->where("cooking_info_id", $cid);
			$qry = $this->db->get();
			
			if($qry) {
				$category = $qry->row();
			}
			
			return $category;
		}
		
		public function update_cooking_info($data, $cid = 0, $action = 'insert') {
			$_data = array('last_mod' => time());
			
			foreach($this->dbTableCols['cooking_info']['edit_form'] as $fld) {
				if(isset($data[$fld])) {
					$_data[$fld] = $data[$fld];
				}
			}
			
			if($action == 'insert') {
				$this->db->insert($this->dbTables['cooking_info'], $_data);
				$cid = $this->db->insert_id();
			} else {
				$this->db->update($this->dbTables['cooking_info'], $_data, array('cooking_info_id' => $cid));
			}
			
			return $cid;
		}
		
		public function delete_cooking_info($cid) {
			$tables = array($this->dbTables['cooking_info']);
			$this->db->where('cooking_info_id', $cid);
			return $this->db->delete($tables);
		}
		
		
	}