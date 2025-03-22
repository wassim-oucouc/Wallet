<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionController extends Controller
{
    public function CreateTransaction(Request $request)
    {
        try
        {
            $id_sender = $request->sender_id;
            $numerowallet = $request->NumerWallet;
            $montant = $request->montant;

            $receiver = DB::table('Wallet')->where('NumeroWallet', $numerowallet)->first();
            if (!$receiver) {
                throw new Exception('Le numéro de wallet du destinataire est invalide.');
            }
            $id_receiver = $receiver->owner_id;

            $sender_wallet = DB::table('Wallet')->where('owner_id', $id_sender)->first();
            if (!$sender_wallet) {
                throw new Exception('L\'expéditeur n\'existe pas.');
            }

            if ($montant > $sender_wallet->Solde) {
                throw new Exception('Solde insuffisant.');
            }

            DB::beginTransaction();

            DB::table('Wallet')->where('owner_id', $id_sender)->decrement('Solde', $montant);

            DB::table('Wallet')->where('owner_id', $id_receiver)->increment('Solde', $montant);

            DB::table('Transactions')->insert([
                'sender_id' => $id_sender,
                'receiver_id' => $id_receiver,
                'montant' => $montant,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json(['message' => 'Transaction réussie'], 200);
        } 
        catch (Exception $e) 
        {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
