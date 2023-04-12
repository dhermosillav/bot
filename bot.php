<?php 

// Registro para verificar si se reciben los datos del bot
error_log("Datos recibidos del bot: " . file_get_contents("php://input"));

// Token de autenticación del bot de Telegram
$telegram_token = "6231788401:AAE8wIzzlciXwSG-75ye-Ngurb2blytvvME";

// URL de la API de Telegram para enviar mensajes
$telegram_api_url = 'https://api.telegram.org/bot' . $telegram_token . '/sendMessage';

// Mensaje de saludo
$greeting = 'Hola! ¿En qué puedo ayudarte?';

// Mensaje de despedida
$farewell = '¡Hasta pronto!';

// Obtenemos los datos de la solicitud POST enviada por Telegram
$update = json_decode(file_get_contents('php://input'), true);

// Verificamos si el mensaje recibido es un mensaje de texto
if (isset($update['message']) && isset($update['message']['text'])) {

  // Obtenemos el texto del mensaje
  $message_text = strtolower($update['message']['text']);

  // Obtenemos el ID de chat del usuario que envió el mensaje
  $chat_id = $update['message']['chat']['id'];

  // Creamos un array con la respuesta que enviaremos a Telegram
  $response = array(
    'text' => '',
    'chat_id' => $chat_id
  );

  // Verificamos si el mensaje recibido es una solicitud de saludo
  if ($message_text == '/start' || $message_text == '/hello') {
    $response['text'] = $greeting;
  }

  // Verificamos si el mensaje recibido es una solicitud de despedida
  else if ($message_text == '/bye' || $message_text == '/goodbye') {
    $response['text'] = $farewell;
  }

  // Si no es una solicitud predefinida, respondemos con un mensaje predeterminado
  else {
    $response['text'] = 'Lo siento, no comprendo lo que quieres decir.';
  }

  // Convertimos el array de respuesta a formato JSON
  $json_response = json_encode($response);

  // Configuramos las opciones de la solicitud HTTP POST a la API de Telegram
  $options = array(
    'http' => array(
      'method'  => 'POST',
      'header'  => 'Content-type: application/json',
      'content' => $json_response
    )
  );

  // Creamos el contexto para la solicitud HTTP POST
  $context = stream_context_create($options);

  // Enviamos la solicitud HTTP POST a la API de Telegram
  $result = file_get_contents($telegram_api_url, false, $context);

  // Imprimimos el resultado de la solicitud para fines de depuración
  echo $result;
}