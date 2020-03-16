<?php


namespace App\Services;

use \App\Models\User;

class UserService
{
    /**
     * Get user by params
     *
     * @param $whereArray array
     *
     * @return User|bool
     */
    public function getUserByWhere($whereArray) {
        $user = new User();
        $fields = $user->getFieldKeys();
        $db = getConnection();

        //prepare query
        $query = "SELECT * FROM `users` WHERE ";
        $queryArray = [];
        foreach ($whereArray as $key => $value) {
            $queryArray[] .= "`$key` = :$key ";
        }
        $query .= implode(' AND ', $queryArray);
        $query .= ' LIMIT 1';

        //bind params
        $query = $db->prepare($query);
        foreach ($whereArray as $key => &$value) {
            $query->bindParam(":$key", $value);
        }

        //execute
        $query->execute();
        $result = $query->fetch();

        //map result with model
        if ($result) {
            foreach ($result as $key => $value) {
                if (in_array($key, $fields)) {
                    $user->{$key} = $value;
                }
            }
        } else {
            $user = false;
        }

        return $user;
    }

    /**
     * Add new user
     *
     * @param User $user
     *
     * @throws \Exception
     */
    public function addUser(User $user) {
        $fields = $user->getFieldKeys();
        $db = getConnection();

        $bindParamsArray = [];
        foreach ($fields as $key => $field) {
            if (isset($user->{$field})) {
                $bindParamsArray[":$field"] = $user->{$field};
            } else {
                unset($fields[$key]);
            }
        }
        $query = "INSERT INTO `users` (" . implode(', ', $fields)
            . ") VALUES (" . implode(', ', array_keys($bindParamsArray)) . ")";

        $query = $db->prepare($query);

        $result = $query->execute($bindParamsArray);
        if ($result === false) {
            throw new \Exception();
        }
    }

    /**
     * Get the Auth
     *
     * @return UserService
     */
    public static function Get() {
        if (!self::$_Instance) {
            self::$_Instance = new self();
        }
        return self::$_Instance;
    }

    private static $_Instance;
}