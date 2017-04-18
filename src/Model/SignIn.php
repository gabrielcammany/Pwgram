<?php
/**
 * Created by PhpStorm.
 * User: Xps_Sam
 * Date: 17/04/2017
 * Time: 18:37
 */

namespace PwGram\Model;


class SignIn
{
    private $request;
    private $status=0;

    public function __construct($request)
    {
        $this->request = $request;
        return $this;
    }

    public function signIn()
    {
        $json = json_decode($_POST['myData']);
        $i = 0;
        $email = "";
        $password = "";
        $username = "";
        $result = array();
        foreach ($json as $key => $value) {
            if (!is_array($value)) {
                switch ($i) {
                    case 0:
                        $email = $value;
                        break;
                    case 1:
                        $username = $value;

                        break;
                    case 2:
                        $password = $value;

                        break;
                }
                $i++;
            }
        }
        $db = new \PDO('mysql:host=localhost;dbname=pwgram', "homestead", "secret");
        /*
         * Nos aseguramos de realizar el inicio de sesion con el email o username
         */
        if (($this->validaEmail($email) || $this->validaUsername($username)) && $this->validatePassword($password)) {
            $stmt = $db->prepare('SELECT * FROM user WHERE (email=? OR username=?) AND password = ?');
            $stmt->bindParam(1, $email, \PDO::PARAM_STR);
            $stmt->bindParam(2, $username, \PDO::PARAM_STR);
            $stmt->bindParam(3, $password, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (sizeof($result['id']) == 0) {
                $this->status = 11;
            } else {

                $stmt = $db->prepare('UPDATE user SET active="1" WHERE email=? OR username=?');
                $stmt->bindParam(1, $email, \PDO::PARAM_STR);
                $stmt->bindParam(2, $username, \PDO::PARAM_STR);
                $stmt->execute();
                $this->status = 10;
            }
        }
        $result["status"] = $this->status;
        return json_encode($result);
    }


/*if(($email!=' ')==1){
    if($this->validaEmail($email) && $this->validatePassword($password)){
        $stmt = $db->prepare('SELECT * FROM user WHERE email=? AND password = ?');
        $stmt->bindParam(1,$email,\PDO::PARAM_STR);
        $stmt->bindParam(2,$password,\PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if(sizeof($result['id'])== 0) {
            return 11;//No se encuentra el respectivo email o contraseña
        }else{
            /*
             * Actualizamos el estado del usuario
             *
            $stmt = $db->prepare('UPDATE user SET active="1" WHERE email=?');
            $stmt->bindParam(1,$email,\PDO::PARAM_STR);
            $stmt->execute();
            return $result;
        }
    }else{
        return 12;
    }
}else{
/*
* PETA LA VALIDACION DEL NOMBRE DE USUARIO PERO NI IDEA DE PK!
*
    //if(/*$this->validaUsername($username)==true&&*$this->validatePassword($password)==true){

        $stmt = $db->prepare('SELECT * FROM user WHERE username=? AND password = ?');
        $stmt->bindParam(1,$username,\PDO::PARAM_STR);
        $stmt->bindParam(2,$password,\PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if(sizeof($result['id'])== 0) {
            return 12;//No se encuentra el respectivo username o contraseña
        }else{
            $stmt = $db->prepare('UPDATE user SET active="1" WHERE username=?');
            $stmt->bindParam(1,$username,\PDO::PARAM_STR);
            $stmt->execute();
            return json_encode($result);
        }
  //  }else{
   //      return 13;
  //  }
}*/

    function validaEmail($v1){
        if(filter_var($v1,FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            $this->status = 3;//formato de email incorrecto
            return false;
        }
    }

    function validaUsername($v1){
        $longitud = strlen($v1);

        if(!ctype_alnum($v1) && $longitud > 20) {
            $this->status = 4;//formato de nombre del usuario incorrecto
            return false;
        }

        return true;

    }

    function validatePassword($v1){

        if (strlen($v1) < 6 || strlen($v1) > 12 ) {
            $this->status = 7;//contraseña incorrecta

            return false;
        }
        if (!preg_match("#[a-z]+#",$v1)) {
            $this->status = 7;//contraseña incorrecta

            return false;
        }
        if (!preg_match("#[A-Z]+#",$v1)) {
            $this->status = 7;//contraseña incorrecta

            return false;
        }

        if (!preg_match("#[0-9]+#",$v1)) {
            $this->status = 7;//contraseña incorrecta

            return false;
        }
        return true;
    }
}