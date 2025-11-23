<?php
// Disattiva output errori a schermo per non rompere la risposta AJAX
ini_set('display_errors', 0);
error_reporting(0);

// 1. CONTROLLO ERRORI NATIVI DI PHP
// Se c'è un errore nell'upload, PHP imposta il campo 'error'. Prima lo ignoravamo.
if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] !== UPLOAD_ERR_OK) {
    $msg = 'Errore sconosciuto';
    switch ($_FILES['userfile']['error']) {
        case UPLOAD_ERR_INI_SIZE:
            $msg = 'Il file supera il limite upload_max_filesize del server';
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $msg = 'Il file supera il limite MAX_FILE_SIZE del form HTML';
            break;
        case UPLOAD_ERR_PARTIAL:
            $msg = 'Il caricamento è stato interrotto (file parziale)';
            break;
        case UPLOAD_ERR_NO_FILE:
            $msg = 'Nessun file è stato caricato';
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $msg = 'Manca la cartella temporanea sul server';
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $msg = 'Impossibile scrivere il file su disco (permessi o disco pieno)';
            break;
        case UPLOAD_ERR_EXTENSION:
            $msg = 'Caricamento interrotto da un estensione PHP';
            break;
    }
    echo 'carno;' . $msg;
    exit;
}

// 2. CONTROLLO DIMENSIONE REALE
// Se il file arriva ma è 0 byte
if (isset($_FILES['userfile']) && $_FILES['userfile']['size'] === 0) {
    echo 'carno;Il file ricevuto è vuoto (0 byte). Controlla il file originale.';
    exit;
}

// 3. VERIFICA UPLOAD STANDARD
if (!isset($_FILES['userfile']) || !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
    echo 'fileno';
    exit;
}

// CONFIGURAZIONE PERCORSI
// Mantengo la logica del tuo file originale, ma aggiungo controlli
$cartella_destinazione = isset($_POST['cartella']) ? $_POST['cartella'] . '/' : '';
if ($cartella_destinazione == '') {
    echo 'carno;Errore interno: destinazione mancante';
    exit;
}

// Percorso temporaneo e nome finale
$userfile_tmp = $_FILES['userfile']['tmp_name'];
// Forzo estensione jpg come nel tuo file originale (gestisce immagini prodotto)
$userfile_name = $_POST['idfile'] . '.jpg'; 

// 4. VERIFICA ESISTENZA CARTELLA DESTINAZIONE
if (!is_dir($cartella_destinazione)) {
    // Provo a crearla se non esiste (utile se cambi server o pulisci cartelle)
    if (!mkdir($cartella_destinazione, 0777, true)) {
        echo 'carno;La cartella di destinazione non esiste e non riesco a crearla';
        exit;
    }
}

// 5. SPOSTAMENTO FILE
if (move_uploaded_file($userfile_tmp, $cartella_destinazione . $userfile_name)) {
    // Controllo paranoico finale: il file creato ha dei dati?
    if (filesize($cartella_destinazione . $userfile_name) > 0) {
        echo $userfile_name;
    } else {
        // Se è 0 byte lo cancello subito per non lasciare file "morti"
        @unlink($cartella_destinazione . $userfile_name);
        echo 'carno;Errore critico: file creato ma vuoto (0 byte) dopo lo spostamento.';
    }
} else {
    // Recupero l'errore di sistema se move_uploaded_file fallisce (es. permessi cartella)
    $error = error_get_last();
    $errore_txt = isset($error['message']) ? $error['message'] : 'Errore generico';
    echo 'carno;Errore nello spostamento del file: ' . $errore_txt;
}
exit;
?>