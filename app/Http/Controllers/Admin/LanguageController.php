<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

use Redirect;

class LanguageController extends Controller
{

  public function __invoke($lang)
     {
         $language = in_array($lang, config('app.languages')) ? $lang : config('app.fallback_locale');

         session()->set('locale', $language);

         return back();
     }

//     public function index() {
//        if(!\Session::has('locale'))
//         { \Session::put('locale',
//           Input::get('locale')); }
//           else{ Session::set('locale',
//             Input::get('locale'));
//           }
//
//             return Redirect::back();
//     }
//
//     public function changeLocale(Request $request)
// {
//     $this->validate($request, ['locale' => 'required|in:th,en']);
//
//     \Session::put('locale', $request->locale);
//
//     return redirect()->back();
// }
}
