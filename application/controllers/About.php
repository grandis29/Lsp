<?php
defined('BASEPATH') or exit('No direct script access allowed');

class About extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'judul'    => 'About',
            'subjudul' => 'About'
        ];
        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('about/data');
        $this->load->view('_templates/dashboard/_footer.php');
    }
}
