<?php
namespace App;

use JetBrains\PhpStorm\NoReturn;

class Controller
{
    private Todo $todo;

    public function __construct()
    {
        $this->todo = new Todo();
    }

    public function home()
    {
        require 'view/button.php';
    }
    public function login()
    {
        require 'view/login.php';
    }
    public function register()
    {
        require 'view/register.php';
    }
    public function bot()
    {
        require 'app/bot.php';
    }

    public function showTodos(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $user_id = $_SESSION['user']['id'];
        $todos = $this->todo->get($user_id);
        view('home', ['todos' => $todos]);
    }


    public function updateTodoForm($id): void
    {
        $task = $this->todo->getById($id);
        view('edit', ['task' => $task]);
    }

    public function updateTodoData($id)
    {
        if (isset($_POST['title'], $_POST['due_date'], $_POST['status'])) {
            $this->todo->update($id, $_POST['title'], $_POST['due_date'], $_POST['status']);
        }
        header('Location: /todos');
        exit();
    }

    public function storeTodo()
    {
        if (!$_SESSION['user']) {
            header('Location: /login');
        }
        if (isset($_POST['title'], $_POST['due_date'], $_POST['status'])) {
            $this->todo->store($_POST['title'], $_POST['due_date'], $_POST['status'] ,$_SESSION['user']['id']);
        }
        header('Location: /todos');
        exit();
    }
    public function deleteTodo($id)
    {
        $this->todo->delete($id);
        header('Location: /todos');
        exit();
    }

     public function storeUser(): void
     {
        if (!empty($_POST['full_name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['repeat_password'])) {
            if ($_POST['password'] != $_POST['repeat_password']) {
                $_SESSION['error_message'][] = 'The passwords do not match';
                header('Location: /register');
                exit();
            }
            $user_App = (new \App\User());
            $user = $user_App->register($_POST['full_name'], $_POST['email'], $_POST['password']);
            if ($user) {
                unset($user['password']);
                unset($_SESSION['error_message']);
                $_SESSION['user'] = $user;
                header('Location: /todos');
                exit();
            }
            $_SESSION['error_message'] = 'Bu email band qilingan';
            header('Location: /register');
            exit();
        }
        $_SESSION['error_message'][] = 'Hamma maydonlarni to‘ldiring';
        header('Location: /register');
        exit();
    }
    public function storeLogin(): void {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $user = (new \App\User())->login($_POST['email'], $_POST['password']);

            if ($user) {
                unset($user['password']);
                $_SESSION['user'] = $user;
                unset($_SESSION['error_message']);

                header('Location: /todos');
                exit();
            }
            $_SESSION['error_message'] = 'Noto\'g\'ri email yoki parol';
            header('Location: /login');
            exit();
        }
        $_SESSION['error_message'][] = 'Email va parolni to\'ldiring';
        header('Location: /login');
        exit();
    }
    public function logout(): void {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: /login');
    }
}