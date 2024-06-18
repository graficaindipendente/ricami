<?php
$targetDirectory = "ricamo4/"; // Specifica qui la cartella di destinazione del server
$targetFile = $targetDirectory . "immagine.jpg"; // Specifica il nome del file come "immagine.jpg"

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

// Verifica se l'immagine è un'immagine reale o una falsa immagine
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "Il file è un'immagine - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "Il file non è un'immagine.";
        $uploadOk = 0;
    }
}





// Consenti solo determinati formati di immagine (puoi aggiungere altri formati se necessario)
$allowedImageFormats = array("jpg", "jpeg", "png", "gif");
if (!in_array($imageFileType, $allowedImageFormats)) {
    echo "Spiacenti, sono consentiti solo file JPG, JPEG, PNG.";
    $uploadOk = 0;
}

// Controlla se $uploadOk è impostato a 0 a causa di un errore
if ($uploadOk == 0) {
    echo "Spiacenti, il tuo ricamo non è stato caricato.";
// Se tutto è ok, carica il file e ridimensiona l'immagine
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        if ($imageFileType == "png") {
            // Converte l'immagine PNG in JPG
            $image = imagecreatefrompng($targetFile);
            $background = imagecreatetruecolor(imagesx($image), imagesy($image));
            imagefill($background, 0, 0, imagecolorallocate($background, 255, 255, 255));
            imagecopy($background, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            imagedestroy($image);
            imagejpeg($background, $targetFile, 100);
            imagedestroy($background);
        }

        echo "Il ricamo " . basename($_FILES["fileToUpload"]["name"]) . " è stato caricato con successo come immagine.jpg.";
    } else {
        echo "Si è verificato un errore durante il caricamento del ricamo.";
    }
}
?>
