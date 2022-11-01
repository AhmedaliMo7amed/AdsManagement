<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\CatResource;
use App\Models\Category;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        $allCategories =  CatResource::collection(Category::all());
        if (count($allCategories) > 0)
        {
            return $this->returnData('Data', $allCategories , 'All Categories Sent Successfully');
        }else{
            return $this->returnError('No Categories Founded In Database');
        }
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // Category Validation
                'name' => 'required|regex:/^[\pL\s\-]+$/u',
                'slug'=>'sometimes|required|alpha_dash',
            ]);

            if ($validator->fails()) {
                return $this->returnValidationError($validator);
            }

            if ($request->has("slug"))
            {
                // use the slug that the user enterd
                $request['ads_no']  = 0;
                Category::create($request->all());
                return $this->returnSuccessMessage('Category Created Successfully');
            }else{
                // if user didnt enter any slug -->> create custom slug
                $custom_slug = str_replace(' ', '-', $request->name);
                $request['slug'] = $custom_slug;
                $request['ads_no'] = 0;
                Category::create($request->all());
                return $this->returnSuccessMessage('Category Created Successfully');
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
                // Category Validation
                'name' => 'required|regex:/^[\pL\s\-]+$/u',
                'slug'=>'sometimes|required|alpha_dash',
            ]);

            if ($validator->fails()) {
                return $this->returnValidationError($validator);
            }

            $category = Category::find($id);

            if (is_null($category))
            {
                return $this->returnError("No Category with #ID".$id);
            }

            if ($request->has("slug"))
            {
                // use the slug that the user enterd
                $category->update($request->all());
                return $this->returnSuccessMessage('Category Updated Successfully');
            }else{
                // if user didnt enter any slug -->> regenerate custom slug
                $custom_slug = str_replace(' ', '-', $request->name);
                $request['slug'] = $custom_slug;
                $category->update($request->all());
                return $this->returnSuccessMessage('Category Updated Successfully');
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
        $category = Category::find($request->id);

        if (is_null($category))
        {
            return $this->returnError("No Category with #ID".$request->id);
        }

        $category->delete();
        return $this->returnSuccessMessage('Category Deleted Successfully');
    }

}
