<?php

class User
{
    private $id;
    private $nickname;
    private $email;
    private $loginTime;

    public function __get($property_name)
    {
        if(isset($this->$property_name))
        {
            return($this->$property_name);
        }
        else
        {
            return(NULL);
        }
    }

    public function __set($property_name, $value)
    {
        $this->$property_name = $value;
    }
}