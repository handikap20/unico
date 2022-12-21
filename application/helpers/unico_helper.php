<?php 

if (!function_exists('error_delimeter')) {
    function error_delimeter($index)
    {
        $data = array('<div><span class="text-danger"><i>*', '</i></span></div>');

        if ($index > count($data)) {
            throw new Exception("Index value can't bigger than " . count($data));
        } else {
            $return = $data[$index - 1];
        }
        return $return;
    }
}


if (!function_exists('show_msg')) {
    function show_msg($messages, $status = false, $redirect_link = null)
    {
        
        $CI =& get_instance();
        
        $array = array('status' => $status, 'message' => $messages);
        if($redirect_link !== null) {
            $array['redirect_link'] = $redirect_link;
        }

        return $CI->load->view('errors/html/error_bootbox', $array, TRUE);
    }
}


?>