<?php
/**
 * Created by PhpStorm.
 * User: larjo_000
 * Date: 22/10/2015
 * Time: 1:15 πμ
 */

namespace App;


use EasyRdf_Http;
use EasyRdf_Sparql_Client;

class SKOSSPARQL
{


    private function client($uri){
        $client = new \EasyRdf_Http_Client($uri, ["timeout"=>600,      'maxredirects'    => 5,
            'useragent'       => 'EasyRdf_Http_Client',]);
        $sparqlclient = new EasyRdf_Sparql_Client($uri);
        EasyRdf_Http::setDefaultHttpClient($client);

        return $sparqlclient;
    }


    public function read($uri, $invalidate){
        /** @var EasyRdf_Sparql_Client $client */
        $client = $this->client($uri);
        //$result = $client->query("")->
        $graph = $this->fromCache($uri, $invalidate);

        $concepts = $graph->allOfType("http://www.w3.org/2004/02/skos/core#Concept");

        $roots =[];



        foreach($concepts as $concept){
            /** @var EasyRdf_Resource $concept */
            /** @var EasyRdf_Resource $broader */
            $broader = $concept->getResource("skos:broader");
            if($broader==null){
                $uri = $concept->getUri();
                $roots[$uri] =["concept" => $concept,
                    "children" =>count($concept->allResources("skos:narrower"))>0];
            }
            else{
                $broaderUri = $broader->getUri();
                if(isset($roots[$broaderUri])){
                    $roots[$broaderUri]["children"] = true;
                }

            }

        }

        foreach($concepts as $concept){
            /** @var EasyRdf_Resource $concept */

            $children = $concept->allResources("skos:narrower") ;

            foreach($children as $item){
                /** @var EasyRdf_Resource $item */
                $uri = $item->getUri();
                if(isset($roots[$uri]))unset($uri);
            }
        }

        return $roots;
    }

    private function getChildren(array $concepts, EasyRdf_Resource $concept){
        $authenticChildren = $concept->all("skos:narrower");
        $affinityChildren = [];
        foreach($concepts as $concepto){
            /** @var EasyRdf_Resource $concepto */
            /** @var EasyRdf_Resource $broader */
            $broader = $concepto->getResource("skos:broader");
            if($broader!=null){
                $broaderUri = $broader->getUri();
                if($broaderUri == $concept->getUri()){
                    $affinityChildren[] = $concepto;
                }
            }

        }

        return array_merge($authenticChildren, $affinityChildren);
    }

    public function getNode($uri, $node){
        $this->client($uri);

        $graph = $this->fromCache($uri, false);

        /** @var EasyRdf_Resource $concept */
        $concept = $graph->resource($node);
        $concepts = $graph->allOfType("http://www.w3.org/2004/02/skos/core#Concept");
        $firstChildren = $this->getChildren($concepts, $concept);

        $children = [];

        foreach($firstChildren as $firstChild){
            /** @var EasyRdf_Resource $firstChild */
            $childUri = $firstChild->getUri();
            $secondChildren = $this->getChildren($concepts, $firstChild);
            $children[$childUri] = ["concept" =>$firstChild, "children" => count($secondChildren)>0];
        }

        return $children;

    }


}