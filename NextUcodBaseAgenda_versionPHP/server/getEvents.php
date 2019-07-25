<?php

/******************************************************************************
 * Modulo que permite recuperar los eventos del usuario que ha iniciado session
 *******************************************************************************/

require('./conector.php');

$con = new ConectorBD(); // coneccion a la base de datos
$response['conexion'] = $con->initConection(); // inicia coneccion a la base de datos 'agenda'

session_start();

// realiza la verificacion si se realizo la coneccion y existe la session
if ($response['conexion'] == 'OK' && isset($_SESSION['iduser']) ) {

  // consulta el usuario mencionado
  $resp_query = $con->getData(['events'],
                              ['*'],
                              'WHERE iduser="'.$_SESSION['iduser'].'"');

  // si existe base el registros ha encontrado al usuario buscado
  if ($resp_query && $resp_query->num_rows != 0) {
      $i=0;
      while ($row = $resp_query->fetch_assoc()) {
        $response['eventos'][$i]['id']   = $row['idevent'];
        $response['eventos'][$i]['title']= $row['title'];
        if($row['allday'] == 0){
          $response['eventos'][$i]['start']  = $row['startdate']." ".$row['starttime'];
          $response['eventos'][$i]['end']    = $row['enddate']." ".$row['endtime'];
          $response['eventos'][$i]['allDay'] = false;
        }else{
          $response['eventos'][$i]['start']  = $row['startdate'];
          $response['eventos'][$i]['allDay'] = true;
        }
        $i++;
      }
  }else{
    $response['eventos'] = [];
  }
  $response['msg'] = "OK";
}else{
    $response['msg'] = "Usuario no autorizado";
}

echo json_encode($response);

$con->cerrarConexion();

 ?>
