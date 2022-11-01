<?php

namespace App\Traits;

trait GeneralTrait
{
    // to return error message
    public function returnError($msg)
    {
        return response()->json([
            'status' => false,
            'data' => NULL,
            'msg' => $msg,
        ]);
    }

    // to return Success message
    public function returnSuccessMessage($msg = "")
    {
        return response()->json([
            'status' => true,
            'msg' => $msg
        ]);
    }

    // to return data
    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'msg' => $msg,
            $key => $value
        ]);
    }

    // to return error of the validation
    public function returnValidationError( $validator)
    {
        return $this->returnError($validator->errors()->first());
    }



}
