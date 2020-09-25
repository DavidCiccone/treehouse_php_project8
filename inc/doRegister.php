<?php

require_once 'bootstrap.php';

$userName = request()->get('username');
$password = request()->get('password');
$confirmPassword = request()->get('confirm_password');

if($password != $confirmPassword){
    redirect('/register.php');
}

$user = findIfUserExists($userName);

if(!empty($user)){
    redirect('/register.php');
};

$hashed = password_hash($password, PASSWORD_DEFAULT);

$user = createUser($userName, $hashed);

redirect('../index.php');