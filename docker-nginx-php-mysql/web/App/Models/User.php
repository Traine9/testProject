<?php
namespace App\Models;

class User
{
    protected $table = 'users';
    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private $_fields = [
        'id', 'name', 'email', 'password','image'
    ];

    function getFieldKeys() {
        return $this->_fields;
    }

}