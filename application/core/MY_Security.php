<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Security extends CI_Security
{
    public function csrf_set_cookie()
    {
        header("Resp-Hash: " . parent::get_csrf_hash());
        parent::csrf_set_cookie();
    }
}