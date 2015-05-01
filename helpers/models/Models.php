<?php

include_once(__DIR__ . '/../databases.php');


class Models implements ArrayAccess
{

  protected $table;

  protected $db_attributes = [];

  protected $container = [];

  protected $guarded = [];

  public function __construct($model_data = null)
  {

    if ($model_data) {
      $this->set_fillable($model_data);
    }

  }

  private function set_fillable($model_data)
  {
    $called_class = get_called_class();

    foreach ($model_data as $key => $val) {

      if (!in_array($key, $this->db_attributes)) {
        throw new Exception("Invalid attribute: '$key' for $called_class");

      }

      if (in_array($key, $this->guarded)) {
        throw new Exception("Can not set protected attribute: '$key' for $called_class ");
      }

      $this{$key} = $val;

    }
  }

  public function save()
  {
    $db = get_db();

    $fields_array = [];
    $params_array = [];
    $values = [];

    foreach ($this->container as $key => $val) {
      if (in_array($key, $this->db_attributes)) {
        $fields_array[] = $key;

        $params_array[] = '?';

        $values[] = $val;
      }
    }

    $fields = '(' . implode(',', $fields_array) . ')';

    $params = '(' . implode(',', $params_array) . ')';

    $stmt = $db->prepare(
      "insert into $this->table $fields VALUES $params"
    );


    $stmt->execute($values);

    return $this->id = $db->lastInsertId();
  }

  public function offsetExists($offset)
  {
    return isset($this->container[$offset]);

  }

  public function offsetGet($offset)
  {
    return $this->offsetExists($offset) ? $this->container[$offset] : null;
  }

  public function offsetSet($offset, $value)
  {
    $this->container[$offset] = $value;
  }

  public function offsetUnset($offset)
  {
    unset($this->container[$offset]);
  }

  function __set($name, $value)
  {
    $this->container[$name] = $value;
  }

  function __get($name)
  {
    return $this->container[$name];
  }

  function __isset($name)
  {
    return $this->offsetExists($name);
  }

  function __unset($name)
  {
    if ($this->offsetExists($name)) {
      unset($this->container[$name]);
    }
  }
}
