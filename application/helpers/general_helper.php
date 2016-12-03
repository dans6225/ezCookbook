<?php
	/**
	 * Created by PhpStorm.
	 * User: dan
	 * Date: 10/14/2016
	 * Time: 8:28 PM
	 */
	
	// Check for null value
	function cb_not_null($value) {
		if(!isset($value)) {
			return false;
		}
		if(is_object($value)) {
			if(empty((array)$value)) {
				return false;
			} else {
				return true;
			}
		} elseif(is_array($value)) {
			if(sizeof($value) > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			$value = strtolower(trim($value));
			if(strlen($value) > 0 && !in_array($value, array('', 'null', null)) ) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	// Returns first non-empty value from any number of paramters
	function cb_first_not_empty() {
		for($i = 0; $i < func_num_args(); $i++) {
			$x = func_get_arg($i);
			if(strlen(trim((string)$x)) > 0) {
				return $x;
			}
		}
		return null;
	}