<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error404 extends MY_Controller
{
    public function index()
    {
        $this->setData('title', 'Ошибка');
        $this->display('frontend/error404');
    }
}