<?php

class User
{
    protected static $db_table = "users";
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;

    public static function find_all_user()
    {
        global $database;
        $result_set = self::find_this_query("SELECT * FROM users");
        return $result_set;
    }

    public static function find_user_by_id($id)
    {

        global $database;
        $result_set = self::find_this_query("SELECT * FROM users WHERE id= '$id' LIMIT 1");
        return !empty($result_set) ? array_shift($result_set) : false;

    }

    public static function find_this_query($sql)
    {
        global $database;
        $result_set = $database->query($sql);
        $the_object_array = array();
        while ($row = mysqli_fetch_array($result_set)) {
            $the_object_array[] = self::instantiation($row);
        }
        return $the_object_array;
    }

    public static function verify_user($username, $password)
    {
        global $database;
        $username = $database->escape_string($username);
        $password = $database->escape_string($password);

        $sql = "SELECT * FROM users WHERE ";
        $sql .= "username = '{$username}' ";
        $sql .= "AND password = '{$password}' ";
        $sql .= "LIMIT 1";

        $the_result_array = self::find_this_query($sql);

        return !empty($the_result_array) ? array_shift($the_result_array) : false;
    }

    public static function instantiation($record)
    {
        $object = new self;

        foreach ($record as $attribute => $value) {
            if ($object->hasAttribute($attribute)) {
                $object->$attribute = $value;
            }
        }

        return $object;
    }

    private function hasAttribute($attribute)
    {
        $object_properties = get_object_vars($this);

        return array_key_exists($attribute, $object_properties);
    }

    protected function properties()
    {
        return get_object_vars($this);
    }

    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()
    {
        global $database;

        $properties = $this->properties();

        $sql = "INSERT INTO " . self::$db_table . "(". implode(",",array_keys($properties)).") ";
        $sql .= "VALUES ('".implode("','",array_values($properties))."')";
        // $sql .= $database->escape_string($this->username) . "', '";
        // $sql .= $database->escape_string($this->password) . "', '";
        // $sql .= $database->escape_string($this->first_name) . "', '";
        // $sql .= $database->escape_string($this->last_name) . "')";

        if ($database->query($sql)) {
            $this->id = $database->the_insert_id();
            return true;
        } else {
            return false;
        }

    }

    public function update()
    {
        global $database;

        $sql = "UPDATE " . self::$db_table . " SET ";
        $sql .= "username= '" . $database->escape_string($this->username) . "', ";
        $sql .= "password= '" . $database->escape_string($this->password) . "', ";
        $sql .= "first_name= '" . $database->escape_string($this->first_name) . "', ";
        $sql .= "last_name= '" . $database->escape_string($this->last_name) . "' ";
        $sql .= " WHERE id= " . $database->escape_string($this->id);

        $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;
    }

    public function delete()
    {
        global $database;

        $sql = "DELETE FROM " . self::$db_table . " ";
        $sql .= "WHERE id=" . $database->escape_string($this->id);
        $sql .= " LIMIT 1";

        $database->query($sql);

        return (mysqli_affected_rows($database->connection) === 1) ? true : false;
    }

}
