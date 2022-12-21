<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $data = array();
    public function __construct()
    {
        parent::__construct();
        
        $this->data['errors'] 			    = array();
        $this->data['messages'] 		    = array();
        $this->data['site_name'] 		    = "Unico";
        $this->data['keywords'] 		    = "pt.unico sentral distribution, ikad, ceramics, padang, material, bangunan, sentral tukang";
        $this->data['description'] 		    = "Masuk Untuk Mengakses Aplikasi Unico.";
        $this->data['regency']		 	    = "Kota Padang";
        $this->data['favicon'] 			    = base_url('assets/backend/images/favicon.ico');
        $this->data['author'] 			    = "Handika Putra";
        $this->data['company']              = "PT. Unico Sentral Distribution";
        $this->data['web_domain']           = 'https://heroku.unicopp.id';
        $this->data['global_images_path']   = base_url('assets/global/images');
        $this->data['global_plugin_path']   = base_url('assets/global/plugin');
        $this->data['global_custom_path']   = base_url('assets/global/custom');
        $this->data['uri_mod']              = '';
        $this->data['version']              = '1.0';
        $this->data['year_created']         = "2022";
        $this->data['year_now']             = date('Y');
        $this->timestamp                    = date('Y-m-d H:i:s');
        $this->loggedin                     = $this->session->userdata('unico_loggedin');
        $this->private_key                  = "?~MgOWmkZ#9G22asd@@&&^%$%JBvnQQhUR6I0^DryEP+#+" . $this->router->fetch_class() . '.UNICO';
        $this->output->set_common_meta(
            $this->data['site_name'],
            $this->data['description'],
            $this->data['keywords']
        );

        $this->_user_id = $this->session->userdata('unico_users_id');


        $this->output->set_meta("author", $this->data['author']);
        $this->output->set_meta("company", $this->data['company']);
        $this->output->set_meta("regency", $this->data['regency']);

        $this->db = $this->load->database('default',TRUE);

        $this->data['file_image_path'] = 'files/images/';
        $this->data['public_image_path'] = 'assets/backend/images/';
        $this->data['files_path'] = './files/uploads/';

    }
}

class Backend_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['page_title']       = '';
        $this->data['judul']	        = '';
        $this->data['breadcrumbs'] 		= '';
        $this->data['theme_path']       = base_url('assets/backend');
        $this->data['page_description'] = '';
        $this->mod 				        = '';
        $this->return 			        = '';
        $this->result			        = array('status' => true, 'message' => '&nbsp');
        $this->msg 				        = '';
        $this->del 				        = '';
        $this->where			        = array();
        $this->data['navtoggle']        = false;
        $this->first_name               = $this->session->userdata('unico_first_name');
        $this->last_name                = $this->session->userdata('unico_last_name');
        $this->email                    = $this->session->userdata('unico_email');
        
        $this->_user_id = $this->session->userdata('unico_users_id');

        if($this->loggedin == FALSE){
			show_msg('Anda belum login', false, 'auth');
		}

        $this->load->section('footer', 'backend/_includes/footer');
    }
}

class Login_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['theme_path']       = base_url('assets/auth');
        $this->data['page_title']       = '';
        $this->data['page_description'] = '';

    }
}