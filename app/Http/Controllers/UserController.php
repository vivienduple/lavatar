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
use App\Mail\AvatarValidation;
use App\User;
use App\Avatar;
use Illuminate\Http\Request;
use Debugbar;
use Validator;

/*
 * This controller manages the actions that a logged in user is allowed to do
 */

class UserController extends Controller
{
    /* list of the avatars related to the user account */
    private $avatars;

    /* method displaying the dashboard of the logged user */
    public function displayUserHomePage()
    {

        // get all the user's avatars to display them in the user dashboard
        $this->avatars = User::find(Auth::id())->avatars;
        return view('userAccount', ['avatars' => $this->avatars]);
    }

    /* method displaying the avatar creation form */
    public function displayAvatarCreationForm()
    {

        return view('avatarCreationForm');
    }

    /* method displaying the  avatar creation form just after his registration on the site
    (with pre-filled email field)*/
    public function displayAvatarCreationFormFromReg()
    {

        $user = User::find(Auth::Id());
        return view('avatarCreationForm', ['email' => $user->email]);
    }

    /* method creating, from a given (jpg or png) file, an avatar image in the same format, at the requested size
    note that the format has to be verified before using this method */
    private function createAvatarImage($extension, $savedFileName, $newWidth, $newHeight, $refImageSize, $userId, $baseFileName, $dossier)
    {
        $avatarImageName = '';

        // in case of a jpg origin file
        if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'pjpg' || $extension == 'pjpeg') {

            // transform the image at expected format
            $refImage = imagecreatefromjpeg($savedFileName);
            $newImage = imagecreatetruecolor($newWidth, $newHeight) or die ("Erreur");
            imagecopyresampled($newImage, $refImage, 0, 0, 0, 0, $newWidth, $newWidth, $refImageSize[0], $refImageSize[1]);
            imagedestroy($refImage);

            $avatarImageName = $newWidth . '_' . time($baseFileName) . '.' . $extension;
            // store the new image avatar in the chosen directory with the chosen name
            imagejpeg($newImage, $dossier . $avatarImageName, 100);
        } else {//$extension == 'png'
            $refImage = imagecreatefrompng($savedFileName);
            $newImage = imagecreatetruecolor($newWidth, $newHeight) or die ("Erreur");
            imagecopyresampled($newImage, $refImage, 0, 0, 0, 0, $newWidth, $newWidth, $refImageSize[0], $refImageSize[1]);
            imagedestroy($refImage);

            $avatarImageName = $newWidth . '_' . time($baseFileName) . '.' . $extension;
            // store the new image avatar in the chosen directory with the chosen name
            imagepng($newImage, $dossier . $avatarImageName);

        }
        return $avatarImageName;
    }

    /* method creating a new avatar (email/image association) on form submission */
    public function createNewAvatar(Request $request)
    {

        $userId = Auth::id();

        /* validation of the form's fields:
        email: format of an email and field required
        download file: is an image file with expected diemnsions */
        $dataFromForm = $request->all();
        $validator = Validator::make($dataFromForm, ['email' => 'required | email',
            'file' => 'required | image | dimensions:min_width=128,min_height=128,max_width=256,max_height=256']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        // get the user instance associated the the given email (if existing)
        // as email is unique in the table User, there is only 1 entry
        $userWithEmail = User::where('email', 'like', $dataFromForm['email'])->first();
        if (!is_null($userWithEmail)) {
            // a user has used this email to create his account
            // continue only if the current connected user is the owner of this account
            // otherwise, redirect to the avatar creation form with an error msg
            if ($userWithEmail->id != $userId) {
                return view('avatarCreationForm', ['msg' => 'email interdit']);
            }
        }

        // check the email is not already present in the DB in the avatars table
        // if there is at least one instance, the avatar can't be created
        $avatarWithMail = Avatar::where('email', 'like', $dataFromForm['email'])->first();
        if (!is_null($avatarWithMail)) {
            // redirect to the avatar creation form with an error msg
            return view('avatarCreationForm', ['msg' => 'email interdit']);
        } else // email ok
        {
            // check of the extension format jpg or png
            $fileComponents = explode('.', $_FILES['file']['name']);
            $origExtension = $fileComponents[count($fileComponents) - 1];
            $extension = strtolower($origExtension);

            $fileName = basename($_FILES['file']['name']);
            $pos = strripos($fileName, '.');
            // the name of the file without extension
            $baseFileName = substr($fileName, 0, $pos);

            // creation of the 2 image files associated to the avatar
            // the image is stored in the directory below
            $dossier = config('lavatar.avatarStoragePath');
            $tmpFileName = $_FILES['file']['tmp_name'];

            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'pjpg' || $extension == 'pjpeg' || $extension == 'png') {

                $savedFileName = $dossier . $baseFileName . '_ori' . '.' . $extension;
                move_uploaded_file($tmpFileName, $savedFileName);

                $refImageSize = getimagesize($savedFileName);

                $avatarName = '';

                // create the 128x128 avatar image
                $avatarName128 = $this->createAvatarImage($extension, $savedFileName, 128, 128, $refImageSize, $userId, $baseFileName, $dossier);
                // create the 256x256 avatar image
                $avatarName256 = $this->createAvatarImage($extension, $savedFileName, 256, 256, $refImageSize, $userId, $baseFileName, $dossier);

                // remove the downloaded origin file
                unlink($savedFileName);

                // get the part of the image avatar file name that has to be strored in DB
                $avatarName = substr($avatarName128, 4);

                // creation of the avatar in DB
                $avatar = new Avatar();
                $avatar->user_id = $userId;
                $avatar->email = $dataFromForm['email'];
                $avatar->image = $avatarName;
                $avatar->validity = time($avatarName);
                $avatar->save();

                // the user is redirected to his own dashboard after its avatar creation

                Mail::to($avatar->email)->send(new AvatarValidation($avatar->id, $avatar->validity));

                //return "je viens de creer un nouvel avatar avec mon formulaire dt le nom de l'image est: ".$avatar->image;
                // return to the updated user dashboard
                return redirect()->route('user.dashboard');
            } else {
                // the downloaded file has a wrong format, redirect the user to the avatar creation form with an error msg
                return view('avatarCreationForm', ['msg' => 'format invalide']);
            }
        }

    }

    /* method deleting an avatar (email/image association) on request*/
    public function deleteAvatar($id)
    {

        // remove the images associated to the avatar from the storage directory
        $avatarToDelete = Avatar::find($id);
        $avatarImgBaseName = $avatarToDelete->image;
        $dossier = config('lavatar.avatarStoragePath');
        unlink($dossier . "128_" . $avatarImgBaseName);
        unlink($dossier . "256_" . $avatarImgBaseName);

        // remove the avatar in DB
        $avatarToDelete->delete();

        // redirect to the updated user dashboard
        return redirect()->route('user.dashboard');
    }

    /* method displaying the page proposing to the new registered user to create the avatar associated to his account its avatar */
    public function displayRegistrationAvatarConfirmation(Request $request)
    {

        $user = User::find(Auth::id());
        Mail::to($user->email)->send(new RegistrationMailConf());
        // return to the updated user dashboard
        return view('addAvatarImageOnRegistration');
    }

    public function validAvatar($id, $tocken)
    {


        // get the Avatar
        $avatarToConfirm = Avatar::find($id);

        if ($avatarToConfirm->validity != null) {
            if ($avatarToConfirm->validity == $tocken) {
                $avatarToConfirm->validity = null;
            }
        }
        $avatarToConfirm->save();

        // return to the updated user dashboard
        return redirect()->route('user.dashboard');
    }

}