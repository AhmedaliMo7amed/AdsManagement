<?php

namespace App\Http\Controllers\API\Advertisers;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use App\Http\Resources\AdvsResource;
use App\Models\Advertiser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AdvertiserController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        $allAdvertiser =  AdvsResource::collection(Advertiser::all());
        if (count($allAdvertiser) > 0)
        {
            return $this->returnData('Data', $allAdvertiser , 'All Advertiser Sent Successfully');
        }else{
            return $this->returnError('No Advertiser Founded In Database');
        }
    }

    public function store(Request $request)
    {

        try {
            $validator =Validator::make($request->all() ,[
                // User Validation
                'firstName' => 'required|regex:/^[\pL\s\-]+$/u' ,
                'lastName' => 'required|regex:/^[\pL\s\-]+$/u' ,
                'email' => 'required|email|unique:users,email|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
            ]);
            if ($validator->fails()) {
                return $this->returnValidationError($validator);
            }
            $RegData = $request->only([
                'firstName',
                'lastName',
                'email',
            ]);

            $RegData['ads_counter'] = 0;

            Advertiser::create($RegData);
            return $this->returnSuccessMessage('Advertiser Created Successfully');
        }

        // catch exception and errors then passing it to general traits
        catch (\Exception $ex){
            return $this->returnError($ex->getMessage());
        }
        catch (Throwable $e){
            return $this->returnError($e->getMessage());
        }
    }



    public function update(Request $request,$id)
    {
        try {
            $validator =Validator::make($request->all() ,[
                // User Validation
                'firstName' => 'required|regex:/^[\pL\s\-]+$/u' ,
                'lastName' => 'required|regex:/^[\pL\s\-]+$/u' ,
                'email' => 'required|email|unique:users,email|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
            ]);
            $RegData = $request->only([
                'firstName',
                'lastName',
                'email',
            ]);

            if ($validator->fails()) {
                return $this->returnValidationError($validator);
            }

            $advertiser = Advertiser::find($id);
            if (is_null($advertiser))
            {
                return $this->returnError("No Advertiser with #ID".$id);
            }
            $advertiser->update($RegData);
            return $this->returnSuccessMessage('Advertiser Updated Successfully');
        }

            // catch exception and errors then passing it to general traits
        catch (\Exception $ex){
            return $this->returnError($ex->getMessage());
        }
        catch (Throwable $e){
            return $this->returnError($e->getMessage());
        }
    }


    public function destroy(Request $request)
    {
        $advertiser = Advertiser::find($request->id);
        if (is_null($advertiser))
        {
            return $this->returnError("No Advertiser with #ID".$request->id);
        }

        $advertiser->delete();
        return $this->returnSuccessMessage('Advertiser Deleted Successfully');
    }
}
