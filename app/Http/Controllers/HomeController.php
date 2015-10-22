<?php
/**
 * Created by PhpStorm.
 * User: larjo_000
 * Date: 21/10/2015
 * Time: 10:31 μμ
 */

namespace App\Http\Controllers;


use Input;
use View;

class HomeController extends Controller
{
    public function index(){
        $uri = Input::get("uri");
        $format = Input::get("format");
        return View::make("home")->with("uri", $uri)->with("invalidate", Input::has("invalidate"))
            ->with("format", $format);
    }
}