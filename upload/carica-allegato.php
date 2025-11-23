<?php
// Disattiva output errori a schermo per non rompere la risposta AJAX
ini_set('display_errors', 0);
error_reporting(0);

// 1. CONTROLLO ERRORI UPLOAD PHP (Prima ancora di guardare i parametri)
if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] !== UPLOAD_ERR_OK) {
    $msg = 'Errore upload PHP: ' . $_FILES['userfile']['error'];
    switch ($_FILES['userfile']['error']) {
        case UPLOAD_ERR_INI_SIZE:   $msg = 'File troppo grande (limite server upload_max_filesize)'; break;
        case UPLOAD_ERR_FORM_SIZE:  $msg = 'File troppo grande (limite form HTML)'; break;
        case UPLOAD_ERR_PARTIAL:    $msg = 'Caricamento interrotto'; break;
        case UPLOAD_ERR_NO_FILE:    $msg = 'Nessun file selezionato'; break;
        case UPLOAD_ERR_NO_TMP_DIR: $msg = 'Manca cartella temporanea server'; break;
        case UPLOAD_ERR_CANT_WRITE: $msg = 'Impossibile scrivere su disco (permessi?)'; break;
    }
    echo 'carno;' . $msg;
    exit;
}

// 2. CONTROLLO FILE VUOTO
if (isset($_FILES['userfile']) && $_FILES['userfile']['size'] === 0) {
    echo 'carno;Il file ricevuto è vuoto (0 byte).';
    exit;
}

// 3. VERIFICA BASE
if (!isset($_FILES['userfile']) || !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
    echo 'fileno';
    exit;
}

// --- INIZIO LOGICA PERSONALIZZATA (Mantenuta dal tuo file originale) ---

// Definizione base upload (assicurati che punti alla cartella giusta rispetto a dove si trova questo file)
// Se questo file è in /upload/, __DIR__ è perfetto.
$base_dir = __DIR__ . '/'; 

// Gestione parametro 'cartella' base
$cartella_base = isset($_POST['cartella']) ? $_POST['cartella'] : '';
// Pulizia base per sicurezza
$cartella_base = preg_replace('/[^a-zA-Z0-9_-]/', '', $cartella_base);

$uploaddir = $base_dir . $cartella_base . '/';
$userfile_name = $_FILES['userfile']['name']; // Fallback di default

// Logica condizionale specifica
if (isset($_POST['cartella']) && $_POST['cartella'] == 'pratiche') {
    $prefisso = isset($_POST['idpratica']) ? $_POST['idpratica'] : '';
    $userfile_name = $prefisso . $_FILES['userfile']['name'];
}

if (isset($_POST['fornitore']) && !empty($_POST['fornitore'])) {
    // Caso Fornitori: cartella specifica e rinomina con [idtipo]
    $uploaddir .= $_POST['fornitore'] . '/';
    $userfile_name = '[' . $_POST['idtipo'] . '] ' . $_FILES['userfile']['name'];

} else if (isset($_POST['dipendente']) && !empty($_POST['dipendente'])) {
    // Caso Dipendente: cartella specifica e rinomina (nomefile o originale)
    $uploaddir .= $_POST['dipendente'] . '/';
    $userfile_name = (isset($_POST['nomefile']) ? $_POST['nomefile'] . '.pdf' : $_FILES['userfile']['name']);

} else if (isset($_POST['segnalazione']) && !empty($_POST['segnalazione'])) {
    // Caso Segnalazione: cartella specifica
    $uploaddir .= $_POST['segnalazione'] . '/';
} 

// --- FINE LOGICA PERSONALIZZATA ---

// 4. CREAZIONE CARTELLA (Se non esiste)
if (!is_dir($uploaddir)) {
    if (!mkdir($uploaddir, 0777, true)) {
        // Restituisce errore specifico col percorso (utile per debug)
        echo 'carno;Impossibile creare la cartella: ' . str_replace($base_dir, '', $uploaddir);
        exit;
    }
}

// 5. SPOSTAMENTO FILE
$destination = $uploaddir . $userfile_name;

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $destination)) {
    // Controllo post-spostamento
    if (filesize($destination) > 0) {
        // Successo!
        echo $userfile_name;
    } else {
        // File creato ma vuoto
        @unlink($destination);
        echo 'carno;File salvato ma risulta vuoto (0 byte). Errore trasferimento.';
    }
} else {
    $error = error_get_last();
    $err_msg = isset($error['message']) ? $error['message'] : 'Permessi cartella insufficienti?';
    echo 'carno;Errore spostamento file. ' . $err_msg;
}
exit;
?>