<?php
/**********************************************************************
 * Elimina un evento en la agenda del usuario en la base de datos
 **********************************************************************/

require('./conector.php');

$con = new ConectorBD(); // coneccion a la base de datos
$response['conexion'] = $con->initConection(); // inicia coneccion a la base de datos 'agenda'

session_start();

// realiza la verificacion si se realizo la coneccion y existe la session
if ($response['conexion'] == 'OK' && isset($_SESSION['iduser']) && $_POST['id'] >= 0) {
  // eliminamos el evento en la base de datos
  if($con->deleteData('events', 'idevent='.$_POST['id'])){
    $response['msg'] = "OK";
  }else{
    $response['msg']= "Error en el servidor, no se ha eliminado el evento";
  }
  //$response['msg'] = $con->insertData('events', $data);
}else{
  $response['msg'] = "No se ha eliminado el evento";
}

echo json_encode($response); //  envia la respuesta

$con->cerrarConexion();

 ?>
