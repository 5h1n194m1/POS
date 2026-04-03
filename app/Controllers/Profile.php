<?php

namespace App\Controllers;

use App\Models\UserModel;
use Config\Services;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $user = $this->userModel->find(session()->get('user_id'));

        if (! $user) {
            return redirect()->to('/login')->with('error', 'Data user tidak ditemukan.');
        }

        return view('profile/index', [
            'title' => 'Profil Saya',
            'user'  => $user,
        ]);
    }

    public function update()
    {
        $id   = (int) session()->get('user_id');
        $user = $this->userModel->find($id);

        if (! $user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.',
            ]);
        }

        $payload = [
            'fullname' => trim((string) $this->request->getPost('fullname')),
            'email'    => trim((string) $this->request->getPost('email')),
        ];

        $validation = Services::validation();
        $validation->setRules([
            'fullname' => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email|max_length[100]',
        ]);

        if (! $validation->run($payload)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validation->getErrors(),
            ]);
        }

        $existingEmail = $this->userModel
            ->where('email', $payload['email'])
            ->where('id !=', $id)
            ->first();

        if ($existingEmail) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Email sudah digunakan user lain.',
            ]);
        }

        $this->userModel->update($id, $payload);

        session()->set([
            'fullname' => $payload['fullname'],
            'email'    => $payload['email'],
        ]);

        return $this->response->setJSON([
            'status'     => 'success',
            'message'    => 'Profil berhasil diperbarui.',
            'fullname'   => $payload['fullname'],
            'email'      => $payload['email'],
            'avatar_url' => $this->resolveAvatarUrl($user['avatar'] ?? null, $payload['fullname']),
        ]);
    }

    public function changePassword()
    {
        $id   = (int) session()->get('user_id');
        $user = $this->userModel->find($id);

        if (! $user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.',
            ]);
        }

        $currentPassword = (string) $this->request->getPost('current_password');
        $newPassword     = (string) $this->request->getPost('new_password');
        $confirmPassword = (string) $this->request->getPost('confirm_password');

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Semua field password wajib diisi.',
            ]);
        }

        if (strlen($newPassword) < 6) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Password baru minimal 6 karakter.',
            ]);
        }

        if ($newPassword !== $confirmPassword) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Konfirmasi password tidak cocok.',
            ]);
        }

        if (! password_verify($currentPassword, $user['password'])) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Password saat ini salah.',
            ]);
        }

        $this->userModel->update($id, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Password berhasil diperbarui.',
        ]);
    }

    public function uploadAvatar()
    {
        $id   = (int) session()->get('user_id');
        $user = $this->userModel->find($id);

        if (! $user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.',
            ]);
        }

        $file = $this->request->getFile('avatar');

        // 1. Belum pilih file
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Pilih file avatar terlebih dahulu.',
            ]);
        }

        // 2. Validasi CI4
        $rules = [
            'avatar' => [
                'label' => 'Avatar',
                'rules' => 'uploaded[avatar]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,2048]',
            ],
        ];

        if (! $this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => $this->validator->getError('avatar') ?: 'File avatar tidak valid.',
            ]);
        }

        if (! $file->isValid()) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Upload file gagal. Kode error: ' . $file->getError(),
            ]);
        }

        if ($file->hasMoved()) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'File sudah pernah diproses sebelumnya.',
            ]);
        }

        $uploadPath = FCPATH . 'uploads/avatars' . DIRECTORY_SEPARATOR;

        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Hapus avatar lama jika ada
        if (! empty($user['avatar'])) {
            $oldFile = FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $user['avatar']);
            if (is_file($oldFile)) {
                @unlink($oldFile);
            }
        }

        $newName = 'avatar_' . $id . '_' . time() . '.' . $file->getExtension();
        $file->move($uploadPath, $newName);

        $relativePath = 'uploads/avatars/' . $newName;

        $this->userModel->update($id, [
            'avatar' => $relativePath
        ]);

        session()->set('avatar', $relativePath);

        return $this->response->setJSON([
            'status'     => 'success',
            'message'    => 'Foto profil berhasil diupload.',
            'avatar_url' => base_url($relativePath),
        ]);
    }

    public function deleteAvatar()
    {
        $id   = (int) session()->get('user_id');
        $user = $this->userModel->find($id);

        if (! $user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.',
            ]);
        }

        if (! empty($user['avatar'])) {
            $oldFile = FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $user['avatar']);
            if (is_file($oldFile)) {
                @unlink($oldFile);
            }
        }

        $this->userModel->update($id, [
            'avatar' => null
        ]);

        session()->remove('avatar');

        return $this->response->setJSON([
            'status'     => 'success',
            'message'    => 'Foto profil berhasil dihapus.',
            'avatar_url' => $this->resolveAvatarUrl(null, session()->get('fullname')),
        ]);
    }

    private function resolveAvatarUrl(?string $avatarPath, string $fullname): string
    {
        if (! empty($avatarPath)) {
            return base_url($avatarPath);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($fullname) . '&background=random';
    }
}