<?php
if(!array_key_exists( 'HTTP_X_TOKEN', $_SERVER )){
    die;
}

$url = 'http:localhost:8001';

$ch = curl_init( $url );
curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    [
        "X-Token: {$_SERVER['HTTP_X_TOKEN']} "
    ]
    );
curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);

$ret = curl_exec( $ch );

if ( $ret !== 'true' ){
    error_log("Failed");
    die;
}

//Definicion de recursos posibles
$allowedResourceTypes = [
    'books',
    'authors',
    'genres',
];

//Obtención del tipo de recurso solicitado
$resourceType = $_GET['resource_type'];

//Validando que el tipo de recurso obtenido sea válido
if( !in_array($resourceType, $allowedResourceTypes)){
    die;
}

//Defino recursos
$books = [
    1 => [
        'titulo' => 'Cien años de soledad',
        'id_autor' => 2,
        'id_genero' => 2, //Fantasia
    ],
    2 => [
        'titulo' => 'La ciudad y los perros',
        'id_autor' => 1,
        'id_genero' => 3, //Realismo
    ],
];

header('Content-type: application/json');

//Levantamos el id del recurso 
$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : '';

//Validando peticion
switch(strtoupper($_SERVER['REQUEST_METHOD'])){
    case 'GET':
        if(empty($resourceId)){
            echo json_encode($books);
        } else {
            if(array_key_exists($resourceId, $books)){
                echo json_encode($books[$resourceId]);
            }
        }
        break;
    case 'POST':
        $json = file_get_contents('php://input');

        $books[] = json_decode($json, true); //Para que se haga como arreglo
        
        //echo array_keys($books)[count($books) - 1]; //Buena practica, mostrar el id generada

        echo json_encode($books);
        break;
    case 'PUT':
        if(!empty($resourceId) && array_key_exists($resourceId, $books)){
            $json = file_get_contents('php://input');

            //Transformar la respuesta a un nuevo array
            $books[ $resourceId ] = json_decode($json, true);
        
            //Retornamos
            echo json_encode( $books );
        }
        break;
    case 'DELETE':
        if(!empty($resourceId) && array_key_exists($resourceId, $books)){
            
            unset( $books[$resourceId]);

            //Retornamos
            echo json_encode($books);
        }
        break;
}