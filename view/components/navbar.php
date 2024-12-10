<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <ul class="navbar-list">
        <li><a href="/">Home</a></li>
        <li><a href="/login">Login</a></li>
        <li><a href="/sing">Register</a></li>
    </ul>
</nav>

<style>
    .navbar {
        background-color: #333;
        color: white;
        padding: 10px 20px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }

    .navbar-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: flex-start;
    }

    .navbar-list li {
        margin-right: 15px;
    }

    .navbar-list li a {
        text-decoration: none;
        color: white;
        padding: 8px 12px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .navbar-list li a:hover {
        background-color: #555;
    }

    .navbar-list li a.active {
        background-color: #444;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    .main-content {
        padding-top: 60px;
    }
</style>
