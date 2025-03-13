<?php

namespace App\Http\Controllers;

use App\Models\Wallet;

use App\Models\Utilisateur;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public  function RandomNb(){
        do {
            $nb = str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
            $exists = Wallet::where('NumeroWallet', $nb)->exists();
        } while ($exists);
        
        return $nb;
    }
    public function Register(Request $request)
    {
        $validated = $request->validate([
            'prenom' =>  'required|string',
            'nom' => 'required|string',
            'email' => 'required|string|email|unique:Utilisateur',
            'password' => 'required',
        ]);

        $user = Utilisateur::create([
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'password' => hash::make($validated ['password']),
            'role_id' => 1,
            'Image' => $request->input('Image'),
            'Status' => 'Active',

        ]);

        

        $token = $user->createToken('my-app-token')->plainTextToken;
        DB::table('Wallet')->insert([
            'NumeroWallet' => $this->RandomNb(),
            'Solde' => 0,
            'Currency' => '€',
            'owner_id' => $user->id
        ]);



        $response = [
            'token' => $token,
        ];


        // return 'hello';

        return response()->json($response,201);
    }


    public function Login(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        $user = Utilisateur::where('email',$validate['email'])->first();

        if(!$user || hash::check($user->password,$request->password))
        {
            return response(
                [
                    'message' => 'the user and password not correct'
                ]
                ); 
        }
        else
        {
            $token = $user->createToken('my_app_token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }

        //dd


        
    }


    public function Logout(Request $request)
    {
        $token = $request->token;
        DB::table('personal_access_tokens')->where('token',$token)->delete();
        // return "Logout is success";
        return response()->json([
            'message' => 'Logout is sucess',
            'token' => $token
        ]);
    }
}
