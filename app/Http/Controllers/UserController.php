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

        return view('avatarCreationForm');return view('avatarCreationForm', ['msg' => '']);
    }

    /*method that create a new avatar (email/image) on form submission*/
    public function createNewAvatar(Request $request){

        // validation of the file: size, dimensions, extension
        $dataFromForm = $request->all();
        $validator = Validator::make($dataFromForm, ['email' => 'required | email',
            'file'  => 'required | image | dimensions:min_width=128,min_height=128,max_width=256,max_height=256']);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        // check of the extension format jpg or png
        $fileComponents = explode('.', $_FILES['file']['name']);
        $extension = strtolower($fileComponents[count($fileComponents)-1]);

        $fileName = basename($_FILES['file']['name']);
        $pos = strripos($fileName , '.');
        // the name of the file without extension
        $baseFileName = substr ( $fileName , 0 , $pos );

        // creation of image files associated to the avatar
        // the image is stored in the directory below
        $dossier = '/users/web/infol/jthebault001/WWW/lavatar-2/storage/app/public/images/';
        $tmpFileName = $_FILES['file']['tmp_name'];

        if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'pjpg' || $extension == 'pjpeg' || $extension == 'png'){

            $savedFileName = $dossier.$baseFileName.'_ori'.'.'.$extension;
            move_uploaded_file($tmpFileName, $savedFileName);

            $refImageSize = getimagesize($savedFileName);
            $newWidth= 128;
            $newHeight= 128;
            $reduction = ( ($newWidth * 100)/$refImageSize[0] );

            $avatarImageName = '';



            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'pjpg' || $extension == 'pjpeg' ) {

                // transformer la photo au bon format
                $refImage = imagecreatefromjpeg($savedFileName);
                $newImage = imagecreatetruecolor($newWidth, $newHeight) or die ("Erreur");
                imagecopyresampled($newImage, $refImage, 0, 0, 0, 0, $newWidth, $newWidth, $refImageSize[0], $refImageSize[1]);
                imagedestroy($refImage);
                //TODO
                // mettre le bon nom de fichier
                $avatarImageName = time($baseFileName) . '.' . $extension;
                imagejpeg($newImage, $dossier . $avatarImageName, 100);
            }
            else{//$extension == 'png'
                $refImage = imagecreatefrompng($savedFileName);
                $newImage = imagecreatetruecolor($newWidth, $newHeight) or die ("Erreur");
                imagecopyresampled($newImage, $refImage, 0, 0, 0, 0, $newWidth, $newWidth, $refImageSize[0], $refImageSize[1]);
                imagedestroy($refImage);

                $avatarImageName = time($baseFileName) . '.' . $extension;
                imagepng($newImage, $dossier . $avatarImageName);

            }

            // creation of the avatar in DB
            $avatar = new Avatar();
            //TODO
            //$avatar->user_id = Auth::id;
            $avatar->user_id = 1;
            $avatar->email = $dataFromForm['email'];
            $avatar->image = $avatarImageName;
            //TODO
            //$avatar->save();


            return "je viens de creer un nouvel avatar avec mon formulaire dt le nom de l'image est: ".$avatar->image;
            // return to the updated user dashboard
            //return view('userAccount', ['avatars' => $this->avatars]);
        }
        else{
            return view('avatarCreationForm', ['msg' => 'invalid format']);
        }
        
    }

    public function deleteAvatar(){

        // return to the updated user dashboard
        //return view('userAccount', ['avatars' => $this->avatars]);
    }
}