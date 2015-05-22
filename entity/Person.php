<?php
/**
 * Created by PhpStorm.
 * User: Morteza
 * Date: 5/16/2015
 * Time: 19:21
 */

include_once 'BaseEntityRepository.php';


class Person extends BaseEntityRepository
{
    public $id;
    public $username = '';
    public $age = '';
    public $name = '';
    public $family = '';

    public $_type = '';


    function __construct($username = null)
    {
        parent::__construct();
        if ($username != null) {
            $this->Load('username', $username);
        }
    }

}

