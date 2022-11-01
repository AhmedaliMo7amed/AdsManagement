<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\RelatedTag;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        $allTags =  TagResource::collection(RelatedTag::all());
        if (count($allTags) > 0)
        {
            return $this->returnData('Data', $allTags , 'All Tags Sent Successfully');
        }else{
            return $this->returnError('No Tags Founded In Database');
        }
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // Tag Validation
                'name' => 'required|regex:/^[\pL\s\-]+$/u',
                'slug' => 'sometimes|required|alpha_dash',
            ]);

            if ($validator->fails()) {
                return $this->returnValidationError($validator);
            }

            if ($request->has("slug"))
            {
                // use the slug that the user enterd
                $serach = RelatedTag::where("slug", $request->slug)->first();
                if (is_null($serach))
                {
                    RelatedTag::create($request->all());
                    return $this->returnSuccessMessage('Tag Created Successfully');
                }else{
                    return $this->returnError("Tag is already added before");
                }

            }else{
                // if user didnt enter any slug -->> create custom slug
                $custom_slug = str_replace(' ', '-', $request->name);
                $serach = RelatedTag::where("slug", $custom_slug)->first();
                if (is_null($serach))
                {
                    $request['slug']= $custom_slug;
                    RelatedTag::create($request->all());
                    return $this->returnSuccessMessage('Tag Created Successfully');
                }else{
                    return $this->returnError("Tag is already added before");
                }
            }
        }
        // catch exception and errors then passing it to general traits
        catch (\Exception $ex){
            return $this->returnError($ex->getMessage());
        }
        catch (Throwable $e){
            return $this->returnError($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                // Tag Validation
                'name' => 'required|regex:/^[\pL\s\-]+$/u',
                'slug'=>'sometimes|required|alpha_dash',
            ]);

            if ($validator->fails()) {
                return $this->returnValidationError($validator);
            }

            $tag = RelatedTag::find($id);

            if (is_null($tag))
            {
                return $this->returnError("No Tag with #ID".$id);
            }

            if ($request->has("slug"))
            {
                // use the slug that the user enterd
                $serach = RelatedTag::where("slug", $request->slug)->first();
                if (is_null($serach))
                {
                    $tag->update($request->all());
                    return $this->returnSuccessMessage('Tag Updated Successfully');
                }else{
                    return $this->returnError("Tag is already added before");
                }
            }else{
                // if user didnt enter any slug -->> create custom slug
                $custom_slug = str_replace(' ', '-', $request->name);
                $serach = RelatedTag::where("slug", $custom_slug)->first();
                if (is_null($serach))
                {
                    $request['slug']= $custom_slug;
                    $tag->update($request->all());
                    return $this->returnSuccessMessage('Tag Updated Successfully');
                }else{
                    return $this->returnError("Tag is already added before");
                }
            }
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
        $tag = RelatedTag::find($request->id);

        if (is_null($tag))
        {
            return $this->returnError("No Tag with #ID".$request->id);
        }

        $tag->delete();
        return $this->returnSuccessMessage('Tag Deleted Successfully');
    }
}
