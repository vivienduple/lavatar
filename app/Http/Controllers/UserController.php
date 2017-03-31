<?php
/**
 * Created by PhpStorm.
 * User: jthebault001
 * Date: 31/03/2017
 * Time: 13:04
 */

namespace App\Http\Controllers;

use App\User;
use App\Avatar;
use Illuminate\Http\Request;
use Debugbar;
use Validator;


class UserController extends Controller
{
    /* list of the avatars related to the user account*/
    private  $avatars;

    /*method that display the dashboard of the logged user*/
    public function displayUserHomePage($id){

        $this->avatars = User::find(Auth::id)->avatars();
        return view('userAccount', ['avatars' => $this->avatars]);
    }

    public function displayAvatarCreationForm(){
            return 'Formulaire de creation d"un avatar';
    }

    public function createNewAvatar(){

        return view('userAccount', ['avatars' => $this->avatars]);
    }

    public function deleteAvatar(){

        return view('userAccount', ['avatars' => $this->avatars]);
    }
}