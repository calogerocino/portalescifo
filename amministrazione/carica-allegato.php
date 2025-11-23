<?php
// Disattiva output errori a schermo per non rompere la risposta AJAX
ini_set('display_errors', 0);
error_reporting(0);

// 1. CONTROLLO ERRORI UPLOAD PHP
if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] !== UPLOAD_ERR_OK) {
    $msg = 'Errore sconosciuto';
    switch ($_FILES['userfile']['error']) {
        case UPLOAD_ERR_INI_SIZE:   $msg = 'File troppo grande (limite server php.ini)'; break;
        case UPLOAD_ERR_FORM_SIZE:  $msg = 'File troppo grande (limite form HTML)'; break;
        case UPLOAD_ERR_PARTIAL:    $msg = 'Caricamento interrotto'; break;
        case UPLOAD_ERR_NO_FILE:    $msg = 'Nessun file selezionato'; break;
        case UPLOAD_ERR_NO_TMP_DIR: $msg = 'Manca cartella temporanea server'; break;
        case UPLOAD_ERR_CANT_WRITE: $msg = 'Impossibile scrivere su disco (permessi?)'; break;
        case UPLOAD_ERR_EXTENSION:  $msg = 'Estensione PHP ha bloccato upload'; break;
    }
    echo 'carno;' . $msg;
    exit;
}

// 2. CONTROLLO FILE VUOTO (0 BYTE)
if (isset($_FILES['userfile']) && $_FILES['userfile']['size'] === 0) {
    echo 'carno;Il file ricevuto è vuoto (0 byte).';
    exit;
}

// 3. VERIFICA BASE
if (!isset($_FILES['userfile']) || !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
    echo 'fileno';
    exit;
}

// 4. PERCORSO DESTINAZIONE
// Se lo script è in /amministrazione/, la cartella upload è in ../upload/
$cartella_nome = isset($_POST['cartella']) ? $_POST['cartella'] : 'misto';
$base_upload = __DIR__ . '/../upload/'; 
$cartella_destinazione = $base_upload . $cartella_nome . '/';

// Verifica esistenza cartella
if (!is_dir($cartella_destinazione)) {
    if (!mkdir($cartella_destinazione, 0777, true)) {
        echo 'carno;Cartella destinazione non esiste e impossibile crearla: ' . $cartella_nome;
        exit;
    }
}

// 5. NOME FILE
// Manteniamo la logica originale: se c'è 'idfile' (prodotti) lo usiamo, altrimenti nome originale
if (isset($_POST['idfile']) && $_POST['idfile'] != '') {
    $userfile_name = $_POST['idfile'] . '.jpg';
} else {
    // Per fatture e pratiche usiamo un prefisso se c'è (es. idpratica) + nome originale
    $prefisso = isset($_POST['idpratica']) ? str_replace(['[',']',' '], '', $_POST['idpratica']) : '';
    $userfile_name = $prefisso . basename($_FILES['userfile']['name']);
}

// 6. SPOSTAMENTO
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $cartella_destinazione . $userfile_name)) {
    // Controllo finale dimensione su disco
    if (filesize($cartella_destinazione . $userfile_name) > 0) {
        echo $userfile_name;
    } else {
        @unlink($cartella_destinazione . $userfile_name);
        echo 'carno;File salvato ma risulta vuoto (0 byte).';
    }
} else {
    $error = error_get_last();
    echo 'carno;Errore nello spostamento file: ' . ($error['message'] ?? 'Permessi cartella?');
}
exit;
?>