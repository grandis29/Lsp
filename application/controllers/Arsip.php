<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Arsip extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
        $this->load->helper('my');// Load Library Ignited-Datatables
        $this->load->model('Arsip_model', 'arsip');
        $this->load->model('Master_model', 'master');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }

    public function index()
    {
        $data = [
            'judul'    => 'Arsip',
            'subjudul' => 'Data Arsip'
        ];
        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('arsip/data');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function data()
    {
        $this->output_json($this->arsip->getData(), false);
    }

    public function add()
    {
        $data = [
            'judul'     => 'Tambah Arsip',
            'subjudul'  => 'Tambah Data Arsip',
            'kategori'  => $this->arsip->getAllKategori()
        ];
        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('arsip/add');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function lihat($id)
    {
        $data = [
            'judul'     => 'Lihat Arsip',
            'subjudul'  => 'Lihat Data Arsip',
            'arsip'     => $this->arsip->getDataById($id)
        ];
        $this->load->view('_templates/dashboard/_header.php', $data);
        $this->load->view('arsip/detail');
        $this->load->view('_templates/dashboard/_footer.php');
    }

    public function file_config()
    {
        $config['upload_path']      = FCPATH . 'uploads/arsip/';
        $config['allowed_types']    = 'pdf';
        $config['file_name'] = $this->input->post('nomor_surat', true);

        return $this->load->library('upload', $config);
    }

    public function save()
    {
        $this->file_config();
        if ($this->input->post('method', true) == "add") {
            $this->form_validation->set_rules('nomor_surat', 'Nomor Surat', 'required');
            $this->form_validation->set_rules('kategori_id', 'Kategori', 'required');
            $this->form_validation->set_rules('judul', 'Judul', 'required');
            if ($this->form_validation->run() === FALSE) {
                $data = [
                    'status'    => false,
                    'errors'    => [
                        'nomor_surat' => form_error('nomor_surat'),
                        'kategori_id' => form_error('kategori_id'),
                        'judul' => form_error('judul'),
                    ]
                ];
                $this->output_json($data);
            } else {
                // return var_dump($_FILES);
                if (!empty($_FILES['file_arsip']['name'])) {
                    if (!$this->upload->do_upload('file_arsip')) {
                        $error = $this->upload->display_errors();
                        show_error($error, 500, 'File Arsip Error');
                        exit();
                    }
                    $file = $this->upload->data('file_name');
                } else {
                    $error = $this->upload->display_errors();
                    show_error($error, 500, 'File Arsip Error');
                    exit();
                }
                $input = [
                    'nomor_surat' => $this->input->post('nomor_surat', true),
                    'id_kategori' => $this->input->post('kategori_id', true),
                    'judul' => $this->input->post('judul', true),
                    'file' => $file,
                    'created_at' => date('Y-m-d h:i:s')
                ];
                $action = $this->master->create('arsip', $input, false);
                $data['status'] = $action ? TRUE : FALSE;
            }
        } else {
            if (!empty($_FILES['file_arsip']['name'])) {
                $img_src = FCPATH . 'uploads/arsip/';
                unlink($img_src . $this->db->get_where('arsip', array('nomor_surat' => $this->input->post('nomor_surat', true)))->row('file'));
                if (!$this->upload->do_upload('file_arsip')) {
                    $error = $this->upload->display_errors();
                    show_error($error, 500, 'File Arsip Error');
                    exit();
                }
                $file = $this->upload->data('file_name');
            } else {
                $error = $this->upload->display_errors();
                show_error($error, 500, 'File Arsip Error');
                exit();
            }
            $input = [
                'file' => $file
            ];
            $action = $this->master->update('arsip', $input, 'id', $this->input->post('id_arsip', true), false);
            $data['status'] = $action ? TRUE : FALSE;
        }
        
        $this->output_json($data);
    }

    public function delete()
    {
        $chk = $this->input->post('checked', true);
        if (!$chk) {
            $this->output_json(['status' => false]);
        } else {
            $img_src = FCPATH . 'uploads/arsip/';
            foreach ($chk as $c) {
                unlink($img_src . $this->db->get_where('arsip', array('nomor_surat' => $c))->row('file'));
            }
            $this->master->delete('arsip', $chk, 'nomor_surat');
            if ($this->master->delete('arsip', $chk, 'nomor_surat')) {
                $this->output_json(['status' => true, 'total' => count($chk)]);
            }
        }
    }
}
