<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('_sidebar_backend')) {
    function _sidebar_backend()
    {
        $ci = &get_instance();
        $output_menu = '';
        $level = '1';
        if ($level) {
            $ci->load->model('m_acl');
            $menu = $ci->m_acl->_acl_sidebar_menu($level);

            //index elements by id
            foreach ($menu as $item) {
                $item->subs = array();
                $indexedItems[$item->id] = (object) $item;
            }
            
            //assign to parent
            $topLevel = array();
            foreach ($indexedItems as $item) {
                if ($item->parent == 0) {
                    $topLevel[] = $item;
                } else {
                    $indexedItems[$item->parent]->subs[] = $item;
                }
            }

            $output_menu = renderMenuSidebar($topLevel);
        }

        return $output_menu;
    }
}

if (!function_exists('_topbar_backend')) {
    function _topbar_backend()
    {
        $ci = &get_instance();
        $output_menu = '';
        $level = $ci->session->userdata('simpel_level');
        if ($level) {
            $ci->load->model('m_acl');
            $menu = $ci->m_acl->_acl_sidebar_menu($level);

            //index elements by id
            foreach ($menu as $item) {
                $item->subs = array();
                $indexedItems[$item->id] = (object) $item;
            }
            //assign to parent
            $topLevel = array();
            foreach ($indexedItems as $item) {
                if ($item->parent == 0) {
                    $topLevel[] = $item;
                } else {
                    $indexedItems[$item->parent]->subs[] = $item;
                }
            }

            $output_menu = renderMenuTopBar($topLevel);
        }

        return $output_menu;
    }
}

if (!function_exists('renderMenuSidebar')) {
    function renderMenuSidebar($items, $parent = false)
    {
        $render = '';
        $no = 1;

        foreach ($items as $item) {
            if (!empty($item->subs)) {
                $render .= ' <li>
                                <a class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false" href="#a' . $no . '">
                                    <i class="' . $item->icon . '"></i>
                                    <span>' . $item->title . '</span>
                                    <i class="ri-arrow-right-s-line iq-arrow-right"></i>
                                </a>';
                $render .= '<ul class="iq-submenu collapse" data-parent="#iq-sidebar-toggle" id="a' . $no . '">';
                $render .= renderMenuSidebar($item->subs, true);
                $render .= '</ul></li>';
            } else {
                $icon = '';
                if (!empty($item->icon)) {
                    $icon = '<i class=" ' . $item->icon . '"></i>';
                }
                if($parent == true) {
                    $render .= '<li>
                                <a href="' . base_url($item->url) . '">  
                                    ' . $icon . ' 
                                    '  . $item->title . '
                                </a></li>';
                }else{
                    $render .= '<li>
                                <a  class="iq-waves-effect" href="' . base_url($item->url) . '">             
                                    ' . $icon . '
                                    <span >' . $item->title . '</span>
                                </a></li>';
                }
                
            }
            $no++;
        }

        return $render;
    }
}

if (!function_exists('renderMenuTopBar')) {
    function renderMenuTopBar($items, $is_subs = false)
    {
        $render = '';
        $no = 1;
        foreach ($items as $item) {
            if (!empty($item->subs)) {
                $render .= ' <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" id="#a' . $no . '" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="' . $item->icon . ' mr-1"></i>
                                    <span>' . $item->title . '</span>
                                    <div class="arrow-down"></div>
                                </a>';
                $render .= '<div class="dropdown-menu" aria-labelledby="a' . $no . '">';
                $render .= renderMenuTopBar($item->subs, true);
                $render .= '</div></li>';
            } else {
                if ($is_subs == true) {
                    $icon = '';
                    if (!empty($item->icon)) {
                        $icon = '<i class="' . $item->icon . ' mr-1"></i>';
                    }

                    $render .= '
                                    <a class="dropdown-item" href="' . base_url($item->url) . '">             
                                        ' . $icon . '
                                        <span>' . $item->title . '</span>
                                    </a>';
                } else {
                    $icon = '';
                    if (!empty($item->icon)) {
                        $icon = '<i class="' . $item->icon . ' mr-1"></i>';
                    }

                    $render .= '<li class="nav-item">
                                    <a class="nav-link" href="' . base_url($item->url) . '">             
                                        ' . $icon . '
                                        <span>' . $item->title . '</span>
                                    </a>
                                </li>';
                }
            }
            $no++;
        }

        return $render;
    }
}

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

if (!function_exists('meta_tag_refresh')) {
    function meta_tag_refresh($delay, $url)
    {
        return "<meta http-equiv='refresh' content='$delay;url=$url'>";
    }
}

if (!function_exists('pg_to_array')) {
    function pg_to_array($path)
    {
        if (!empty($path)) {
            return explode(',', trim($path, '{}'));
        } else {
            return null;
        }
    }
}

if (!function_exists('multi_pg_to_array')) {
    function multi_pg_to_array($path)
    {
        if (!empty($path)) {
            $return = explode('},{', trim($path, "{}"));
            for($i=0;$i < count($return); $i++){
                $return[$i] = explode(',', str_replace('"', '', $return[$i]));
            }

            return $return;
        } else {
            return null;
        }
    }
}

if (!function_exists('array_to_pg')) {
    function array_to_pg($data_array)
    {
        if (!empty($data_array)) {
            return str_replace(['[', ']'], ['{', '}'], json_encode($data_array));
        } else {
            return null;
        }
    }
}
if (!function_exists('p_to_text')) {
    function p_to_text($data_array)
    {
        if (!empty($data_array)) {
            return str_replace(['<p>', '</p>'], ['', ''],$data_array);
        } else {
            return null;
        }
    }
}

if (!function_exists('array_list_loop')) {
    function array_list_loop($count_loop, $value)
    {
        for ($i = 0; $i < $count_loop; $i++) $arr_id_key[] = $value;
        return $arr_id_key;
    }
}

if (!function_exists('data_expl')) {
    function data_expl($json, $name_json)
    {
        $a = '';
        if ($json) {
            $pgarray_nama = json_decode($json, true);
            $nip  = $pgarray_nama[$name_json];
            $no = 1;
            for ($i = 0; $i < count($nip); $i++) {
                $nama  = $nip[$i];
                $a .= $no++ . '.' . $nama . '<hr class="m-1">';
            }
        } else {
            $a = 'Tidak ada data';
        }


        return $a;
    }
}

if (!function_exists('db_in_list')) {
    function db_in_list($table, $field, $extra = NULL, $id_key = NULL)
    {
        $return = '';
        $CI = &get_instance();
        $extra;
        $data = $CI->db->select($field)->get($table);

        if ($data->num_rows() > 0) {
            if ($id_key !== NULL) {
                $array = array_map('encrypt_url', array_column($data->result(), $field), array_list_loop($data->num_rows(), $id_key));
            } else {
                $array = array_column($data->result(), $field);
            }

            $array_str = implode(',', $array);

            $return = 'in_list[' . $array_str . ']';
        }

        return $return;
    }
}

if (!function_exists('data_in_list')) {
    function data_in_list($data, $column, $id_key = NULL)
    {
        $return = '';
        if ($id_key !== NULL) {
            $array = array_map('encrypt_url', array_column($data, $column), array_list_loop(count($data), $id_key));
        } else {
            $array = array_column($data, $column);
        }

        $array_str = implode(',', $array);

        $return = 'in_list[' . $array_str . ']';

        return $return;
    }
}

if (!function_exists('tabel_icon')) {
    function tabel_icon($id, $session_id, $action, $link_url = '', $keyid = '', $modal_name = '', $attr =  '')
    {
        $a = '';

        if ($id !== $session_id) {
            if ($keyid !== '') {
                $id = encrypt_url($id, $keyid);
            }

            if ($link_url !== '') {
                $a_tag = 'a';
                $link_url = 'href="' . base_url($link_url . $id) . '"';
                $modal_attr = '';
            } else {
                $a_tag = 'span';
                $link_url = "";
                if ($modal_name !== '') {
                    $modal_attr = 'data-bs-toggle="modal" data-bs-target="#' . $modal_name . '"';
                } else {
                    $modal_attr = '';
                }
            }

            if ($action == "delete") {
                $a = '<' . $a_tag . ' ' . $link_url . '  '. $attr .' class="button-hapus btn btn-icon btn-icon rounded-circle btn-flat-danger" title="Hapus" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                            <span class="btn-icon-wrap"><i data-feather="trash"></i></span>
                        </' . $a_tag . '>';
            } elseif ($action == "edit") {
                if($modal_name !== ''){
                    $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-edit btn btn-icon btn-icon rounded-circle btn-flat-warning" data-id="' . $id . '" ' . $modal_attr . '>
                            <a title="Edit" data-bs-toggle="tooltip" data-bs-placement="top"> <i data-feather="edit"></i></a>
                        </' . $a_tag . '>';
                }else{
                    $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-edit btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Edit"  data-id="' . $id . '" ' . $modal_attr . '>
                                                <i data-feather="edit"></i>
                        </' . $a_tag . '>';
                }
                
            } elseif ($action == "tanggapi") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-edit btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Tanggapi" data-bs-toggle="tooltip" data-bs-placement="top"  data-id="' . $id . '" ' . $modal_attr . '>
                                                <i data-feather="edit"></i>
                        </' . $a_tag . '>';
            } elseif ($action == "reset") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-reset-pass btn btn-icon btn-info btn-xs" title="Reset" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                                                <span class="btn-icon-wrap"><i class="mdi mdi-autorenew"></i></span>
                        </' . $a_tag . '>';
            } elseif ($action == "add") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="btn btn-icon btn-icon rounded-circle btn-info" title="Tambah" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i data-feather="edit"></i>
                        </' . $a_tag . '>';
            } elseif ($action == "view") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-lihat btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat" data-bs-toggle="tooltip" data-bs-placement="top"  data-id="' . $id . '" ' . $modal_attr . '>
                            <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                        </' . $a_tag . '>';
            }
        }

        return $a;
    }
}

if (!function_exists('active_status')) {
    function active_status($id, $session_id, $status_field, $keyid = '', $link_status = '', $modal_name = '')
    {
        $a = '';

        if ($id !== $session_id) {
            if ($keyid !== '') {
                $id = encrypt_url($id, $keyid);
            }

            if ($link_status !== '' && $link_status !== ' ' ) {
                $a_tag = 'a';
                $link_status = 'href="' . base_url($link_status . $id) . '"';
            } else {
                $a_tag = 'span';
                $link_status = "";

                if ($modal_name !== '') {
                    $modal_attr = 'data-bs-toggle="modal" data-bs-target="#' . $modal_name . '"';
                } else {
                    $modal_attr = '';
                }
            }

            if ($status_field == "0") {
                $a = '<' . $a_tag . ' ' . $link_status . '  data-status="0" class="button-status btn btn-icon btn-icon rounded-circle btn-flat-success" title="Aktifkan" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                            <i data-feather="user-check"></i>
                        </' . $a_tag . '>';
            } else {
                $a = '<' . $a_tag . ' ' . $link_status . ' data-status="1" class="button-status btn btn-icon btn-icon rounded-circle btn-flat-danger" title="Non Aktifkan" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" ' . $modal_attr . '>
                        <i data-feather="user-x"></i>
                    </' . $a_tag . '>';
            }
        }

        return $a;
    }
}

if (!function_exists('process_status')) {
    function process_status($id, $session_id, $status_field, $keyid = '', $link_status = '')
    {
        $a = '';

        if ($id !== $session_id) {
            if ($keyid !== '') {
                $id = encrypt_url($id, $keyid);
            }

            if ($link_status !== '') {
                $a_tag = 'a';
                $link_status = 'href="' . base_url($link_status . $id) . '"';
            } else {
                $a_tag = 'span';
                $link_status = "";
            }

            if ($status_field == "2") {
                $a = '<' . $a_tag . ' ' . $link_status . '  class="button-proses btn btn-icon btn-success btn-xs btn-icon-style-1" data-id="' . $id . '" >
                            <span class="btn-icon-wrap"><i class="mdi mdi-check-all"></i></span>
                        </' . $a_tag . '>'; //DALAM PROSES
            } elseif ($status_field == "3") {
                $a = '<' . $a_tag . ' ' . $link_status . '  class="button-ambil btn btn-icon btn-info btn-xs btn-icon-style-1" data-id="' . $id . '" >
                            <span class="btn-icon-wrap"><i class="mdi mdi-handshake"></i></span>
                        </' . $a_tag . '>'; //DAPAT DIAMBIL
            } else {
                $a = '';
            }
        }

        return $a;
    }
}

if (!function_exists('str_sensor')) {
    function str_sensor($str)
    {
        return substr($str, 0, 2).str_repeat('x', strlen($str)-2);
    }
}

if (!function_exists('str_status')) {
    function str_status($status)
    {
        $a = '';

        if ($status == '1') {
            $a = '<span class="badge bg-success">Aktif</span>';
        } else {
            $a = '<span class="badge bg-danger">Tidak Aktif</span>';
        }

        return $a;
    }
}

if (!function_exists('str_public_images')) {
    function str_public_images($path, $filename)
    {
        $a = '';

        if (!empty($filename)) {
            $ci = &get_instance();
            $a = base_url($ci->data['public_image_path'] . $path . $filename);
        } else {
            $a = "";
        }

        return $a;
    }
}

if (!function_exists('str_files_images')) {
    function str_files_images($path, $filename)
    {
        $a = '';

        if (!empty($filename)) {
            $ci = &get_instance();
            $a = base_url($ci->data['file_image_path'] . $path . $filename);
        } else {
            $a = "";
        }

        return $a;
    }
}

if (!function_exists('btn_view_images')) {
    function btn_view_images($path, $filename)
    {
        $a = '';

        if (!empty($filename)) {
            $a = "<a class='btn btn-info btn-sm' data-fancybox href=" . str_files_images($path, $filename) . ">Lihat File</a>";
        } else {
            $a = '<label class="btn btn-danger btn-sm">Tidak ada file</label>';
        }

        return $a;
    }
}

if (!function_exists('str_btn_files')) {
    function str_btn_files($path, $filename)
    {
        $a = '';

        if (!empty($filename)) {
            $ci = &get_instance();
            $a = base_url($ci->data['files_path'] . $path . $filename);
        } else {
            $a = "";
        }

        return $a;
    }
}

if (!function_exists('btn_view_file')) {
    function btn_view_file($path, $filename = null)
    {
        $a = '';

        if (!empty($filename)) {
            $ci = &get_instance();
            $a = base_url($ci->data['files_path'] . $path . $filename);
            $a = "<a class='btn btn-success btn-block' data-fancybox href=" . str_btn_files($path, $filename) . ">Lihat File</a>";
        } else {
            $a = '<label class="btn btn-danger btn-block">Tidak ada file</label>';
        }

        return $a;
    }
}


if (!function_exists('str_email')) {
    function str_email($email, $active_email)
    {
        $a = '';

        if ($active_email == '1') {
            $a = "<span class=\"badge badge-success\">$email</span>";
        } else {
            $a = "<span class=\"badge badge-danger\">$email</span>";
        }

        return $a;
    }
}

if (!function_exists('fix_number')) {
    function fix_number($number, $digit)
    {
        $prefix = '';
        if (strlen($number) < $digit) {
            for ($i = strlen($number); $i < $digit; $i++) {
                $prefix .= '0';
            }
        }

        return $prefix . $number;
    }
}

if (!function_exists('jenisKelamin')) {
    function jenisKelamin($id)
    {
        switch ($id) {
            case 1:
                $return = 'Laki-Laki';
                break;
            case 2:
                $return = 'Perempuan';
                break;
            default:
                $return = '';
                break;
        }

        return $return;
    }
}

if (!function_exists('agama')) {
    function agama($id)
    {
        switch ($id) {
            case 1:
                $return = 'Islam';
                break;
            case 2:
                $return = 'Protestan';
                break;
            case 3:
                $return = 'Katolik';
                break;
            case 4:
                $return = 'Hindu';
                break;
            case 5:
                $return = 'Buddha';
                break;
            case 6:
                $return = 'Khonghucu';
                break;
            default:
                $return = '';
                break;
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

if (!function_exists('check_islogin')) {
    function check_islogin()
    {
        $CI =& get_instance();

        if ($CI->session->userdata('simpel_loggedin') == FALSE || empty($CI->session->userdata('simpel_loggedin')) || $CI->session->userdata('simpel_loggedin') == NULL) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}

if (!function_exists('first_letter_each_word')) {
    function first_letter_each_word($string)
    {
        $word = $string;
        $expr = '/(?<=\s|^)[a-z]/i';
        preg_match_all($expr, $word, $matches);
        
        return substr(implode('', $matches[0]), 0, 2  ) ;
    }
}

if (!function_exists('create_default_avatar')) {
    function create_default_avatar(
        string $text = 'DEV',
        array $bgColor = [0, 0, 0],
        array $textColor = [255, 255, 255],
        int $fontSize = 140,
        int $width = 600,
        int $height = 600,
        string $font = './arial.ttf'
    ) {
        $image = @imagecreate($width, $height)
            or die("Cannot Initialize new GD image stream");

        imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);

        $fontColor = imagecolorallocate($image, $textColor[0], $textColor[1], $textColor[2]);
        $textBoundingBox = imagettfbbox($fontSize, 0, $font, $text);
        $y = abs(ceil(($height - $textBoundingBox[5]) / 2));
        $x = abs(ceil(($width - $textBoundingBox[2]) / 2));

        imagettftext($image, $fontSize, 0, $x, $y, $fontColor, $font, $text);

        return $image;
    }
}

if (!function_exists('label_required')) {
    function label_required($id=null)
    {
        return '<span id="'.$id.'" class="text-danger">*</span>';
    }
}

if (!function_exists('generate_avatar')) {
    function generate_avatar($photo = null, $jenis_kelamin = null, $nama_pegawai = null)
    {
        if($jenis_kelamin == "L"){
            $badge = "info";
        }else {
            $badge = "danger";
        }
        
         if(!empty($photo)){
            $_photo = '<div class="avatar  me-1"><img src="https://simpeg.agamkab.go.id/files/images/profile_picture/'.$photo.'" alt="Avatar" width="32" height="32"></div>';
        }else {
            $_photo = '<div class="avatar bg-light-'.$badge.' me-1"><span class="avatar-content">'._str_limit($nama_pegawai,1).'</span></div>';
        }

        return $_photo;
    }
}

if (!function_exists('make_button')) {
    function make_button($btn_name, $btn_label, $btn_tag, $btn_type, $btn_size='', $custom_class='', $btn_icon = '', $btn_link = '#')
    {
        if($btn_tag=='a') {
            $btn_link_tag = 'href';
        }else{
            $btn_link_tag = 'data-action';
        }

        if($btn_link !== '#') {
            $btn_link = base_url($btn_link);
        }

        if($btn_icon !== '') {
            $btn_icon = '<i class="'.$btn_icon.'"></i>';
        }

        return '<'.$btn_tag.' id="'.$btn_name.'" '.$btn_link_tag.'="' . $btn_link . '" class="btn '.$btn_size.' '.$btn_type.' '.$custom_class.'">'.$btn_icon.$btn_label.'</'.$btn_tag.'>';
    }
}

if (!function_exists('time_convert')) {
    function time_convert($time = '')
    {
       if ($time) {
           $result = date('H:i', strtotime($time));
       } else {
           $result = '';
       }

       return $result;
    }
}

if (!function_exists('jadwal')) {
    function jadwal($start_date = '', $end_date = '')
    {
        if ($start_date && $end_date) {
            $result = time_convert($start_date) .' <i class="ti-control-forward"></i> '. time_convert($end_date);
        } else {
            $result = '';
        }

       return $result;
    }
}

if (!function_exists('jenjang_pendidikan')) {
    function jenjang_pendidikan($id)
    {
        switch ($id) {
            case 1:
                $return = 'SD / Sederajat';
                break;
            case 2:
                $return = 'SMP / Sederajat';
                break;
            case 3:
                $return = 'SMA / Sederajat';
                break;
            case 4:
                $return = 'S1';
                break;
            case 5:
                $return = 'S2';
                break;
            case 6:
                $return = 'S3';
                break;
            default:
                $return = 'Lainnya';
                break;
        }

        return $return;
    }
}

if (!function_exists('kamus_data')) {
    function kamus_data($type)
    {
        switch ($type) {
            case 1:
                $return = 'Pekerjaan';
                break;
            case 2:
                $return = 'Belum Tau';
                break;
            case 3:
                $return = 'Belum Tau 2';
                break;
            default:
                $return = '';
                break;
        }

        return $return;
    }
}

if (!function_exists('uc_words')) {
    function uc_words($data)
    {
        $word = strtolower($data);
        $result = ucwords($word);
        
        return $result;
    }
}

if (!function_exists('detail_region')) {
    function detail_region($address, $villages, $districts, $regencies, $provinces)
    {
        $data = $address . ", Desa " .uc_words($villages). ", Kecamatan " .uc_words($districts);
        $_regencies = uc_words($regencies);
        $_provinces = "Provinsi " .uc_words($provinces);
        $icon = '
            <span class="fa-stack fa-lg mr-1">
                <i class="far fa-circle fa-stack-2x text-success"></i>
                <i class="fas fa-home fa-stack-1x text-success"></i>
            </span>
        ';

        $result = '
            <div class="media d-inline-flex align-items-center">
                '.$icon.'
                <div class="media-body">
                    <div class="marquee-md">
                        <a href="javascript: void(0);" class="text-default font-weight-semibold letter-icon-title">'.$data.'</a>
                    </div>
                    <div class="marquee-md">
                        <a href="javascript: void(0);" class="text-default font-weight-semibold letter-icon-title">'.$_regencies.'</a>
                    </div>
                     <div class="marquee-md">
                        <a href="javascript: void(0);" class="text-default font-weight-semibold letter-icon-title">'.$_provinces.'</a>
                    </div>
                </div>
            </div>
        ';

        return $result;
    }
}

if (!function_exists('phone_number')) {
    function phone_number($phone_number)
    {
        $data = sprintf(
            "%s-%s-%s",
            substr($phone_number, 0, 4),
            substr($phone_number, 4, 4),
            substr($phone_number, 8)
        );

        return $data;
    }
}

if (!function_exists('age_counter')) {
    function age_counter($date, $year_only = FALSE)
    {
        $_date = new DateTime($date);
        $_today = new DateTime('today');
        $_year = $_today->diff($_date)->y;
        $_month = $_today->diff($_date)->m;
        $_day = $_today->diff($_date)->d;

        if ($year_only === TRUE) {
            return $_year ." Tahun " . $_month ." Bulan " . $_day ." Hari";
        } else {
            return $_year ." Tahun";
        }
    }
}

if (!function_exists('check_nisn')) {
    function check_nisn($nisn , $nis)
    {
        if (!empty($nisn) && $nisn !== '-') {
            $result = '
                <b class="cst_bold">NISN: </b>
                <span class="badge badge-soft-success cst_bold">'.$nisn.'</span>
            ';
        } else if (!empty($nis) && $nis !== '-') {
            $result = '
                <b class="cst_bold">NIS: </b>
                <span class="badge badge-soft-success cst_bold">'.$nis.'</span>
            ';
        } else {
            $result = '
                <span class="badge badge-soft-success cst_bold">NIS dan NISN belum terdaftar.</span>
            ';
        }

        return $result;
    }
}

if (!function_exists('check_name_buku')) {
    function check_name_buku($jenis_buku, $kategori)
    {
        if (!empty($kategori) && $kategori !== '-') {
            $result = '
                <b class="cst_bold">Kategori Buku: </b>
                <span class="badge badge-soft-success cst_bold">'.$kategori.'</span>
';
        } else if (!empty($jenis_buku) && $jenis_buku !== '-') {
            $result = '
                <b class="cst_bold">Jenis Buku: </b>
                <span class="badge badge-soft-success cst_bold">'.$jenis_buku.'</span>
            ';
        } else {
            $result = '
                <span class="badge badge-soft-success cst_bold">NIS dan NISN belum terdaftar.</span>
            ';
        }

        return $result;
    }
}

if (!function_exists('pegawai_image')) {
    function pegawai_image($profile, $gender)
    {
       if (empty($profile)) {
           if ($gender == '1') {
               $result = str_files_images('', 'student_men.png');
           } else {
                $result = str_files_images('', 'student_girl.png');
           }
       } else {
           $result = str_files_images('', $profile);
       }

       return $result;
    }
}

if (!function_exists('buku_image')) {
    function buku_image($profile)
    {
       if (empty($profile)) {
               $result = str_files_images('', 'no_image.png'); 
       } else {
           $result = str_files_images('', $profile);
       }

       return $result;
    }
}

if (!function_exists('icon_pegawai')) {
    function icon_pegawai($nama_pegawai = '', $nip = '', $photo = '', $gelar_depan = '', $gelar_blkng= '', $marquee = '',$jenis_kelamin ='')
    {
        if($jenis_kelamin === "L"){
            $badge = "info";
        }else {
            $badge = "danger";
        }

        if(!empty($photo)){
            $_photo = '<div class="avatar  me-1"><img src="https://simpeg.agamkab.go.id/files/images/profile_picture/'.$photo.'" alt="Avatar" width="32" height="32"></div>';
        }else {
            $_photo = '<div class="avatar bg-light-'.$badge.' me-1"><span class="avatar-content">'._str_limit($nama_pegawai,1).'</span></div>';
        }

        $result = '
            <div class="d-flex justify-content-left align-items-center">
                '.(!empty($_photo) ? $_photo: '').'
                <div class="d-flex flex-column">
                    <span class="emp_name text-truncate fw-bold">'.nama_gelar($nama_pegawai,$gelar_depan,$gelar_blkng).'</span>
                <small class="emp_post text-truncate text-muted">'.$nip.'</small>
                </div>
            </div>
        ';

        return $result;
    }
}

if (!function_exists('icon_buku')) {
    function icon_buku($name1 = '',$jenis_buku = '', $kateogri = '',$profile ='', $marquee = '')
    {
        $_profile = '
            <img class="mr-2 avatar-sm rounded" src="'.buku_image($profile).'" width="44"  data-fancybox href="'.buku_image($profile).'" alt="Profile Image">
        ';

        $result = '
            <div class="media d-inline-flex align-items-center">
                '.(!empty($_profile) ? $_profile: '').'
                <div class="media-body">
                    <div class="'.(!empty($marquee) ? 'marquee-md' : '').' ">
                        <a href="javascript: void(0);" class="text-default font-weight-semibold letter-icon-title">'.$name1.'</a>
                    </div>
                    <span class="font-13">
                        '.check_name_buku($jenis_buku, $kateogri).'
                    </span>
                </div>
            </div>
        ';

        return $result;
    }
}

if (!function_exists('datatables_icon'))
{
    function datatables_icon($name1 = '', $name2 = '', $icon = '', $color = '', $marquee = '')
    {
        
        if($icon && $color) {
            $_icon = '
                <div class="mr-2">
                    <a href="javascript: void(0);" class="btn bg-transparent border-'.$color.' text-'.$color.' rounded-circle border-2 btn-icon" data-popup="tooltip" title="'.$name1.'" data-placement="left">
                        <i class="'.$icon.'"></i>
                    </a>
                </div>
            ';
        }      

        $result = '
            <div class="d-inline-flex align-items-center">
                '.(!empty($_icon) ? $_icon: '').'
                <div>
                    <div class="'.(!empty($marquee) ? 'marquee-md' : '').' ">
                        <a href="javascript: void(0);" class="text-default font-weight-semibold letter-icon-title">'.$name1.'</a>
                    </div>
                    '.(!empty($name2) ? '<div class="font-size-sm">'.$name2.'</div>': '').'
                </div>
            </div>
        ';
        return $result;
    }
}

if (!function_exists('check_nip')) {
    function check_nip($nip , $nik)
    {
        if (!empty($nip) && $nip !== '-') {
            $result = '
                <b class="cst_bold">NIP: </b>
                <span class="badge badge-soft-success cst_bold">'.$nip.'</span>
            ';
        } else if (!empty($nik) && $nik !== '-') {
            $result = '
                <b class="cst_bold">NIK: </b>
                <span class="badge badge-soft-success cst_bold">'.$nip.'</span>
            ';
        } else {
            $result = '
                <span class="badge badge-soft-success cst_bold">NIP dan NIK belum terdaftar.</span>
            ';
        }

        return $result;
    }
}

if (!function_exists('icon_teacher')) {
    function icon_teacher($name1 = '', $nip = '', $nik = '', $profile = '', $gender = '', $marquee = '')
    {
        $_profile = '
            <img class="mr-2 avatar-sm rounded" src="'.students_image($profile, $gender).'" width="44" data-fancybox href="'.students_image($profile, $gender).'" alt="Profile Image">
        ';

        $result = '
            <div class="media d-inline-flex align-items-center">
                '.(!empty($_profile) ? $_profile: '').'
                <div class="media-body">
                    <div class="'.(!empty($marquee) ? 'marquee-md' : '').' ">
                        <a href="javascript: void(0);" class="text-default font-weight-semibold letter-icon-title">'.$name1.'</a>
                    </div>
                    <span class="font-13">
                        '.check_nip($nip, $nik).'
                    </span>
                </div>
            </div>
        ';

        return $result;
    }
}

if (!function_exists('pendidikan_terakhir')) {
    function pendidikan_terakhir($id)
    {
        switch ($id) {
            case 1:
                $return = 'SMU';
                break;
            case 2:
                $return = 'D1';
                break;
            case 3:
                $return = 'D2';
                break;
            case 4:
                $return = 'D3';
                break;
            case 5:
                $return = 'S1';
                break;
            case 6:
                $return = 'S2';
                break;
            case 7:
                $return = 'S3';
                break;
            default:
                $return = '';
                break;
        }

        return $return;
    }
}

if (!function_exists('status_peg')) {
    function status_peg($id)
    {
        switch ($id) {
            case 1:
                $return = 'PNS';
                break;
            case 2:
                $return = 'CPNS';
                break;
            case 3:
                $return = 'Guru Bantu Pusat';
                break;
            case 4:
                $return = 'GTT/Honorer Daerah';
                break;
            case 5:
                $return = 'GTT/Honorer Sekolaj';
                break;
            default:
                $return = '';
                break;
        }

        return $return;
    }
}

if (!function_exists('status_kawin')) {
    function status_kawin($id)
    {
        switch ($id) {
            case 1:
                $return = 'Kawin';
                break;
            case 2:
                $return = 'Belum Kawin';
                break;
            case 3:
                $return = 'Duda';
                break;
            case 4:
                $return = 'Janda';
                break;
            default:
                $return = '';
                break;
        }

        return $return;
    }
}
if (!function_exists('status_peg')) {
    function status_peg($id)
    {
        switch ($id) {
            case 1:
                $return = 'PNS';
                break;
            case 2:
                $return = 'CPNS';
                break;
            case 3:
                $return = 'Guru Bantu Pusat';
                break;
            case 4:
                $return = 'GTT/Honorer Daerah';
                break;
            case 5:
                $return = 'GTT/Honorer Sekolaj';
                break;
            default:
                $return = '';
                break;
        }

        return $return;
    }
}

if (!function_exists('ttl')) {
    function ttl($tgl,$tempat)
    {
        
        $return = $tempat.', '.tanggal_indo($tgl);

        return $return;
    }
}
if (!function_exists('agama')) {
    function agama($id)
    {
        switch ($id) {
            case 1:
                $return = 'Islam';
                break;
            case 2:
                $return = 'Protestan';
                break;
            case 3:
                $return = 'Katolik';
                break;
            case 4:
                $return = 'Hindu';
                break;
            case 5:
                $return = 'Budha';
                break;
            case 6:
                $return = 'Khonghucu';
                break;
            default:
                $return = '';
                break;
        }

        return $return;
    }
}
if (!function_exists('any_image')) {
    function any_image($profile)
    {
        if (empty($profile)) {
               $result = str_files_images('', 'no_image.png');
        } else {
           $result = str_files_images('', $profile);
        }        
       return $result;
    }
}
if (!function_exists('keadaan_barang')) {
    function keadaan_barang($kondisi)
    {
        if ($kondisi == 1) {
               $result = "Rusak Berat";
        } else if($kondisi == 2){
                $result = "Sedang";
        } else if($kondisi == 3){
                $result = "Baik";
        }        
       return $result;
    }
}

if (!function_exists('icon_inventaris')) {
    function icon_inventaris($name = '',$tingkatan_kelas_name='', $jurusan_name='', $type= '', $profile = '', $tgl_terima='',$keadaan_barang='', $marquee = '')
    {
        $_profile = '
            <img class="mr-2 avatar-sm rounded" src="'.any_image($profile).'" width="44" data-fancybox href="'.any_image($profile).'" alt="Profile Image">
        ';

        $result = '
            <div class="media d-inline-flex align-items-center">
                '.(!empty($_profile) ? $_profile: '').'
                <div class="media-body">
                    <div class="'.(!empty($marquee) ? 'marquee-md' : '').' ">
                        <a href="javascript: void(0);" class="text-default font-weight-semibold letter-icon-title">'.$name.'</a>
                    </div>
                    <span class="font-13">
                    <b class="cst_bold">Kelas: </b>
                    <span class="badge badge-soft-success cst_bold">'.$tingkatan_kelas_name.' '.$jurusan_name.' '.$type.'</span>
                    <br><b class="cst_bold">Tanggal Terima: </b>
                    <span class="badge badge-soft-success cst_bold">'.tanggal_indo($tgl_terima).'</span>
                    <br><b class="cst_bold">Kondisi Barang: </b>
                    <span class="badge badge-soft-success cst_bold">'.keadaan_barang($keadaan_barang).'</span>
                    </span>
                </div>
            </div>
        ';

        return $result;
    }
}


// if (!function_exists('detail_region')) {
//     function detail_region($address, $villages, $districts, $regencies, $provinces)
//     {
//         $data = $address . ", Desa " .uc_words($villages). ", Kecamatan " .uc_words($districts);
//         $_regencies = uc_words($regencies);
//         $_provinces = "Provinsi " .uc_words($provinces);
//         $icon = '
//             <span class="fa-stack fa-lg mr-1">
//                 <i class="far fa-circle fa-stack-2x text-success"></i>
//                 <i class="fas fa-home fa-stack-1x text-success"></i>
//             </span>
//         ';

//         $result = '
//             <div class="media d-inline-flex align-items-center">
//                 '.$icon.'
//                 <div class="media-body">
//                     <div class="marquee-md">
//                         <a href="javascript: void(0);" class="text-default font-weight-semibold letter-icon-title">'.$data.'</a>
//                     </div>
//                     <div class="marquee-md">
//                         <a href="javascript: void(0);" class="text-default font-weight-semibold letter-icon-title">'.$_regencies.'</a>
//                     </div>
//                      <div class="marquee-md">
//                         <a href="javascript: void(0);" class="text-default font-weight-semibold letter-icon-title">'.$_provinces.'</a>
//                     </div>
//                 </div>
//             </div>
//         ';

//         return $result;
//     }
// }

if (!function_exists('name_school')) {
    function name_school($name = '',$npsn='',$marquee='')
    {
        $_profile = '
         <span class="fa-stack fa-lg mr-1">
                <i class="far fa-circle fa-stack-2x text-success"></i>
                <i class="fas fa-graduation-cap fa-stack-1x text-success"></i>
        </span>
        ';

        $result = '
            <div class="media d-inline-flex align-items-center">
                '.(!empty($_profile) ? $_profile: '').'
                <div class="media-body">
                    <div class="'.(!empty($marquee) ? 'marquee-md' : '').' ">
                        <a href="javascript: void(0);" class="text-default font-weight-semibold letter-icon-title">'.$name.'</a>
                    </div>
                    <span class="font-13">
                    <b class="cst_bold">NPSN: </b>
                    <span class="badge badge-soft-success cst_bold">'.$npsn.'</span>
                </div>
            </div>
        ';

        return $result;
    }
}

if (!function_exists('status_sekolah')) {
    function status_sekolah($status_sekolah = '',$jenjang_pendidikan='')
    {
        $result = '
            <div class="media d-inline-flex align-items-center">
                <div class="media-body">
                        <div class="text-default font-weight-semibold letter-icon-title">'.$jenjang_pendidikan.'</div>
                    <span class="font-13">
                    <span class="badge badge-soft-success cst_bold">'.$status_sekolah.'</span>
                </div>
            </div>
        ';

        return $result;
    }
}
if (!function_exists('kontak_sekolah')) {
    function kontak_sekolah($no_telp = '',$kode_pos='')
    {
        $_profile = '
        <span class="fa-stack fa-lg mr-1 ">
               <i class="far fa-circle fa-stack-2x text-success"></i>
               <i class="fas fa-phone fa-stack-1x text-success"></i>
         </span>
       ';

       $result = '
           <div class="media d-inline-flex align-items-center">
               '.(!empty($_profile) ? $_profile: '').'
                <div class="media-body">
                    <span class="font-13">
                    <b class="cst_bold">No Telp: </b>
                    <span class="badge badge-soft-success cst_bold">'.phone_number($no_telp).'</span><br>
                    <span class="font-13">
                    <b class="cst_bold">Kode Pos: </b>
                    <span class="badge badge-soft-success cst_bold">'.$kode_pos.'</span>
                </div>
            </div>
        ';

        return $result;
    }
}

if (!function_exists('rentan')) {
    function rentan($nama_jadwal= '', $start_date = '',$end_date='')
        {
            if($nama_jadwal == ''){
                return $start_date." s/d ".$end_date;
            }else {
                $result = '
                    <div class="media d-inline-flex align-items-center">
                        <div class="media-body">
                                <div class="text-default font-weight-semibold letter-icon-title">'.$nama_jadwal.'</div>
                            <span class="font-13">
                            <span class="badge badge-soft-success cst_bold">'.tanggal_indo($start_date).' - '.tanggal_indo($end_date).'</span>
                        </div>
                    </div>
                ';
                return $result;
            }
        }
}
if (!function_exists('btn_jadwal')) {
    function btn_jadwal($id= '',$status='', $link='',$keyid='')
    {
        $id = encrypt_url($id,$keyid);
        $link_url = base_url($link.$id);
        $attr1 = 'blue';
        $attr2 = '';
        if($status != 1)
        {
            $attr1 = "light";
            $attr2 = 'style="pointer-events: none;"';
        }
        $a = '<a href="' . $link_url . '" class="button-view btn btn-icon btn-' . $attr1 .' btn-xs" title="Entry Data" data-plugin="tippy" data-tippy-size="small" data-tippy-placement="bottom" data-id="' . $id . '" '.$attr2.'>
                            <span class="btn-icon-wrap"><i class="fe-clock"></i> Entry Jadwal Belajar</span> 
            </a>';
            return $a;
    }
}

if (!function_exists('total_siswa')) {
    function total_siswa($total ='',$string='')
    {
        $a = '';
        if($string == '')
        {
            $text = "Siswa";
        }else {
            $text = $string;
        }

        if ($total == '0') {
            $a = '<span class="badge badge-soft-danger">0 Siswa</span>';
        } else {
            $a = '<span class="badge badge-soft-success">'.$total.' '.$text.'</span>';
        }

        return $a;
    }
}

if (!function_exists('btn_add_siswa')) {
    function btn_add_siswa($id= '',$kapasitas='',$ttl_siswa='', $link='',$keyid='')
    {
        $id = encrypt_url($id,$keyid);
        $link_url = base_url($link.$id);
        $attr1 = 'blue';
        $attr2 = '';
        if($kapasitas === $ttl_siswa)
        {
            $attr1 = "light";
            $attr2 = 'style="pointer-events: none;"';
        }
        $a = '<a href="' . $link_url . '" class="button-view btn btn-icon btn-' . $attr1 .' btn-xs" title="Entry Siswa" data-plugin="tippy" data-tippy-size="small" data-tippy-placement="bottom" data-id="' . $id . '" '.$attr2.'>
                            <span class="btn-icon-wrap"><i class="fe-users"></i> Tambah Siswa</span> 
            </a>';
            return $a;
    }
}

if (!function_exists('btn_add_jadwal')) {
    function btn_add_jadwal($id= '',$kapasitas='',$ttl_siswa='', $link='',$keyid='')
    {
        $id = encrypt_url($id,$keyid);
        $link_url = base_url($link.$id);
        $attr1 = 'blue';
        $attr2 = '';
        if($kapasitas === $ttl_siswa)
        {
            $attr1 = "light";
            $attr2 = 'style="pointer-events: none;"';
        }
        $a = '<a href="' . $link_url . '" class="button-view btn btn-icon btn-' . $attr1 .' btn-xs" title="Entry Jadwal" data-plugin="tippy" data-tippy-size="small" data-tippy-placement="bottom" data-id="' . $id . '" '.$attr2.'>
                            <span class="btn-icon-wrap"><i class="fe-clock"></i> Tambah Jadwal</span> 
            </a>';
            return $a;
    }
}

if (!function_exists('cek_bil')) {
    function cek_bil($bil)
    {
        $sbstr = substr($bil ,0,4);
        
        if ($sbstr % 2 == 0){ //Kondisi
            return "Genap"; //Kondisi true
        }else {
            return "Ganjil"; //Kondisi false
        }
    }
}

if (!function_exists('createRandomPassword')) {
    function createRandomPassword() 
    { 
    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 

    while ($i <= 7) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 
    return $pass;   
    }
}

if (!function_exists('createRandomString')) {
    function createRandomString() 
    { 
    $chars = "qwertyzxcsdf"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $string = '' ; 

    while ($i <= 7) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $string = $string . $tmp; 
        $i++; 
    } 
    return $string;   
    }
}

if (!function_exists('str_status_wali_kelas')) {
    function str_status_wali_kelas($level)
    {
        $a = '';

        if ($level == '3') {
            $a = '<span class="badge badge-soft-success">Aktif</span>';
        } else {
            $a = '<span class="badge badge-soft-danger">Tidak Aktif</span>';
        }

        return $a;
    }
}

if (!function_exists('active_wali_kelas')) {
    function active_wali_kelas($id, $level, $save_link, $keyid='', $modal_name='')
    {
        $a = '';

            if ($keyid !== '') {
                $id = encrypt_url($id, $keyid);
            }
            if ($save_link !== '' && $save_link !== ' ' ) {
                $a_tag = 'a';
                $save_link = 'href="' . base_url($save_link . $id) . '"';
            } else {
                $a_tag = 'span';
                $save_link = "";

                if ($modal_name !== '') {   
                    $modal_attr = 'data-toggle="modal" data-target="#' . $modal_name . '"';
                } else {
                    $modal_attr = '';
                }
            }
            if ($level == 4) {
                $a = '<' . $a_tag . ' ' . $save_link . '  data-status="0" class="button-status btn btn-icon btn-success btn-xs btn-icon-style-1" title="Aktifkan" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                            <span class="btn-icon-wrap"><i class="mdi mdi-check"></i></span>
                        </' . $a_tag . '>';
            } else {
                $a = '<' . $a_tag . ' ' . $save_link . ' data-status="1" class="button-status btn btn-icon btn-danger btn-xs btn-icon-style-1" title="Non Aktifkan" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" ' . $modal_attr . '>
                        <span class="btn-icon-wrap" ><i class="mdi mdi-close"></i></span>
                    </' . $a_tag . '>';
            }

        return $a;
    }
}

if (!function_exists('btn_add_nilai')) {
    function btn_add_nilai($sch_run_id= '', $link='',$keyid='')
    {
        $id = encrypt_url($sch_run_id,$keyid);
        $link_url = base_url($link.$id);
        $a = '<a href="' . $link_url . '" class="button-view btn btn-icon btn-primary btn-xs" title="Entry Siswa" data-plugin="tippy" data-tippy-size="small" data-tippy-placement="bottom" data-id="' . $id . '">
                            <span class="btn-icon-wrap"><i class="fe-edit-1"></i> Entry Nilai Siswa</span> 
            </a>';
        return $a;
    }
}

if (!function_exists('level_tugas')) {
    function level_tugas($level=null)
    {
        if($level == '1'){
            $text = "Penanggung Jawab";
        }else if($level == '2'){
            $text = "Wakil Penanggung Jawab";
        }else if($level == '3'){
            $text = "Dalnis/Supervisor";
        }else if($level == '4'){
            $text = "Ketua Tim";
        }else if($level == '5'){
            $text = "Anggota";
        }
        return $text;
    }
}

// SIMPEL PKPT

if (!function_exists('str_ruang_lingkup')) {
    function str_ruang_lingkup($unor_id = '',$nama_unor = '')
    {
        $sum = 0;
        $unor_id = pg_to_array($unor_id);
        if(!empty($unor_id)){
             if(count($unor_id) < 5){
                $unor_ = multi_pg_to_array($nama_unor);
                $_unor = '';
                foreach($unor_[0] as $row){$_unor .= "<br>".$row;}
            }else {
                $sum = count($unor_id);
            }
        }
        if(empty($_unor))
        {
            return $sum." OPD";
        }else{
            return $_unor;
        }
    }
}

if (!function_exists('str_hari')) {
    function str_hari($rmp = '',$rpl = '')
    {
        $date1 = strtotime($rmp);
        $date2 = strtotime($rpl);
        $jarak = $date2 - $date1;
        $hari = $jarak / 60 / 60 / 24;
        
        return $hari." Hari";
    }
}

if (!function_exists('str_jumlah')) {
    function str_jumlah($pj= '',$wkpj = '', $kt = '', $at= '')
    {   
        return $pj+$wkpj+$kt+$at;
    }
}

if (!function_exists('str_laporan')) {
    function str_laporan($jumlah_laporan='')
    {   
        return $jumlah_laporan." LHP";
    }
}

if (!function_exists('array_to_ul')) {
    function array_to_ul($data_array)
    {
        $text = "<ol>";
        if (!empty($data_array)) {
            foreach($data_array as $da){
                $text .= "<li>".$da."</li>";
            }          
        }
        $text .= "</ol>";
        return $text;
    }
}

// SIMPEL USER
if (!function_exists('name')) {
    function _name($name='')
    {
        return ucwords(strtolower($name));
    }
}


if (!function_exists('nama_gelar')) {
    function nama_gelar($name='', $gl_d='', $gl_b='') 
    { 
        if ($gl_d) {
              $gl_d = $gl_d.'. ';
              $gl_d = str_replace([', ,', '. .'],[',', '.'], $gl_d);
        }
        if ($gl_b) {
            $gl_b = ', '.$gl_b;
            $gl_b = str_replace([", ,", ". ."],[',', '.'], $gl_b);
        }
        return $gl_d._name($name).$gl_b;
    }
}

if (!function_exists('_str_limit')) {
    function _str_limit($str='',$limit='')
    {
        if ($str) {
            $str = substr($str,0,$limit);
        }else {
            $str = '?';
        }

        return $str;
    }
}


// SIMPEL DAFT PKP
if (!function_exists('lama_waktu')) {
    function lama_waktu($start_date,$end_date,$riwayat_end_date) {
        $tanggal1 = tanggal_indo($start_date,'tanggal');
        $tanggal2 = tanggal_indo($end_date,'tanggal');
        $tanggal3 = tanggal_indo($riwayat_end_date,'tanggal');
        $bulan1 = bulan(date('m', strtotime($start_date)));
        $bulan2 = bulan(date('m', strtotime($end_date)));
        $bulan3 = bulan(date('m', strtotime($riwayat_end_date)));    
        if(!empty($jenis_format->riwayat_end_date)) {
            if($bulan1 != $bulan3){
                $text = "Tanggal ".tanggal_indo($start_date)." s/d ".tanggal_indo($riwayat_end_date)." ".$bulan3." ".date('Y',strtotime($riwayat_end_date))." (".hitung_tanggal($start_date,$riwayat_end_date)." Hari)"; 
            }else{
                $text = "Tanggal ".$tanggal1." s/d ".$tanggal3." ".$bulan3." ".date('Y',strtotime($riwayat_end_date))." (".hitung_tanggal($start_date,$riwayat_end_date)." Hari)"; 
            }
        } else {
            if($bulan1 != $bulan2){
                $text = "Tanggal ".tanggal_indo($start_date)." s/d ".tanggal_indo($end_date)." ".$bulan2." ".date('Y',strtotime($end_date))." (".hitung_tanggal($start_date,$end_date)." Hari)"; 
            }else{
                $text = "Tanggal ".$tanggal1." s/d ".$tanggal2." ".$bulan2." ".date('Y',strtotime($end_date))." (".hitung_tanggal($start_date,$end_date)." Hari)"; 
            }
        }
        
        return $text;
    }
}
if (!function_exists('nomor_surat')) {
    function nomor_surat($no2='',$type='')
    {
        if($type == "kertas_kerja"){
            if(!empty($no2)){
                return $no2;
            }else{
                return "<span class='badge badge-glow bg-warning'>Belum Ada Nomor</span>";
            }
        }else if($type == "pkp"){     
            if(!empty($no2)){
                return "700/".$no2."/PKP/"."Inspek-".date('Y');
            }else {
                return "<span class='badge badge-glow bg-warning'>Belum Ada Nomor</span>";
            }
        }else if($type == "surat_tugas") {
            if(!empty($no2)){
                return "700/".$no2."/"."Inspek-".date('Y');
            }else {
                return "<span class='badge badge-glow bg-warning'>Belum Ada Nomor</span>";
            }
        }
        
    }
}

if (!function_exists('check_draft_verifikasi')) {
    function check_draft_verifikasi($is_dalnis,$is_wanjab,$is_pejab){
        
        $a = '';
        if(empty($is_dalnis)){
            $a .= 'Dalnis,';
        }
        if(empty($is_wanjab)){
            $a .= 'Wakil Penanggung Jawab,';
        }
        if(empty($is_pejab)){
            $a .= 'Penanggung Jawab';
        }

        return $a;
    }
}
if (!function_exists('tabel_icon_pkp')) {
    function tabel_icon_pkp($id, $session_id, $link_url = '', $keyid = '', $level_tugas =  '', $action = '')
    {
        $a = '';

        if ($id !== $session_id) {
            if ($keyid !== '') {
                $id = encrypt_url($id, $keyid);
            }

            $a_tag = 'a';
            $link_url = 'href="' . base_url($link_url . $id) . '"';

            if($level_tugas != '4'){
                if($action == "tanggapi" ){
                    $a = '<' . $a_tag . ' ' . $link_url .' class="btn btn-icon btn-icon rounded-circle btn-flat-secondary" title="Tanggapi/Ekspose" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i data-feather="alert-circle"></i>
                            </' . $a_tag . '>';
                }
            }else if($level_tugas == "4"){
                if($action == "langkah_kerja" ){
                $a = '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-info" title="Langkah Kerja" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i data-feather="file"></i>
                        </' . $a_tag . '>';
                }
                if($action == "edit" ){
                $a = '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i data-feather="edit"></i>
                        </' . $a_tag . '>';
                }
                if($action == "hapus" ){
                $a = '<' . $a_tag . ' ' . $link_url . ' class="button-hapus btn btn-icon btn-icon rounded-circle btn-flat-danger" title="Hapus" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                    <span class="btn-icon-wrap"><i data-feather="trash"></i></span>
                </' . $a_tag . '>'; 
                }
            }
            if($action == "lihat" ){
                $a = '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">  
                            <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                        </' . $a_tag . '>';
            }
        }
        return $a;
    }
}
if (!function_exists('durasi')) {
    function durasi($start_date = '', $end_date = ''){
         $span = tanggal_indo($start_date).' s.d '.tanggal_indo($end_date);
        return $span;
    }
}
if (!function_exists('status_draft_pkp')) {
    function status_draft_pkp($is_verifikasi_ketua = '', $is_tanggapan_dalnis = '', $is_tanggapan_wanjab = '',$is_tanggapan_pejab = ''){
        $span = '';

        if(!empty($is_tanggapan_pejab)){
            $span = 'Menunggu verifikasi penanggung jawab';
        }else if(!empty($is_tanggapan_wanjab)){
            $span = 'Menunggu verifikasi wanjab';
        }else if(!empty($is_tanggapan_dalnis)){
            $span = 'Menunggu verifikasi dalnis';
        }else if(!empty($is_verifikasi_ketua)){
            $span = 'Menunggu tanggapan anggota';
        }
        return $span;
    }
}



//Simpel Surat Tugas
if (!function_exists('tabel_icon_surat_tugas')) {
    function tabel_icon_surat_tugas($id, $session_id, $link_url = '', $keyid = '', $level_tugas =  '', $action = '', $type='', $pkp_id='',$session_level='')
    {
        $a = '';
        if ($id !== $session_id) {
            if ($keyid !== '') {
                $id = encrypt_url($id, $keyid);
            }
            $a_tag = 'a';
            $link_url = 'href="' . base_url($link_url . $id) . '"';

            if($level_tugas == '5'){
                if($action == "tanggapi" ){
                    $a = '<' . $a_tag . ' ' . $link_url .' class="btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Tanggapi" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i data-feather="external-link"></i>
                            </' . $a_tag . '>';
                }
            
            }else if($level_tugas == "4" && $type == 1){
                    if($action == "tambah" ){
                    $a = '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-green" title="Buat PKP" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i data-feather="file"></i>
                            </' . $a_tag . '>';
                    }
            }
            else if($level_tugas == "3"){
                if($action == "edit" ){
                    $a = '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i data-feather="edit"></i>
                            </' . $a_tag . '>';
                    }
                    if($action == "hapus" ){
                    $a = '<' . $a_tag . ' ' . $link_url . ' class="button-hapus btn btn-icon btn-icon rounded-circle btn-flat-danger" title="Hapus" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                        <span class="btn-icon-wrap"><i data-feather="trash"></i></span>
                    </' . $a_tag . '>'; 
                    }
            }
                if($action == "lihat" ){
                    $a = '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat" data-plugin="tippy" data-tippy-placement="bottom" target="_blank" data-tippy-size="small">
                                <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                            </' . $a_tag . '>';
                }
            
        }
        return $a;
    }
    
}

if (!function_exists('str_final')) {
    function str_final($is_final)
    {
        $a = '';

        if ($is_final == '1') {
            $a = '<span class="badge bg-success">Sudah Verifikasi</span>';
        } else {
            $a = '<span class="badge bg-danger">Belum Verifikasi</span>';
        }

        return $a;
    }
}
if (!function_exists('str_reviu')) {
    function str_reviu($is_ketua)
    {
        $a = '';

        if ($is_ketua == '1') {
            $a = '<span class="badge bg-success">Sudah Direviu</span>';
        } else {
            $a = '<span class="badge bg-danger">Belum Direviu</span>';
        }

        return $a;
    }
}
if (!function_exists('str_type')) {
    function str_type($type)
    {
        $a = '';

        if ($type == '1') {
            $a = '<span class="badge bg-dark">Perencanaan</span>';
        } else if ($type == '2') {
            $a = '<span class="badge bg-secondary">Pelaksanaan</span>';
        }else{
            $a = '<span class="badge bg-light text-dark">Pelaporan</span>';
            
        }

        return $a;
    }
}
if (!function_exists('concat')) {
    function concat($start_date,$end_date)
    {
        $a = format_tgl_ind($start_date).' s.d '.format_tgl_ind($end_date);

        return $a;
    }
}
if (!function_exists('encrypt_array_string')) {
    function encrypt_array_string($decryptedArray, $keyString)
    {
        return array_map(function($row) use($keyString)  {
            return encrypt_url($row, $keyString);
        }, $decryptedArray);
    }
}

if (!function_exists('encrypt_array_object_string')) {
    function encrypt_array_object_string($decryptedArray, $keyString,$position)
    {
        $pgarray_data = json_decode($decryptedArray,true);
        $json_data = $pgarray_data['data_kk'];
        $new_array = array();
        $no=1;
        

        foreach ($json_data as  $value) {
            $new_array[] = [
                'f1' => encrypt_url($value['f1'],$keyString),
                'f2' => $value['f2'],
                'f3' => $value['f3'],
                'f4' => $value['f4'],
                'f5' => $position
            ];
        }
        return $new_array;
    }
}


if (!function_exists('decrypt_array_string')) {
    function decrypt_array_string($encryptedArray, $keyString)
    {
        return array_map(function($row) use($keyString)  {
            return decrypt_url($row, $keyString);
        }, $encryptedArray);
    }
}

if (!function_exists('data_expl_json')) {
    function data_expl_json($json, $label_key, $label_callback = null)
    {
        $a = '';
        $a_array = array();
        if ($json) {
            $json_array = json_decode($json, true);
            $label_key = explode("|", $label_key);
            $no = 1;
            $a .= "<ol>";
            for ($i = 0; $i < count($json_array); $i++) {
                if(function_exists($label_callback)) {
                    $label = call_user_func_array($label_callback, array_intersect_key($json_array[$i], array_flip($label_key)));
                }else{
                    $label = "";
                    foreach ($label_key as $row) {
                        $label .= $json_array[$i][$row];
                    }
                }
               
                $a .= "<li>" . $label . "</li>";
            } 
            $a .= "</ol>";
        } else {
            $a = 'Tidak ada data';
        }


        return $a;
    }
}

if (!function_exists('data_expl_json_encrypt')) {
    function data_expl_json_encrypt($json, $label_key, $label_callback = null)
    {
        $a = '';
        $a_array = array();
        if ($json) {
            $json_array = json_decode($json, true);
            $label_key = explode("|", $label_key);
            for ($i = 0; $i < count($json_array); $i++) {
                if(function_exists($label_callback)) {
                    $label = call_user_func_array($label_callback, array_intersect_key($json_array[$i], array_flip($label_key)));
                    array_push($a_array, $label);
                    
                }else{
                    $label = "";
                    foreach ($label_key as $row) {
                        array_push($a_array, $json_array[$i][$row]);
                    } 
                    // var_dump($label);
                }
            } 
        } else {
            $a = 'Tidak ada data';
        }
        return $a_array;
    }
}

if (!function_exists('data_expl_array_encrypt')) {
    function data_expl_array_encrypt($json_array, $label_key, $key_id, $label_callback = null)
    {
        $a_array = array();
        if ($json_array) {
            $label_key = explode("|", $label_key);
            
            foreach($json_array as $row) {
                $a_array[] = [
                    $label_key[0] => encrypt_url($row[$label_key[0]],$key_id),
                    $label_key[1] => $row[$label_key[1]],
                ];
            } 
        }
        return $a_array;
    }
}

if (!function_exists('index_tujuan')) {
    function index_tujuan($index, $tujuan)
    {
        $a = $index;
        if(!empty($tujuan)) {
            $a .= "<br/>" . "<b>Tujuan: </b>" . $tujuan;
        }

        return $a;
    }
}

// Langkah kerja
if (!function_exists('header_dt_lk')) {
    function header_dt_lk($judul_pkp, $nomor_pkp, $nama_jenis)
    {
        (!empty($nomor_pkp)) ? $nomor_pkp = $nomor_pkp : $nomor_pkp = "Belum Ada Nomor";
        $a = "Judul PKP : ".$judul_pkp." <br/>Nomor PKP : ".$nomor_pkp;
        return $a;
    }
}
if (!function_exists('badge_text_langkah_kerja')) {
    function badge_text_langkah_kerja($text, $kegiatan_langkah_kerja = '')
    {
        $a = '';
        if(!empty($text)) {
            if($kegiatan_langkah_kerja !== ''){
                $a = $text.'<br>'.str_kegiatan_langkah_kerja($kegiatan_langkah_kerja);
            }else {
                $a = '<div class="alert alert-dark h5">'.$text.'</div>';
            }
        }

        return $a;
    }
}

if (!function_exists('convert_text_to_link')) {
    function convert_text_to_link($text)
    {
        $_change = '';
        if(!empty($text)) {
            $strlower = strtolower($text);
            $_change = str_replace(" ","_",$strlower);
        }

        return $_change;
    }
}

if (!function_exists('tabel_icon_langkah_kerja')) {
    function tabel_icon_langkah_kerja($id, $session_id, $action, $keyid = '', $nama_jenis= '', $jenis_pengawasan ='', $pkp_id ='', $id_kk ='', $unor_id ='', $kegiatan_reviu='', $jenis_nhp ='')
    {
        $a = '';
            $url_data = array();
            
            if ($keyid !== '') {
                $_id = encrypt_url($id, $keyid);
            }
            if($pkp_id !== '' && $jenis_pengawasan !== '' && $id_kk !== '' && $unor_id !== ''){
                if($kegiatan_reviu !== ''){
                    array_push($url_data,(int)$pkp_id,(int)$jenis_pengawasan,(int)$id_kk, (int)$unor_id, (int)$id);
                }else{
                    array_push($url_data,(int)$pkp_id,(int)$jenis_pengawasan,(int)$id_kk, (int)$unor_id, (int)$id);
                }
            }

            if(!empty($url_data)) {
                $id_ = encrypt_url(array_to_pg($url_data),$keyid);
            }
          
            $link_url = "";
            $text_link = convert_text_to_link($nama_jenis);
            if($text_link !== "") {
                $a_tag = 'a';
                if(!empty($kegiatan_reviu)){
                    if($nama_jenis == 'Reviu Khusus'){
                        $link_url = 'href="' . base_url(url_is_reviu($kegiatan_reviu)."/nhp_reviu_khusus/add/".$id_) . '"';
                    }elseif($nama_jenis == 'Audit Ketaatan'){
                        $link_url = 'href="' . base_url(url_is_reviu($kegiatan_reviu)."/nhp_audit_ketaatan/add/".$id_) . '"';
                    }elseif($jenis_pengawasan == "4"){
                        if($kegiatan_reviu == "nhp"){
                            $link_url = 'href="' . base_url(url_is_reviu($kegiatan_reviu)."/nhp_reviu/add/".$id_) . '"';
                        }else if($kegiatan_reviu == "lhp"){
                            $link_url = 'href="' . base_url(url_is_reviu($kegiatan_reviu)."/lhp_reviu/add/".$id_) . '"';
                        }
                    }
                }else{
                    $link_url = 'href="' . base_url("/kertas_kerja/".$text_link."/add/".$id_) . '"';
                }
            }else {
                $a_tag = 'a';
                $link_url = 'href="' . base_url("/langkah_kerja/view/".$_id) . '"';
            }
            if ($action == "add") {
                $a .= '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-info" title="Buat Kertas Kerja" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i data-feather="edit"></i>
                        </' . $a_tag . '>';

            }

        return $a;
    }
}


if (!function_exists('is_json')) {
    function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

//tabel icon kertas kerja cash opname
if (!function_exists('tabel_icon_cash_opname')) {
    function tabel_icon_cash_opname($id, $session_id, $action, $link_url = '', $keyid = '', $modal_name = '', $attr =  '',$pembuat='',$user_id='')
    {
        $a = '';

        if ($id !== $session_id) {
            if ($keyid !== '') {
                $id = encrypt_url($id, $keyid);
            }

            if ($link_url !== '') {
                $a_tag = 'a';
                $link_url = 'href="' . base_url($link_url . $id) . '"';
            } 
            
             
             if ($action == "tanggapi") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-edit btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Tanggapi" data-bs-toggle="tooltip" data-bs-placement="top"  data-id="' . $id . '">
                                                <i data-feather="edit"></i>
                        </' . $a_tag . '>';
            } elseif ($action == "reset") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-reset-pass btn btn-icon btn-info btn-xs" title="Reset" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                                                <span class="btn-icon-wrap"><i class="mdi mdi-autorenew"></i></span>
                        </' . $a_tag . '>';
            } elseif ($action == "add") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="btn btn-icon btn-icon rounded-circle btn-info" title="Tambah" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i data-feather="edit"></i>
                        </' . $a_tag . '>';
            } elseif ($action == "lihat") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-lihat btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat" data-bs-toggle="tooltip" data-bs-placement="top"  data-id="' . $id . '">
                            <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                        </' . $a_tag . '>';
            }
            if($pembuat==$user_id){
                if ($action == "edit") {
                    $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-edit btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top"  data-id="' . $id . '">
                                                    <i data-feather="edit"></i>
                            </' . $a_tag . '>';
                }else if ($action == "hapus") {
                    $a = '<' . $a_tag . ' ' . $link_url . '  '. $attr .' class="button-hapus btn btn-icon btn-icon rounded-circle btn-flat-danger" title="Hapus" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                                <span class="btn-icon-wrap"><i data-feather="trash"></i></span>
                            </' . $a_tag . '>';
                }
            }
        }

        return $a;
    }
    
}

// kk reviu identifikasi 
if (!function_exists('tabel_icon_reviu')) {
    function tabel_icon_reviu($id, $session_id, $action='', $keyid = '', $level_tugas =  '', $is_final = '', $penyusun_pegawai_id = '', $level_pemeriksa = '', $type ='')
    {
        $a = '';

        if ($id !== $session_id) {
            if ($keyid !== '') {
                $id = encrypt_url($id, $keyid);
            }
            
            $action = explode("|", $action);
            $a_tag = 'a';
            
            if(!empty($action)){
                if($session_id == $penyusun_pegawai_id){ 
                    if($is_final != "1"){
                        if(in_array("edit", $action)){
                            if($type == "pendampingan"){
                                $link_url = 'href="' . base_url("/kertas_kerja/".$type."/edit/") . $id . '/isi_kertas_kerja"';  
                            }else {
                                $link_url = 'href="' . base_url("/kertas_kerja/".$type."/edit/") . $id . '"';  
                            }
                            
                            $a .= '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Edit Kertas Kerja" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i data-feather="edit"></i>
                            </' . $a_tag . '> ';
                        }
                        if(in_array("hapus", $action)){
                            $a .= '<' . $a_tag . ' class="button-hapus btn btn-icon btn-icon rounded-circle btn-flat-danger" title="Hapus" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                                <span class="btn-icon-wrap"><i data-feather="trash"></i></span>
                            </' . $a_tag . '>';
                        }  
                    }
                    if(in_array("show", $action)){
                        $link_url = 'href="' . base_url("/kertas_kerja/".$type."/show/") . $id. '"';  
                        $a .= '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">  
                            <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                        </' . $a_tag . '> ';
                    }
                }else {
                     if(in_array("show", $action)){
                        $link_url = 'href="' . base_url("/kertas_kerja/".$type."/show/") . $id . '"';  
                        $a .= '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">  
                            <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                        </' . $a_tag . '> ';
                    }
                    if($level_tugas == $level_pemeriksa){
                        if(in_array("periksa", $action)){
                            $link_url = 'href="' . base_url("/kertas_kerja/".$type."/periksa/") . $id . '"';  
                            
                            $a .= '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Edit Kertas Kerja" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i data-feather="edit"></i>
                            </' . $a_tag . '> ';
                        }
                    }
                }
                // if(array_search('edit',$action,true)){
                   
                // }
            }           
        }

        return $a;
    }
}

if (!function_exists('checker')) {
    function checker($is_final = '' , $nama_pegawai ='', $gelar_dpn= '', $gelar_blkng= '')
    {
        $a = '';
        if(empty($is_final)) {
            $a = nama_gelar($nama_pegawai, $gelar_dpn, $gelar_blkng);
        }else {
            $a = '<span class="badge badge-glow bg-success">Selesei Diperiksa</span>';
        }
        return $a;
    }
}

if (!function_exists('check_reviu')) {
    function check_reviu($is_final = '')
    {
        $a = '';
        if($is_final == '2') {
            $a = 'Identifikasi';
        }else if($is_final == "3") {
            $a = 'Analisis';
        }else if($is_final == "4") {
            $a = 'Evaluasi';
        }
        return $a;
    }
}

if (!function_exists('str_files')) 
{
    function str_files($path, $filename)
    {
        $a = '';
        if (!empty($filename)) {
            $ci = &get_instance();

            $a = base_url($ci->data['files_path'] . $path . $filename);
        } else {
            $a = "";
        }

        return $a;
    }
}

if (!function_exists('str_files')) 
{
    function str_files($path, $filename)
    {
        $a = '';
        if (!empty($filename)) {
            $ci = &get_instance();

            $a = base_url($ci->data['files_path'] . $path . $filename);
        } else {
            $a = "";
        }

        return $a;
    }
}

if (!function_exists('str_files_public')) 
{
    function str_files_public($path, $filename)
    {
        $a = '';

        if (!empty($filename)) {
            $ci = &get_instance();
            $a = base_url($ci->data['public_files'] . $path . $filename);
        } else {
            $a = "";
        }

        return $a;
    }
}
if (!function_exists('check_available_reviu')) 
{
    function check_available_reviu($key, $data, $position, $key_before)
    {
        $result = '';
        if (!empty($data)) {
            if(in_array($key, $data)){
                $result = "disabled";
            }else {
                if(!in_array($key_before, $data)){
                    $result = "disabled";                    
                }
            }
        }
        return $result;
    }
}
//tabel icon kertas kerja koreksi
if (!function_exists('tabel_icon_koreksi')) {
    function tabel_icon_koreksi($id, $session_id, $action, $link_url = '', $keyid = '', $modal_name = '', $attr =  '')
    {
        $a = '';

        if ($id !== $session_id) {
            if ($keyid !== '') {
                $id = encrypt_url($id, $keyid);
            }

            if ($link_url !== '') {
                $a_tag = 'a';
                $link_url = 'href="' . base_url($link_url . $id) . '"';
            } 
            if ($action == "hapus") {
                $a = '<' . $a_tag . ' ' . $link_url . '  '. $attr .' class="button-hapus btn btn-icon btn-icon rounded-circle btn-flat-danger" title="Hapus" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                            <span class="btn-icon-wrap"><i data-feather="trash"></i></span>
                        </' . $a_tag . '>';
            } elseif ($action == "edit") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-edit btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top"  data-id="' . $id . '">
                                                <i data-feather="edit"></i>
                        </' . $a_tag . '>';
            } elseif ($action == "tanggapi") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-edit btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Tanggapi" data-bs-toggle="tooltip" data-bs-placement="top"  data-id="' . $id . '">
                                                <i data-feather="edit"></i>
                        </' . $a_tag . '>';
            } elseif ($action == "reset") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-reset-pass btn btn-icon btn-info btn-xs" title="Reset" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                                                <span class="btn-icon-wrap"><i class="mdi mdi-autorenew"></i></span>
                        </' . $a_tag . '>';
            } elseif ($action == "add") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="btn btn-icon btn-icon rounded-circle btn-info" title="Tambah" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i data-feather="edit"></i>
                        </' . $a_tag . '>';
            } elseif ($action == "lihat") {
                $a = '<' . $a_tag . ' ' . $link_url . ' '. $attr .' class="button-lihat btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat" data-bs-toggle="tooltip" data-bs-placement="top"  data-id="' . $id . '">
                            <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                        </' . $a_tag . '>';
            }
        }

        return $a;
    }   
}

if (!function_exists('tabel_icon_reviu_khusus')) {
    function tabel_icon_reviu_khusus($id, $session_id, $action, $link_url = '', $keyid = '', $unor_id ='',$pkp_id ='',$pembuat='',$user_id='')
    { 
        $a = '';
        if ($id !== $session_id) {
           
            $url_data = array();
            if($action == 'lihat'){
                if($pkp_id !== '' && $unor_id !== ''){
                    array_push($url_data,(int)$pkp_id, (int)$unor_id);
                }
                if(!empty($url_data)) {
                    $id = array_to_pg($url_data);
                    $id = encrypt_url($id,$keyid);
                    $link_url = 'href="' . base_url("/kertas_kerja/reviu_khusus/show/".$id) . '"';

                }
            }else{
                if ($link_url !== '') {
                    $a_tag = 'a';
                    $id = encrypt_url($id,$keyid);
                    $link_url = 'href="' . base_url($link_url . $id) . '"';
                }
            }
            

            $a_tag = 'a';
            if ($action == "add") {
                $a = '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-info" title="Buat Kertas Kerja" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i data-feather="edit"></i>
                        </' . $a_tag . '>';
            } else if ($action == "lihat") {
                $a = '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat Langkah Kerja" data-bs-toggle="tooltip" data-bs-placement="top">
                            <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                        </' . $a_tag . '>';
            }
            if($pembuat==$user_id){
                if ($action == "edit") {
                    $a = '<' . $a_tag . ' ' . $link_url . ' class="button-edit btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top"  data-id="' . $id . '">
                                                    <i data-feather="edit"></i>
                            </' . $a_tag . '>';
                }else if ($action == "hapus") {
                    $a = '<' . $a_tag . ' ' . $link_url . '  class="button-hapus btn btn-icon btn-icon rounded-circle btn-flat-danger" title="Hapus" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                                <span class="btn-icon-wrap"><i data-feather="trash"></i></span>
                            </' . $a_tag . '>';
                }
            }

        return $a;
        }
    }
}

if (!function_exists('str_kegiatan_langkah_kerja')) {
    function str_kegiatan_langkah_kerja($kegiatan_langkah_kerja){
        $a = '';
        if($kegiatan_langkah_kerja == 'kk_identifikasi_perancangan'){
            $a = "Kertas Kerja Identifikasi Perancangan";
        }else if($kegiatan_langkah_kerja == 'kk_identifikasi'){
            $a = "Kertas Kerja Identifikasi";
        }else if($kegiatan_langkah_kerja == 'kertas_kerja_konsep_pkp'){
            $a = "Kertas Kerja Konsep PKP";
        }else if($kegiatan_langkah_kerja == 'konsep_pkp'){
            $a = "Konsep PKP";
        }else if($kegiatan_langkah_kerja == 'ekspose_pkp'){
            $a = "Ekspose PKP";
        }else if($kegiatan_langkah_kerja == 'kk_analisis'){
            $a = "Kertas Kerja Analisis";
        }else if($kegiatan_langkah_kerja == 'kk_evaluasi'){
            $a = "Kertas Kerja Evaluasi";
        }else if($kegiatan_langkah_kerja == 'nhp'){
            $a = "Naskah Hasil Pengawasan";
        }else if($kegiatan_langkah_kerja == 'kk_bahan_konsep_lhp'){
            $a = "Kertas Kerja Bahan Konsep LHP";
        }else if($kegiatan_langkah_kerja == 'kk_konsep_lhp'){
            $a = "Kertas Kerja Konsep LHP";
        }else if($kegiatan_langkah_kerja == 'kk_reviu_konsep'){
            $a = "Kertas Kerja Reviu Konsep LHP";
        }else if($kegiatan_langkah_kerja == 'lhp'){
            $a = "Laporan Hasil Pengawasan";
        }else if($kegiatan_langkah_kerja == 'reviu_pkp'){
            $a = "Reviu PKP";
        }
        return $a; 
    }
}   

if (!function_exists('str_is_reviu_kegiatan_langkah_kerja')) {
    function str_is_reviu_kegiatan_langkah_kerja($kegiatan_langkah_kerja){
        $a = '';
        if($kegiatan_langkah_kerja == 'kk_identifikasi'){
            $a = "2";
        }else if($kegiatan_langkah_kerja == 'kk_analisis'){
            $a = "3";
        }else if($kegiatan_langkah_kerja == 'kk_evaluasi'){
            $a = "4";
        }else if($kegiatan_langkah_kerja == 'kk_identifikasi_perancangan'){
            $a = "8";
        }else if($kegiatan_langkah_kerja == 'kertas_kerja_konsep_pkp'){
            $a = "9";
        }else if($kegiatan_langkah_kerja == 'kk_bahan_konsep_lhp'){
            $a = "10";
        }else if($kegiatan_langkah_kerja == 'kk_konsep_lhp'){
            $a = "11";
        }else if($kegiatan_langkah_kerja == 'kk_reviu_konsep'){
            $a = "12";
        }else if($kegiatan_langkah_kerja == 'reviu_pkp'){
            $a = "13";
        }
        return $a; 
    }
}     

if (!function_exists('url_is_reviu')) {
    function url_is_reviu($kegiatan_langkah_kerja){
        $a = '';

        if($kegiatan_langkah_kerja == 'kk_identifikasi_perancangan'){
            $a = "kertas_kerja/reviu/add/";
        }else if($kegiatan_langkah_kerja == 'kk_identifikasi'){
            $a = "kertas_kerja/reviu/add/";
        }else if($kegiatan_langkah_kerja == 'kertas_kerja_konsep_pkp'){
            $a = "kertas_kerja/konsep_pkp/add/";
        }else if($kegiatan_langkah_kerja == 'konsep_pkp'){
            $a = "pkp/draft_pkp/check/";
        }else if($kegiatan_langkah_kerja == 'ekspose_pkp'){
            $a = "pkp/draft_pkp/view/";
        }else if($kegiatan_langkah_kerja == 'kk_analisis'){
            $a = "kertas_kerja/reviu/add/";
        }else if($kegiatan_langkah_kerja == 'kk_evaluasi'){
            $a = "kertas_kerja/reviu/add/";
        }else if($kegiatan_langkah_kerja == 'nhp'){
            $a = "nhp";
        }else if($kegiatan_langkah_kerja == 'kk_bahan_konsep_lhp'){
            $a = "kertas_kerja/lhp/bahan_konsep/";
        }else if($kegiatan_langkah_kerja == 'kk_konsep_lhp'){
            $a = "kertas_kerja/lhp/konsep/";
        }else if($kegiatan_langkah_kerja == 'kk_reviu_konsep'){
            $a = "kertas_kerja/koreksi_reviu/add/";
        }else if($kegiatan_langkah_kerja == 'lhp'){
            $a = "lhp";
        }else if($kegiatan_langkah_kerja == 'reviu_pkp'){
            $a = "kertas_kerja/reviu/";
        }
        return $a; 
    }
}

if (!function_exists('array_debug')) {
    function array_debug($data)
    {
        return "<pre>".print_r($data, true)."</pre>";
    }
}

if (!function_exists('tabel_icon_nhp')) {
    function tabel_icon_nhp($id, $session_id, $action='', $keyid = '', $level_tugas =  '', $is_final = '', $penyusun_pegawai_id = '', $level_pemeriksa = '',$type = '')
    {
        $a = '';

        if ($id !== $session_id) {
            if ($keyid !== '') {
                $id = encrypt_url($id, $keyid);
            }
            
            $action = explode("|", $action);
            $a_tag = 'a';
            
            if(!empty($action)){
                if($session_id == $penyusun_pegawai_id){ 
                    if($is_final != "1"){
                        if(in_array("edit", $action)){
                            $link_url = 'href="' . base_url("/nhp/".$type."/edit/") . $id . '"';  
                            $a .= '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i data-feather="edit"></i>
                            </' . $a_tag . '> ';
                        }
                        if(in_array("hapus", $action)){
                            $a .= '<' . $a_tag . ' class="button-hapus btn btn-icon btn-icon rounded-circle btn-flat-danger" title="Hapus" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                                <span class="btn-icon-wrap"><i data-feather="trash"></i></span>
                            </' . $a_tag . '>';
                        }  
                    }
                    if(in_array("show", $action)){
                        $link_url = 'href="' . base_url("/nhp/".$type."/show/") . $id. '"';  
                        $a .= '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">  
                            <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                        </' . $a_tag . '> ';
                    }
                }else {
                     if(in_array("show", $action)){
                        $link_url = 'href="' . base_url("/nhp/".$type."/show/") . $id . '"';  
                        $a .= '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">  
                            <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                        </' . $a_tag . '> ';
                    }
                }
                // if(array_search('edit',$action,true)){
                   
                // }
            }           
        }

        return $a;
    }
}

if (!function_exists('check_pemeriksa_nhp')) {
    function check_pemeriksa_nhp($nama_pegawai = '',$gelar_depan = '',$gelar_blkng ='', $is_ketua = '', $is_final = '')
    {   
        $a = '';
        if(empty($is_ketua)){
            $a = "<span class='badge rounded-pill badge-light-danger'>Masih dalam draft ketua</span>";
        }elseif($is_final == "0"){
            $a = "<span class='badge rounded-pill badge-light-warning'>Sedang diberi nomor oleh ketua</span>";
        }elseif($is_final == "1") {
            $a = "<span class='badge rounded-pill badge-light-success'>Telah diperiksa</span>";
        }else{
            $a = nama_gelar($nama_pegawai,$gelar_depan,$gelar_blkng);
        }
        return $a;
    }
}

if (!function_exists('tabel_icon_lhp')) {
    function tabel_icon_lhp($id, $session_id, $action='', $keyid = '', $level_tugas =  '', $is_final = '', $penyusun_pegawai_id = '', $level_pemeriksa = '',$type = '')
    {
        $a = '';

        if ($id !== $session_id) {
            if ($keyid !== '') {
                $id = encrypt_url($id, $keyid);
            }
            
            $action = explode("|", $action);
            $a_tag = 'a';
            
            if(!empty($action)){
                if($session_id == $penyusun_pegawai_id){ 
                    if($is_final != "1"){
                        if(in_array("edit", $action)){
                            $link_url = 'href="' . base_url("/lhp/".$type."/edit/") . $id . '"';  
                            $a .= '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-warning" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i data-feather="edit"></i>
                            </' . $a_tag . '> ';
                        }
                        if(in_array("hapus", $action)){
                            $a .= '<' . $a_tag . ' class="button-hapus btn btn-icon btn-icon rounded-circle btn-flat-danger" title="Hapus" data-bs-toggle="tooltip" data-bs-placement="top" data-id="' . $id . '" >
                                <span class="btn-icon-wrap"><i data-feather="trash"></i></span>
                            </' . $a_tag . '>';
                        }  
                    }
                    if(in_array("show", $action)){
                        $link_url = 'href="' . base_url("/lhp/".$type."/show/") . $id. '"';  
                        $a .= '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">  
                            <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                        </' . $a_tag . '> ';
                    }
                }else {
                     if(in_array("show", $action)){
                        $link_url = 'href="' . base_url("/lhp/".$type."/show/") . $id . '"';  
                        $a .= '<' . $a_tag . ' ' . $link_url . ' class="btn btn-icon btn-icon rounded-circle btn-flat-primary" title="Lihat" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">  
                            <span class="btn-icon-wrap"><i data-feather="eye"></i></span>
                        </' . $a_tag . '> ';
                    }
                }
                // if(array_search('edit',$action,true)){
                   
                // }
            }           
        }

        return $a;
    }
}