<?php

namespace App\Http\Controllers\API\Ads;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Models\Category;
use App\Models\RelatedTag;
use App\Models\TagList;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FiltersController extends Controller
{
    use GeneralTrait;


    public function filterByCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError($validator);
        }

        $slug = str_replace(' ', '-', $request->category);
        $pickCategoryModel = Category::where('slug',$slug)->first();

        if (is_null($pickCategoryModel))
        {
            return $this->returnError("No Category with Name => ".$request->category);
        }

        $getFilterdAds = Ad::where('category_id',$pickCategoryModel->id)->get();

        if (count($getFilterdAds) > 0)
        {
            $tags = [];

            foreach ($getFilterdAds as $adItem)
            {
                $category = Category::find($adItem->category_id);
                $TagList = TagList::where('ad_id',$adItem->id)->get();

                foreach ($TagList as $tag)
                {
                    $tagSelector = RelatedTag::find($tag->tag_id);
                    $tagName = $tagSelector->name;
                    array_push($tags, $tagName);
                }
                $adItem['category'] = $category->slug;
                $adItem['tags'] = $tags;
                $tags = [];
            }

            $data = AdResource::collection($getFilterdAds);
            return $this->returnData('Data', $data , 'All Ads Sent Successfully');

        }else{
            return $this->returnError('No Ads For This Category Founded In Database');

        }

    }

    public function filterByTags(Request $request)
    {
        $filterTags = [] ;
        $validator = Validator::make($request->all(), [
            'tags' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError($validator);
        }

        foreach ($request->tags as $item)
        {
            $slug = str_replace(' ', '-', $item);
            $RelatedTag = RelatedTag::where('slug', $slug )->first();
            if (!is_null($RelatedTag))
            {
                array_push($filterTags, $RelatedTag->id);
            }
        }

        $cheker = TagList::get();
        $data = [];
        $adsCollection= [];
        $tags = [];
        if (!is_null($cheker)) {
            foreach ($filterTags as $tag) {
                $reportQuery = TagList::where('tag_id',$tag)->get();
                array_push($data, $reportQuery);
            }

            foreach ($data as $dataItem)
            {
                foreach ($dataItem as $element)
                {
                    $ad = Ad::where('id',$element->ad_id)->first();
                    $category = Category::find($ad->category_id);
                    if (is_null($category)) {
                        return $this->returnError("No Category with #ID ".$ad->category_id);
                    }
                    $TagList = TagList::where('ad_id',$ad->id)->get();

                    foreach ($TagList as $tag)
                    {
                        $tagSelector = RelatedTag::find($tag->tag_id);
                        $tagName = $tagSelector->name;
                        array_push($tags, $tagName);
                    }

                    $ad['category'] = $category->slug;
                    $ad['tags'] = $tags;
                    if(!in_array($ad, $adsCollection)){
                        array_push($adsCollection, $ad);
                    }

                    $tags = [];
                }
            }

            $data = AdResource::collection($adsCollection);
            return $this->returnData('Data', $data , 'All Ads Sent Successfully');

        }else{
            return $this->returnError('No Tags founded In Database');
        }

    }

}
