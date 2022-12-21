<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Migration Controller
| Run On Command prompt
| "php index.php migrate current"
*/

/*
| Notes : Add this script on base_url config.php file : 
| **************** BEGIN SCRIPT ***********************
$config['base_url'] = "";

if (PHP_SAPI != 'cli') {
	$http = 'http' . (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || isset($_SERVER['HTTP_X_SSL_PORT'])) ? 's' : '') . '://';
	$newurl = str_replace("index.php","", $_SERVER['SCRIPT_NAME']);
	//proxy
	if (isset($_SERVER['HTTP_X_SCRIPT_NAME'])) {
		$newurl = str_replace("index.php","", $_SERVER['HTTP_X_SCRIPT_NAME']);
	}

	$config['base_url'] = "$http" . $_SERVER['SERVER_NAME'] . "" . $newurl;
}
| **************** END SCRIPT *************************
| Remove this note if you read this.
*/

class Migrate extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        if(! $this->input->is_cli_request()) {
            show_404();
            exit;
        }
        
        $this->load->library('migration');
    }
    
    function current($dev)
    {
        $this->data['default_db'] = 'default';

        $this->db = $this->load->database($this->data['default_db'],TRUE);

        if ($this->migration->current()) {
            log_message('error', 'Migration Success.');
            echo "Migration Success";
        } else {
            log_message('error', $this->migration->error_string());
            echo $this->migration->error_string();
        }
    }

    function rollback($version,$dev)
    {  
        $this->data['default_db'] = 'default';

        $this->db = $this->load->database($this->data['default_db'],TRUE);
        
        if ($this->migration->version($version)) {
            log_message('error', 'Migration Success.');
            echo "Migration Success";
        } else {
            log_message('error', $this->migration->error_string());
            echo $this->migration->error_string();
        }
    }

    function latest($dev)
    {
        $this->data['default_db'] = 'default';

        $this->db = $this->load->database($this->data['default_db'],TRUE);

        if ($this->migration->latest()) {
            log_message('error', 'Migration Success.');
            echo "Migration Success";
        } else {
            log_message('error', $this->migration->error_string());
            echo $this->migration->error_string();
        }
    }
}

/* End of file Migrate.php */


?>