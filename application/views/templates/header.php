<?php
	/**
	 * Created by PhpStorm.
	 * User: dan
	 * Date: 6/5/2016
	 * Time: 6:03 PM
	 */
	
	$baseURL = base_url();
	
	$baseURI = parse_url($_SERVER['REQUEST_URI']);
	
?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $page_title; ?></title>
		
		<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.min.css">
		
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
		<!--<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css">-->
		
		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
		
		<link rel="stylesheet" href="/css/menus.css">
		<link rel="stylesheet" type="text/css" href="/css/cookbook.css">
		<link rel="stylesheet" type="text/css" href="/css/print.css" media="print">
		
		<link rel="stylesheet" type="text/css" href="/javascript/lightbox2/css/lightbox.min.css">
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<!--<script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>-->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
		
		<!--<script src="/javascript/general_javascript.js"></script>-->
		
		<script type="text/javascript">
			$(document).ready(function() {
				// Global page load jQuery init
				// alert('jQuery is in here OK');
			});
		</script>
		<?php
			// Load any page specific head data
			if(isset($headData) && cb_not_null($headData)) {
				echo $headData;
			}
			
			$activeNav = 'default';
			if(preg_match("/(category|categories)/", $baseURI['path']) > 0) {
				$activeNav = 'categories';
			} elseif(strpos($baseURI['path'], 'recipes/') !== false) {
				$activeNav = 'recipes';
			}
			
		?>
	</head>
	<?php
		$body_tag = "<body>";
		if(isset($bodyTag) && cb_not_null($bodyTag)) {
			$body_tag = $bodyTag;
		}
		echo $body_tag;
	?>
	<!-- Begin Main Content -->
	<div id="header_region">
		<div class="inner">
			<div class="header-top"></div>
			<div class="header-nav">
				<ul id="header_nav" class="nav-ul nav-horizontal">
					<li class="nav-to-dahsboard first<?php echo ($activeNav == 'default' ? " active" : ''); ?>"><a href="/">Dashboard</a></li>
					<li class="nav-to-categories-manager<?php echo ($activeNav == 'categories' ? " active" : ''); ?>"><a href="/recipes/categories_manager/">Categories</a></li>
					<li class="nav-to-recipes-manager<?php echo ($activeNav == 'recipes' ? " active" : ''); ?>"><a href="/recipes/manager/">Recipes</a></li>
					<!--<li class="nav-to-bus-users"><a href="/recipes/admins_manager/">Admin Users</a></li>-->
				</ul>
			</div>
		</div>
	</div>
<?php
