<?php
	/**
	 * Created by PhpStorm.
	 * User: dan
	 * Date: 10/14/2016
	 * Time: 8:29 PM
	 */
	
	
	// Editor field set
	function draw_form_set($label, $field, $label_above = false) {
		$lCols = ($label_above ? "col-xs-12" : "col-sm-3");
		$fCols = ($label_above ? "col-xs-12" : "col-sm-9");
		return <<<FSO
			<div class="form-field-box">
				<div class="editor-form-label {$lCols}">{$label}</div>
				<div class="editor-form-field {$fCols}">{$field}</div>
				<div class="clearfix"></div>
			</div>
FSO;
		
	}
	
	
	/*
	 * Output a Font Awesome Button
	 * Note: params['icon_only'] setting requires both icon and link values to work
	 */
	function cb_draw_button($title = null, $icon = null, $link = null, $style = null, $params = null) {
		// Setup function data
		$bID = (isset($params['bID']) && cb_not_null($params['bID']) ? $params['bID'] : 'btn');
		$types = array('submit', 'button', 'reset');
		$button = NULL;
		$_icon = false;
		$iconpos = (isset($params['iconpos']) && cb_not_null($params['iconpos']) ? $params['iconpos'] : "left");
		$icon_only = (isset($params['icon_only']) && $params['icon_only'] == true ? true : false);
		$style = trim(($icon_only ? "icon_only " : "") . $style);
		$noprint = ' noprint';
		
		
		static $button_counter;
		if(!is_object($button_counter)) {
			$button_counter = new stdClass();
			$button_counter->$bID = 1;
		}
		
		$button_id = $bID . $button_counter->$bID;
		
		if(!isset($params['type'])) {
			$params['type'] = 'submit';
		}
		
		if(!in_array($params['type'], $types)) {
			$params['type'] = 'submit';
		}
		
		if(($params['type'] == 'submit') && cb_not_null($link)) {
			$params['type'] = 'button';
		}
		
		if(($params['type'] == 'button' || $icon_only) && cb_not_null($link)) {
			$button .= '<a id="' . $button_id . '" href="' . $link . '"';
			
			if(isset($params['newwindow'])) {
				$button .= ' target="_blank"';
			}
		} else {
			$button .= '<button id="' . $button_id . '" type="' . $params['type'] . '"';
		}
		if(cb_not_null($title)) {
			$button .= " alt=\"{$title}\" title=\"{$title}\"";
		}
		
		if(isset($params['params']) && cb_not_null(trim($params['params']))) {
			$button .= ' ' . trim($params['params']);
		}
		
		$button .= ' class="btn ' . (cb_not_null($style) ? $style : '') . $noprint . '">';
		
		if(isset($icon) && cb_not_null($icon)) {
			$_icon = '<i class="fa fa-' . $icon . '"></i>';
		}
		
		//   && (cb_not_null($link) )
		if($icon_only && $_icon) {
			$button .= $_icon;
		} else {
			$button .= ($_icon && $iconpos == "left" ? "{$_icon} " : '') . $title . ($_icon && $iconpos == "right" ? " {$_icon}" : '');
		}
		$button .= ($params['type'] == 'button' && cb_not_null($link) ? "</a>" : "</button>");
		
		$button_counter->$bID++;
		
		return $button;
	}
	
	
	function cb_draw_status_set($tid, $status = "1", $func = 'toggleStatus', $surl = '') {
		$ss = "<span id=\"status_{$tid}\">";
		if(in_array($status, array('1', 'enabled'))) {
			// {$surl}/0/{$tid}
			$ss .= "<a href=\"javascript:void(0);\" class=\"status-toggle\" onclick=\"{$func}(0, {$tid})\"><i class=\"fa fa-check status-enabled\"></i></a>";
		} else {
			// {$surl}/1/{$tid}
			$ss .= "<a href=\"javascript:void(0);\" class=\"status-toggle\" onclick=\"{$func}(1, {$tid})\"><i id=\"status_{$tid}\" class=\"fa fa-close status-disabled\"></a>";
		}
		$ss .= "</span>";
		
		return $ss;
	}
	