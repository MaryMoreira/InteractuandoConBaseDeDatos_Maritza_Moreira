<?php
/**********************************************************************
 * Actualiza un evento en la agenda del usuario en la base de datos
 **********************************************************************/

require('./conector.php');

$con = new ConectorBD(); // coneccion a la base de datos
$response['conexion'] = $con->initConection(); // inicia coneccion a la base de datos 'agenda'

session_start();

// realiza la verificacion si se realizo la coneccion y existe la session
if ($response['conexion'] == 'OK' && isset($_SESSION['iduser']) && $_POST['id'] >= 0) {

    // obtiene el evento en la base de datos
    $resp_query = $con->getData(['events'],
                                ['allday'],
                                'WHERE idevent='.$_POST['id']);

   if ($resp_query && $resp_query->num_rows != 0) {
        $row = $resp_query->fetch_assoc();
        $data['startdate'] = "'".$_POST['start_date']."'";
        if($row['allday'] == 0){
            $data['starttime'] = "'".$_POST['start_hour']."'";
            $data['enddate']   = "'".$_POST['end_date']."'";
            $data['endtime']   = "'".$_POST['end_hour']."'";
        }
        // actualiza el registro en la base de datos
        if($con->updateData('events', $data, 'idevent='.$_POST['id'])){
            $response['msg'] = "OK";
        }else{
            $response['msg']= "El evento no se actualizo en la base de datos";
        }

    }else{
        $response['msg'] = "El evento no existe en la base de datos";
    }
    //$response['msg'] = $con->insertData('events', $data);
}else{
  $response['msg'] = "Datos innecesarios para actualizar el evento";
}

echo json_encode($response); //  envia la respuesta

$con->cerrarConexion();

 ?>
