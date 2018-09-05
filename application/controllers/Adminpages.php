<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminpages extends MY_Controller
{
    public function index()
    {
        if ($this->checkAdmin()) {
            $this->load->model('Pages_model');
            $pages = $this->Pages_model->getAll();

            $this->setData('title', 'Редактор');
            $this->setData('pages', $pages);
            $this->display('backend/admin_pages/index');
        } else {
            print_r($this->checkAdmin());
            die('Необходимо войти в систему под учетной записью администратора.');
        }
    }

    public function red($name)
    {
        if ($this->checkAdmin()) {

            if (isset($_POST['submit'])) {

                $text = $this->input->post('text');
                $description = $this->input->post('description');
                $errors = 'Страница обновлена! ';

                $this->load->model('Pages_model');
                $this->Pages_model->updatePage($name,$text, $description);
            } else {
                $errors = 0;
            }

            $this->load->model('Pages_model');
            $about = $this->Pages_model->getByName($name);

            $this->setData('title', 'Редактор');
            $this->setData('about', $about);
            $this->setData('errors', $errors);
            $this->display('backend/admin_pages/red');
        } else {
            print_r($this->checkAdmin());
            die('Необходимо войти в систему под учетной записью администратора.');
        }
    }

    public function addPage()
    {
        if ($this->checkAdmin()) {
        $name = $this->input->post('name');
        $title = $this->input->post('title');

        $config = array(
            array(
                'field'   => 'title',
                'label'   => 'Название',
                'rules'   => 'trim|required|max_length[50]'
            ),
            array(
                'field'   => 'name',
                'label'   => 'Псевдоним',
                'rules'   => 'trim|required'
            ),
        );

        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run()) {
            if (preg_match('#^[A-Za-z0-9]$#',$name)) {
                $this->load->model('Pages_model');
                $this->Pages_model->addPage($name, $title);

                redirect('adminpages');
            }
        }

        redirect('adminpages');
        } else {
            print_r($this->checkAdmin());
            die('Необходимо войти в систему под учетной записью администратора.');
        }
    }

    public function removePage($id) {
        if ($this->checkAdmin()) {
        $this->load->model('Pages_model');
        $this->Pages_model->removePage($id);

        redirect('adminpages');
        } else {
            print_r($this->checkAdmin());
            die('Необходимо войти в систему под учетной записью администратора.');
        }
    }

    public function redGeneral()
    {
        if ($this->checkAdmin()) {

            $this->load->model('Slider_model');
            $sliders = $this->Slider_model->getAll();
            $this->setData('sliders', $sliders);

            $errors = 0;

            if (isset($_POST['submit'])) {
                $id = $this->input->post('id');
                $img = $this->input->post('img');
                $this->load->helper(array('form', 'url'));

                $this->load->library('upload');

                $config['upload_path'] = 'assets/images/slider/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_width'] = 2048;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('image')) {
                    unlink('assets/images/slider/' . $img);

                    $data = array('upload_data' => $this->upload->data());
                    $image = $data['upload_data']['file_name'];

                    $this->Slider_model->updateById($id, $image);

                    $errors = 'Слайдер обновлен';
                    redirect('adminpages/redGeneral');
                }
            }


                $this->setData('title', 'Редактор');
                $this->setData('errors', $errors);
                $this->display('backend/admin_pages/redGeneral');
        } else {
            print_r($this->checkAdmin());
            die('Необходимо войти в систему под учетной записью администратора.');
        }

    }
}