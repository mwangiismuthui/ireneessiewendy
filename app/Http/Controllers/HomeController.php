<?php

namespace App\Http\Controllers;

use App\Analytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function redirectPlayStore(Request $request)
    {
        $referer = $request->headers->get('referer');
        // $referer = URL::previous();
        // $referer = $request->server('HTTP_REFERER');
        // dd($referer);
        if ($referer != null) {
            $status = Analytic::where('referring_url', $referer)->count();
            if ($status > 0) {
                $analytic = Analytic::where('referring_url', $referer)->first();
                $analytic->referring_url = $referer;
                $analytic->increment('clicks');
                $analytic->update();
            } else {
                $analytic = new Analytic();
                $analytic->referring_url = $referer;
                $analytic->increment('clicks');
                $analytic->save();
            }
        } else {
            $status = Analytic::where('referring_url', 'uknown')->count();
            if ($status > 0) {
                $analytic = Analytic::where('referring_url', 'uknown')->first();
                $analytic->increment('clicks');
                $analytic->update();
            } else {
                $analytic = new Analytic();
                $analytic->referring_url = 'uknown';
                $analytic->increment('clicks');
                $analytic->save();
            }
        }

        return Redirect::to('https://play.google.com/store/apps/details?id=com.trichain.kenyasihami');
    }
}
