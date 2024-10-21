<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['croppedImage'])) {
        $image = $_FILES['croppedImage'];

        // Pfad zum Speichern des Bildes
        $uploadDir = 'uploads/';

        // Dateiendung (hier: PNG)
        $fileExtension = '.png';

        // Aktueller Zeitstempel
        $timestamp = time();

        // Erstelle einen eindeutigen Dateinamen mit Zeitstempel
        // Hier kannst du noch deinen Namen der Datei anpassen und auch den Datenbank-Eintrag erledigen, damit dein Script weiß, wie dein Bild heißt und wo sich die Datei befindet.
        $uploadFile = $uploadDir . 'thumbnail_' . $timestamp . $fileExtension;

        // Bildgröße überprüfen
        list($width, $height) = getimagesize($image['tmp_name']);
        if ($width === 640 && $height === 360) {
            if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
                echo 'Bild erfolgreich hochgeladen unter: ' . $uploadFile;
            } else {
                echo 'Fehler beim Hochladen des Bildes.';
            }
        } else {
            echo 'Bild hat nicht die richtige Größe.';
        }
    } else {
        echo 'Kein Bild empfangen.';
    }
}
?>
