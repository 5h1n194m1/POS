<?php

namespace App\Controllers;

use App\Models\UserModel;
use Config\Services;

class User extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->validation = Services::validation();
    }

    public function index()
    {
        return view('user/index', [
            'title' => 'Management User',
        ]);
    }

    public function listData()
    {
        $users = $this->userModel
            ->orderBy('id', 'DESC')
            ->findAll();

        foreach ($users as &$user) {
            unset($user['password']);
        }

        return $this->response->setJSON([
            'data' => $users
        ]);
    }

    public function save()
    {
        $data = [
            'username' => trim((string) $this->request->getPost('username')),
            'fullname' => trim((string) $this->request->getPost('fullname')),
            'email'    => trim((string) $this->request->getPost('email')),
            'password' => (string) $this->request->getPost('password'),
            'role'     => (string) $this->request->getPost('role'),
            'status'   => (string) $this->request->getPost('status'),
        ];

        $this->validation->setRules([
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'fullname' => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,kasir]',
            'status'   => 'required|in_list[aktif,non-aktif]',
        ]);

        if (! $this->validation->run($data)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validation->getErrors(),
                'msg'    => 'Validasi gagal.',
            ]);
        }

        $insert = [
            'username'  => $data['username'],
            'fullname'  => $data['fullname'],
            'email'     => $data['email'],
            'password'  => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'      => $data['role'],
            'status'    => $data['status'],
            'is_active' => $data['status'] === 'aktif' ? 1 : 0,
        ];

        $this->userModel->insert($insert);

        return $this->response->setJSON([
            'status' => 'success',
            'msg'    => 'User berhasil ditambahkan.',
        ]);
    }

    public function update()
    {
        $id = (int) $this->request->getPost('id');
        $user = $this->userModel->find($id);

        if (! $user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'msg'    => 'User tidak ditemukan.',
            ]);
        }

        $username = trim((string) $this->request->getPost('username'));
        $fullname = trim((string) $this->request->getPost('fullname'));
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $role     = (string) $this->request->getPost('role');
        $status   = (string) $this->request->getPost('status');

        $this->validation->setRules([
            'username' => 'required|min_length[3]|max_length[100]',
            'fullname' => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email|max_length[100]',
            'role'     => 'required|in_list[admin,kasir]',
            'status'   => 'required|in_list[aktif,non-aktif]',
        ]);

        $payload = [
            'username' => $username,
            'fullname' => $fullname,
            'email'    => $email,
            'role'     => $role,
            'status'   => $status,
        ];

        if (! $this->validation->run($payload)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validation->getErrors(),
                'msg'    => 'Validasi gagal.',
            ]);
        }

        $sameUsername = $this->userModel
            ->where('username', $username)
            ->where('id !=', $id)
            ->first();

        if ($sameUsername) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Username sudah digunakan.',
            ]);
        }

        $sameEmail = $this->userModel
            ->where('email', $email)
            ->where('id !=', $id)
            ->first();

        if ($sameEmail) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Email sudah digunakan.',
            ]);
        }

        $adminCount = $this->userModel->where('role', 'admin')->countAllResults();

        if ($user['role'] === 'admin' && $role !== 'admin' && $adminCount <= 1) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Minimal harus ada 1 admin aktif di sistem.',
            ]);
        }

        $update = [
            'username'  => $username,
            'fullname'  => $fullname,
            'email'     => $email,
            'role'      => $role,
            'status'    => $status,
            'is_active' => $status === 'aktif' ? 1 : 0,
        ];

        if ($password !== '') {
            if (strlen($password) < 6) {
                return $this->response->setStatusCode(422)->setJSON([
                    'status' => 'error',
                    'msg'    => 'Password minimal 6 karakter.',
                ]);
            }

            $update['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $update);

        return $this->response->setJSON([
            'status' => 'success',
            'msg'    => 'User berhasil diperbarui.',
        ]);
    }

    public function delete($id)
    {
        $id = (int) $id;
        $user = $this->userModel->find($id);

        if (! $user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'msg'    => 'User tidak ditemukan.',
            ]);
        }

        if ($id === (int) session()->get('user_id')) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Anda tidak bisa menghapus akun yang sedang digunakan.',
            ]);
        }

        $adminCount = $this->userModel->where('role', 'admin')->countAllResults();
        if (($user['role'] ?? '') === 'admin' && $adminCount <= 1) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'msg'    => 'Minimal harus ada 1 admin aktif di sistem.',
            ]);
        }

        $this->userModel->delete($id);

        return $this->response->setJSON([
            'status' => 'success',
            'msg'    => 'User berhasil dihapus.',
        ]);
    }
}