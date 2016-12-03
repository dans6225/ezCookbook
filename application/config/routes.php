<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
	
	
	$route['recipes'] = 'recipes/view/home';
	
	$route['recipes/viewer/(.+)'] = 'recipes/viewer/$1';
	
	$route['recipes/manager'] = 'recipes/manager';
	$route['recipes/manager/(.+)'] = 'recipes/manager/$1';
	$route['recipes/editor/(.+)'] = 'recipes/editor/$1';
	$route['recipes/update_recipe/(.+)'] = 'recipes/update_recipe/$1';
	$route['recipes/delete_recipe/(.+)'] = 'recipes/delete_recipe/$1';
	
	$route['recipes/categories_manager'] = 'recipes/categories_manager';
	$route['recipes/categories_manager/(.+)'] = 'recipes/categories_manager/$1';
	$route['recipes/category_viewer/(.+)'] = 'recipes/category_viewer/$1';
	$route['recipes/category_editor/(.+)'] = 'recipes/category_editor/$1';
	$route['recipes/update_category/(.+)'] = 'recipes/update_category/$1';
	$route['recipes/delete_category/(.+)'] = 'recipes/delete_category/$1';
	
	$route['recipes/toggle_favorite/(.+)'] = 'recipes/toggle_favorite/$1';
	
	
	$route['recipes/finder'] = 'recipes/finder';
	$route['recipes/finder/(.+)'] = 'recipes/finder/$1';
	$route['recipes/autocomplete/(.+)'] = 'recipes/autocomplete/$1';
	
	$route['recipes/(.+)'] = 'recipes/view/$1';
	
	// Default routes
	$route['default_controller'] = 'recipes/view';
	$route['(:any)'] = 'recipes/view/$1';
	
	$route['404_override'] = '';
	$route['translate_uri_dashes'] = FALSE;
