<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function CreateTransaction(Request $request)
    {
        try
        {
        $id_sender = $request->sender_id;
        $numerowallet = $request->NumerWallet;
        $id_receiver = DB::table('Wallet')->where('NumeroWallet',$numerowallet)->select('owner_id')->get();
        $Montant = $request->montant;
        $Solde_Sender = DB::table('Wallet')->where('owner_id',$id_sender)->select('Solde')->get();
        if($id_sender)
        {
            if($Montant > $Solde_Sender)
            {
                throw new Exception('Solde est insuffisant');
            }
        }
        else
        {
            echo "h";
            throw new Exception('User not Exists');
        }
    }
    catch(Exception $e)
    {
         return $e->getMessage();
    }








    }
}
