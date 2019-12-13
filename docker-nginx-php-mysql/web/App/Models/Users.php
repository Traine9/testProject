<?php

namespace App\Models;

use \Core\Model;
use PDO;

/**
 * Users model
 *
 * PHP version 7.0
 */
class Users extends Model
{
    /**
     * Create table Users
     *
     * @return array
     */
    public static function createTable()
    {
        $db = static::getDB();
        $Create_Table_query ="create table if not exists Users";
        $Create_Table_query .= "(
                                  ID               int auto_increment primary key,
                                  First_Name       varchar(40)  null,
                                  Last_Name        varchar(50)  null,
                                  eMail            varchar(70)  not null UNIQUE,
                                  File             varchar(230) null,
                                  Password         varchar(255) not null,
                                  Activation_Key   varchar(255) not null,
                                  Status           varchar(20)  not null
                                )";
        $stmt = $db->query($Create_Table_query);
        return $stmt;
    }


    /**
     * get all data from Users table
     *
     * @return array
     */
    public static function getData(){
        $db = static::getDB();

        $Select_All = "select * from `Users`;";
        $stmt = $db->query($Select_All);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function getUser_eMail_Where($where, $it ){
        $db = static::getDB();
        if ( !empty($where) and !empty($it) ){
            $get_eMail_query = "select `eMail` from `Users` where `$where`='$it'";

            $stmt = $db->query($get_eMail_query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
        else{
            return "eMail is an empty";
        }
    }


    public static function getFirstName_Where($where, $it ){
        $db = static::getDB();
        if ( !empty($where) and !empty($it) ){
            $get_query = "select `First_Name` from `Users` where `$where`='$it'";

            $stmt = $db->query($get_query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
        else{
            return "eMail is an empty";
        }
    }


    public static function getActivationKey( $key ){
        $db = static::getDB();
        if ( !empty($key) ){
            $get_key_query = "select `Activation_Key` from Users where `Activation_Key`='$key'";

            $stmt = $db->query($get_key_query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
        else{
            return "eMail is an empty";
        }
    }


    public static function getUser_Password( $login ){
        $db = static::getDB();
        if ( !empty($login) ){
            $get_eMail_query = "select `Password` from `Users` where `eMail`='$login'";

            $stmt = $db->query($get_eMail_query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
        else{
            return "eMail is an empty";
        }
    }


    public static function getStatus( $eMail ){
        $db = static::getDB();
        if ( !empty($eMail) ){
            $get_eMail_query = "select `Status` from `Users` where `eMail`='$eMail'";

            $stmt = $db->query($get_eMail_query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
        else{
            return "eMail is an empty";
        }
    }


    /**
     * get all data from Users table
     *
     * @return array
     */
    public static function setValues( array $Values ){
        $db = static::getDB();

        if ( !empty($Values) ){
            $SetValues_query = "insert into `Users`(First_Name, Last_Name, eMail, File, Password, Activation_key, Status) ";
            $SetValues_query .= "values(:First_Name, :Last_Name, :eMail, :File, :Password, :Activation_key, 'not activated')";

            $stmt = $db->prepare($SetValues_query);
//            $stmt = $db->query($SetValues_query);

            return $stmt->execute($Values);
        }
        else{
            return "Data is an empty";
        }
    }


    /**
     * get all data from Users table
     *
     * @return array
     */
    public static function UpdateStatus( $key ){
        $db = static::getDB();

        if ( !empty($key) ){
            $Update_query = "update Users set `Status`='activate' WHERE `Activation_Key`='$key'";

            return $db->query($Update_query);
        }
        else{
            return "Data is an empty";
        }
    }
}