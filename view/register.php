<?php require 'view/components/header.php'?>
<?php require 'view/components/navbar.php'?>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-header {
            margin-bottom: 20px;
        }
    </style>
<div class="form-container">
    <h2 class="text-center form-header">Create an Account</h2>
    <form action="/register" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="full_name" placeholder="Enter your name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
        </div>
        <div class="mb-3">
            <label for="confirm-password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm-password" name="repeat_password" placeholder="Re-enter your password" required>
        </div>
        <p class="text-danger text-center" style="display: block; "><?= $_SESSION['error_massege'] ?? ''?></p>
        <button type="submit" class="btn btn-primary w-100">Register</button>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="/login">Login here</a></p>
        </div>
    </form>
</div>
<?php require 'view/components/footer.php'?>
