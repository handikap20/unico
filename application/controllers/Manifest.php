<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Manifest extends MY_Controller {
 
    public function __construct() {
        parent::__construct();
    }
 
    public function site(){
        $this->load->view('manifest/webmanifest', $this->data);
    }

    public function browserconfig(){
        $this->load->view('manifest/browserconfig', $this->data);
    }
 
}