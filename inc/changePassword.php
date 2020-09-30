<?php

require_once 'bootstrap.php';
requireAuth();

$currentPassword = request()->get('current_password');
$newPassword = request()->get('password');
$confirmPassword = request()->get('confirm_password');

if($newPassword != $confirmPassword){
    $session->getflashbag()->add('error', 'Passwords do not match.');
    redirect('/account.php');
}

$user = findUserByAccessToken();

if(empty($user)){
    $session->getFlashBag()->add('error', 'user is empty');
    redirect('/account.php');
}

if(!password_verify($currentPassword, $user['password'])) {
    $session->getFlashBag()->add('error', 'Current password is incorrect, please try again.');
    redirect('/account.php');
}

$updated = updatePassword(password_hash($newPassword, PASSWORD_DEFAULT), $user['id']);

if(!$updated){
    $session->getFlashBag()->add('error', 'Could not updated password, please try again.');
    redirect('/account.php');
}

$session->getFlashBag()->add('success', 'Password updated');
redirect('/account.php');
