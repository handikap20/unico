<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

interface ModelInterface {
    public function find($id);
    public function findAll();
    public function save($id = null);
    public function delete($id);
}