<?php

  /**
   * connection with database.
   */
  function connectionDataBase() {
    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);

    $dsn = 'mysql:host=' . $server . ';dbname=' . $db;
    $dbh = new PDO($dsn, $username, $password);

    return $dbh;
  }

  /**
   * create geolocation table.
   *
   * @return true|false success or failed.
   */
  function createTable($dbh) {
    $tableName = "geolocation_log";
    $column = (
      "id INT(10) AUTO_INCREMENT PRIMARY KEY, " . 
      "latitude TEXT NOT NULL, " .
      "longitude TEXT NOT NULL, " .
      "address TEXT NOT NULL, " . 
      "created_at TEXT NOT NULL"
    );

    try {
      $dbh->exec(
        "CREATE TABLE IF NOT EXISTS $db.$tableName ($column) "
      );
      return true;
    } catch (PDOException $e) {
      // echo $e->getMessage();
      return false;
    }
  };

  /**
   * validate instert params
   *
   * @return true|false success or failed.
   */
  function validationInsertParams() {
    if (empty($_POST['latitude']) &&
        empty($_POST['longitude']) &&
        empty($_POST['address'])) {
      return false;
    }
    else {
      return true;
    } 
  }

  /**
   * insert geolocation data to log table.
   *
   * @param $dbh database connection.
   * @return true|false success or failed.
   */
  function insertGeolocationDataToLog($dbh) {
    $latitude = $dbh->quote($_POST['latitude'], PDO::PARAM_STR);
    $longitude = $dbh->quote($_POST['longitude'], PDO::PARAM_STR);
    $address = $dbh->quote($_POST['address'], PDO::PARAM_STR);
    $created_at = strftime("%Y-%m-%d %H:%M:%S");

    $sql = "
      INSERT INTO geolocation_log
        (latitude, longitude, address, created_at)
      VALUES(:latitude, :longitude, :address, :created_at);
    ";

    try {

      $sth = $dbh->prepare($sql, 
        array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));

      $sth->execute(
        array(':latitude' => preg_replace("/^'|'$/", "", $latitude),
              ':longitude' => preg_replace("/^'|'$/", "", $longitude),
              ':address' => preg_replace("/^'|'$/", "", $address),
              ':created_at' => $created_at
            ));
      $dbh->commit();
      return true;

    } catch (PDOException $e) {
      // echo $e->getMessage();
      return false;
    }
  }

  /**
   * get geolocation from log table.
   *
   * @param $dbh database connection object
   * @return string result from table
   */
  function getGeolocationLog($dbh) {
    $r = array();
    $rows = $dbh->query(
      "SELECT id, latitude, longitude, address, created_at 
        FROM geolocation_log
        ORDER BY created_at DESC "
    );
    foreach($rows as $row) {
      array_push($r, array(
        'id' => $row['id'],
        'latitude' => $row['latitude'],
        'longitude' => $row['longitude'],
        'address' => $row['address'],
        'created_at' => $row['created_at']
      ));
    } 
    return json_encode($r);
  }

  function main() {
    $con = connectionDataBase();
    createTable($con);

    if (!empty($_POST['vote'])) {
      if (validationInsertParams() === false) {
        echo json_encode(array("status" => "NG"));
      }
      else {
        insertGeolocationDataToLog($con);
        echo json_encode(array("status" => "OK"));
      }
    }

    if ($_GET['list']) {
      echo getGeolocationLog($con);
    }

  }
  
  main();
?>
