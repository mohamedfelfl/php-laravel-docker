<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Traits\response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OffersController extends Controller
{
    use response;

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required|date|after_or_equal:now',
            'end_date' => 'required|date|after:start_date',
            'foods_included' => 'required|array',
        ]);
        if(Offer::where('title', $request->input('title'))->first()){
            return $this->jsonResponseMessage('Offer already exist', false);
        }
        $offer = new Offer();
        $offer->title = $request->input('title');
        $offer->start_date = Carbon::parse($request->input('start_date'))->isoFormat("YYYY/MM/DD hh:mm:ss");
        $offer->end_date = Carbon::parse($request->input('end_date'))->isoFormat("YYYY/MM/DD hh:mm:ss");
        $offer->foods_included = $request->input('foods_included');

        if($offer->save()){
            return $this->jsonResponseMessage('Offer added successfully' , data: $offer);
        }else{
            return $this->jsonResponseMessage("Error with adding offer", false);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required'
        ]);

        $offer = Offer::where('id', $request->input('id'))->first();
        if($offer){
            $offer->delete();
            return $this->jsonResponseMessage('Offer deleted successfully');
        }else{
            return $this->jsonResponseMessage('Error with deleting offer', false);
        }
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required',
        ]);
        $offer = Offer::where('id', $request->input('id'))->first();
        if(!$offer){
            return $this->jsonResponseMessage('Offer does not exist', false);
        }
        $fields = $request->except('id');
        foreach ($fields as $key => $value) {
            $offer->$key = $value;
        }
        if($offer->save()){
            return $this->jsonResponseMessage('Offer updated successfully' , data: $offer);
        }else{
            return $this->jsonResponseMessage("Error with updating offer", false);
        }
    }

    public function get(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required']);
        $offer = Offer::where('id', $request->input('id'))->first();
        if($offer){
            return $this->jsonResponseMessage('Offer is available', data: $offer);
        }else{
            return $this->jsonResponseMessage('Offer does not exist', false);
        }
    }

    public function allOffers(): JsonResponse
    {
        $offers = Offer::all();
        return $this->jsonResponseMessage('Available offers', data: $offers);
    }
}
