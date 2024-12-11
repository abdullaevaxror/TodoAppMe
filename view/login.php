<?php require 'view/components/header.php'?>
<?php require 'view/components/navbar.php'?>
    <style>
        body {
            background-color: #e9ecef;
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
</head>
<body>

<div class="form-container">
    <h2 class="text-center form-header">Login to Your Account</h2>
    <form action="/login" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <div class="text-center mt-3">
            <p>Don't have an account? <a href="/register">Register here</a></p>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<?php require 'view/components/footer.php'?>
