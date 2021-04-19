<?php

namespace App\Controllers;

use App\Models\KomikModel;

class Komik extends BaseController
{
    protected $komikModel;

    public function ___construct()
    {
        $this->komikModel = new KomikModel();
    }


    public function index()
    {
        // $komik = $this->komikModel->findAll();
        $data = [
            'title' => 'Daftar Komik',
            'komik' => $this->komikModel->getKomik()
        ];


        // $komikModel = new \App\Models\KomikModel();
        // $komik = $this->komikModel->findAll();


        return view('komik/index', $data);
    }

    public function detail($slug)
    {

        $data = [
            'title' => 'detail komik',
            'komik' => $this->komikModel->getKomik($slug)
        ];

        if (empty($data['komik'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('judul komik ' . $slug . 'tidak di temukan.');
        }

        return view('komik/detail', $data);
    }

    public function create()
    {

        $data = [
            'title' => 'Form Data Komik',
            'validation' => \Config\Services::validation()
        ];

        return view('komik/create', $data);
    }

    public function save()
    {

        // validasi input 
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required' => '{field} komik harus diisi. . .',
                    'is_unique' => '{field} komik sudah ada . . .'
                ]
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,1024]is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar . . .',
                    'is_image' => 'File in bukan gambar . . .',
                    'mime_in' => 'File yang dipilih bukan gambar . . .'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();

            // return redirect()->to('/komik/create')->withInput()->with('validation', $validation);
            return redirect()->to('/komik/create')->withInput();
        }

        //pindah gambar
        $fileSampul = $this->request->getFile('sampul');
        //jika tidak upload gambar
        if ($fileSampul->getError() == 4) {
            $namaSampul = 'default.png';
        } else {
            //generate name random
            $namaSampul = $fileSampul->getRandomName();
            // simpan file 
            $fileSampul->move('img', $namaSampul);
        }



        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data Berhasil ditambah.');

        return redirect()->to('/komik');
    }

    public function delete($id)
    {
        // cari gambar id 
        $komik = $this->komikModel->find($id);

        // jika file default
        if ($komik['sampul'] != 'default.png') {
            //hapus gambar
            unlink('img/' . $komik['sampul']);
        }

        $this->komikModel->delete($id);
        session()->setFlashdata('pesan', 'Data Berhasil dihapus.');
        return redirect()->to('/komik');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Data Komik',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug)
        ];

        return view('komik/edit', $data);
    }

    public function update($id)
    {
        //cek judul
        $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        if ($komikLama['judul'] == $this->request->getVar('judul')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[komik.judul]';
        }
        if (!$this->validate([
            'judul' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} komik harus diisi. . .',
                    'is_unique' => '{field} komik sudah ada . . .'
                ]
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,1024]is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar . . .',
                    'is_image' => 'File in bukan gambar . . .',
                    'mime_in' => 'File yang dipilih bukan gambar . . .'
                ]
            ]
        ])) {

            return redirect()->to('/komik/edit/' . $this->request->getVar('slug'))->withInput();
        }

        $fileSampul = $this->request->getFile('sampul');

        // cek gambar lama
        if ($fileSampul->getError() == 4) {
            $namaSampul = $this->request->getVar('sampulLama');
        } else {
            // generate name
            $namaSampul = $fileSampul->getRandomName();
            // pindah gambar
            $fileSampul->move('img', $namaSampul);
            // hapus file
            // unlink('img/' . $this->request->getVar('sampulLama'));
            unlink('img/'.$this->request->getVar('sampulLama'));
            
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->komikModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data Berhasil diupdate.');

        return redirect()->to('/komik');
    }
}
