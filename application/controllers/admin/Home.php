<?php
/**
 * Created by PhpStorm.
 * User: ThisPC
 * Date: 21/12/2017
 * Time: 10:20 CH
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
    function index(){
        $this->data['template'] = 'admin/home/index';

        $this->load->view('admin/layout',$this->data);
    }
}