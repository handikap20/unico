<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Login_Controller {
   
    public function __construct()
    {
        parent::__construct();
        
        $this->_init();
        $this->load->model('m_users');
        $this->load->library('user_agent');
    }
    
    public function _init()
    {
        $this->output->set_template('auth');
    }

    public function index()
    {
        $mobile = $this->agent->is_mobile();
        if ($this->loggedin == TRUE) {
            redirect('home');    
        }
        if($mobile){
            $this->data['is_mobile'] = TRUE;
            $this->load->view('auth/mobile/v_login', $this->data);  
        }else{
            $this->load->view('auth/desktop/v_login', $this->data);  
        }
      
    }

    public function do_login() 
    {
        if ($this->loggedin == TRUE) {
            redirect('dashboard');
        }

        $this->output->unset_template();
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[35]');
        $this->form_validation->set_error_delimiters(error_delimeter(1), error_delimeter(2));
        
        
        if ($this->form_validation->run() == TRUE) {
            $auth = $this->m_users->authentication_check();

            if ($auth == TRUE) {
                $this->m_users->unico_session_register($auth);
                $data = array(
                    'status' => TRUE,
                    'message' => '<b>Login Success</b>',
                );
            } else {
                $data = array(
                    'status' => FALSE,
                    'message' => '<b>Email or password wrong</b>',
                );
            }
        } else {
            $data = array(
                'status' => FALSE,
                'message' => validation_errors()
            );
        }

        if ($data) {
            $this->output->set_output(json_encode($data));
        } else {
            $this->output->set_output(json_encode(['message' => FALSE, 'msg' => 'Gagal mengambil data.']));
        }
        
    }

    public function do_register() 
    {
        $this->output->unset_template();
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'is_unique[users.email]|trim|required|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[35]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|max_length[35]|matches[password]');
        $this->form_validation->set_error_delimiters(error_delimeter(1), error_delimeter(2));
        
        
        if ($this->form_validation->run() == TRUE) {
         $this->return =  $this->m_users->push_to_data('password',$this->m_users->ghash($this->input->post('password')))
                                        ->save(NULL);
            if ($this->return) {
                $response = array(
                    'status' => TRUE,
                    'message' => '<b>Congratulations !<br> Registration is successful</b>',
                    'msgLabel' => 'Log In',
                    'redirect' => base_url('auth'),
                );
            } else {
                $response = array(
                    'status' => FALSE,
                    'message' => '<b>Oops !<br> Registration failed, Please check your data',
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

    public function do_logout()
    {
        if ($this->loggedin == TRUE) {
            $this->m_users->unico_session_destroy();
            $this->data['page_title'] = "Logout";
            $this->data['page_description'] = "Halaman Logout.";
            show_msg('<b>Session ended</b>', false, base_url('auth'));
        } else {
            show_msg('<b>Session ended</b>', false, base_url('auth'));
        } 
    }

    public function register()
    {
        $mobile = $this->agent->is_mobile();
        $this->data['top_nav'] = TRUE;
        $this->data['page_title'] = "Register";
        $this->data['url_back'] = 'auth';

        $this->load->section('header', 'backend/_includes/header',$this->data);

        if($mobile){
               $this->data['is_mobile'] = TRUE;
               $this->load->view('auth/mobile/v_register', $this->data);  
        }else{
            $this->load->view('auth/desktop/v_register', $this->data);  
        }
    }
}

/* End of file Auth.php */