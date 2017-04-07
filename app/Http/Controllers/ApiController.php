<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avatar;

/* This controller processes the requests sent via the API*/

class ApiController extends Controller
{
    /*
     * From a given email and a format size (1=small 2=medium),
     * this method returns through the API, a JSON file containing data about
     * the avatar associated to the mail:
     * - the error returned code (0=OK 1=Error) on request process
     * - the email (those of the request if request is OK)
     * - the size of the requested avatar image (in pixels: 128 or 256)   
    */
    public function provideAvatarInfos($email, $size = 1)
    {

        $error = 0;

        // get the first instance of the avatars table with the given email
        $avatarWithMail = Avatar::where('email', 'like', $email)->first();

        // if there is no avatar associated to the given email, return "error"
        if (is_null($avatarWithMail) || !is_null($avatarWithMail->validity)) {
            $error = 1;
            return response()->json(['error' => '1', "mail" => "", "size" => ""]);
        } // an avatar exists for the given email, return data about the avatar
        else {
            // converts the small/medium size into pixels size
            $imgSize = "128";
            if ($size == 2) {
                $imgSize = "256";
            }
            return response()->json(['error' => '0', "mail" => $email, "size" => $imgSize]);
        }

    }

    /*
     * From a given email and a format size (1=small 2=medium),
     * this method returns through the API, an image (as a downloaded file):
     * - the avatar associated to the mail in the requested size if existing
     * - an image representing the 404 error otherwise
    */
    public function provideAvatarFromEmail($email, $size = 1)
    {
        // get the first instance of the avatars table with the given email
        $avatarWithMail = Avatar::where('email', 'like', $email)->first();

        // get the path of the directory containing the avatars
        $dossier = config('lavatar.avatarStoragePath');
        
        // if there is no avatar associated to the given email, retunn "404 error' image
        if (is_null($avatarWithMail) || !is_null($avatarWithMail->validity)) {
            return response()->download($dossier . "error404.jpg");
        } 
        // else, return the avatr in the requested format
        else {
            // get the name (crypted) of the image stored
            $avatarImg = $avatarWithMail->image;
            
            // convert small/medium size into pixels
            $imgSize = "128";
            $nameImg = "Avatar128";
            if ($size == 2) {
                $imgSize = "256";
                $nameImg = "Avatar256";
            }
            // return the corresponding file
            $pathToFile = $dossier . $imgSize . "_" . $avatarImg;
            return response()->download($pathToFile, $nameImg);
        }

    }

    /*
  * this method returns through the API, the data about the application through a JSON file
 */
    public function provideAppData()
    {
        return response()->json(['App Version' => '1.0',
            "Authorized Format" => "png, jpeg", "Sizes" => "128x128 256x256",
            "DefaultSize" => "128x128"]);
        }

}
