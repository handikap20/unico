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

if (!function_exists('img_color_bottom_nav')) {
    function img_color_bottom_nav($url1='', $url2='')
    {
        $array = array('home','cart','profile');
        if(!in_array($url1, $array)){
            return "_black";
        }
        if(!in_array($url1, $array)){
            return "_black";
        }
    }
}



?>