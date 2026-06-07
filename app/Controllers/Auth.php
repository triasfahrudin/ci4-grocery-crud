<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    public function login(): ResponseInterface|string
    {
        // If already logged in, redirect to demo
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/grocery-crud-demo');
        }

        $data = ['error' => ''];

        if ($this->request->getMethod() === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            if (empty($username) || empty($password)) {
                $data['error'] = 'Username and password are required.';
            } else {
                $user = model('App\Models\UserModel')->findByUsername($username);

                if ($user === null || !password_verify($password, $user['password'])) {
                    $data['error'] = 'Invalid username or password.';
                } elseif (!$user['is_active']) {
                    $data['error'] = 'Account is disabled.';
                } else {
                    // Set session
                    session()->set([
                        'isLoggedIn' => true,
                        'userId'     => $user['id'],
                        'username'   => $user['username'],
                        'fullName'   => $user['full_name'],
                        'role'       => $user['role'],
                        'email'      => $user['email'],
                    ]);

                    // Update last login
                    model('App\Models\UserModel')->update($user['id'], [
                        'last_login' => date('Y-m-d H:i:s'),
                    ]);

                    return redirect()->to('/grocery-crud-demo');
                }
            }
        }

        return view('auth/login', $data);
    }

    public function logout(): ResponseInterface
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }

    public function profile(): ResponseInterface|string
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        return view('auth/profile');
    }
}
