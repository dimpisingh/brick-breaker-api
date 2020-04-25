<?php

class user extends base {
    protected static $table = 'users';

    public static function create ($data) {
        return self::save($data, self::$table);
    }

    public static function getByCredentials ($login, $password) {
        return App::$db->getOne(
            'SELECT * FROM ' . self::$table . ' WHERE (email = :email OR username = :username) AND password = :password LIMIT 1',
            [
                ':email' => $login,
                ':username' => $login,
                ':password' => $password
            ]
        );
    }

    public static function getByAuthToken ($token) {
        return App::$db->getOne(
            'SELECT u.* FROM authentication a JOIN ' . self::$table . ' u ON u.id = a.user_id WHERE a.token = :token LIMIT 1',
            [':token' => $token]
        );
    }

    public static function getExistent ($email, $username)
    {
        return App::$db->getOne(
            'SELECT email, username FROM ' . self::$table . ' WHERE email = :email OR username = :username LIMIT 1',
            [
                ':email' => $email,
                ':username' => $username
            ]
        );
    }

    public static function getLocation ($ip)
    {
        $location = json_decode(@file_get_contents('https://www.iplocate.io/api/lookup/' . $ip), true);

        return [
            'country_code' => $location['country_code'],
            'country_name' => $location['country']
        ];
    }
}
