<?php

require_once 'bootstrap.php';

$userName = request()->get('username');
$password = request()->get('password');
$confirmPassword = request()->get('confirm_password');

if($password != $confirmPassword){
    $session->getflashbag()->add('error', 'Passwords do not match.');
    redirect('/register.php');
}

$user = findIfUserExists($userName);

if(!empty($user)){
    $session->getflashbag()->add('error', 'User already exists.');
    redirect('/register.php');
};

$hashed = password_hash($password, PASSWORD_DEFAULT);

$user = createUser($userName, $hashed);

$session->getflashbag()->add('success', 'Accout Registered.');
redirect('doLogin.php');