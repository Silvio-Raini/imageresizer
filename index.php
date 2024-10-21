<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bild-Upload mit fester Größe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
        }
        #uploadForm {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px; /* Noch größere Box */
            width: 100%;
            text-align: center;
        }
        #previewImage {
            width: 100%;
            height: auto;
            max-height: 600px; /* Vorschau noch größer */
            display: none;
            margin-bottom: 10px;
        }
        #cropButton {
            display: none;
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        #cropButton:hover {
            background-color: #0056b3;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<form id="uploadForm" enctype="multipart/form-data">
    <h2>Bild hochladen</h2>
    <input type="file" id="imageInput" accept="image/*">
    <div>
        <img id="previewImage" alt="Bildvorschau">
    </div>
    <button type="button" id="cropButton">Zuschneiden und Hochladen</button>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    let cropper;
    const imageInput = document.getElementById('imageInput');
    const previewImage = document.getElementById('previewImage');
    const cropButton = document.getElementById('cropButton');

    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                cropButton.style.display = 'inline-block';

                // Cropper.js initialisieren
                cropper = new Cropper(previewImage, {
                    aspectRatio: 16 / 9, // Festes Seitenverhältnis 16:9
                    viewMode: 1, // Benutzer kann nur den Bildausschnitt und Zoom ändern
                    zoomable: true, // Benutzer darf zoomen
                    scalable: false, // Verhindert das Skalieren (nur Position/Zoom möglich)
                    movable: true, // Bild kann verschoben werden
                    rotatable: false, // Drehen wird deaktiviert
                    cropBoxResizable: true, // Zuschneidebox bleibt unveränderbar
                    minCropBoxWidth: 256, // Minimale Zuschneidebox-Breite
                    minCropBoxHeight: 144 // Minimale Zuschneidebox-Höhe (entspricht 16:9)
                });
            };
            reader.readAsDataURL(file);
        }
    });

    cropButton.addEventListener('click', function() {
        const canvas = cropper.getCroppedCanvas({
            width: 640, // Feste Breite
            height: 360 // Feste Höhe
        });

        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('croppedImage', blob, 'thumbnail.png'); // Speichere das Bild als PNG

            fetch('upload.php', {
                method: 'POST',
                body: formData
            }).then(response => {
                return response.text();
            }).then(data => {
                alert('Bild erfolgreich hochgeladen!');
                console.log(data);
            }).catch(error => {
                console.error('Fehler:', error);
            });
        }, 'image/png'); // Spezifizieren des MIME-Typs als PNG
    });
</script>

</body>
</html>
