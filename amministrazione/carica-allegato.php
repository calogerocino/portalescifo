<?php
// per prima cosa verifico che il file sia stato effettivamente caricato
if (!isset($_FILES['userfile']) || !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
    echo 'fileno';
    exit;
}

//percorso della cartella dove mettere i file caricati dagli utenti
$uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/v3/upload/' . $_POST['cartella'] . '/';

//Recupero il percorso temporaneo del file
$userfile_tmp = $_FILES['userfile']['tmp_name'];

//recupero il nome originale del file caricato
$userfile_name = $_POST['idpratica'] . $_FILES['userfile']['name'];

//copio il file dalla sua posizione temporanea alla mia cartella upload
if (move_uploaded_file($userfile_tmp, $uploaddir . $userfile_name)) {
    //Se l'operazione è andata a buon fine...
    echo $userfile_name;
} else {
    //Se l'operazione è fallta...
    echo 'carno';
}
