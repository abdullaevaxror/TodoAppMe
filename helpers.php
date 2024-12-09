<?php
    function view ($page , $data=[]):void{
        extract($data);
        require 'view/' . $page . '.php';
}