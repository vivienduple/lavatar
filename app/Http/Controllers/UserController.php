<?php
/**
 * Created by PhpStorm.
 * User: jthebault001
 * Date: 31/03/2017
 * Time: 13:04
 */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationMailConf;
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

        $this->avatars = User::find(Auth::id())->avatars;
        return view('userAccount', ['avatars' => $this->avatars]);
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

    public function createAvatarImage($extension,$savedFileName,$newWidth,$newHeight,$refImageSize,$userId,$baseFileName,$dossier){
        $avatarImageName='';
        if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'pjpg' || $extension == 'pjpeg' ) {

            // transform the image at exoected formats
            $refImage = imagecreatefromjpeg($savedFileName);
            $newImage = imagecreatetruecolor($newWidth, $newHeight) or die ("Erreur");
            imagecopyresampled($newImage, $refImage, 0, 0, 0, 0, $newWidth, $newWidth, $refImageSize[0], $refImageSize[1]);
            imagedestroy($refImage);

            $avatarImageName = $newWidth.'_'.time($baseFileName) . '.' . $extension;
            imagejpeg($newImage, $dossier . $avatarImageName, 100);
        }
        else{//$extension == 'png'
            $refImage = imagecreatefrompng($savedFileName);
            $newImage = imagecreatetruecolor($newWidth, $newHeight) or die ("Erreur");
            imagecopyresampled($newImage, $refImage, 0, 0, 0, 0, $newWidth, $newWidth, $refImageSize[0], $refImageSize[1]);
            imagedestroy($refImage);

            $avatarImageName = $newWidth.'_'.time($baseFileName) . '.' . $extension;
            imagepng($newImage, $dossier . $avatarImageName);

        }
        return $avatarImageName;
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

        $userWithEmail = User::where('email','like',$dataFromForm['email'])->get();
        if (count($userWithEmail) != 0){
            // a user has used this email to create his account
            // continue only if the currect connected user is the owner of this account
            // as email is unique in the table User, there is only 1 entry
            foreach ($userWithEmail as $m)
                if ($m->id != $userId){
                    return view('avatarCreationForm', ['msg' => 'email interdit']);
                }
        }
        
        // check the email is not already present in the DB
        $avatarWithMail = Avatar::where('email','like',$dataFromForm['email'])->get();
        if (count($avatarWithMail)!=0){
            //pb: an avatar with with email is already present in DB Avatars table
            return view('avatarCreationForm', ['msg' => 'email interdit']);
        }
        else{
            // check of the extension format jpg or png
            $fileComponents = explode('.', $_FILES['file']['name']);
            $origExtension = $fileComponents[count($fileComponents)-1];
            $extension = strtolower($origExtension);

            $fileName = basename($_FILES['file']['name']);
            $pos = strripos($fileName , '.');
            // the name of the file without extension
            $baseFileName = substr ( $fileName , 0 , $pos );

            // creation of image files associated to the avatar
            // the image is stored in the directory below
            $dossier = config('lavatar.avatarStoragePath');
            $tmpFileName = $_FILES['file']['tmp_name'];

            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'pjpg' || $extension == 'pjpeg' || $extension == 'png'){

                $savedFileName = $dossier.$baseFileName.'_ori'.'.'.$extension;
                move_uploaded_file($tmpFileName, $savedFileName);

                $refImageSize = getimagesize($savedFileName);

                $avatarName = '';

                // create the 128x128 avatar image
                $avatarName128 = $this->createAvatarImage($extension,$savedFileName,128,128,$refImageSize,$userId,$baseFileName,$dossier);
                // create the 256x256 avatar image
                $avatarName256 = $this->createAvatarImage($extension,$savedFileName,256,256,$refImageSize,$userId,$baseFileName,$dossier);

                unlink($savedFileName);

                $avatarName = substr($avatarName128, 4 );

                // creation of the avatar in DB
                $avatar = new Avatar();
                $avatar->user_id = $userId;
                $avatar->email = $dataFromForm['email'];
                $avatar->image = $avatarName;
                $avatar->save();
                
                //return "je viens de creer un nouvel avatar avec mon formulaire dt le nom de l'image est: ".$avatar->image;
                // return to the updated user dashboard
                return redirect()->route('user.dashboard');
            }
            else{
                return view('avatarCreationForm', ['msg' => 'format invalide']);
            }
        }

    }



    public function deleteAvatar($id){
        

        // remove the images associated to the avatar
        $avatarToDelete = Avatar::find($id);
        $avatarImgBaseName = $avatarToDelete->image;
        $dossier = config('lavatar.avatarStoragePath');
        unlink($dossier."128_".$avatarImgBaseName);
        unlink($dossier."256_".$avatarImgBaseName);

        // remove the avatar in DB
        $avatarToDelete->delete();

        // return to the updated user dashboard
        return redirect()->route('user.dashboard');
    }

    public function displayRegistrationAvatarConfirmation(Request $request){

        $user = User::find(Auth::id());
        Mail::to($user->email)->send(new RegistrationMailConf());
        // return to the updated user dashboard
        return view('addAvatarImageOnRegistration');
    }

}