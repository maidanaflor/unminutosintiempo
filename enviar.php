<?php
$mensajeEnviado = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $email = htmlspecialchars($_POST["email"]);
    $mensaje = htmlspecialchars($_POST["mensaje"]);

    $para = "unminutosintiempo@gmail.com"; 
    $asunto = "Mensaje desde formulario de contacto";
    $cuerpo = "Nombre: $nombre\nEmail: $email\nMensaje:\n$mensaje";

    $headers = "From: $email" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    if (mail($para, $asunto, $cuerpo, $headers)) {
        $mensajeEnviado = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Formulario Enviado</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<section class="contacto" id="Contacto">
  <form class="contact-form" action="enviar.php" method="POST">
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <label for="mensaje">Mensaje</label>
    <textarea id="mensaje" name="mensaje" rows="5" required></textarea>

    <?php if ($mensajeEnviado): ?>
      <button type="button" disabled style="background-color: #D9C2AB; color: #1e1e1e;">
        Tu mensaje ya fue enviado
      </button>
    <?php else: ?>
      <button type="submit">Enviar mensaje</button>
    <?php endif; ?>
  </form>

  <div class="contact-info">
    <h3>Resolvamos tus<br>inquietudes</h3>
    <p>Te invito a explorar tu voz y dejar que la música revele lo que las palabras no pueden</p>
    <div class="instagram">
      <img class="instagram-icon" src="https://cdn-icons-png.flaticon.com/512/174/174855.png" alt="Instagram">
      <span>@unminutosintiempo</span>
    </div>
  </div>
</section>

</body>
</html>
