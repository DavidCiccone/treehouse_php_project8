<?php

function findIfUserExists($username){
    global $db;
    
    try{
        $query = "SELECT * FROM users WHERE name = :username";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        if(isset($query)){
            return "this name already exists";
        }
    } catch(\Exception $e){
        throw $e;
    }
}

function createUser($username, $password){
    global $db;
     
    try{
        $query = "INSERT INTO users (name, password) VALUES (:user, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return findIfUserExists($username);
     }catch(\Exception $e){
         throw $e;
     }
}

function isAuthenticated() {
    if(!request()->cookies->has('access_token')) {
        return false;
    }

    try {
        \Firebase\JWT\JWT::$leeway = 1;
        \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
        return true;
    } catch(\Exception $e){
        return false;
    }
}

function requireAuth() {
    if(!isAuthenticated()) {
        $accessToken = new \Symfony\Component\HttpFoundation\Cookie("access_token", "Expired", time()-3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('/login.php', ['cookies' => [$accessToken]]);
    }
}