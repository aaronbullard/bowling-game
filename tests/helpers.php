<?php

if (!function_exists('dd')) {
    function dd($payload){
        print_r($payload);
        echo PHP_EOL;
        die;
    }
}

if (!function_exists('disp')) {
    function disp($payload){
        print_r($payload);
        echo PHP_EOL;
    }
}