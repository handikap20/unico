<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| BREADCRUMB CONFIG
| -------------------------------------------------------------------
| This file will contain some breadcrumbs' settings.
|
| $config['breadcrumb_default']['crumb_divider']		The string used to divide the crumbs
| $config['breadcrumb_default']['tag_open'] 			The opening tag for breadcrumb's holder.
| $config['breadcrumb_default']['tag_close'] 			The closing tag for breadcrumb's holder.
| $config['breadcrumb_default']['crumb_open'] 		The opening tag for breadcrumb's holder.
| $config['breadcrumb_default']['crumb_close'] 		The closing tag for breadcrumb's holder.
|
| Defaults provided for twitter bootstrap 2.0
*/

$config['breadcrumb_default']['crumb_divider'] = '';
$config['breadcrumb_default']['tag_open'] = '<ol class="breadcrumb"><li><a href="'.base_url().'">Home</a></li>';
$config['breadcrumb_default']['tag_close'] = '</ol>';
$config['breadcrumb_default']['crumb_open'] = '<li>';
$config['breadcrumb_default']['crumb_last_open'] = '<li>';
$config['breadcrumb_default']['crumb_anchor_attr'] = '';
$config['breadcrumb_default']['crumb_close'] = '</li>';

$config['breadcrumb_users']['crumb_divider'] = '';
$config['breadcrumb_users']['tag_open'] = '<ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="'.base_url('dashboard').'">Home</a></li>';
$config['breadcrumb_users']['tag_close'] = '</ol>';
$config['breadcrumb_users']['crumb_open'] = '<li class="breadcrumb-item">';
$config['breadcrumb_users']['crumb_last_open'] = '<li class="breadcrumb-item active">';
$config['breadcrumb_users']['crumb_anchor_attr'] = '';
$config['breadcrumb_users']['crumb_close'] = '</li>';

/* End of file breadcrumbs.php */
/* Location: ./application/config/breadcrumbs.php */