<?php
/******************************************************************************
 * Modulo que crea los usuarios por omision
 *******************************************************************************/

class CreateUsers
{
    private $conexion;

    function __construct($conexion){
      $this->conexion = $conexion;
    }

    // inicializamos la coneccion con la base de datos mysql ()
    function init(){
        // inserta los usuarios si no existen aun
        $this->insertUser('12345', 'Maritza Moreira', 'maritza@nextui.com', '1983-07-23');
        $this->insertUser('12345', 'Tutor 1', 'tutor1@nextui.com', '1973-12-25');
        $this->insertUser('12345', 'Tutor 2', 'tutor2@nextui.com', '1977-1-14');
    }

    // inserta el usuario requerido en la base de datos si no existe aun
    function insertUser($pass, $name, $email, $birthdate){
        $data['password']  = "'".password_hash($pass, PASSWORD_DEFAULT)."'";
        // consulta el usuario mencionado si existe caso contrario le creamos
        $data['email']     = "'".$email."'";
        $resp  =  $this->conexion->getData(['users'],
                                           ['email'],
                                           'WHERE email='.$data['email']);

        if(!$resp || $resp->num_rows == 0) { // inserta el registro si no existe
            $data['name']      = "'".$name."'";
            $data['birthdate'] = "'".$birthdate."'";
            $this->conexion->insertData('users', $data);
        }
    }
}


 ?>
