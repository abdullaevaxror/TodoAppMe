<?php
    function view ($page , $data=[]):void{
        extract($data);
        require 'view/' . $page . '.php';
    }
    function redirect ($page):void
    {
        header('Location:' . $page);
    }

