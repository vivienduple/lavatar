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

    /*method that display the new avatar creation form*/
    public function displayAvatarCreationForm(){

        return view('avatarCreationForm');
    }

    /*method that create a new avatar (email/image) on form submission*/
    public function createNewAvatar(Request $request){

        $dataFromForm = $request->all();
        $validator = Validator::make($dataFromForm, ['email' => 'required | email',
            'file'  => 'required | image | dimensions:min_width=128,min_height=128,max_width=256,max_height=256']);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }


        return "je viens de creer un nouvel avatar avec mon formulaire";
    }

    public function deleteAvatar(){

        return ""/*view('userAccount', ['avatars' => $this->avatars])*/;
    }
}