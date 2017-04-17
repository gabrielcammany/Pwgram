<?php
/**
 * Created by PhpStorm.
 * User: Xps_Sam
 * Date: 12/04/2017
 * Time: 1:48
 */

namespace PwGram\Controller;


use PwGram\Model\SignUp;
use PwGram\Model\upload;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
//use PwGram\Model;


class RegistrationController
{

    public function registrationController(Application $app, Request $request){
        //echo "LLEGO AL CONTROLLER";
        $signUp = new SignUp($request);

        return $signUp->registerUser();
   }
    public function uploadImage(Request $request){
        //echo "LLEGO AL CONTROLLER";
        $upload = new upload($request);

        return $upload->saveImage();
    }
}