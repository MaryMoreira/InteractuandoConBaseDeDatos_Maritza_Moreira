<?php
/**********************************************************************
 * Inserta un nuevo evento en la agenda del usuario en la base de datos
 **********************************************************************/

require('./conector.php');

$con = new ConectorBD(); // coneccion a la base de datos
$response['conexion'] = $con->initConection(); // inicia coneccion a la base de datos 'agenda'

session_start();

// realiza la verificacion si se realizo la coneccion y existe la session
if ($response['conexion'] == 'OK' && isset($_SESSION['iduser']) ) {
  // preparamos el registro a insertarse
  $data['iduser']    = $_SESSION['iduser'];
  $data['title']     = "'".$_POST['titulo']."'";
  $data['startdate'] = "'".$_POST['start_date']."'";
  if($_POST['allDay']){ // solo inserta lo que se espera
    $data['allday']    = 1;
  }else{
    $data['starttime'] = "'".$_POST['start_hour'].":00'";
    $data['enddate']   = "'".$_POST['end_date']."'";
    $data['endtime']   = "'".$_POST['end_hour'].":00'";
    $data['allday']    = 0;
  }
  // insertamos el evento en la base de datos
  if($con->insertData('events', $data)){
    $response['msg'] = "OK";

    // obtiene el id del evento autogenerado
    $resp_query = $con->getData(['events'],
                                ['idevent'],
                                'WHERE iduser='.$data['iduser'].' AND title='.$data['title'].' AND startdate='.$data['startdate']);
    if ($resp_query && $resp_query->num_rows != 0) {
        $row = $resp_query->fetch_assoc();
        $response['id'] = $row['idevent']; // coloca el id del evento
    }
  }else{
    $response['msg']= "Hubo un error y los datos no han sido cargados";
  }
  //$response['msg'] = $con->insertData('events', $data);
}else{
  $response['msg'] = "Usuario no autorizado";
}

echo json_encode($response); //  envia la respuesta

$con->cerrarConexion();

 ?>
