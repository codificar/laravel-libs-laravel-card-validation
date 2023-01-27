<?php

namespace Codificar\Generic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// Importar models
use Codificar\Generic\Models\Generic;

// Importar Resource
use Codificar\Generic\Http\Resources\TesteResource;


use Input, Validator, View, Response;
use Provider, Settings, Ledger, Finance, Bank, LedgerBankAccount;

class GenericController extends Controller {

    /**
     * Do the validation and call the fuction that will add the card
     * 
     * @return newCreditCard
     */
	public function addCreditCard(AddCardUserFormRequest $request) {
		$enviroment = $this->getEnviroment();
		return $this->newCreditCard($enviroment['holder'], $enviroment['type'], $request);
	}

    /**
     * add the new cart to system
     * 
     * @return newCreditCard
     */
	private function newCreditCard($holder, $type, $request) {
		$data = array();
		$payment = new Payment;
		if($type == 'provider') {
			$payment->provider_id = $holder->id;
		} else {
			$payment->user_id = $holder->id;
		}
		$return = $payment->createCard($request->cardNumber, $request->cardExpMonth, $request->cardExpYear, $request->cardCvv, $request->cardHolder);

		if($return['success']){
            return new AddCardUserResource($payment);
		} else {
			return response()->json(['message' => $return['message'],'success'=> false, 'type' => $return['type'], 'card' => $payment], 406);
		}
	}

}