<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Arsip_model extends CI_Model
{
    public function getData()
    {
        $this->datatables->select('a.id, a.nomor_surat, a.id_kategori, b.kategori, a.judul, a.file, a.created_at');
        $this->datatables->from('arsip a');
        $this->datatables->join('kategori b', 'a.id_kategori=b.id');
        return $this->datatables->generate();
    }

    public function getDataById($id)
    {
        $this->db->select('a.id, a.nomor_surat, a.id_kategori, b.kategori, a.judul, a.file, a.created_at');
        $this->db->from('arsip a');
        $this->db->join('kategori b', 'a.id_kategori=b.id');
        $this->db->where('a.id', $id);
        return $this->db->get()->row();
    }

    public function getAllKategori()
    {
        return $this->db->get('kategori')->result();
    }
}
