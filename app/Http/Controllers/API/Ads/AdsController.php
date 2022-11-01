<?php

namespace App\Http\Controllers\API\Ads;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Models\Advertiser;
use App\Models\Category;
use App\Models\RelatedTag;
use App\Models\TagList;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdsController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        $allAds =  Ad::all();
        if (count($allAds) > 0)
        {
            $tags = [];

            foreach ($allAds as $adItem)
            {
                $category = Category::find($adItem->category_id);
                $TagList = TagList::where('ad_id',$adItem->id)->get();

                foreach ($TagList as $tag)
                {
                    $tagSelector = RelatedTag::find($tag->tag_id);
                    $tagName = $tagSelector->slug;
                    array_push($tags, $tagName);
                }
                $adItem['category'] = $category->slug;
                $adItem['tags'] = $tags;
                $tags = [];
            }
            $data = AdResource::collection($allAds);



            return $this->returnData('Data', $data , 'All Advertiser Sent Successfully');
        }else{
            return $this->returnError('No Categories Founded In Database');
        }
    }

    public function userAds($id)
    {
        $userAds = Ad::where("advertiser_id" , $id)->get();

        if (count($userAds) > 0)
        {
            $tags = [];

            foreach ($userAds as $adItem)
            {
                $category = Category::find($adItem->category_id);
                $TagList = TagList::where('ad_id',$adItem->id)->get();

                foreach ($TagList as $tag)
                {
                    $tagSelector = RelatedTag::find($tag->tag_id);
                    $tagName = $tagSelector->slug;
                    array_push($tags, $tagName);
                }
                $adItem['category'] = $category->slug;
                $adItem['tags'] = $tags;
                $tags = [];
            }

            $data = AdResource::collection($userAds);


            return $this->returnData('Data', $data , 'All Ads Sent Successfully');

        }else{
            return $this->returnError('No Ads For This User Founded In Database');

        }

    }


    public function store(Request $request)
    {
            $validator = Validator::make($request->all(), [
                // Ad Validation
                'type' => 'required|string',
                'title' => 'required|string|max:64',
                'description' => 'required|string|max:100',
                'category_id' => 'required|numeric',
                'tags' => 'required',
                'start_date' => 'required|date_format:"Y-m-d',
            ]);

            if ($validator->fails()) {
                return $this->returnValidationError($validator);
            }

            $request["advertiser_id"] = $request->adv_id;

            $adData = $request->only([
                'advertiser_id',
                'category_id',
                'type',
                'title',
                'description',
                'start_date',
            ]);

            $advertiser = Advertiser::find($request->adv_id);
            if (is_null($advertiser)) {
                return $this->returnError("No Advertiser with #ID ".$request->adv_id);
            }
            $category = Category::find($request->category_id);
            if (is_null($category)) {
                return $this->returnError("No Category with #ID ".$request->category_id);
            }

            $myAd = Ad::create($adData);
            foreach ($request->tags as $tagElement) {

                $slug = str_replace(' ', '-', $tagElement);

                $serach = RelatedTag::where("slug", $slug)->first();
                if (is_null($serach)) {

                    $tagCreator['name'] = $tagElement;
                    $tagCreator['slug'] = $slug;
                    $myTag = RelatedTag::create($tagCreator);

                    $tagData = ([
                        'tag_id' => $myTag->id
                     ]);

                    $myAd->adTags()->create($tagData);
                }else{

                    $tagData = ([
                        'tag_id' => $serach->id
                    ]);

                    $myAd->adTags()->create($tagData);
                }
            }

             return $this->returnSuccessMessage('Ad Created Successfully');

    }

    public function show($id)
    {
        $tags = [];
        $ad = Ad::find($id);
        if (is_null($ad)) {
            return $this->returnError("No Ads with #ID ".$id);
        }
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
        return $this->returnData('Data', $ad , 'AD Sent Successfully');
    }



    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // Ad Validation
            'type' => 'required|string',
            'title' => 'required|string|max:64',
            'description' => 'required|string|max:100',
            'category_id' => 'required|numeric',
            'tags' => 'required',
            'start_date' => 'required|date_format:"Y-m-d',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError($validator);
        }

        $adData = $request->only([
            'category_id',
            'type',
            'title',
            'description',
            'start_date',
        ]);


        $myAd = Ad::where('id',$id)->first();

        $myAd->update($adData);
        $deleteCurrentTags = TagList::where('ad_id',$myAd->id)->delete();

        foreach ($request->tags as $tagElement) {

            $slug = str_replace(' ', '-', $tagElement);

            $serach = RelatedTag::where("slug", $slug)->first();
            if (is_null($serach)) {

                $tagCreator['name'] = $tagElement;
                $tagCreator['slug'] = $slug;
                $myTag = RelatedTag::create($tagCreator);

                $tagData = ([
                    'tag_id' => $myTag->id
                ]);

                $myAd->adTags()->create($tagData);
            }else{

                $tagData = ([
                    'tag_id' => $serach->id
                ]);

                $myAd->adTags()->create($tagData);
            }
        }
        return $this->returnSuccessMessage('Ad Updated Successfully');

    }


    public function destroy(Request $request)
    {
        $ad = Ad::find($request->id);

        if (is_null($ad))
        {
            return $this->returnError("No ADs with #ID".$request->id);
        }

        $ad->delete();
        return $this->returnSuccessMessage('Ad Deleted Successfully');
    }
}
