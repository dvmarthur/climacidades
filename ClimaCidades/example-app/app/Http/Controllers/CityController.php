<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\DadosClima;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $dados = DadosClima::max('id');
        $dados = DadosClima::where('id', $dados)->get();
        //dd($dados);
        return view('welcome', compact('dados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCityRequest  $request
     * @return \Illuminate\Http\Response
     */


    public function sendRequest(Request $request)
    {
        $dados = DadosClima::where('city', $request->city)->first();

        if ($dados != null) {
            if (Carbon::now()->diffInSeconds(Carbon::parse($dados->updated_at)) > 1200) {
                $data = static::getAPI($request);
                $data['updated_at'] = Carbon::now();
                $dados->update($data);
            }
        } else {
            $data = static::getAPI($request);
            DadosClima::create($data);
        }
        return redirect()->route('clima.index');
    }

    public static function getAPI(Request $request)
    {

        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $request->city,
            'appid' => 'b310c06867eeed5a9c060e6d4b04bbdd',
            'lang' => 'pt_br',
            'units' => 'metric'
        ]);
        $response = json_decode($response->getBody()->getContents());
        $data = array();
        if (!empty($response)) {
            $data['city'] = $response->name;
            $data['description'] = $response->weather[0]->description;
            $data['temp'] = $response->main->temp;
            $data['temp_min'] = $response->main->temp_min;
            $data['temp_max'] = $response->main->temp_max;
        } else {
            exit();
        }
        return $data;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCityRequest  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
}
