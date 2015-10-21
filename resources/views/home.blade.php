<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{URL::asset("bower_resources/jstree/dist/themes/default/style.css")}}">
    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: monospace;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 96px;
        }

        footer{
            opacity:.5;
            margin-top: 30px;;
        }
    </style>

    <script src="{{URL::asset("bower_resources/jquery/dist/jquery.js")}}"></script>
    <script src="{{URL::asset("bower_resources/jstree/dist/jstree.js")}}"></script>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">Organic SKOS</div>

        <div style="text-align: left" id="jstree_demo">

        </div>
    </div>
    <footer>Organic SKOS was developed by <a target="_blank" href="http://okfn.gr">OKF Greece</a></footer>
</div>

<script>
    $('#jstree_demo').jstree({
        "core" : {
            "animation" : 0,
            "check_callback" : true,
            "themes" : { "stripes" : true },
            'data' : {
                'url' : function (node) {
                    return node.id === '#' ?
                            '{{URL::to("api/resources")}}' : '{{URL::to("api/resources/children")}}';
                },
                'data' : function (node) {
                    var params =  { 'id' : node.id, 'uri':'{{$uri}}' };
                    var invalidate = @if($invalidate)true @else false @endif;
                    if(invalidate==1){
                        params = $.extend({"invalidate":"true"}, params);
                    }
                    return params;
                }
            }
        },
        "types" : {
            "#" : {
                "max_children" : 1,
                "max_depth" : 4,
                "valid_children" : ["root"]
            },
            "root" : {
                "icon" : "/static/3.2.1/assets/images/tree_icon.png",
                "valid_children" : ["default"]
            },
            "default" : {
                "valid_children" : ["default","file"]
            },
            "file" : {
                "icon" : "glyphicon glyphicon-file",
                "valid_children" : []
            }
        },
        "plugins" : [
            "contextmenu", "dnd", "search",
            "state", "types", "wholerow", "search"
        ]
    });

</script>
</body>
</html>
