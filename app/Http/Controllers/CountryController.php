<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function list(Request $request)
    {
        return ['results' => Country::select(['id', 'name as text'])
        ->where('name', 'LIKE' , '%'.$request->input('term').'%')
        ->take(20)
        ->get()];
    }

    public function getCounty(Request $request)
    {
        return $request->user()->visitedCountry()->get();
    }

    public function addCountry(Request $request)
    {
        $country = Country::findOrFail($request->input('countryID'));
        $request->user()
            ->visitedCountry()
            ->attach($request->input('countryID'));
        $request->user()->save();
        return ['status' => 'success'];
    }

    public function removeCountry(Request $request)
    {
        $country = Country::findOrFail($request->countryID);
        $request->user()
            ->selectedCountry()
            ->detach($request->countryID);
        $request->user()->save();
        return ['status' => 'success'];
    }
}
