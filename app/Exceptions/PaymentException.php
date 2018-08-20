<?php

namespace App\Exceptions;

use Exception;
use Lang;

class PaymentException extends Exception
{
	public function __construct($reponse, $code = 0, Exception $previous = null)
    {
        $msg = Lang::get('payments/message.error.something_went_wrong');
        switch($reponse->response) {
            case(2): //declined
                $msg = Lang::get('payments/message.error.transaction_was_declined');
                break;
            case(3): //error
                //$msg = Lang::get('payments/message.error.transaction_error');
                $msg = $reponse->responsetext;
                break;
            default:
                $msg = $reponse->responsetext;
        }

        parent::__construct($msg, $code, $previous);
    }
}
