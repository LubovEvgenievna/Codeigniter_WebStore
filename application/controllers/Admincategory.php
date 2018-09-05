<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admincategory extends MY_Controller
{
    public function index()
    {
        if ($this->checkAdmin()) {

            $this->load->model('Category_model');
            $category = $this->Category_model->getAll();

            $this->load->model('Uncategory_model');
            $uncategory = $this->Uncategory_model->getAll();

            $this->setData('category', $category);
            $this->setData('uncategory', $uncategory);
            $this->setData('title', 'Админпанель');
            $this->display('backend/admin_category/index');
        } else {
            print_r($this->checkAdmin());
            die('Необходимо войти в систему под учетной записью администратора.');
        }
    }

    public function reduncat($id)
    {
        $this->load->model('Uncategory_model');
        $uncat = $this->Uncategory_model->getById($id);

        echo json_encode($uncat);
    }

    public function safeuncat()
    {
        $title = $this->input->post('title');
        $sort_order = $this->input->post('sort_order');
        $id = $this->input->post('id');

        $config = array(
            array(
                'field'   => 'title',
                'label'   => 'Название',
                'rules'   => 'trim|required|max_length[50]'
            ),
            array(
                'field'   => 'sort_order',
                'label'   => 'Цена',
                'rules'   => 'trim|required'
            ),
        );

        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run()) {
            $this->load->model('Uncategory_model');
            $this->Uncategory_model->updateById($id, $title, $sort_order);

            redirect('admincategory');
        }

        redirect('admincategory');
    }

    public function redcat($id)
    {
        $this->load->model('Category_model');
        $cat = $this->Category_model->getById($id);

        echo json_encode($cat);
    }

    public function safecat()
    {
        $title = $this->input->post('title');
        $sort_order = $this->input->post('sort_order');
        $id = $this->input->post('id');

        $config = array(
            array(
                'field'   => 'title',
                'label'   => 'Название',
                'rules'   => 'trim|required|max_length[50]'
            ),
            array(
                'field'   => 'sort_order',
                'label'   => 'Цена',
                'rules'   => 'trim|required'
            ),
        );

        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run()) {
            $this->load->model('Category_model');
            $this->Category_model->updateById($id, $title, $sort_order);

            redirect('admincategory');
        }

        redirect('admincategory');
    }

    public function removecategory($id) {
        $this->load->model('Category_model');
        $this->Category_model->removeCat($id);

        redirect('admincategory');
    }

    public function removeuncategory($id) {
        $this->load->model('Uncategory_model');
        $this->Uncategory_model->removeUncat($id);

        redirect('admincategory');
    }

    public function addcat(){

        $title = $this->input->post('title');
        $sort_order = $this->input->post('sort_order');

        $config = array(
            array(
                'field'   => 'title',
                'label'   => 'Название',
                'rules'   => 'trim|required|max_length[50]'
            ),
            array(
                'field'   => 'sort_order',
                'label'   => 'Цена',
                'rules'   => 'trim|required'
            ),
        );

        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run()) {
            $this->load->model('Category_model');
            $this->Category_model->addCat($title, $sort_order);

            redirect('admincategory');
        }

        redirect('admincategory');
    }

    public function adduncat(){

        $title = $this->input->post('title');
        $category_id = $this->input->post('category_id');
        $sort_order = $this->input->post('sort_order');

        $config = array(
            array(
                'field'   => 'title',
                'label'   => 'Название',
                'rules'   => 'trim|required|max_length[50]'
            ),
            array(
                'field'   => 'sort_order',
                'label'   => 'Цена',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'category_id',
                'label'   => 'Категория',
                'rules'   => 'trim|required'
            ),
        );

        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run()) {
            $this->load->model('Uncategory_model');
            $this->Uncategory_model->addUncat($title, $category_id, $sort_order);

            redirect('admincategory');
        }

        redirect('admincategory');
    }
}