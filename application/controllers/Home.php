<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Backend_Controller {

    public function __construct()
	 {
        parent::__construct();
        $this->_init();
        $this->load->model(array('m_users'));
        $this->load->library('user_agent');
        $this->id_key = $this->private_key;
		$this->data['uri_mod'] = 'home';        
	 }

	public function _init()
    {
        $this->output->set_template('backend');
    }

    public function index()
    {
        
        $this->data['page_title'] = "Home";

        $mobile = $this->agent->is_mobile();
        $this->load->section('header', 'backend/_includes/header_icon',$this->data);

        if($mobile){
            $this->data['is_mobile'] = TRUE;
            $this->load->view('dashboard/mobile/v_index', $this->data);  
        }else{
            $this->load->css($this->data['global_custom_path'] . '/css/add-on-desktop.css');

            $this->load->view('dashboard/desktop/v_index', $this->data);  
        }
    }

    public function profile()
    {
        
        $this->data['page_title'] = "My Account";
        $this->load->section('header', 'backend/_includes/header',$this->data);

        $mobile = $this->agent->is_mobile();
        if($mobile){
            $this->data['is_mobile'] = TRUE;
            $this->load->view('dashboard/mobile/v_profile', $this->data); 
        }else{
            $this->load->css($this->data['global_custom_path'] . '/css/add-on-desktop.css');
            $this->load->view('dashboard/desktop/v_profile', $this->data);  
        }
    }

    public function users_list()
    {
        $this->data['page_title'] = "User List";
        $mobile = $this->agent->is_mobile();
        $this->load->section('header', 'backend/_includes/header',$this->data);

        if($mobile){
            $this->data['is_mobile'] = TRUE;
            $this->load->view('dashboard/mobile/v_users_list', $this->data);  
            
        }else{
            $this->load->css($this->data['global_custom_path'] . '/css/add-on-desktop.css');
            $this->load->view('dashboard/desktop/v_users_list', $this->data);  
        }
    }

    public function create_users_list()
    {
        $mobile = $this->agent->is_mobile();
        $this->data['top_nav'] = TRUE;
        $this->data['url_back'] = 'users_list';
        $this->data['page_title'] = "Add Users";

        $this->load->section('header', 'backend/_includes/header',$this->data);

        if($mobile){
               $this->data['is_mobile'] = TRUE;
               $this->load->view('dashboard/mobile/v_add', $this->data);  
        }else{
            $this->load->css($this->data['global_custom_path'] . '/css/add-on-desktop.css');

            $this->load->view('dashboard/desktop/v_add', $this->data);  
        }
    }

    public function edit_users_list($id = null)
    {
        $_id = decrypt_url($id, $this->id_key);
        if ($_id == FALSE) {
			show_msg('ID not found', false, base_url('users_list'));
        }else{
            $this->data["data_id"] = encrypt_url($_id,$this->id_key);
        }

        $mobile = $this->agent->is_mobile();
        $this->data['top_nav'] = TRUE;
        $this->data['url_back'] = 'users_list';
        $this->data['page_title'] = "Edit Users";
        $this->data['data'] = xss_escape($this->m_users->find($_id));

        $this->load->section('header', 'backend/_includes/header',$this->data);

        if($mobile){
               $this->data['is_mobile'] = TRUE;
               $this->load->view('dashboard/mobile/v_edit', $this->data);  
        }else{
            $this->load->css($this->data['global_custom_path'] . '/css/add-on-desktop.css');

            $this->load->view('dashboard/desktop/v_edit', $this->data);  
        }
    }

    public function AjaxSaveUsers($id = null)
    {
        $this->output->unset_template();
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[5]|max_length[50]');
        $_id = decrypt_url($id, $this->id_key);
        if($_id ==false) $this->form_validation->set_rules('email', 'Email', 'is_unique[users.email]|trim|required|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[35]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|max_length[35]|matches[password]');
        $this->form_validation->set_error_delimiters(error_delimeter(1), error_delimeter(2));
        
        
        if ($this->form_validation->run() == TRUE) {
            if ($_id == FALSE) $id = null;

         $this->return =  $this->m_users->push_to_data('password',$this->m_users->ghash($this->input->post('password')))
                                        ->save($_id);
            if ($this->return) {
                if ($this->loggedin == TRUE) {
                    if($this->_user_id == $_id) {
                        $this->m_users->unico_session_destroy();
                    }
                }
                $response = array(
                    'status' => TRUE,
                    'message' => '<b>Save Success</b>'
                );
            } else {
                $response = array(
                    'status' => FALSE,
                    'message' => '<b>Save Failed</b>'
                );
            }
        } else {
            $response = array(
                'status' => FALSE,
                'message' => validation_errors()
            );
        }

        if ($response) {
            $this->output->set_output(json_encode($response));
        } else {
            $this->output->set_output(json_encode(['message' => FALSE, 'msg' => '<b>Failed get data</b>']));
        }
    }

    public function AjaxGetUsers()
    {
        $this->output->unset_template();
        $data_ = array();
        $data = $this->m_users->findAll();
        foreach($data as $row) {
            $action = '';
            if($row->id != $this->_user_id){
                $action = '<a href="'.base_url('users_list/edit/').encrypt_url($row->id,$this->id_key).'" class="font-weight-bold"><i class="material-icons">edit</i></a><a href="#" class="button-delete font-weight-bold" data-id="'.encrypt_url($row->id,$this->id_key).'"><i class="material-icons">delete</i></a>';
            }
            $data_[] = [
                'id' => encrypt_url($row->id,$this->id_key),
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'email' => $row->email,
                'action' => $action
            ];
        }
        if($data){
            $this->result = array(
                'status'   => TRUE,
                'data' => $data_,
                'message' => '<b>Success get data</b>'
            );
        }else {
            $this->result = array(
                'status'   => FALSE,
                'message' => '<b>Data not found</b>'
            );
        }
      
        $this->output->set_output(json_encode($this->result));
    }

    public function AjaxDel($id = null){
        $this->output->unset_template();
        $_id = decrypt_url($id, $this->id_key);
      
        if ($_id != FALSE) {
            $this->return = $this->m_users->delete($_id);

            if ($this->return) {
                if ($this->loggedin == TRUE) {
                    if($this->_user_id == $_id) {
                        $this->m_users->unico_session_destroy();
                    }
                }
                $this->result = array(
                    'status'   => TRUE,
                    'message' => 'Data success deleted'
                );
            } else {
                $this->result = array(
                    'status'   => FALSE,
                    'message' => 'Data failed deleted.'
                );
            }
        } else {
            $this->result = array(
                'status'   => FALSE,
                'message' => 'ID not valid'
            );
        }

        $this->output->set_output(json_encode($this->result));
    }
}

/* End of file Home.php */