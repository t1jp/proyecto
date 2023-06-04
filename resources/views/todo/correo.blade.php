<?php
$destinatario = "ejemplo@ejemplo.com";
$asunto = "Correo electrónico dinámico";
$mensaje = "Hola, {NOMBRE}.\n\nEste es un correo electrónico dinámico enviado desde PHP.";

// Reemplaza "{NOMBRE}" con el valor real
$mensaje = str_replace("{NOMBRE}", "John Doe", $mensaje);

// Establece las cabeceras del correo electrónico
$cabeceras = "From: tu_direccion@ejemplo.com\r\n";
$cabeceras .= "Reply-To: tu_direccion@ejemplo.com\r\n";
$cabeceras .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Envía el correo electrónico
mail($destinatario, $asunto, $mensaje, $cabeceras);
?>