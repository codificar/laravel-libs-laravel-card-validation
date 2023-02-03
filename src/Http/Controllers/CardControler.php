<?php

namespace Codificar\Generic\Http\Controllers;

use App\Http\Controllers\Controller;
use Codificar\Finance\Http\Requests\AddCardUserFormRequest;
use Codificar\Finance\Http\Resources\AddCardUserResource;
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
	public function addCreditCardProvider(AddCardUserFormRequest $request) {
		$provider = Provider::find($request->id);
		return $this->newCreditCard($provider, 'provider', $request);
	}
	public function addCreditCardUser(AddCardUserFormRequest $request) {
		$user = User::find($request->id);
		return $this->newCreditCard($user, 'user', $request);
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

	private function getEnviroment() {
		$type = Request::segment(1);
		switch($type){
			case Finance::TYPE_USER:
				$id = Auth::guard("clients")->user()->id;
				$holder = User::find($id);
				$type = 'user';
			break;
			case Finance::TYPE_CORP:
				$admin_id = LibModel::getGuardWebCorp();
				$holder = AdminInstitution::getUserByAdminId($admin_id);
				$id = $holder->id;
				$type = 'corp';
			break;
			case Finance::TYPE_PROVIDER:
				$id = \Auth::guard("providers")->user()->id;
				$holder = Provider::find($id);
				$type = 'provider';
			break;
		}
		return array(
			'type' => $type,
			'id' => $id,
			'holder' => $holder
		);
	}

}