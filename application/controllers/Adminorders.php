<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminorders extends MY_Controller
{
    public function ord()
    {
        if ($this->checkAdmin()) {
            $this->load->model('Users_orders_model');
            $orderssnum = $this->Users_orders_model->getNum();

            $this->load->model('Shop_model');
            $shop = $this->Shop_model->getAll();

            $this->load->model('Product_model');
            $prodabout=$this->Product_model->getAll();

            $this->load->library('pagination');

            $config['base_url'] = 'http://hookan.local/adminorders/ord';
            $config['total_rows'] = $orderssnum;
            $config['per_page'] = 60;

            $this->pagination->initialize($config);

            $links = $this->renderHTML('links', $contents=0, true);
            $this->setData('links', $links);

            $orders = $this->Users_orders_model->getOrdersAll($config['per_page'],$this->uri->segment(3));

            $prod = array();
            $products = array();
            $i=0;
            foreach ($orders as $value) {
                foreach ($value as $item) {
                    $prod[$value['id']] = json_decode($value['products'], true);
                }
            }

            $ords = array_keys($prod);
            foreach ($prod as $value) {
                $ids = array_keys($value);
                $k=0;
                foreach ($value as $item) {
                    $products[] = array(
                        'id' => $ids[$k],
                        'qty' => $item,
                        'ord' => $ords[$i]
                    );
                    $k++;
                }
                $i++;
            }

            $this->load->model('Users_model');
            $users = $this->Users_model->getAll();

            $this->load->model('Status_model');
            $status = $this->Status_model->getAll();

            $this->setData('title', 'Админпанель');
            $this->setData('shop', $shop);
            $this->setData('orders', $orders);
            $this->setData('products', $products);
            $this->setData('prodabout', $prodabout);
            $this->setData('users', $users);
            $this->setData('status', $status);
            $this->display('backend/admin_orders/index');
        } else {

            die('Необходимо войти в систему под учетной записью администратора.');
        }
    }

    public function nextStatus($status, $id) {
        $this->load->model('Users_orders_model');
        $order = $this->Users_orders_model->nextStatus($status, $id);

        return $order;
    }
}