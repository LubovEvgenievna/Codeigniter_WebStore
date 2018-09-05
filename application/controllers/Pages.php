<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_Controller
{
    public function showPage($name)
    {
        $this->load->model('Pages_model');
        $page_array = $this->Pages_model->getByName($name);

        $this->setData('title', $page_array['title']);
        $this->setData('description', $page_array['description']);
        $this->setData('text', $page_array['text']);
        $this->display('frontend/pages/index');
    }
}