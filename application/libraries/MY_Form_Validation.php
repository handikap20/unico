<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    protected $CI;
    
    public function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    public function validate_secured_string($str, $key) {
        $this->CI->form_validation->set_message('validate_secured_string', "Bidang {field} tidak valid.");
        if(function_exists("decrypt_url")) {
            return (decrypt_url($str, $key) !== false ) ? true : false;
        }else{
            return true;
        }
    }

    public function alphanumber_dot($str)
    {
        
        $this->CI->form_validation->set_message('alphanumber_dot', "Bidang {field} harus berupa karakter huruf kecil, angka dan titik.");
        
        
        return ( ! preg_match("/^([a-z0-9._])+$/", $str)) ? FALSE : TRUE;
    }

    public function field_must_empty($str)
    {
        
        $this->CI->form_validation->set_message('field_must_empty', "Bidang {field} harus kosong.");
        
        
        return (empty($str) && strlen($str)==0 && $str=="")? true : false;
    }

    public function field_unique($str, $field)
    {
        sscanf($field, '%[^.].%[^.].%[^.].%[^.]', $table, $field, $extra_field, $extra_field_value);
        $this->CI->form_validation->set_message('field_unique', "{field} yang anda inputkan sudah ada.");
        $where = array($field => $str, 'status' => '1');
    
        if(!empty($extra_field)){
            $where[$extra_field] = $extra_field_value;
        }
        
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, $where)->num_rows() === 0)
            : FALSE;
    }

    public function field_edit_unique($str, $field)
    {
        sscanf($field, '%[^.].%[^.].%[^.].%[^.].%[^.]', $table, $field, $extra_field, $extra_field_value, $id);
        $this->CI->form_validation->set_message('field_edit_unique', "{field} yang anda inputkan sudah ada.");
        $where = array($field => $str, 'id !=' => $id, 'status' => '1');
    
        if(!empty($extra_field)){
            $where[$extra_field] = $extra_field_value;
        }
        
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, $where)->num_rows() === 0)
            : FALSE;
    }

	public function edit_unique($str, $field)
    {
        sscanf($field, '%[^.].%[^.].%[^.].%[^.]', $table, $field, $id, $level);
        $this->CI->form_validation->set_message('edit_unique', "{field} yang anda inputkan sudah digunakan.");
        $where = array($field => $str, 'id !=' => $id, 'is_deleted' => '0');
    
        if(!empty($level)){
            $where['level'] = $level;
        }
        
        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, $where)->num_rows() === 0)
            : FALSE;
    }

    public function password_matches($str, $field)
    {
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $id);
        
        $where = array('id =' => $id, 'is_deleted' => '0');
        

        if(isset($this->CI->db)){
            $query = $this->CI->db->limit(1)->get_where($table, $where);
            if($query->num_rows() > 0){
                $query = $query->row_array();
                if (password_verify($str, $query[$field])==false) {
                    $this->CI->form_validation->set_message('password_matches', "{field} yang anda inputkan tidak sesuai.");
                    return FALSE;
                }
            }else{
                $this->CI->form_validation->set_message('password_matches', "ID untuk {field} tidak ditemukan.");
                return FALSE;
            }
        }else{
            $this->CI->form_validation->set_message('password_matches', "Tidak bisa memvalidasi {field}.");
            return FALSE;
        }
    }

    public function db_unique($str, $field)
    {
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $level);
        $this->CI->form_validation->set_message('db_unique', "{field} yang anda inputkan sudah digunakan.");
        $where = array($field => $str, 'is_deleted' => '0');
        
        if(!empty($level)){
            $where['level'] = $level;
        }

        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where($table, $where)->num_rows() === 0)
            : FALSE;
    }

    public function password_unique($str, $field)
    {
        $return = true;
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $nik);
        if(!empty($nik)){
            $this->CI->form_validation->set_message('password_unique', "{field} yang anda inputkan harus berbeda dengan password lama.");
            if(isset($this->CI->db)){
                $query = $this->CI->db->get_where($table, array('id =' => $nik, 'is_deleted' => '0'));
                if($query->num_rows() > 0){
                    $query = $query->row_array();
                    if (password_verify($str, $query[$field])) {
                        $return = FALSE;
                    }
                }
            }else{
                $return = FALSE;
            }
        }else{
            $this->CI->form_validation->set_message('password_unique', "Bidang NIK untuk bidang {field} diperlukan.");
            $return = FALSE;
        }
        
        return $return;
    }

    public function validate_date($date, $format = 'Y-m-d')
    {
        if($format == false){
            $format = 'Y-m-d';
        }

        $d = DateTime::createFromFormat($format, $date);
        $this->CI->form_validation->set_message('validate_date', "Bidang {field} tidak valid.");
        return $d && $d->format($format) == $date;
    }
    

    public function greater_then_date($date, $date_compare = false)
    {
        if($date_compare == false){
            $date_compare = date('Y-m-d');
        }
        $this->CI->form_validation->set_message('greater_then_date', "Bidang {field} harus lebih dari tanggal saat ini.");
        return strtotime($date) > strtotime($date_compare);
    }

    public function less_then_date($date, $date_compare = false)
    {
        if($date_compare == false){
            $date_compare = date('Y-m-d');
        }
        $this->CI->form_validation->set_message('less_then_date', "Bidang {field} harus kurang dari tanggal saat ini.");
        return strtotime($date) < strtotime($date_compare);
    }

    public function validate_options($value, $function)
    {
        $this->CI->load->model('m_options');
        $compare = $this->CI->m_options->get_value($function, $value);
        $this->CI->form_validation->set_message('validate_options', "Nilai pada bidang {field} tidak valid.");
        return ($compare !== false) ? true : false;
    }
}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */