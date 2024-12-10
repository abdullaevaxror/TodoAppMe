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
    public function sing()
    {
        require 'view/sing.php';
    }
    public function bot()
    {
        require 'app/bot.php';
    }

    // Todosni ko'rsatish
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
    public function storeUser()
    {

    }
}