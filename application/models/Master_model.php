<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {

    public function create($table, $data, $batch = false)
    {
        if($batch === false){
            $insert = $this->db->insert($table, $data);
        }else{
            $insert = $this->db->insert_batch($table, $data);
        }
        return $insert;
    }

    public function update($table, $data, $pk, $id = null, $batch = false)
    {
        if($batch === false){
            $update = $this->db->update($table, $data, array($pk => $id));
        }else{
            $update = $this->db->update_batch($table, $data, $pk);
        }
        return $update;
    }

    public function delete($table, $data, $pk)
    {
        $this->db->where_in($pk, $data);
        return $this->db->delete($table);
    }

    /**
     * Data Kelas
     */

    public function getDataKelas()
    {
        $this->datatables->select('id_kelas, nama_kelas, id_jurusan, nama_jurusan');
        $this->datatables->from('kelas');
        $this->datatables->join('jurusan', 'jurusan_id=id_jurusan');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_kelas, nama_kelas, id_jurusan, nama_jurusan');        
        return $this->datatables->generate();
    }

    public function getKelasById($id)
    {
        $this->db->where_in('id_kelas', $id);
        $this->db->order_by('nama_kelas');
        $query = $this->db->get('kelas')->result();
        return $query;
    }

    /**
     * Data Jurusan
     */

    public function getDataJurusan()
    {
        $this->datatables->select('id_jurusan, nama_jurusan');
        $this->datatables->from('jurusan');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_jurusan, nama_jurusan');
        return $this->datatables->generate();
    }

    public function getJurusanById($id)
    {
        $this->db->where_in('id_jurusan', $id);
        $this->db->order_by('nama_jurusan');
        $query = $this->db->get('jurusan')->result();
        return $query;
    }

    /**
     * Data Siswa
     */

    public function getDataSiswa()
    {
        $this->datatables->select('a.id_siswa, a.nama, a.nim, a.email, b.nama_kelas, c.nama_jurusan');
        $this->datatables->select('(SELECT COUNT(id) FROM users WHERE username = a.nim) AS ada');
        $this->datatables->from('siswa a');
        $this->datatables->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->datatables->join('jurusan c', 'b.jurusan_id=c.id_jurusan');
        return $this->datatables->generate();
    }

    public function getSiswaById($id)
    {
        $this->db->select('*');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas_id=id_kelas');
        $this->db->join('jurusan', 'jurusan_id=id_jurusan');
        $this->db->where(['id_siswa' => $id]);
        return $this->db->get()->row();
    }

    public function getJurusan()
    {
        $this->db->select('id_jurusan, nama_jurusan');
        $this->db->from('kelas');
        $this->db->join('jurusan', 'jurusan_id=id_jurusan');
        $this->db->order_by('nama_jurusan', 'ASC');
        $this->db->group_by('id_jurusan');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllJurusan($id = null)
    {
        if($id === null){
            $this->db->order_by('nama_jurusan', 'ASC');
            return $this->db->get('jurusan')->result();    
        }else{
            $this->db->select('kelas_id');
            $this->db->from('kelas_mapel');
            $this->db->where('mapel_id', $id);
            $jurusan = $this->db->get()->result();
            $id_jurusan = [];
            foreach ($jurusan as $j) {
                $id_jurusan[] = $j->jurusan_id;
            }
            if($id_jurusan === []){
                $id_jurusan = null;
            }
            
            $this->db->select('*');
            $this->db->from('jurusan');
            $this->db->where_not_in('id_jurusan', $id_jurusan);
            $mapel = $this->db->get()->result();
            return $mapel;
        }
    }

    public function getKelasByJurusan($id)
    {
        $query = $this->db->get_where('kelas', array('jurusan_id'=>$id));
        return $query->result();
    }

    /**
     * Data Pengajar
     */

    public function getDataPengajar()
    {
        $this->datatables->select('a.id_pengajar,a.nip, a.nama_pengajar, a.email, (SELECT COUNT(id) FROM users WHERE username = a.nip OR email = a.email) AS ada');
        $this->datatables->from('pengajar a');
        return $this->datatables->generate();
    }

    public function getPengajarById($id)
    {
        $query = $this->db->get_where('pengajar', array('id_pengajar'=>$id));
        return $query->row();
    }

    /**
     * Data Mapel
     */

    public function getDataMapel()
    {
        $this->datatables->select('id_mapel, nama_mapel');
        $this->datatables->from('mapel');
        return $this->datatables->generate();
    }

    public function getAllMapel()
    {
        return $this->db->get('mapel')->result();
    }

    public function getMapelById($id, $single = false)
    {
        if($single === false) {
            $this->db->select('mapel.*, GROUP_CONCAT(kelas.id_kelas) as id_kelas');
            $this->db->from('mapel');
            $this->db->join('kelas_mapel', 'kelas_mapel.mapel_id=mapel.id_mapel');
            $this->db->join('kelas', 'kelas_mapel.kelas_id=kelas.id_kelas');
            $this->db->where_in('id_mapel', $id);
            $this->db->order_by('nama_mapel');
            $this->db->group_by('mapel.nama_mapel');
            $query = $this->db->get()->result();
        }else {
            $this->db->select('mapel.*, GROUP_CONCAT(kelas.id_kelas) as id_kelas');
            $this->db->from('mapel');
            $this->db->join('kelas_mapel', 'kelas_mapel.mapel_id=mapel.id_mapel');
            $this->db->join('kelas', 'kelas_mapel.kelas_id=kelas.id_kelas');
            $this->db->where_in('id_mapel', $id);
            $this->db->order_by('nama_mapel');
            $query = $this->db->get()->row();
        }
        return $query;
    }

    /**
     * Data Kelas Pengajar
     */

    public function getKelasPengajar()
    {
        $this->datatables->select('kelas_pengajar.id, pengajar.id_pengajar, pengajar.nip, pengajar.nama_pengajar, GROUP_CONCAT(kelas.nama_kelas) as kelas');
        $this->datatables->from('kelas');
        $this->datatables->join('kelas_pengajar', 'kelas_pengajar.kelas_id=kelas.id_kelas');
        $this->datatables->join('pengajar', 'pengajar_id=id_pengajar');
        $this->datatables->group_by('pengajar.nama_pengajar');
        return $this->datatables->generate();
    }

    public function getAllPengajar($id = null)
    {
        $this->db->select('pengajar_id');
        $this->db->from('kelas_pengajar');
        if($id !== null){
            $this->db->where_not_in('pengajar_id', [$id]);
        }
        $pengajar = $this->db->get()->result();
        $id_pengajar = [];
        foreach ($pengajar as $d) {
            $id_pengajar[] = $d->pengajar_id;
        }
        if($id_pengajar === []){
            $id_pengajar = null;
        }

        $this->db->select('id_pengajar, nip, nama_pengajar');
        $this->db->from('pengajar');
        $this->db->where_not_in('id_pengajar', $id_pengajar);
        return $this->db->get()->result();
    }

    public function getPengajar()
    {
        $this->db->select('id_pengajar, nip, nama_pengajar');
        $this->db->from('pengajar');
        return $this->db->get()->result();
    }
    
    public function getAllKelas()
    {
        $this->db->select('id_kelas, nama_kelas, nama_jurusan');
        $this->db->from('kelas');
        $this->db->join('jurusan', 'jurusan_id=id_jurusan');
        $this->db->order_by('nama_kelas');
        return $this->db->get()->result();
    }
    
    public function getKelasByPengajar($id)
    {
        $this->db->select('kelas.id_kelas');
        $this->db->from('kelas_pengajar');
        $this->db->join('kelas', 'kelas_pengajar.kelas_id=kelas.id_kelas');
        $this->db->where('pengajar_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }

    public function getKelasByIdMapel($id)
    {
        $this->db->select('kelas.id_kelas');
        $this->db->from('kelas');
        $this->db->join('kelas_mapel', 'kelas_mapel.kelas_id=kelas.id_kelas');
        $this->db->where('mapel_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
    /**
     * Data Kelas Mapel
     */

    public function getKelasMapel()
    {
        $this->datatables->select('a.id, b.id_mapel, b.nama_mapel, c.id_jurusan, GROUP_CONCAT(d.nama_kelas,";",c.nama_jurusan) as nama_kelas');
        $this->datatables->from('kelas_mapel a');
        $this->datatables->join('mapel b', 'a.mapel_id=b.id_mapel');
        $this->datatables->join('kelas d', 'a.kelas_id=d.id_kelas');
        $this->datatables->join('jurusan c', 'd.jurusan_id=c.id_jurusan');
        $this->datatables->group_by('b.nama_mapel');
        return $this->datatables->generate();
    }

    public function getMapel($id = null)
    {
        $this->db->select('mapel_id');
        $this->db->from('kelas_mapel');
        if($id !== null){
            $this->db->where_not_in('mapel_id', [$id]);
        }
        $mapel = $this->db->get()->result();
        $id_mapel = [];
        foreach ($mapel as $d) {
            $id_mapel[] = $d->mapel_id;
        }
        if($id_mapel === []){
            $id_mapel = null;
        }

        $this->db->select('id_mapel, nama_mapel');
        $this->db->from('mapel');
        $this->db->where_not_in('id_mapel', $id_mapel);
        return $this->db->get()->result();
    }

    public function getJurusanByIdMapel($id)
    {
        $this->db->select('jurusan.id_jurusan, jurusan.nama_jurusan');
        $this->db->from('kelas_mapel');
        $this->db->join('kelas', 'kelas_mapel.kelas_id=kelas.id_kelas');
        $this->db->join('jurusan', 'kelas.jurusan_id=jurusan.id_jurusan');
        $this->db->where('mapel_id', $id);
        $this->db->group_by('id_jurusan');
        $query = $this->db->get()->result();
        return $query;
    }

    public function getPJurusanByIdMapel($id)
    {
        $this->db->select('jurusan.id_jurusan, jurusan.nama_jurusan');
        $this->db->from('pengajar_mapel');
        $this->db->join('jurusan', 'pengajar_mapel.jurusan_id=jurusan.id_jurusan');
        $this->db->where('mapel_id', $id);
        $this->db->group_by('id_jurusan');
        $query = $this->db->get()->result();
        return $query;
    }
    /**
     * Data Pengajar Mapel
     */

    public function getPengajarMapel()
    {
        $this->datatables->select('pengajar_mapel.id, mapel.id_mapel, mapel.nama_mapel, GROUP_CONCAT(DISTINCT jurusan.nama_jurusan) as nama_jurusan, pengajar.id_pengajar, GROUP_CONCAT(DISTINCT pengajar.nama_pengajar) as nama_pengajar');
        $this->datatables->from('pengajar_mapel');
        $this->datatables->join('mapel', 'pengajar_mapel.mapel_id=mapel.id_mapel');
        $this->datatables->join('pengajar', 'pengajar_id=id_pengajar');
        $this->datatables->join('kelas_mapel', 'kelas_mapel.mapel_id=mapel.id_mapel');
        $this->datatables->join('kelas', 'kelas_mapel.kelas_id=kelas.id_kelas');
        $this->datatables->join('jurusan', 'pengajar_mapel.jurusan_id=jurusan.id_jurusan');
        $this->datatables->group_by('pengajar_mapel.mapel_id');
        return $this->datatables->generate();
    }

    public function getPMapel($id = null)
    {
        $this->db->select('mapel_id');
        $this->db->from('pengajar_mapel a');
        if ($id !== null) {
            $this->db->where_not_in('mapel_id', [$id]);
        }
        $mapel = $this->db->get()->result();
        $id_mapel = [];
        foreach ($mapel as $d) {
            $id_mapel[] = $d->mapel_id;
        }
        if ($id_mapel === []) {
            $id_mapel = null;
        }

        $this->db->select('id_mapel, nama_mapel');
        $this->db->from('mapel');
        $this->db->where_not_in('id_mapel', $id_mapel);
        return $this->db->get()->result();
    }

    public function getPengajarByIdMapel($id)
    {
        $this->db->select('pengajar.id_pengajar');
        $this->db->from('pengajar_mapel');
        $this->db->join('pengajar', 'pengajar_mapel.pengajar_id=pengajar.id_pengajar');
        $this->db->where('mapel_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
}