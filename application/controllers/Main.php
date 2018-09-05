<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller
{
    public function index()
    {
        $this->setData('title', 'Online-Shop');
        $this->display('frontend/general/index');
    }
}