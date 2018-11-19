<?php
/**
 * Created by PhpStorm.
 * User: ThisPC
 * Date: 05/01/2018
 * Time: 4:19 SA
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
class Products extends  \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->model('product_model');
    }

    public function index_get() {
        $products= $this->product_model->get_list();
        if($products) {
            $this->response($products, 200);
        }
        else {
            $this->response(NULL, 404);
        }
    }
    public function search_get() {
        if(!$this->get('term')) {
            $this->response(NULL, 400);
        }
        $input = array();
        $input['like'] = array('name', $this->get('term'));
        $list = $this->product_model->get_list($input);

        if($list) {
            $this->response($list, 200); // 200 being the HTTP response code
        }
        else {
            $this->response(NULL, 404);
        }
    }

    public function topmonth_get(){
        if(!$this->get('month') || !$this->get('year')) {
            $this->response(NULL, 400);
        }
        $list = $this->product_model->topmonth($this->get('month'),$this->get('year'));

        if($list) {
            $this->response($list, 200); // 200 being the HTTP response code
        }
        else {
            $this->response(NULL, 404);
        }
    }
    /*
     * Thống kê sl sản phẩm bán ra hàng ngày của tháng
     */
    public function thongkesl_get(){
        if(!$this->get('month') || !$this->get('year')) {
            $this->response(NULL, 400);
        }
        $list = $this->product_model->thongkesl($this->get('month'),$this->get('year'));

        if($list) {
            $this->response($list, 200); // 200 being the HTTP response code
        }
        else {
            $this->response(NULL, 404);
        }
    }

    public function thongketien_get(){
        if(!$this->get('month') || !$this->get('year')) {
            $this->response(NULL, 400);
        }
        $list = $this->product_model->thongketien($this->get('month'),$this->get('year'));

        if($list) {
            $this->response($list, 200); // 200 being the HTTP response code
        }
        else {
            $this->response(NULL, 404);
        }
    }
}