<?php

namespace App\Controllers;

use App\Models\MemberModel;
use Config\Services;

class Member extends BaseController
{
    protected $memberModel;
    protected $validation;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->validation  = Services::validation();
    }

    public function index()
    {
        return view('member/index', [
            'title' => 'Data Member',
        ]);
    }

    public function listData()
    {
        return $this->response->setJSON([
            'data' => $this->memberModel->orderBy('nama', 'ASC')->findAll(),
        ]);
    }

    public function save()
    {
        $data = $this->collectInput();

        $this->validation->setRules([
            'no_member' => 'required|min_length[3]|max_length[30]|is_unique[member.no_member]',
            'nama'      => 'required|min_length[2]|max_length[120]',
            'alamat'    => 'permit_empty|max_length[500]',
            'no_hp'     => 'permit_empty|max_length[25]',
        ]);

        if (! $this->validation->run($data)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => reset($this->validation->getErrors()) ?: 'Validasi gagal.',
            ]);
        }

        $this->memberModel->insert($data);

        return $this->response->setJSON([
            'status' => 'success',
            'msg'    => 'Member berhasil ditambahkan.',
        ]);
    }

    public function update()
    {
        $id     = (int) $this->request->getPost('id');
        $member = $this->memberModel->find($id);

        if (! $member) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'msg'    => 'Member tidak ditemukan.',
            ]);
        }

        $data = $this->collectInput();

        $this->validation->setRules([
            'no_member' => 'required|min_length[3]|max_length[30]',
            'nama'      => 'required|min_length[2]|max_length[120]',
            'alamat'    => 'permit_empty|max_length[500]',
            'no_hp'     => 'permit_empty|max_length[25]',
        ]);

        if (! $this->validation->run($data)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => reset($this->validation->getErrors()) ?: 'Validasi gagal.',
            ]);
        }

        $sameNo = $this->memberModel
            ->where('no_member', $data['no_member'])
            ->where('id !=', $id)
            ->first();

        if ($sameNo) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Nomor member sudah digunakan.',
            ]);
        }

        $this->memberModel->update($id, $data);

        return $this->response->setJSON([
            'status' => 'success',
            'msg'    => 'Member berhasil diperbarui.',
        ]);
    }

    public function delete($id)
    {
        $member = $this->memberModel->find((int) $id);

        if (! $member) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'msg'    => 'Member tidak ditemukan.',
            ]);
        }

        $this->memberModel->delete((int) $id);

        return $this->response->setJSON([
            'status' => 'success',
            'msg'    => 'Member berhasil dihapus.',
        ]);
    }

    private function collectInput(): array
    {
        return [
            'no_member' => trim((string) $this->request->getPost('no_member')),
            'nama'      => trim((string) $this->request->getPost('nama')),
            'alamat'    => trim((string) $this->request->getPost('alamat')),
            'no_hp'     => trim((string) $this->request->getPost('no_hp')),
        ];
    }
}
