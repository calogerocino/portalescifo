<?php
$al = 'https://' . $_SERVER['HTTP_HOST'];

if (isset($_SESSION['session_id'])) {
  header('Location: ' . $al);
  exit;
}

if (isset($_POST['login'])) {
  error_reporting(0);
  session_start();
  session_regenerate_id();

  require_once('../../assets/inc/database.php');

  $username = $_POST['InputUser'] ?? '';
  $password = $_POST['PassInput'] ?? '';

  if (empty($username) || empty($password)) {
    $msg = 'Inserisci username e password %s';
  } else {
    $query = "
              SELECT id, user, nome, password, email, IPTelefono, Ruolo, Attivo
              FROM app_utenti
              WHERE user = :username
          ";

    $check = $pdo->prepare($query);
    $check->bindParam(':username', $username, PDO::PARAM_STR);
    $check->execute();

    $user = $check->fetch(PDO::FETCH_ASSOC);

    if (!$user || password_verify($password, $user['password']) === false) {
      $msg = 'Credenziali utente errate!';
      echo $msg;
      exit;
    } else if ($user['Attivo'] == 0) {
      $msg = 'Questo utente è disattivato, non è possibile accedere, riprova con un altro account!';
      activitylog($conn, 'in:Login Bloccato:' . get_client_ip(), $user['id']);
      echo $msg;
      exit;
    } else {
      session_regenerate_id();
      $_SESSION['session_id'] = session_id();
      $_SESSION['session_user'] = $user['user'];
      //$_SESSION['session_perm'] = $user['permessi'];
      $_SESSION['session_email'] = $user['email'];
      $_SESSION['session_ipt'] = $user['IPTelefono'];
      $_SESSION['session_idu'] = $user['id'];
      $_SESSION['session_nome'] = $user['nome'];
      $_SESSION['session_ruolo'] = $user['Ruolo'];
      $_SESSION['session_attivo'] = $user['Attivo'];

      $sql = "SELECT valore FROM app_impostazioni WHERE tipo=6";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $_SESSION['debug'] = $row['valore'];

      setcookie("login", "OK", strtotime("+1 year"));
      echo "Login effettuato con successo";
      activitylog($conn, 'in:login:' . get_client_ip(), $user['id']);

      $sql = "SELECT * FROM app_permessi WHERE IDU=" . $user['id'];
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $_SESSION['session_perm'][0] = $row['s_Online'];
          $_SESSION['session_perm'][1] = $row['s_PVend'];
          $_SESSION['session_perm'][2] = $row['s_Amm'];
          $_SESSION['session_perm'][3] = $row['s_Altro'];
          $_SESSION['session_perm'][4] = $row['home'];
        }
      }

      $category = explode(';', $_SESSION['session_perm'][0]);
      foreach ($category as $key => $val) {
        if ($key == 0) { // - +
          $_SESSION['session_perm'][0][0] = $val; //+-
        } else if ($key == 1) { // ordini, mm, sinistri
          $mini_category = explode(',', $val);
          $_SESSION['session_perm'][0][1] = $mini_category[0];  //lista ordini
          $_SESSION['session_perm'][0][2] = $mini_category[1];  //manomano
          $_SESSION['session_perm'][0][3] = $mini_category[2];  //sinistri
        } else if ($key == 2) { // feedback
          $_SESSION['session_perm'][0][4] = $val;
        } else if ($key == 3) { // segnalazioni
          $_SESSION['session_perm'][0][5] = $val;
        }
      }

      $category = explode(';', $_SESSION['session_perm'][1]);
      foreach ($category as $key => $val) {
        if ($key == 0) { // - +
          $_SESSION['session_perm'][1][0] =  $val; //+-
        } else if ($key == 1) { // preventivi,spaccati,usato,listaschede
          $mini_category = explode(',', $val);
          $_SESSION['session_perm'][1][1] = $mini_category[0];  //preventivi
          $_SESSION['session_perm'][1][2] = $mini_category[1];  //spaccati
          $_SESSION['session_perm'][1][3] = $mini_category[2];  //usato
          $_SESSION['session_perm'][1][4] = $mini_category[3];  //listaschede
        } else if ($key == 2) { // vendita,catalogo,ordine prodotti,lista vendite
          $mini_category = explode(',', $val);
          $_SESSION['session_perm'][1][5] = $mini_category[0];  //vendita
          $_SESSION['session_perm'][1][6] = $mini_category[1];  //catalogo
          $_SESSION['session_perm'][1][7] = $mini_category[2];  //ordine prodotti
          $_SESSION['session_perm'][1][8] = $mini_category[3];  //lista vendite
        } else if ($key == 3) { // vendita, catalogo,documenti,clienti,lista vendite
          $mini_category = explode(',', $val);
          $_SESSION['session_perm'][1][9] = $mini_category[0];  //documenti
          $_SESSION['session_perm'][1][10] = $mini_category[1];  //clienti
        }
      }

      $category = explode(';', $_SESSION['session_perm'][2]);
      foreach ($category as $key => $val) {
        if ($key == 0) { // - +
          $_SESSION['session_perm'][2][0] = $val; //+-
        } else if ($key == 1) { // scadenze,fatture,fornitori
          $mini_category = explode(',', $val);
          $_SESSION['session_perm'][2][1] = $mini_category[0];  //scadenze
          $_SESSION['session_perm'][2][2] = $mini_category[1];  //fatture
          $_SESSION['session_perm'][2][3] = $mini_category[2];  //fornitori
        } else if ($key == 2) { // pratiche legali
          $_SESSION['session_perm'][2][4] = $val;
        } else if ($key == 3) { // lista,buste
          $mini_category = explode(',', $val);
          $_SESSION['session_perm'][2][5] = $mini_category[0];  //lista
        }
      }

      $category = explode(';', $_SESSION['session_perm'][3]);
      foreach ($category as $key => $val) {
        if ($key == 0) { // - +
          $_SESSION['session_perm'][3][0] = $val; //+-
        } else if ($key == 1) { // lista utenti, task
          $mini_category = explode(',', $val);
          $_SESSION['session_perm'][3][1] = $mini_category[0];  //lista utenti
          $_SESSION['session_perm'][3][2] = $mini_category[1];  //task
        } else if ($key == 2) { // database, backup
          $mini_category = explode(',', $val);
          $_SESSION['session_perm'][3][3] = $mini_category[0];  //database
          $_SESSION['session_perm'][3][4] = $mini_category[1];  //backup
        }
      }

      exit;
    }
  }
}

function get_client_ip()
{
  $ipaddress = '';
  if (getenv('HTTP_CLIENT_IP'))
    $ipaddress = getenv('HTTP_CLIENT_IP');
  else if (getenv('HTTP_X_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
  else if (getenv('HTTP_X_FORWARDED'))
    $ipaddress = getenv('HTTP_X_FORWARDED');
  else if (getenv('HTTP_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_FORWARDED_FOR');
  else if (getenv('HTTP_FORWARDED'))
    $ipaddress = getenv('HTTP_FORWARDED');
  else if (getenv('REMOTE_ADDR'))
    $ipaddress = getenv('REMOTE_ADDR');
  else
    $ipaddress = 'UNKNOWN';
  return $ipaddress;
}
?>

<!DOCTYPE html>
<html lang="it-IT" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Utente - PixelSmart</title>

  <!-- ===============================================-->
  <!--    Favicons-->
  <!-- ===============================================-->
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $al; ?>/assets/img/favicons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $al; ?>/assets/img/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $al; ?>/assets/img/favicons/favicon-16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo $al; ?>/assets/img/favicons/favicon.ico">
  <link rel="manifest" href="<?php echo $al; ?>/assets/img/favicons/manifest.json">
  <meta name="msapplication-TileImage" content="<?php echo $al; ?>/assets/img/favicons/mstile-150x150.png">
  <meta name="theme-color" content="#ffffff">
  <script src="<?php echo $al; ?>/app/js/config.js"></script>

  <!-- ===============================================-->
  <!--    File CSS-->
  <!-- ===============================================-->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
  <link href="<?php echo $al; ?>/app/css/theme-rtl.min.css" rel="stylesheet" id="style-rtl">
  <link href="<?php echo $al; ?>/app/css/theme.min.css" rel="stylesheet" id="style-default">
  <link href="<?php echo $al; ?>/app/css/user-rtl.min.css" rel="stylesheet" id="user-style-rtl">
  <link href="<?php echo $al; ?>/app/css/user.min.css" rel="stylesheet" id="user-style-default">
  <link href="<?php echo $al; ?>/vendors/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="<?php echo $al; ?>/app/css/swal2.css" rel="stylesheet" type="text/css">

  <!-- ===============================================-->
  <!--    Caricamento pagina-->
  <!-- ===============================================-->

  <script src="<?php echo $al; ?>/vendors/jquery/jquery.min.js"></script>
  <script src="<?php echo $al; ?>/vendors/jquery/jquery.easing.min.js"></script>

  <!-- ===============================================-->
  <!--    Script Direzionali-->
  <!-- ===============================================-->

  <script>
    var isRTL = JSON.parse(localStorage.getItem('isRTL'));
    if (isRTL) {
      var linkDefault = document.getElementById('style-default');
      var userLinkDefault = document.getElementById('user-style-default');
      linkDefault.setAttribute('disabled', true);
      userLinkDefault.setAttribute('disabled', true);
      document.querySelector('html').setAttribute('dir', 'rtl');
    } else {
      var linkRTL = document.getElementById('style-rtl');
      var userLinkRTL = document.getElementById('user-style-rtl');
      linkRTL.setAttribute('disabled', true);
      userLinkRTL.setAttribute('disabled', true);
    }
  </script>
</head>

<body>
  <!-- ===============================================-->
  <!--    Main Content-->
  <!-- ===============================================-->
  <main class="main" id="top">
    <div class="container-fluid">
      <div class="row min-vh-100 flex-center g-0">
        <div class="col-lg-8 col-xxl-5 py-3 position-relative"><img class="bg-auth-circle-shape" src="<?php echo $al; ?>/assets/img/icons/spot-illustrations/bg-shape.png" alt="" width="250"><img class="bg-auth-circle-shape-2" src="<?php echo $al; ?>/<?php echo $al; ?>/assets/img/icons/spot-illustrations/shape-1.png" alt="" width="150">
          <div class="card overflow-hidden z-index-1">
            <div class="card-body p-0">
              <div class="row g-0 h-100">
                <div class="col-md-5 text-center bg-card-gradient">
                  <div class="position-relative p-4 pt-md-5 pb-md-7 light">
                    <div class="bg-holder bg-auth-card-shape" style="background-image:url(<?php echo $al; ?>/assets/img/icons/spot-illustrations/half-circle.png);"></div>
                    <!--/.bg-holder-->
                    <div class="z-index-1 position-relative"><a class="link-light mb-4 font-sans-serif fs-4 d-inline-block fw-bolder" href="<?php echo $al; ?>/index.php">PixelSmart</a>
                      <p class="opacity-75 text-white">Sfrutta la potenza di PixelSmart! Versione aggiornata V3.x, accedi o registrati per poterla utilizzare</p>
                    </div>
                  </div>
                  <div class="mt-3 mb-4 mt-md-4 mb-md-5 light">
                    <p class="text-white">Non hai un account?<br><a class="text-decoration-underline link-light" href="<?php echo $al; ?>/utente/auth/register.php">Inizia da qui!</a></p>
                    <p class="mb-0 mt-4 mt-md-5 fs--1 fw-semi-bold text-white opacity-75">Leggi <a class="text-decoration-underline text-white" href="#!">termini</a> e <a class="text-decoration-underline text-white" href="#!">conditions </a></p>
                  </div>
                </div>
                <div class="col-md-7 d-flex flex-center">
                  <div class="p-4 p-md-5 flex-grow-1">
                    <div class="row flex-between-center">
                      <div class="col-auto">
                        <h3>Effettua il login</h3>
                      </div>
                    </div>
                    <form action="javascript:void(0);" method="post">
                      <div class="mb-3"><label class="form-label" for="card-email">Indirizzo mail o nome utente</label><input class="form-control" id="card-email" type="text" /></div>
                      <div class="mb-3">
                        <div class="d-flex justify-content-between"><label class="form-label" for="card-password">Password</label></div><input class="form-control" id="card-password" type="password" />
                      </div>
                      <div class="row flex-between-center">
                        <div class="col-auto">
                          <div class="form-check mb-0"><input class="form-check-input" type="checkbox" id="card-checkbox" checked="checked" /><label class="form-check-label mb-0" for="card-checkbox">Ricordami</label></div>
                        </div>
                        <div class="col-auto"><a class="fs--1" href="<?php echo $al; ?>/utente/auth/password-dimentica.php">Password dimenticata?</a></div>
                      </div>
                      <div class="mb-3"><button class="btn btn-primary d-block w-100 mt-3" type="submit" id="LogIn">Log in</button></div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main><!-- ===============================================-->
  <!--    End of Main Content-->
  <!-- ===============================================-->

  <!-- ===============================================-->
  <!--    INIZIO PERSONALIZZAZIONE-->
  <!-- ===============================================-->
  <div class="offcanvas offcanvas-end settings-panel border-0" id="settings-offcanvas" tabindex="-1" aria-labelledby="settings-offcanvas">
    <div class="offcanvas-header settings-panel-header bg-shape">
      <div class="z-index-1 py-1 light">
        <h5 class="text-white"> <span class="fas fa-palette me-2 fs-0"></span>Personalizza</h5>
        <p class="mb-0 fs--1 text-white opacity-75"> Personalizza il tuo stile preferito</p>
      </div><button class="btn-close btn-close-white z-index-1 mt-0" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body scrollbar-overlay px-card" id="themeController">
      <h5 class="fs-0">Schema colori</h5>
      <p class="fs--1">Seleziona il tuo colore preferito dell'app.</p>
      <div class="btn-group d-block w-100 btn-group-navbar-style">
        <div class="row gx-2">
          <div class="col-6"><input class="btn-check" id="themeSwitcherLight" name="theme-color" type="radio" value="light" data-theme-control="theme" /><label class="btn d-inline-block btn-navbar-style fs--1" for="themeSwitcherLight"> <span class="hover-overlay mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="assets/img/generic/falcon-mode-default.jpg" alt="" /></span><span class="label-text">Chiaro</span></label></div>
          <div class="col-6"><input class="btn-check" id="themeSwitcherDark" name="theme-color" type="radio" value="dark" data-theme-control="theme" /><label class="btn d-inline-block btn-navbar-style fs--1" for="themeSwitcherDark"> <span class="hover-overlay mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="assets/img/generic/falcon-mode-dark.jpg" alt="" /></span><span class="label-text">
                Scuro</span></label></div>
        </div>
      </div>
      <hr />
      <div class="d-flex justify-content-between">
        <div class="d-flex align-items-start"><img class="me-2" src="assets/img/icons/left-arrow-from-left.svg" width="20" alt="" />
          <div class="flex-1">
            <h5 class="fs-0">Modalità RTL</h5>
            <p class="fs--1 mb-0">Seleziona la direzione del linguaggio </p><a class="fs--1" href="javascript:void(0)" disabled>Documentazione RTL</a>
          </div>
        </div>
        <div class="form-check form-switch"><input class="form-check-input ms-0" id="mode-rtl" type="checkbox" data-theme-control="isRTL" /></div>
      </div>
      <hr />
      <div class="d-flex justify-content-between">
        <div class="d-flex align-items-start"><img class="me-2" src="assets/img/icons/arrows-h.svg" width="20" alt="" />
          <div class="flex-1">
            <h5 class="fs-0">Fluid Layout</h5>
            <p class="fs--1 mb-0">Attiva/disattiva il sistema di layout del contenitore </p><a class="fs--1" href="javascript:void(0)" disabled>Documentazione Fluid</a>
          </div>
        </div>
        <div class="form-check form-switch"><input class="form-check-input ms-0" id="mode-fluid" type="checkbox" data-theme-control="isFluid" /></div>
      </div>
      <hr />
      <div class="d-flex align-items-start"><img class="me-2" src="assets/img/icons/paragraph.svg" width="20" alt="" />
        <div class="flex-1">
          <h5 class="fs-0 d-flex align-items-center">Posizione di navigazione </h5>
          <p class="fs--1 mb-2">Seleziona un sistema di navigazione adatto alla tua applicazione web </p>
          <div>
            <div class="form-check form-check-inline"><input class="form-check-input" id="option-navbar-vertical" type="radio" name="navbar" value="vertical" data-theme-control="navbarPosition" /><label class="form-check-label" for="option-navbar-vertical">Verticale</label></div>
            <div class="form-check form-check-inline"><input class="form-check-input" id="option-navbar-top" type="radio" name="navbar" value="top" data-theme-control="navbarPosition" /><label class="form-check-label" for="option-navbar-top">Top</label></div>
            <div class="form-check form-check-inline me-0"><input class="form-check-input" id="option-navbar-combo" type="radio" name="navbar" value="combo" data-theme-control="navbarPosition" /><label class="form-check-label" for="option-navbar-combo">Combo</label></div>
          </div>
        </div>
      </div>
      <hr />
      <h5 class="fs-0 d-flex align-items-center">Stile barra verticale</h5>
      <p class="fs--1 mb-0">Passa da uno stile all'altro per la tua barra di navigazione verticale </p>
      <p> <a class="fs--1" href="javascript:void(0)" disabled>Vedi
          documentezione</a></p>
      <div class="btn-group d-block w-100 btn-group-navbar-style">
        <div class="row gx-2">
          <div class="col-6"><input class="btn-check" id="navbar-style-transparent" type="radio" name="navbarStyle" value="transparent" data-theme-control="navbarStyle" /><label class="btn d-block w-100 btn-navbar-style fs--1" for="navbar-style-transparent"> <img class="img-fluid img-prototype" src="assets/img/generic/default.png" alt="" /><span class="label-text">
                Trasparente</span></label></div>
          <div class="col-6"><input class="btn-check" id="navbar-style-inverted" type="radio" name="navbarStyle" value="inverted" data-theme-control="navbarStyle" /><label class="btn d-block w-100 btn-navbar-style fs--1" for="navbar-style-inverted"> <img class="img-fluid img-prototype" src="assets/img/generic/inverted.png" alt="" /><span class="label-text">
                Ivertita</span></label></div>
          <div class="col-6"><input class="btn-check" id="navbar-style-card" type="radio" name="navbarStyle" value="card" data-theme-control="navbarStyle" /><label class="btn d-block w-100 btn-navbar-style fs--1" for="navbar-style-card"> <img class="img-fluid img-prototype" src="assets/img/generic/card.png" alt="" /><span class="label-text"> Carta</span></label></div>
          <div class="col-6"><input class="btn-check" id="navbar-style-vibrant" type="radio" name="navbarStyle" value="vibrant" data-theme-control="navbarStyle" /><label class="btn d-block w-100 btn-navbar-style fs--1" for="navbar-style-vibrant"> <img class="img-fluid img-prototype" src="assets/img/generic/vibrant.png" alt="" /><span class="label-text"> Vibrant</span></label></div>
        </div>
      </div>
    </div>
  </div>
  <a class="card setting-toggle" href="#settings-offcanvas" data-bs-toggle="offcanvas">
    <div class="card-body d-flex align-items-center py-md-2 px-2 py-1">
      <div class="bg-soft-primary position-relative rounded-start" style="height:34px;width:28px">
        <div class="settings-popover"><span class="ripple"><span class="fa-spin position-absolute all-0 d-flex flex-center"><span class="icon-spin position-absolute all-0 d-flex flex-center"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M19.7369 12.3941L19.1989 12.1065C18.4459 11.7041 18.0843 10.8487 18.0843 9.99495C18.0843 9.14118 18.4459 8.28582 19.1989 7.88336L19.7369 7.59581C19.9474 7.47484 20.0316 7.23291 19.9474 7.03131C19.4842 5.57973 18.6843 4.28943 17.6738 3.20075C17.5053 3.03946 17.2527 2.99914 17.0422 3.12011L16.393 3.46714C15.6883 3.84379 14.8377 3.74529 14.1476 3.3427C14.0988 3.31422 14.0496 3.28621 14.0002 3.25868C13.2568 2.84453 12.7055 2.10629 12.7055 1.25525V0.70081C12.7055 0.499202 12.5371 0.297594 12.2845 0.257272C10.7266 -0.105622 9.16879 -0.0653007 7.69516 0.257272C7.44254 0.297594 7.31623 0.499202 7.31623 0.70081V1.23474C7.31623 2.09575 6.74999 2.8362 5.99824 3.25599C5.95774 3.27861 5.91747 3.30159 5.87744 3.32493C5.15643 3.74527 4.26453 3.85902 3.53534 3.45302L2.93743 3.12011C2.72691 2.99914 2.47429 3.03946 2.30587 3.20075C1.29538 4.28943 0.495411 5.57973 0.0322686 7.03131C-0.051939 7.23291 0.0322686 7.47484 0.242788 7.59581L0.784376 7.8853C1.54166 8.29007 1.92694 9.13627 1.92694 9.99495C1.92694 10.8536 1.54166 11.6998 0.784375 12.1046L0.242788 12.3941C0.0322686 12.515 -0.051939 12.757 0.0322686 12.9586C0.495411 14.4102 1.29538 15.7005 2.30587 16.7891C2.47429 16.9504 2.72691 16.9907 2.93743 16.8698L3.58669 16.5227C4.29133 16.1461 5.14131 16.2457 5.8331 16.6455C5.88713 16.6767 5.94159 16.7074 5.99648 16.7375C6.75162 17.1511 7.31623 17.8941 7.31623 18.7552V19.2891C7.31623 19.4425 7.41373 19.5959 7.55309 19.696C7.64066 19.7589 7.74815 19.7843 7.85406 19.8046C9.35884 20.0925 10.8609 20.0456 12.2845 19.7729C12.5371 19.6923 12.7055 19.4907 12.7055 19.2891V18.7346C12.7055 17.8836 13.2568 17.1454 14.0002 16.7312C14.0496 16.7037 14.0988 16.6757 14.1476 16.6472C14.8377 16.2446 15.6883 16.1461 16.393 16.5227L17.0422 16.8698C17.2527 16.9907 17.5053 16.9504 17.6738 16.7891C18.7264 15.7005 19.4842 14.4102 19.9895 12.9586C20.0316 12.757 19.9474 12.515 19.7369 12.3941ZM10.0109 13.2005C8.1162 13.2005 6.64257 11.7893 6.64257 9.97478C6.64257 8.20063 8.1162 6.74905 10.0109 6.74905C11.8634 6.74905 13.3792 8.20063 13.3792 9.97478C13.3792 11.7893 11.8634 13.2005 10.0109 13.2005Z" fill="#2A7BE4"></path>
                </svg></span></span></span></div>
      </div>
      <small class="text-uppercase text-primary fw-bold bg-soft-primary py-2 pe-2 ps-1 rounded-end">Personalizza</small>
    </div>
  </a>
  <!-- ===============================================-->
  <!--    FINE PERSONALIZZAZIONE-->
  <!-- ===============================================-->

  <!-- ===============================================-->
  <!--    JavaScripts-->
  <!-- ===============================================-->
  <script src="<?php echo $al; ?>/vendors/popper/popper.min.js"></script>
  <script src="<?php echo $al; ?>/vendors/bootstrap/bootstrap.min.js"></script>
  <script src="<?php echo $al; ?>/vendors/anchorjs/anchor.min.js"></script>
  <script src="<?php echo $al; ?>/vendors/is/is.min.js"></script>
  <script src="<?php echo $al; ?>/vendors/fontawesome/all.min.js"></script>
  <script src="<?php echo $al; ?>/vendors/lodash/lodash.min.js"></script>
  <script src="<?php echo $al; ?>/https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
  <script src="<?php echo $al; ?>/vendors/list.js/list.min.js"></script>
  <script src="<?php echo $al; ?>/app/js/theme.js"></script>
  <script src="<?php echo $al; ?>/app/js/sweetalert2.all.min.js"></script>

  <script>
    $(document).on('click', '#LogIn', function() {
      if ($('#card-email').val() == '' || $('#card-password').val() == '') {
        Swal.fire({
          icon: 'error',
          title: 'Inserisci tutti i dati correttamente'
        })
      } else {
        $.post("login.php", {
          InputUser: $('#card-email').val(),
          PassInput: $('#card-password').val(),
          login: "login"
        }, function(response) {
          if (response == "Login effettuato con successo") {
            window.location.replace('<?php echo $al; ?>');
          } else {
            Swal.fire({
              icon: 'info',
              title: response
            })
          }
        })
      }
    });
  </script>
</body>

</html>