<?php
/*
  Copyright (C) 2006 Sid Srivastava 
 
  Permission is hereby granted, free of charge, to any person obtaining a
  copy of this software and associated documentation files (the
  "Software"), to deal in the Software without restriction, including
  without limitation the rights to use, copy, modify, merge, publish,
  distribute, sublicense, and/or sell copies of the Software, and to
  permit persons to whom the Software is furnished to do so, subject to
  the following conditions:
 
  The above copyright notice and this permission notice shall be included
  in all copies or substantial portions of the Software.
 
  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
  OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
  MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
  IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
  CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
  TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
  SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
?>
<?php
class photon_mysql_database
{
  var $username;
  var $password;
  var $database_name;
  var $server;
  var $database;

  function photon_mysql_database($username, $password, $database_name, $server)
  {
    $this->username = $username;
    $this->password = $password;
    $this->database_name = $database_name;
    $this->server = $server;
  }

  function select($table, $field, $value)
  {
    $this->database = mysql_connect($this->server, $this->username, $this->password);
    mysql_select_db($this->database_name, $this->database);
    $sql_query = "SELECT * FROM $table WHERE $field='" . addslashes($value) . "'";
    $result = mysql_query($sql_query, $this->database);
    return $result;
  }


  function select_less_limit($table, $field, $value)
  {
    $this->database = mysql_connect($this->server, $this->username, $this->password);
    mysql_select_db($this->database_name, $this->database);
    $sql_query = "SELECT * FROM $table WHERE $field < '" . addslashes($value) . "' LIMIT 1";
    $result = mysql_query($sql_query, $this->database);
    return $result;
  }

  function select_more_limit($table, $field, $value)
  {
    $this->database = mysql_connect($this->server, $this->username, $this->password);
    mysql_select_db($this->database_name, $this->database);
    $sql_query = "SELECT * FROM $table WHERE $field > '" . addslashes($value) . "' LIMIT 1";
    $result = mysql_query($sql_query, $this->database);
    return $result;
  }

  function select_all($table)
  {
    $this->database = mysql_connect($this->server, $this->username, $this->password);
    mysql_select_db($this->database_name, $this->database);
    $sql_query = "SELECT * FROM $table";
    $result = mysql_query($sql_query, $this->database);
    return $result;
  }

  function update($table, $field, $unique_value, $values)
  {
    $this->database = mysql_connect($this->server, $this->username, $this->password);
    mysql_select_db($this->database_name, $this->database);

    $sql_query = "UPDATE $table SET ";
    foreach($values as $key => $value)
      $sql_query .= "$key='$value',";
    // remove the last comma
    $sql_query = substr($sql_query, 0, strlen($sql_query)-1);    
    $sql_query .= " WHERE $field ='" . $unique_value . "'";
    $result = mysql_query($sql_query, $this->database);
    return $result;
  }

  function insert($table, $values)
  {
    $this->database = mysql_connect($this->server, $this->username, $this->password);
    mysql_select_db($this->database_name, $this->database);

    $sql_query = "INSERT INTO $table SET ";
    foreach($values as $key => $value)
      $sql_query .= "$key='$value',";
    /* Remove the last comma */
    $sql_query = substr($sql_query, 0, strlen($sql_query)-1);
    $result = mysql_query($sql_query, $this->database);
  }

  function query($sql_query)
  {
    $this->database = mysql_connect($this->server, $this->username, $this->password);
    mysql_select_db($this->database_name, $this->database);

    $result = mysql_query($sql_query, $this->database);
    return $result;
  }
}
?>
