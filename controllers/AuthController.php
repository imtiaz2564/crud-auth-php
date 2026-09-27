<?php

namespace Controller;

use Model\User;
use Repository\Interfaces\LoginRepositoryInterface;


class AuthController
{
    private LoginRepositoryInterface $authRepo;

    public function __construct(LoginRepositoryInterface $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function loginView()
    {
        if (isset($_SESSION['email'])) {
            header('Location: index.php?act=sports');
            exit;
        }

        include __DIR__ . '/../views/auth/login.php';
    }

    public function registrationView()
    {
        if (isset($_SESSION['email'])) {
            header('Location: index.php?act=sports');
            exit;
        }

        include __DIR__ . '/../views/auth/registration.php';
    }

    public function registration()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $error = "Email and password are required.";
            include __DIR__ . '/../views/auth/registration.php';
            return;
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
            include __DIR__ . '/../views/auth/registration.php';
            return;
        }

        // Optional: minimum password length
        if (strlen($password) < 6) {
            $error = "Password must be at least 6 characters.";
            include __DIR__ . '/../views/auth/registration.php';
            return;
        }

        // Check duplicate email
        if ($this->authRepo->emailExists($email)) {
            $error = "This email is already registered.";
            include __DIR__ . '/../views/auth/registration.php';
            return;
        }

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $user = new User(
            $email,
            $hashedPassword
        );

        $result = $this->authRepo->registration($user);

        if ($result) {
             header("Location: index.php?act=loginView");
        } else {
            $error = "Registration failed.";
            include __DIR__ . '/../views/auth/registration.php';
        }
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $error = "Email and password are required.";

            include __DIR__ . '/../views/auth/login.php';

            return;
        }

        $user = new User(
            $email,
            $password
        );

        $result =
            $this->authRepo->loginUser($user);

        if ($result) {
            session_regenerate_id(true);

            $_SESSION['email'] = $email;

            header("Location: index.php?act=sports");
            exit;
        } else {

            $error =
                "Invalid email or password.";

            include __DIR__ . '/../views/auth/login.php';
        }
    }

    public function logout()
    {
        $_SESSION = [];

        session_destroy();

        header("Location: index.php?act=loginView");
        exit;
    }
}
