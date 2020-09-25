<?php

require_once 'bootstrap.php';

$user = findIfUserExists(request()->get('username'));

if(empty($user)) {
    redirect('/login.php');
}

if(!password_verify(request()->get('password'), $user['password'])) {
    redirect('/login.php');
}

$expTime = time() + 3600;

$jwt = \Firebase\JWT\JWT::encode([
    'iss' => request()->getBaseUrl(),
    'sub' => "{$user['id']}",
    'exp' => $expTime,
    'iat' => time(),
    'nbf' => time(),
    'is_admin' => $user['role_id'] == 1
], getenv("SECRET_KEY"),'HS256');

$accessToken = new Symfony\Component\HttpFoundation\Cookie('access_token', $jwt, $expTime, '/', getenv('COOKIE_DOMAIN'));

redirect('/',['cookies' =>[$accessToken]]);

