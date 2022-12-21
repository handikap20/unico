<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_users extends MY_Model {

    protected $_table = 'users';
    protected $_order_by = 'id';
    protected $_order = 'ASC';
    protected $_timestamps = TRUE;
    protected $_log_user = TRUE;
    protected $_fields_toshow = ['id','first_name', 'last_name', 'email'];
    protected $_fields = [
        'first_name' => 'first_name', 
        'last_name' => 'last_name',  
        'email' => 'email',
        'password' => 'password'
    ];

    public $default_password = "unico_pass";

    public function __construct()
    {
        parent::__construct();
    }

    public function _is_default_password($password){
        return password_verify($this->default_password, $password);
    }

    public function ghash($string)
    {
        $options = [
            'cost' => 12,
        ];

        return password_hash($string, PASSWORD_BCRYPT, $options);
    }

    public function authentication_check()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $this->db->select('id, first_name, last_name, email, password')
            ->from('users a')
            ->where('email', $email);
        $login = $this->db->get();


        if ($login->num_rows() > 0) {
            $data = $login->row();
            if (password_verify($password, $data->password)) {
             return $data;
            }else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function unico_session_register($row)
    {
        $data = ([
            'unico_users_id' => (!empty($row->id)) ? $row->id : NULL,
            'unico_first_name' => (!empty($row->first_name)) ? $row->first_name : NULL,
            'unico_last_name' => (!empty($row->last_name)) ? $row->last_name : NULL,
            'unico_email' => (!empty($row->email)) ? $row->email : NULL,
            'unico_loggedin' => TRUE,
        ]);

        $this->session->set_userdata($data);
    }

    public function unico_session_destroy()
    {
        $data = ([
            'unico_users_id',
            'unico_first_name',
            'unico_last_name',
            'unico_email',
            'unico_loggedin'
        ]);

        $this->session->unset_userdata($data);
    }

}

/* End of file M_users.php */