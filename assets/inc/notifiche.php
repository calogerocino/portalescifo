<?php
session_start();

if (isset($_SESSION['session_id'])) {
    $session_idu = htmlspecialchars($_SESSION['session_idu'], ENT_QUOTES, 'UTF-8');
}

include("database.php");

$dataoggi = date("d/m/Y");
$notifiche = "";


if ($_POST['azione'] == 'invia') {
    $sql = "INSERT INTO `app_notifiche`( `dachi`, `quando`, `cosa`, `lettura`, `perchi`) VALUES ('" . $session_idu . "','" . $dataoggi . "','" . $_POST['cosa'] . "','NO','" . $_POST['perchi'] . "')";

    if (!$conn->query($sql)) {
        echo "Errore";
    } else {
        echo "Fatto";
    }

    activitylog($conn, 'in:notifiche:invia', $_SESSION['session_idu']);
    exit();
} else if ($_POST['azione'] == 'controlla') {
    $sql = "SELECT * FROM app_notifiche WHERE perchi=" . $session_idu . " AND lettura='NO'";
    $result = $conn->query($sql);
    while ($row = mysqli_fetch_array($result)) {
        $sql2 = "SELECT Nome FROM app_utenti WHERE id=" . $row["dachi"];
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();

        $notifiche .= "<a class=\"dropdown-item d-flex align-items-center\" href=\"javascript:void(0)\" id=\"notifica\" idnot=" . $row["ID"] . " onclick=\"aprinotifica()\">";
        $notifiche .= "<div class=\"mr-3\">";
        $notifiche .= "<div class=\"icon-circle bg-primary\">";
        $notifiche .= "<i class=\"fa-duotone fa-file-alt text-white\"></i>";
        $notifiche .= "</div>";
        $notifiche .= "</div>";
        $notifiche .= "<div>";
        $notifiche .= "<div class=\"small text-gray-500\">" . $row["quando"] . "</div>";
        $notifiche .= "<span class=\"font-weight-bold\">" . $row["cosa"] . "</span>";
        $notifiche .= "<div class=\"small text-gray-500\">" . $row2["Nome"] . "</div>";
        $notifiche .= "</div>";
        $notifiche .= "</a>";
    }
    echo $notifiche;
    exit();
} else if ($_POST['azione'] == 'leggi') {
    $sql = "SELECT * FROM app_notifiche WHERE ID='" . $_POST['idnot']  . "'";
    $result = $conn->query($sql);
    while ($row = mysqli_fetch_array($result)) {
        $notifica = $row["cosa"] . ";";
        $notifica .= $row["dachi"];
    }
    echo $notifica;


    $sql = "UPDATE app_notifiche SET lettura='SI' WHERE ID='" . $_POST['idnot']  . "'";
    if (!$conn->query($sql)) {
    } else {
    }

    activitylog($conn, 'up:notifiche:leggi', $_SESSION['session_idu']);
    exit;
} else if ($_POST['azione'] == 'numero') {
    $sql = "SELECT COUNT(ID) as Totale FROM app_notifiche WHERE perchi='" . $session_idu . "' AND lettura='NO'";
    $result = $conn->query($sql);
    while ($row = mysqli_fetch_array($result)) {
        echo $row["Totale"];
    }
    exit();
} else if ($_POST['azione'] == 'problema') {
    $sql = "INSERT INTO `app_notifiche`( `dachi`, `quando`, `cosa`, `lettura`, `perchi`) VALUES ('" . $session_idu . "','" . $dataoggi . "','" . $_POST['cosa'] . "','NO','1')";

    if (!$conn->query($sql)) {
        $conn->error;
    } else {
        echo "ok";
    }

    activitylog($conn, 'in:notifiche:problema', $_SESSION['session_idu']);
    exit;
}
