<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avatar;

class ApiController extends Controller
{
    public function provideAvatarInfos($email, $size=1){

        $error = 0;
        $avatarWithMail = Avatar::where('email','like',$email)->first();
        
        if (is_null($avatarWithMail)){
            $error=1;
            return response()->json(['error' => '1', "mail" => "", "size"=>""]);
        }
        else{
            $imgSize = "128";
            if ($size == 2) {
                $imgSize = "256";
            }
            return response()->json(['error' => '0', "mail" => $email, "size"=>$imgSize]);
        }

    }

    public function provideAvatarFromEmail($email, $size=1){

        $avatarWithMail = Avatar::where('email','like',$email)->first();

        $dossier = config('lavatar.avatarStoragePath');
        if (is_null($avatarWithMail)){
            return response()->download($dossier."error404.jpg");
        }
        else{
            $avatarImg = $avatarWithMail->image;

            $imgSize = "128";
            $nameImg = "Avatar128";
            if ($size == 2) {
                $imgSize = "256";
                $nameImg = "Avatar256";
            }
            $pathToFile = $dossier.$imgSize."_".$avatarImg;
            return response()->download($pathToFile, $nameImg);
        }

    }



}
