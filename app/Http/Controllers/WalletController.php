<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
        public  function RandomNb(){
            do {
                $nb = str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
                $exists = Wallet::where('NumeroWallet', $nb)->exists();
            } while ($exists);
            
            return $nb;
        }


    public function DeleteWallet(Request $request)
    {
        $id_wallet = $request->id;
        DB::table('Wallet')->where('id',$id_wallet)->delete();

        return response()->json([
            'message' => 'Wallet Deleted',
            'id_wallet' => $id_wallet,
        ]);
    }
}
