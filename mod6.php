<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $immagine = "ricamo6/immagine.jpg"; // Percorso dell'immagine
  $descrizione = $_POST["modifiche6"]; // Descrizione inserita dal cliente

  // Invio dell'email
  $to = "grafica@olalla.it"; // Indirizzo email del destinatario
  $subject = "Revisione Ricamo 6";
  $message = $descrizione;
  $headers = "From: ricami@olalla.it" . "\r\n" .
             "Reply-To: no_reply@olalla.it" . "\r\n";

  // Boundary per separare le parti dell'email
  $boundary = md5(time());

  $headers .= "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"";

  // Creazione del corpo dell'email
  $body = "--" . $boundary . "\r\n";
  $body .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";
  $body .= "Content-Transfer-Encoding: 7bit" . "\r\n\r\n";
  $body .= $message . "\r\n\r\n";

  // Lettura del file dell'immagine
  $file_content = file_get_contents($immagine);
  $file_name = basename($immagine);

  // Aggiunta dell'allegato dell'immagine
  $body .= "--" . $boundary . "\r\n";
  $body .= "Content-Type: application/octet-stream; name=\"" . $file_name . "\"" . "\r\n";
  $body .= "Content-Description: " . $file_name . "\r\n";
  $body .= "Content-Disposition: attachment; filename=\"" . $file_name . "\"; size=" . filesize($immagine) . ";" . "\r\n";
  $body .= "Content-Transfer-Encoding: base64" . "\r\n\r\n";
  $body .= chunk_split(base64_encode($file_content)) . "\r\n";

  $body .= "--" . $boundary . "--";

  // Invio dell'email
  $success = mail($to, $subject, $body, $headers);

  if ($success) {
    echo "Risposta inviata con successo!";
  } else {
    echo "Si è verificato un errore durante l'invio della risposta.";
  }
}
?>
