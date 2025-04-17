<?php
$data = array(
    'respuesta' => 'Probando desde PHP',
    'pregunta' => '¿Funciona el POST?',
    'encuesta' => 1,
    'encuestado' => 123
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
    ),
);

print_r($options);

$context  = stream_context_create($options);
$result = file_get_contents('http://localhost:3000/respuestas', false, $context);

?>