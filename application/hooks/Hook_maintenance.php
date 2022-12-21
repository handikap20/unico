<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hook_maintenance {

	public function offline_check() 
    {
        if(file_exists(APPPATH.'config/maintenance_config.php')){
            include(APPPATH.'config/maintenance_config.php');
            
            if(!empty($config['maintenance_mode']) && $config['maintenance_mode'] == true){
                require_once BASEPATH.'core/Router.php';
                define('UTF8_ENABLED', TRUE);
                $router = new CI_Router();
                $class = $router->fetch_class();
                $white_list = array('sitemap','manifest','migrate');
                if(is_integer(array_search($class, $white_list))==FALSE) {
                    include(APPPATH.'views/maintenance.php');
                    exit();
                }
            }
        }
    }
}

/* End of file Hook_akses.php */
/* Location: ./application/hooks/Hook_akses.php */