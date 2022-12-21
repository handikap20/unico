<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
 
/* Check Is Maintanence */
$hook['pre_system'][] = array(
    'class'    => 'Hook_maintenance',
    'function' => 'offline_check',
    'filename' => 'Hook_maintenance.php',
    'filepath' => 'hooks'
 );