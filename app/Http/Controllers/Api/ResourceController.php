<?php
/**
 * Created by PhpStorm.
 * User: larjo_000
 * Date: 21/10/2015
 * Time: 8:17 μμ
 */

namespace app\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\SKOSReader;
use EasyRdf_Resource;
use Illuminate\Support\Collection;
use Input;

class ResourceController extends Controller
{
    public function index(){
        $uri = Input::get("uri");
        $skosReader = new SKOSReader();
        $roots = $skosReader->read($uri, Input::has("invalidate"));
        $collection = new Collection($roots);

        $result = $collection->map(function(array $element){
            return ["uri"=>$element["concept"]->getUri(),
                "text"=>$element["concept"]->get("skos:prefLabel")->getValue(),
                "children" => $element["children"],
                "id"=>str_replace("=","",'res_'.base64_encode($element["concept"]->getUri()))] ;
        });

        return $result->values();
    }

    public function children(){
        $uri = Input::get("uri");
        $inp_id = Input::get("id");
        $b64_id = str_replace("res_", "",$inp_id);
        $id = base64_decode($b64_id);
        $skosReader = new SKOSReader();
        $nodeChildren = $skosReader->getNode($uri, $id);
        $collection = new Collection($nodeChildren);

        $result = $collection->map(function(array $element){
            return ["uri"=>$element["concept"]->getUri(),
                "text"=>$element["concept"]->get("skos:prefLabel")->getValue(),
                "children" => $element["children"],
                "id"=>str_replace("=","",'res_'.base64_encode($element["concept"]->getUri()))] ;
        });

        return $result->values();
    }
}