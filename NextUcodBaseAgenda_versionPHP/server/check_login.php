<?php

require('./conector.php');
require('./create_user.php');

$con = new ConectorBD('localhost','mary','12345'); // coneccion a la base de datos

$response['conexion'] = $con->initConection('agenda'); // inicia coneccion a la base de datos 'agenda'

// realiza la verificacion si se realizo la coneccion
if ($response['conexion'] == 'OK') {

  $createUser = new CreateUsers($con);
  $respuesta = $createUser->init(); // crea los usuarios por omision del sistema, si no existen aun

  // consulta el usuario mencionado
  $resp_query = $con->getData(['users'],
                              ['email', 'password'],
                              'WHERE email="'.$_POST['username'].'"');

  // si existe base el registros ha encontrado al usuario buscado
  if ($resp_query && $resp_query->num_rows != 0) {
    $row = $resp_query->fetch_assoc();
    if (password_verify($_POST['password'], $row['password'])) {
      $response['msg'] = 'OK'; // permite el ingreso e inicia la sesion
      session_start();
      $_SESSION['username']=$row['email'];
    }else {
      $response['msg'] = 'Contraseña incorrecta';
    }
  }else{
    $response['msg'] = 'Email incorrecto';
  }

}else{
    $response['msg'] = 'Sin conexión a Base de Datos';
}

echo json_encode($response);

$con->cerrarConexion();

 ?>
