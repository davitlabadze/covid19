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

    public function visitedCounty(Request $request)
    {
        return $request->user()->visitedCountry()->get();
    }

    public function toVistCountry(Request $request)
    {
        return $request->user()->toVisitCountry()->get();

    }

    public function addVisitedCountry(Request $request)
    {
        $country = Country::findOrFail($request->input('countryID'));
        $request->user()
            ->visitedCountry()
            ->attach($request->input('countryID'));
        $request->user()->save();
        return ['status' => 'success'];
    }
}
