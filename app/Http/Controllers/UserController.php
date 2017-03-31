<?php
/**
 * Created by PhpStorm.
 * User: jthebault001
 * Date: 31/03/2017
 * Time: 13:04
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;
use Validator;


class UserController extends Controller
{
    public function displayUserHomePage($id){

        return view('userAccount');
    }

    public function displayAvatarCreationForm(){

        return 'Formulaire de creation d"un avatar';
    }

    public function createNewAvatar(){

        return 'Creation de l"avatar';
    }

    public function deleteAvatar(){

        return 'Suppression de l"avatar';
    }
}