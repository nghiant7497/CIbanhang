<?php
/**
 * Created by PhpStorm.
 * User: ThisPC
 * Date: 03/01/2018
 * Time: 8:18 CH
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MY_Controller
{
    function __construct() {
        parent::__construct();

    }

    /*
     * Them sp vào giỏ hàng
     */
    function add(){
        $id = $this->input->post('product_id');
        //$id = $this->uri->rsegment(3);
        $this->load->model('product_model');
        $product = $this->product_model->get_info($id);
        if(!$product){
            exit(json_encode(array(
                'error' => true,
                'mess' => 'Product does not exist.'
            )));
        }

        //tổng số sp
        $qty = 1;

        $price = $product->price;
        if($product->discount > 0){
            $price = $product->price - $product->discount;
        }
        $data = array();
        $data['id'] = $product->id;
        $data['qty'] = $qty;
        $data['name'] = url_title($product->name);
        $data['image_link'] = $product->image_link;
        $data['price'] = $price;
        $this->cart->insert($data);

        echo $this->show_cart();

        /*if(!empty($_SERVER['HTTP_REFERER'])){
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect('cart');
        }*/
    }

    /*
     * Danh sách sản phẩm trong giỏ hàng
     */
    function index(){
        //load view
        $this->data['template'] = 'front/cart/index';
        $this->load->view('front/layout', $this->data);
    }

    /*
     * Cập nhật giỏ hàng
     */
    function update(){
        $carts = $this->cart->contents();
        foreach ($carts as $key => $cart){
            $total_qty = $this->input->post('qty_'.$cart['id']);
            $data = array();
            $data['rowid'] = $key;
            $data['qty'] = $total_qty;
            $this->cart->update($data);
        }

        if(!empty($_SERVER['HTTP_REFERER'])){
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect('cart');
        }
    }

    function delete(){
        $id = intval($this->uri->rsegment(3));

        $carts = $this->cart->contents();
        foreach ($carts as $key => $cart){
            if($cart['id'] == $id){
                $data = array();
                $data['rowid'] = $key;
                $data['qty'] = 0;
                $this->cart->update($data);
            }

        }

        if(!empty($_SERVER['HTTP_REFERER'])){
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect('cart');
        }
    }

    function show_cart(){
        $html = '';

        $this->load->library('cart');

        $html .= '
            <div class="inner-box">
	            <a href="#" title="">
		            <div class="icon-cart">
			            <img src="'.public_url().'/front/images/icons/cart.png" alt="">
			            <span>'.$this->cart->total_items().'</span>
		            </div>
		            <div class="price">
			            '.number_format($this->cart->total()).' đ
		            </div>
	            </a>
	            <div class="dropdown-box">
		            <ul>
        ';

        foreach ($this->cart->contents() as $cart){
            $html .= '
                        <li>
                            <div class="img-product">
                                <img src="'.base_url('upload/product/'.$cart['image_link']).'" alt="">
                            </div>
                            <div class="info-product">
                                <div class="name">
                                    '.$cart['name'].'
                                </div>
                                <div class="price">
                                    <span>'.$cart['qty'].' x</span>
                                    <span>'.number_format($cart['price']).' đ</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <span class="delete"><a href="'.base_url('cart/delete/'.$cart['id']).'" >x</a></span>
                        </li>
            ';
        }
        $html .= '
                    </ul>
                    <div class="total">
                        <span>Subtotal:</span>
                        <span class="price">'.number_format($this->cart->total()).' đ</span>
                    </div>
                    <div class="btn-cart">
                        <a href="'.base_url('cart').'" class="view-cart" title="">View Cart</a>
                        <a href="'.base_url('order/checkout').'" class="check-out" title="">Checkout</a>
                    </div>
                </div>
            </div>
        ';


        return $html;
    }

    function load_cart(){
        echo $this->show_cart();
    }
}