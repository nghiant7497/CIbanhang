<?php
/**
 * Created by PhpStorm.
 * User: ThisPC
 * Date: 01/01/2018
 * Time: 7:04 CH
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->model('news_model');
    }

    function index() {
        //lay tong so luong ta ca cac bai vai trong websit
        $total_rows = $this->news_model->get_total();
        $this->data['total_rows'] = $total_rows;

        //load ra thu vien phan trang
        $this->load->library('pagination');
        $config = array();
        $config['total_rows'] = $total_rows;
        $config['base_url']   = admin_url('news/index');
        $config['per_page']   = 5;
        $config['uri_segment'] = 4;//phân đoạn số trang trên URL

        //khởi chạy cấu hình phân trang
        $this->pagination->initialize($config);

        $segment = intval($this->uri->segment(4));

        $inp = array();
        $inp['limit'] = array($config['per_page'], $segment);

        //kiem tra co thuc hien loc du lieu hay khong
        $id = $this->input->get('id');
        $id = intval($id);
        $inp['where'] = array();
        if($id > 0) {
            $inp['where']['id'] = $id;
        }
        $title = $this->input->get('title');
        if($title) {
            $inp['like'] = array('title', $title);
        }

        //lay danh sach bai viet
        $newss = $this->news_model->get_list($inp);
        $this->data['newss'] = $newss;

        //lay nội dung của biến message
        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;

        //load view
        $this->data['template'] = 'admin/news/index';
        $this->load->view('admin/layout', $this->data);
    }

    function add() {
        //load thư viện validate dữ liệu
        $this->load->library('form_validation');
        $this->load->helper('form');

        if($this->input->post()) {
            $this->form_validation->set_rules('title', 'Tiêu đề bài viết', 'required');
            $this->form_validation->set_rules('content', 'Nội dung bài viết', 'required');

            //nhập liệu chính xác
            if($this->form_validation->run()) {
                //lay ten file anh minh hoa duoc update len
                $this->load->library('upload_library');
                $upload_path = './upload/news';
                $upload_data = $this->upload_library->upload($upload_path, 'image');
                $image_link = '';
                if(isset($upload_data['file_name'])) {
                    $image_link = $upload_data['file_name'];
                }

                //luu du lieu can them
                $data = array(
                    'title'      => $this->input->post('title'),
                    'image_link' => $image_link,
                    'created'    => date('Y-m-d H:i:s'),
                );
                //them moi vao csdl
                if($this->news_model->create($data)) {
                    //tạo ra nội dung thông báo
                    $this->session->set_flashdata('message', 'Thêm Tin tức thành công!');
                }else{
                    $this->session->set_flashdata('message', 'Không thêm được!');
                }
                //chuyen tới trang danh sách
                redirect(admin_url('news'));
            }
        }


        //load view
        $this->data['template'] = 'admin/news/add';
        $this->load->view('admin/layout', $this->data);
    }

    function edit(){

        //lấy id từ URL
        $id = $this->uri->rsegment(3);
        $news = $this->news_model->get_info($id);
        if(!$news) {//ko tồn tại
            $this->session->set_flashdata('message', 'Không tồn tại bài viết này');
            redirect(admin_url('news'));
        }
        $this->data['news'] = $news;

        //load thư viện validate dữ liệu
        $this->load->library('form_validation');
        $this->load->helper('form');

        //neu ma co du lieu post len thi kiem tra
        if($this->input->post()) {
            $this->form_validation->set_rules('title', 'Tiêu đề bài viết', 'required');
            $this->form_validation->set_rules('content', 'Nội dung bài viết', 'required');

            if($this->form_validation->run()) {
                //lay ten file anh minh hoa duoc update len
                $this->load->library('upload_library');
                $upload_path = './upload/news';
                $upload_data = $this->upload_library->upload($upload_path, 'image');
                $image_link = '';
                if(isset($upload_data['file_name'])) {
                    $image_link = $upload_data['file_name'];
                }

                //luu du lieu can them
                $data = array(
                    'title'      => $this->input->post('title'),
                    'created'    => date('Y-m-d H:i:s'),
                );

                //có sửa ảnh bài viết
                if($image_link != '') {
                    $data['image_link'] = $image_link;

                    //xóa ảnh bài viết cũ


                }

                //them moi vao csdl
                if($this->news_model->update($news->id, $data)) {
                    //tạo ra nội dung thông báo
                    $this->session->set_flashdata('message', 'Cập nhật Tin tức thành công!');
                }
                else{
                    $this->session->set_flashdata('message', 'Không cập nhật được!');
                }
                //chuyen tới trang danh sách
                redirect(admin_url('news'));
            }
        }

        //load view
        $this->data['template'] = 'admin/news/edit';
        $this->load->view('admin/layout', $this->data);
    }

    function delete() {
        $id = $this->uri->rsegment(3);
        $this->_del($id);

        //tạo ra nội dung thông báo
        $this->session->set_flashdata('message', 'Xóa Tin tức thành công');
        redirect(admin_url('news'));
    }


    function delete_multi() {
        //lấy tất cả id cần xóa
        $ids = $this->input->post('ids');
        foreach ($ids as $id) {
            $this->_del($id);
        }
    }

    private function _del($id)
    {
        $news = $this->news_model->get_info($id);
        if(!$news) {
            $this->session->set_flashdata('message', 'Không tồn tại Tin tức này');
            redirect(admin_url('news'));
        }
        //thưc hiện xóa tin tức
        $this->news_model->delete($id);
        //xoa cac anh cua bài viết
        $image_link = './upload/news/'.$news->image_link;
        if(file_exists($image_link)) {
            unlink($image_link);
        }

    }
}