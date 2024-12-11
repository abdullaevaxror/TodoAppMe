<?php
namespace App;

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

    public function showTodos()
    {
        $todos = $this->todo->get();
        view('home', ['todos' => $todos]);
    }

    // Todo taskini tahrirlash formasi
    public function updateTodoForm($id)
    {
        $task = $this->todo->getById($id);
        view('edit', ['task' => $task]);
    }

    // Todo taskini yangilash
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
        if (isset($_POST['title'], $_POST['due_date'], $_POST['status'])) {
            $this->todo->store($_POST['title'], $_POST['due_date'], $_POST['status']);
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

    public function deleteTodoData($id)
    {
        // Ma'lumotlar bazasidan o'chirish
        if ($id) {
            /** @var TYPE_NAME $db */
            $db->delete("DELETE FROM todos WHERE id = $id");
            echo "Todo o'chirildi!";
        } else {
            echo "ID topilmadi!";
        }
    }

    public function index()
    {
        /** @var TYPE_NAME $db */
        $todos = $db->select("SELECT * FROM todo");
        foreach ($todos as $todo) {
            echo "<div>{$todo['task']} 
                    <a href='/delete/{$todo['id']}'>O'chirish</a>
                  </div>";
        }
    }
    public function storeUser(): void {
        if (!empty($_POST['full_name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['repeat_password'])) {
            if ($_POST['password'] != $_POST['repeat_password']) {
                $_SESSION['error_message'][] = 'The passwords do not match';
                header('Location: /register');
                exit();
            }
            $lastUserId = (new \App\User())->register($_POST['full_name'], $_POST['email'], $_POST['password']);
            if ($lastUserId) {
                unset($_SESSION['error_message']);
                $_SESSION['user_id'] = $lastUserId;
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
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
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

}