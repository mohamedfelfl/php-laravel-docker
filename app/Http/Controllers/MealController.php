<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Offer;
use App\Traits\response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealController extends Controller
{
    use response;

    public function index(): Collection
    {
        return Meal::all( );
    }
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|url',
            'rating' => 'required|numeric',
            'type' => 'required|integer',
            'restaurant' => 'required',
            'tags' => 'required|array',
        ]);
        if(Meal::where('name', $request->input('name'))->first()){
            return $this->jsonResponseMessage('Meal already exist', false);
        }
        $meal = new Meal();
        $meal->name = $request->input('name');
        $meal->description = $request->input('description');
        $meal->image = $request->input('image');
        $meal->rating = $request->input('rating');
        $meal->type = $request->input('type');
        $meal->restaurant = $request->input('restaurant');
        $meal->tags = implode(",", $request->input('tags'));

        if($meal->save()){
            return $this->jsonResponseMessage('Meal added successfully' , data: $meal);
        }else{
            return $this->jsonResponseMessage("Error with adding meal", false);
        }
    }

    public function addMealToFavourite(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required',
        ]);
        $user = $request->user();
        $user->favourites = $user->favourites + $request->input('id');
        if($user->save()){
            return $this->jsonResponseMessage('Meal added to favourites successfully', data: $user);
        }
        else{
            return $this->jsonResponseMessage("Error with adding to favourites", false);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required'
        ]);

        $meal = Meal::where('id', $request->input('id'))->first();
        if($meal){
            $meal->delete();
            return $this->jsonResponseMessage('Meal deleted successfully');
        }else{
            return $this->jsonResponseMessage('Error with deleting offer', false);
        }
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required',
        ]);
        $meal = Meal::where('id', $request->input('id'))->first();
        if(!$meal){
            return $this->jsonResponseMessage('Meal does not exist', false);
        }
        $fields = $request->except('id');
        foreach ($fields as $key => $value) {
            $meal->$key = $value;
        }
        if($meal->save()){
            return $this->jsonResponseMessage('Offer updated successfully' , data: $meal);
        }else{
            return $this->jsonResponseMessage("Error with updating offer", false);
        }
    }

    public function get(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required']);
        $meal = Meal::where('id', $request->input('id'))->first();
        if($meal){
            return $this->jsonResponseMessage('Meal is available', data: $meal);
        }else{
            return $this->jsonResponseMessage('Meal does not exist', false);
        }
    }

    public function allMeals(): JsonResponse
    {
        $meals = Meal::all();
        return $this->jsonResponseMessage('Available meals', data: $meals);
    }
}
