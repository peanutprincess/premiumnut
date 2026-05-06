<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller{
  function __construct(){
    parent::__construct();
    $this->load->model('login_model');
  }

  function index(){
    $this->load->view('home');
  }

  function auth(){
    $email    = $this->input->post('email',TRUE);
    $password = sha1($this->input->post('password',TRUE));
    $validate = $this->login_model->validate($email,$password);
    
    if($validate->num_rows() > 0){
        $data  = $validate->row_array();
        $name  = $data['first_name'];
        $email = $data['email'];
        $level = $data['level'];
        $uid = $data['id'];
        $sesdata = array(
            'uid'  => $uid,
            'username'  => $name,
            'email'     => $email,
            'level'     => $level,
            'logged_in' => TRUE
        );
        $this->session->set_userdata($sesdata);
        // access login for admin
        if($level === '1'){
            redirect('dashboard');
        // access login for staff
        }elseif($level === '2'){
            redirect('dashboard');
        // access login for author
        }else{
            redirect('dashboard');
        }
    }else{
        echo $this->session->set_flashdata('msg','Wrong username or password! Please try again.');
        redirect('/');
    }
  }

}