<?php

  /******************************************************************************
   * Clase que maneja la conexion con la base de datos MySQL utilizando 'mysqli'
   *******************************************************************************/


  class ConectorBD
  {
    private $host;
    private $user;
    private $password;
    private $conexion;

    function __construct($host, $user, $password){
      $this->host = $host;
      $this->user = $user;
      $this->password = $password;
    }

    // inicializamos la coneccion con la base de datos mysql ()
    function initConection($db){
      $this->conexion = new mysqli($this->host, $this->user, $this->password, $db);
      if ($this->conexion->connect_errno) {
        return "Error:" . $this->conexion->connect_error;
      }else {
        return "OK";
      }
    }

    // crea una nueva tabla en la base de datos
    function newTable($nombre_tbl, $campos){
      $sql = 'CREATE TABLE '.$nombre_tbl.' (';
      $length_array = count($campos);
      $i = 1;
      foreach ($campos as $key => $value) {
        $sql .= $key.' '.$value;
        if ($i!= $length_array) {
          $sql .= ', ';
        }else {
          $sql .= ');';
        }
        $i++;
      }
      return $this->execQuery($sql);
    }

    // ejecuta un query (cualquier query: Select, Alter, Create....)
    function execQuery($query){
      return $this->conexion->query($query);
    }

    // cierra la coneccion de la base de datos
    function cerrarConexion(){
      $this->conexion->close();
    }

    // crea una nueva restriccion
    function nuevaRestriccion($tabla, $restriccion){
      $sql = 'ALTER TABLE '.$tabla.' '.$restriccion;
      return $this->execQuery($sql);
    }

    // añade una nueva realacion (asocia el foreing key de una tabla)
    function nuevaRelacion($from_tbl, $to_tbl, $from_field, $to_field){
      $sql = 'ALTER TABLE '.$from_tbl.' ADD FOREIGN KEY ('.$from_field.') REFERENCES '.$to_tbl.'('.$to_field.');';
      return $this->execQuery($sql);
    }

    // inserta registros a una tabla
    function insertData($tabla, $data){
      $sql = 'INSERT INTO '.$tabla.' (';
      $i = 1;
      //  data[nombre_campo] = valor_campo
      // asocia los campos a insertarse
      foreach ($data as $key => $value) {
        $sql .= $key;
        if ($i<count($data)) {
          $sql .= ', ';
        }else $sql .= ')';
        $i++;
      }
      $sql .= ' VALUES (';
      $i = 1;
      // coloca los datos de los campos a insertarse
      foreach ($data as $key => $value) {
        $sql .= "\"".$value;
        if ($i<count($data)) {
          $sql .= "\"".', ';
        }else $sql .= "\"".');';
        $i++;
      }
      //return $sql;
      return $this->execQuery($sql);
    }

    // obtiene la conexion de la base de datos
    function getConection(){
      return $this->conexion;
    }

    // actualiza un registro en la base de datos
    function updateData($tabla, $data, $condicion){
      $sql = 'UPDATE '.$tabla.' SET ';
      $i=1;
      foreach ($data as $key => $value) {
        $sql .= $key.'='.$value;
        if ($i<sizeof($data)) {
          $sql .= ', ';
        }else $sql .= ' WHERE '.$condicion.';';
        $i++;
      }
      return $this->execQuery($sql);
    }

    // elimina registros de la tabla pasado como datos
    function deleteData($tabla, $condicion){
      $sql = "DELETE FROM ".$tabla." WHERE ".$condicion.";";
      return $this->execQuery($sql);
    }

    // realiza una consulta a la base de datos, query
    function getData($tablas, $campos, $condicion = ""){
      $sql = "SELECT ";
      $size  = count($campos);
      $count = 0; // coloca los campos requeridos del query
      foreach ($campos as $key => $value) {
        $count += 1;
        $sql .= $value;
        if ($count!=$size) {
          $sql.=", ";
        }else $sql .=" FROM ";
      }
      $size  = count($tablas);
      $count = 0; // coloca las tablas de la que se hara el query
      foreach ($tablas as $key => $value) {
        $count += 1;
        $sql .= $value;
        if ($count!=$size) {
          $sql.=", ";
        }else $sql .= " ";
      }

      if ($condicion == "") { // coloca la condicion del query
        $sql .= ";";
      }else {
        $sql .= $condicion.";";
      }

      return $this->execQuery($sql);
    }


    function getViajesUser($user_id){
      $sql = "SELECT co.nombre AS ciudad_origen, cd.nombre AS ciudad_destino, v.placa AS placa, v.fabricante AS fabricante, v.referencia AS referencia, a.fecha_salida AS fecha_salida, a.fecha_llegada AS fecha_llegada, a.hora_salida AS hora_salida, a.hora_llegada AS hora_llegada
              FROM viajes AS a
              JOIN ciudades AS co ON co.id = a.fk_ciudad_origen
              JOIN ciudades AS cd ON cd.id = a.fk_ciudad_destino
              JOIN vehiculos AS v ON v.placa = a.fk_vehiculo
              WHERE a.fk_conductor = ".$user_id.";";
      return $this->execQuery($sql);
    }

  }

 ?>
