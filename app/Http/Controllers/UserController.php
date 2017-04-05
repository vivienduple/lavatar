<?php
/**
 * Created by PhpStorm.
 * User: jthebault001
 * Date: 31/03/2017
 * Time: 13:04
 */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

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
    public function displayUserHomePage(){

        /*$this->avatars = User::find(Auth::id)->avatars();
        return view('userAccount', ['avatars' => $this->avatars]);*/
        return view('userAccount');
    }

    /*method that display the new avatar creation form*/
    public function displayAvatarCreationForm(){

        return view('avatarCreationForm');
    }

    /*method that display the new avatar creation form*/
    public function displayAvatarCreationFormFromReg(){

        $user=User::find(Auth::Id());
        return view('avatarCreationForm',['email' => $user->email]);
    }

    /*method that create a new avatar (email/image) on form submission*/
    public function createNewAvatar(Request $request){

        $userId = Auth::id();

        // validation of the file: size, dimensions, extension
        $dataFromForm = $request->all();
        $validator = Validator::make($dataFromForm, ['email' => 'required | email',
            'file'  => 'required | image | dimensions:min_width=128,min_height=128,max_width=256,max_height=256']);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        
        // check the email is not already present in the DB
        $avatarWithMail = Avatar::where('email','like',$dataFromForm['email'])->get();

        if (count($avatarWithMail)!=0){
            //pb: an avatar with with email is already present in DB for this user
            return view('avatarCreationForm', ['msg' => 'an avatar with this email already exists']);
        }
        else{
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
                    $avatarImageName = $userId.'_'.time($baseFileName) . '.' . $extension;
                    imagejpeg($newImage, $dossier . $avatarImageName, 100);
                }
                else{//$extension == 'png'
                    $refImage = imagecreatefrompng($savedFileName);
                    $newImage = imagecreatetruecolor($newWidth, $newHeight) or die ("Erreur");
                    imagecopyresampled($newImage, $refImage, 0, 0, 0, 0, $newWidth, $newWidth, $refImageSize[0], $refImageSize[1]);
                    imagedestroy($refImage);

                    $avatarImageName = $userId.'_'.time($baseFileName) . '.' . $extension;
                    imagepng($newImage, $dossier . $avatarImageName);

                }
                echo "test3";
                // creation of the avatar in DB
                $avatar = new Avatar();
                $avatar->user_id = $userId;
                $avatar->email = $dataFromForm['email'];
                $avatar->image = $avatarImageName;
                $avatar->save();


                //return "je viens de creer un nouvel avatar avec mon formulaire dt le nom de l'image est: ".$avatar->image;
                // return to the updated user dashboard
                return route('user.dashboard');
            }
            else{
                return view('avatarCreationForm', ['msg' => 'invalid format']);
            }
        }


 
        
    }

    public function deleteAvatar(){

        // return to the updated user dashboard
        //return view('userAccount', ['avatars' => $this->avatars]);
    }

    public function displayRegistrationAvatarConfirmation(){

        // return to the updated user dashboard
        return view('addAvatarImageOnRegistration');
    }

}