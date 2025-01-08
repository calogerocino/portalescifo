<!DOCTYPE html>
<html lang="it-IT" dir="ltr">
<?php
session_start();
unset($_SESSION['debug']);
if (isset($_SESSION['session_id']) || $_COOKIE["login"] == "OK") {
  $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
  $session_id = htmlspecialchars($_SESSION['session_id']);
  $session_ipt = htmlspecialchars($_SESSION['session_ipt']);
  $session_idu = htmlspecialchars($_SESSION['session_idu']);
  $session_mail = htmlspecialchars($_SESSION['session_email']);
  $session_uname = htmlspecialchars($_SESSION['session_nome']);
  $session_ruolo = htmlspecialchars($_SESSION['session_ruolo']);
  $session_attivo = htmlspecialchars($_SESSION['session_attivo']);
  require_once('assets/inc/database.php');
  $sql = "SELECT valore FROM app_impostazioni WHERE tipo=6";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $debug = $row['valore'];
  header('Access-Control-Allow-Origin: *', false);
  $jc = round(microtime(true) * 1000);
} else {
  header("location: utente/auth/login.php");
}
?>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - PixelSmart</title>
  <!-- ===============================================-->
  <!--    Favicons-->
  <!-- ===============================================-->
  <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
  <link rel="manifest" href="assets/img/favicons/manifest.json">
  <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
  <meta name="theme-color" content="#ffffff">
  <script src="app/js/config.js?jc=<?= $jc ?>"></script>
  <!-- ===============================================-->
  <!--    File CSS-->
  <!-- ===============================================-->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
  <link href="vendors/overlayscrollbars/OverlayScrollbars.min.css" rel="stylesheet">
  <link href="app/css/theme-rtl.min.css" rel="stylesheet" id="style-rtl">
  <link href="app/css/theme.min.css" rel="stylesheet" id="style-default">
  <link href="app/css/user-rtl.min.css" rel="stylesheet" id="user-style-rtl">
  <link href="app/css/user.min.css" rel="stylesheet" id="user-style-default">
  <link href="app/css/swal2.css" rel="stylesheet" type="text/css">
  <link href="app/css/dataTables.min.css" rel="stylesheet" type="text/css">
  <link href="vendors/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendors/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css">
  <link href="vendors/dropzone/dropzone.min.css" rel="stylesheet" type="text/css">
  <script src="vendors/dropzone/dropzone.min.js"></script>
  <!-- ===============================================-->
  <!--    Caricamento pagina-->
  <!-- ===============================================-->
  <script src="vendors/jquery/jquery.min.js"></script>
  <script src="vendors/jquery/jquery.easing.min.js"></script>
  <!-- ===============================================-->
  <!--    Script Direzionali-->
  <!-- ===============================================-->
  <script>
    var ModalitaDebug = <?= $debug; ?>;
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
  <div id="loader-screen" class="loader-screen">
    <div class="loader-screen__content"><svg xmlns="http://www.w3.org/2000/svg" viewBox="-5 -5 55 30" class="loader-screen__loader">
        <path id="inf" d="M22.5 6.05c-5.9 0-6.76-6-13.21-6A9.56 9.56 0 0 0 0 9.8a9.56 9.56 0 0 0 9.29 9.8c6.45 0 7.31-6.05 13.21-6.05s6.76 6.05 13.21 6.05A9.56 9.56 0 0 0 45 9.8 9.56 9.56 0 0 0 35.71 0C29.26 0 28.4 6.05 22.5 6.05z"></path>
        <use class="loader-screen__loader-mask" xlink:href="#inf" stroke-dasharray="15 104" stroke-dashoffset="0"></use>
      </svg></div>
  </div>
  <!-- ===============================================-->
  <!--    Main Content-->
  <!-- ===============================================-->
  <main class="main" id="top">
    <div class="container" data-layout="container">
      <script>
        var isFluid = JSON.parse(localStorage.getItem('isFluid'));
        if (isFluid) {
          var container = document.querySelector('[data-layout]');
          container.classList.remove('container');
          container.classList.add('container-fluid');
        }
      </script>
      <nav class="navbar navbar-light navbar-vertical navbar-expand-xl" style="display: none;">
        <script>
          var navbarStyle = localStorage.getItem("navbarStyle");
          if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
          }
        </script>
        <div class="d-flex align-items-center">
          <div class="toggle-icon-wrapper">
            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Attiva/Disattiva navigazione"><span class="navbar-toggle-icon">
                <span class="toggle-line"></span></span></button>
          </div><a class="navbar-brand" href="javascript:void(0)" onlick="cambiopagina('','home','')">
            <div class="d-flex align-items-center py-3">
              <h3 class="text-primary fw-bold mb-0">Pixel<span class="text-info fw-bold">Smart</span></h3>
            </div>
          </a>
        </div>
        <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
          <script>
            $('#navbarVerticalCollapse').load('page/navbar-side.php')
          </script>
        </div>
      </nav>
      <nav class="navbar navbar-light navbar-glass navbar-top navbar-expand-xl" style="display: none;" id="navbar-top">
        <script>
          $('#navbar-top').load('page/navbar-top.php')
        </script>
      </nav>
      <div class="content">
        <nav class="navbar navbar-light navbar-glass navbar-top navbar-expand" style="display: none;">
          <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Attiva/Disattiva navigazione"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
          <a class="navbar-brand me-1 me-sm-3" href="index.html">
            <div class="d-flex align-items-center">
              <h3 class="text-primary fw-bold mb-0">Pixel<span class="text-info fw-bold">Smart</span></h3>
            </div>
          </a>
          <ul class="navbar-nav align-items-center d-none d-lg-block">
            <li class="nav-item">
              <div class="search-box" data-list='{"valueNames":["title"]}'>
                <form class="position-relative" data-bs-toggle="search" data-bs-display="static"><input class="form-control search-input search-input-f fuzzy-search" type="search" placeholder="Cerca..." aria-label="Cerca" />
                  <span class="fas fa-search search-box-icon"></span>
                </form>
                <div class="btn-close-falcon-container position-absolute end-0 top-50 translate-middle shadow-none" data-bs-dismiss="search">
                  <div class="btn-close-falcon" aria-label="Chiudi"></div>
                </div>
                <div class="dropdown-menu border font-base start-0 mt-2 py-0 overflow-hidden w-100">
                  <div class="scrollbar list py-3" style="max-height: 24rem;">
                    <h6 class="dropdown-header fw-medium text-uppercase px-card fs--2 pt-0 pb-2">Visualizzati di recente</h6>
                    <div id="VisRecenti"></div>
                    <hr class="bg-200 dark__bg-900" />
                    <h6 class="dropdown-header fw-medium text-uppercase px-card fs--2 pt-0 pb-2">Pagine suggerite</h6><a class="dropdown-item px-card py-1 fs-0" href="javascript:void(0)" id="imp-c">
                      <div class="d-flex align-items-center"><span class="badge fw-medium text-decoration-none me-2 badge-soft-warning">corrieri:</span>
                        <div class="flex-1 fs--1 title">Costi di spedizione</div>
                      </div>
                    </a>
                    <a class="dropdown-item px-card py-1 fs-0" href="#tutorial-offcanvas" data-bs-toggle="offcanvas">
                      <div class="d-flex align-items-center"><span class="badge fw-medium text-decoration-none me-2 badge-soft-success">tutorial:</span>
                        <div class="flex-1 fs--1 title">Video tutorial!</div>
                      </div>
                    </a>
                    <a class="dropdown-item px-card py-1 fs-0" href="javascript:void(0)" id="imp-cl">
                      <div class="d-flex align-items-center"><span class="badge fw-medium text-decoration-none me-2 badge-soft-info">pixelsmart:</span>
                        <div class="flex-1 fs--1 title">Visualizza Changelog</div>
                      </div>
                    </a>
                    <hr class="bg-200 dark__bg-900">
                    <h6 class="dropdown-header fw-medium text-uppercase px-card fs--2 pt-0 pb-2">File</h6>
                    <a class="dropdown-item px-card py-2" href="assets/ext/bp.zip">
                      <div class="d-flex align-items-center">
                        <div class="file-thumbnail me-2"><img class="img-fluid" src="assets/img/icons/zip.png" alt="" />
                        </div>
                        <div class="flex-1">
                          <h6 class="mb-0 title">Driver Zebra</h6>
                          <p class="fs--2 mb-0 d-flex"><span class="fw-semi-bold">admin</span><span class="fw-medium text-600 ms-2">25 Ott a 18:22 PM</span></p>
                        </div>
                      </div>
                    </a>
                    <hr class="bg-200 dark__bg-900" />
                    <h6 class="dropdown-header fw-medium text-uppercase px-card fs--2 pt-0 pb-2">Membri online</h6>
                    <div id="UtentiOnline"></div>
                  </div>
                  <div class="text-center mt-n3">
                    <p class="fallback fw-bold fs-1 d-none">Nessun risultato.</p>
                  </div>
                </div>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center" id="NavbarUser">
            <script>
              $('#NavbarUser').load('page/navbar-user.php')
            </script>
          </ul>
        </nav>
        <nav class="navbar navbar-light navbar-glass navbar-top navbar-expand-lg" style="display: none;" data-move-target="#navbarVerticalNav" data-navbar-top="combo" id="NavbarCombo">
          <script>
            $('#NavbarCombo').load('page/navbar-combo.html')
          </script>
        </nav>
        <script>
          var navbarPosition = localStorage.getItem("navbarPosition"),
            navbarVertical = document.querySelector(".navbar-vertical"),
            navbarTopVertical = document.querySelector(".content .navbar-top"),
            navbarTop = document.querySelector("[data-layout] .navbar-top"),
            navbarTopCombo = document.querySelector('.content [data-navbar-top="combo"]');
          "top" === navbarPosition ? (navbarTop.removeAttribute("style"), navbarTopVertical.remove(navbarTopVertical), navbarVertical.remove(navbarVertical), navbarTopCombo.remove(navbarTopCombo)) : "combo" === navbarPosition ? (navbarVertical.removeAttribute("style"), navbarTopCombo.removeAttribute("style"), navbarTop.remove(navbarTop), navbarTopVertical.remove(navbarTopVertical)) : (navbarVertical.removeAttribute("style"), navbarTopVertical.removeAttribute("style"), navbarTop.remove(navbarTop), navbarTopCombo.remove(navbarTopCombo));
        </script>
        <div id="messgiorno" class="my-2 my-md-0"></div>
        <div id="contenutopagina1"></div>
        <footer class="footer">
          <div class="row g-0 justify-content-between fs--1 mt-4 mb-3">
            <div class="col-12 col-sm-auto text-center">
              <p class="mb-0 text-600">PixelSmart <span class="d-none d-sm-inline-block">|
                </span><br class="d-sm-none" /> <?= date('Y') ?> &copy;</p>
            </div>
            <div class="col-12 col-sm-auto text-center">
              <p class="mb-0 text-600">v3.0.0 <strong>¯\_(ツ)_/¯</strong></p>
            </div>
          </div>
        </footer>
      </div>
      <div class="modal fade" id="authentication-modal" tabindex="-1" role="dialog" aria-labelledby="authentication-modal-label" aria-hidden="true">
        <div class="modal-dialog mt-6" role="document">
          <div class="modal-content border-0">
            <div class="modal-header px-5 position-relative modal-shape-header bg-shape">
              <div class="position-relative z-index-1 light">
                <h4 class="mb-0 text-white" id="authentication-modal-label">Registrati</h4>
                <p class="fs--1 mb-0 text-white">Crea il tuo account PixelSmart</p>
              </div><button class="btn-close btn-close-white position-absolute top-0 end-0 mt-2 me-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 px-5">
              <form>
                <div class="mb-3"><label class="form-label" for="modal-auth-name">Nome</label><input class="form-control" type="text" autocomplete="on" id="modal-auth-name" /></div>
                <div class="mb-3"><label class="form-label" for="modal-auth-email">Indirizzo mail</label><input class="form-control" type="email" autocomplete="on" id="modal-auth-email" /></div>
                <div class="row gx-2">
                  <div class="mb-3 col-sm-6"><label class="form-label" for="modal-auth-password">Password</label><input class="form-control" type="password" autocomplete="on" id="modal-auth-password" /></div>
                  <div class="mb-3 col-sm-6"><label class="form-label" for="modal-auth-confirm-password">Conferma
                      password</label><input class="form-control" type="password" autocomplete="on" id="modal-auth-confirm-password" /></div>
                </div>
                <div class="form-check"><input class="form-check-input" type="checkbox" id="modal-auth-register-checkbox" /><label class="form-label" for="modal-auth-register-checkbox">Accetta i <a href="#!">termini di servizio </a>e la <a href="#!">privacy policy</a></label></div>
                <div class="mb-3"><button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Prosegui</button></div>
              </form>
              <div class="position-relative mt-5">
                <hr class="bg-300" />
                <div class="divider-content-center">oppure registrati con</div>
              </div>
              <div class="row g-2 mt-2">
                <div class="col-sm-6"><a class="btn btn-outline-google-plus btn-sm d-block w-100" href="#"><span class="fab fa-google-plus-g me-2" data-fa-transform="grow-8"></span> google</a></div>
                <div class="col-sm-6"><a class="btn btn-outline-facebook btn-sm d-block w-100" href="#"><span class="fab fa-facebook-square me-2" data-fa-transform="grow-8"></span> facebook</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- ===============================================-->
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
    <div class="offcanvas-body scrollbar px-card" id="themeController">
      <h5 class="fs-0">Schema colori</h5>
      <p class="fs--1">Seleziona il tuo colore preferito dell'app.</p>
      <div class="btn-group d-block w-100 btn-group-navbar-style">
        <div class="row gx-2">
          <div class="col-6"><input class="btn-check" id="themeSwitcherLight" name="theme-color" type="radio" value="light" data-theme-control="theme" /><label class="btn d-inline-block btn-navbar-style fs--1" for="themeSwitcherLight"> <span class="hover-overlay mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="assets/img/generic/falcon-mode-default.jpg" alt="" /></span><span class="label-text">Chiaro</span></label></div>
          <div class="col-6"><input class="btn-check" id="themeSwitcherDark" name="theme-color" type="radio" value="dark" data-theme-control="theme" /><label class="btn d-inline-block btn-navbar-style fs--1" for="themeSwitcherDark"> <span class="hover-overlay mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="assets/img/generic/falcon-mode-dark.jpg" alt="" /></span><span class="label-text">Scuro</span></label></div>
        </div>
      </div>
      <hr />
      <div class="d-flex justify-content-between">
        <div class="d-flex align-items-start"><img class="me-2" src="assets/img/icons/left-arrow-from-left.svg" width="20" alt="" />
          <div class="flex-1">
            <h5 class="fs-0">Modalità RTL</h5>
            <p class="fs--1 mb-0">Seleziona la direzione del linguaggio </p><a class="fs--1" href="documentation/customization/configuration.html">Documentazione RTL</a>
          </div>
        </div>
        <div class="form-check form-switch"><input class="form-check-input ms-0" id="mode-rtl" type="checkbox" data-theme-control="isRTL" /></div>
      </div>
      <hr />
      <div class="d-flex justify-content-between">
        <div class="d-flex align-items-start"><img class="me-2" src="assets/img/icons/arrows-h.svg" width="20" alt="" />
          <div class="flex-1">
            <h5 class="fs-0">Fluid Layout</h5>
            <p class="fs--1 mb-0">Attiva/disattiva il sistema di layout del contenitore </p><a class="fs--1" href="documentation/customization/configuration.html">Documentazione Fluid</a>
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
      <p> <a class="fs--1" href="modules/components/navs-and-tabs/vertical-navbar.html#navbar-styles">Vedidocumentezione</a></p>
      <div class="btn-group d-block w-100 btn-group-navbar-style">
        <div class="row gx-2">
          <div class="col-6"><input class="btn-check" id="navbar-style-transparent" type="radio" name="navbarStyle" value="transparent" data-theme-control="navbarStyle" /><label class="btn d-block w-100 btn-navbar-style fs--1" for="navbar-style-transparent"> <img class="img-fluid img-prototype" src="assets/img/generic/default.png" alt="" /><span class="label-text">Trasparente</span></label></div>
          <div class="col-6"><input class="btn-check" id="navbar-style-inverted" type="radio" name="navbarStyle" value="inverted" data-theme-control="navbarStyle" /><label class="btn d-block w-100 btn-navbar-style fs--1" for="navbar-style-inverted"> <img class="img-fluid img-prototype" src="assets/img/generic/inverted.png" alt="" /><span class="label-text">Ivertita</span></label></div>
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
                </svg></span></span></span>
        </div>
      </div>
      <small class="text-uppercase text-primary fw-bold bg-soft-primary py-2 pe-2 ps-1 rounded-end">Personalizza</small>
    </div>
  </a>
  <!-- ===============================================-->
  <!--    PANNELLO LATERALE-->
  <!-- ===============================================-->
  <div id="SectCanvas">
    <script>
      $('#SectCanvas').load('assets/offcanvas/tutorial.html')
    </script>
  </div>
  <div id="SectCanvasFrame">
    <script>
      $('#SectCanvasFrame').load('assets/offcanvas/universale.php')

      function CaricaFrame(p, t, d, w) {
        $('#canv-contenuto').empty();
        $('#univ-offcanvas').css('width', w)
        $('#canv-titolo').html(t)
        $('#canv-descrizione').html(d)
        $('#canv-contenuto').load(p)
        $('#univ-offcanvas').offcanvas('show')
        var myOffcanvas = document.getElementById('univ-offcanvas')
        myOffcanvas.addEventListener('hide.bs.offcanvas', function() {
          $('#canv-contenuto').empty();
        })
      }

      function CaricaFrameExt(p, t, d, w) {
        $('#canv-contenuto').empty();
        $('#univ-offcanvas').css('width', w)
        $('#canv-titolo').html(t)
        $('#canv-descrizione').html(d)
        $('#canv-contenuto').html('<iframe height="100%" width="100%" src="' + p + '"></iframe>')
        $('#univ-offcanvas').offcanvas('show')
      }
    </script>
  </div>
  <!-- ===============================================-->
  <!--    MAIL MODAL SYSTEM  [MMS]-->
  <!-- ===============================================-->
  <div class="modal fade" id="mms-mailmodal" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="mms-mailmodallbl" aria-hidden="true">
    <div class="modal-dialog modal-lg mt-6" role="document">
      <div class="modal-content border-0">
        <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1"><button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <div class="modal-body p-0">
          <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
            <h4 class="mb-1" id="staticBackdropLabel">Invia mail</h4>
          </div>
          <div class="p-4">
            <div class="row">
              <div class="col-lg-9">
                <div class="d-flex"><span class="fa-stack ms-n1 me-3"><i class="fas fa-circle fa-stack-2x text-200"></i><i class="fa-inverse fa-stack-1x text-primary fas fa-tag" data-fa-transform="shrink-2"></i></span>
                  <div class="flex-1">
                    <h5 class="mb-2 fs-0">Mittente</h5>
                    <div class="d-flex">
                      <input list="mms-mail_mitt-list" id="mms-mail_mitt" type="text" class="form-control form-control-sm" autocomplete=" off">
                      <datalist id="mms-mail_mitt-list">
                        <option value="noreply">
                          <?php if ($session_user == 'pasquale') {
                            echo '<option value="amministrazione">';
                          } else if ($session_user == 'calogero') {
                            echo '<option value="assistenza"><option value="ricambi">';
                          } else if ($session_user == 'salvatoreg') {
                            echo '<option value="info"><option value="spedizioni">';
                          } else if ($session_user == 'gabriele') {
                            echo '<option value="eprice">';
                          } ?>
                        <option value="WhatsApp">
                    </div>
                    <h5 class="mb-2 mt-2 fs-0">Destinatario</h5>
                    <div class="d-flex">
                      <input id="mms-mail_dest" type="text" class="form-control form-control-sm" autocomplete="off">
                    </div>
                    <hr class="my-4" />
                  </div>
                </div>
                <div class="d-flex"><span class="fa-stack ms-n1 me-3"><i class="fas fa-circle fa-stack-2x text-200"></i><i class="fa-inverse fa-stack-1x text-primary fas fa-align-left" data-fa-transform="shrink-2"></i></span>
                  <div class="flex-1">
                    <h5 class="mb-2 fs-0">Oggetto</h5>
                    <div class="d-flex">
                      <input id="mms-mail_ogg" type="text" class="form-control form-control-sm" autocomplete="off">
                    </div>
                    <h5 class="mb-2 mt-2 fs-0">Messaggio</h5>
                    <div class="min-vh-50">
                      <textarea id="tmce-MMS" class="tinymce d-none" name="content"></textarea>
                      <input id="mms-mail_mess-supp" type="text" hidden>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <h6 class="mt-5 mt-lg-0">Azioni</h6>
                <ul class="nav flex-lg-column fs--1">
                  <li class="nav-item me-2 me-lg-0"><a class="nav-link nav-link-card-details mms-mess_rapidi" href="javascript:void(0)"><span class="fa-solid fa-bolt me-2"></span>Messaggi rapidi</a></li>
                  <li class="nav-item me-2 me-lg-0"><label for="mms-attachmentButton" type="button" class="nav-link nav-link-card-details fw-normal" aria-describedby="upload-tooltip">
                      <span><span aria-hidden="true" class="fa-solid fa-paperclip"></span></span>
                      <input type="file" id="mms-attachmentButton" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" style="width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; position: absolute; z-index: -1;">
                      <span>Allega un file</span>
                    </label></li>
                  <li class="nav-item me-2 me-lg-0"><a class="nav-link nav-link-card-details mms-mail_send" href="javascript:void(0)"><span class="fa-solid fa-paper-plane-top me-2"></span>Invia</a></li>
                  <li class="nav-item me-2 me-lg-0"><a class="nav-link nav-link-card-details" href="javascript:void(0)" data-bs-dismiss="modal" aria-label="Close"><span class="fa-solid fa-xmark me-2"></span>Chiudi </a></li>
                </ul>
                <div class="mt-2" id="Impostazioni_mail" hidden>
                  <h6 class="mt-lg-0">Impostazioni</h6>
                  <div class="form-check form-switch">
                    <input class="form-check-input" id="mms_mail_cliente" type="checkbox" />
                    <label class="form-check-label" for="mms_mail_cliente">Notifica cliente</label>
                  </div>
                  <div hidden>
                    <input id="mms-idoperatore" type="text" value="<?= $session_idu; ?>">
                    <input id="mms-idordine" type="text" value="">
                  </div>
                </div>
                <div class="mt-2" id="MessaggiRapidi_mail" hidden>
                  <h6 class="mt-lg-0">Messaggi rapidi</h6>
                  <select class="form-control selectpicker" data-style="btn btn-link" id="mms-risp_rap1">
                    <option value="" disabled="" selected="">Seleziona la tua opzione</option>
                    <option disabled="">----- Feedback -----</option>
                    <option value="rf">Testo feedback</option>
                    <option value="rfwa">Testo feedback WA</option>
                    <option disabled="">----- Pagamenti -----</option>
                    <option value="bfpi">Bonificio Poste Italiane</option>
                    <option value="bfuc">Bonifico UniCredit</option>
                    <option value="bfbn">Bonifico BNL</option>
                    <option value="bfcr">Bonifico Credem</option>
                    <option value="bfrp">Bonifico Ricarica PostePay</option>
                    <option value="cu">Codice Univoco</option>
                    <option disabled="">----- Rimborso -----</option>
                    <option value="rb">Richiesta di rimborso</option>
                    <option value="librimb">Liberatoria rimborso</option>
                    <option disabled="">----- Vari -----</option>
                    <option value="vr">Verrai ricontattato</option>
                    <option value="sped">Richiesta di spedizione</option>
                    <option value="ddsped">Dati di spedizione</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="mmsr-mailmodal" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="mmsr-mailmodallbl" aria-hidden="true">
    <div class="modal-dialog modal-lg mt-6" role="document">
      <div class="modal-content border-0">
        <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1"><button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <div class="modal-body p-0">
          <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
            <h4 class="mb-1" id="staticBackdropLabel">Leggi mail</h4>
          </div>
          <div class="p-4">
            <div class="row">
              <div class="col-lg-9">
                <div class="d-flex"><span class="fa-stack ms-n1 me-3"><i class="fas fa-circle fa-stack-2x text-200"></i><i class="fa-inverse fa-stack-1x text-primary fas fa-tag" data-fa-transform="shrink-2"></i></span>
                  <div class="flex-1">
                    <h5 class="mb-2 fs-0">Mittente</h5>
                    <div class="d-flex">
                      <input id="mmsr-mail_mitt" type="text" class="form-control form-control-sm" autocomplete=" off" readonly>
                    </div>
                    <hr class="my-4" />
                  </div>
                </div>
                <div class="d-flex"><span class="fa-stack ms-n1 me-3"><i class="fas fa-circle fa-stack-2x text-200"></i><i class="fa-inverse fa-stack-1x text-primary fas fa-align-left" data-fa-transform="shrink-2"></i></span>
                  <div class="flex-1">
                    <h5 class="mb-2 fs-0">Oggetto</h5>
                    <div class="d-flex">
                      <input id="mmsr-mail_ogg" type="text" class="form-control form-control-sm" autocomplete="off" readonly>
                    </div>
                    <h5 class="mb-2 mt-2 fs-0">Messaggio</h5>
                    <div class="min-vh-50">
                      <textarea id="tmce-MMSR" class="tinymce d-none" name="content"></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <h6 class="mt-5 mt-lg-0">Azioni</h6>
                <ul class="nav flex-lg-column fs--1">
                  <li class="nav-item me-2 me-lg-0"><a class="nav-link nav-link-card-details mmsr-mail_reply" href="javascript:void(0)"><span class="fa-solid fa-paper-plane-top me-2"></span>Rispondi</a></li>
                  <li class="nav-item me-2 me-lg-0"><a class="nav-link nav-link-card-details" href="javascript:void(0)" data-bs-dismiss="modal" aria-label="Close"><span class="fa-solid fa-xmark me-2"></span>Chiudi </a></li>
                </ul>
                <div hidden>
                  <input id="mmsr-idmail" type="text" value="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="business-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
      <div class="modal-content position-relative">
        <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
          <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
            <h4 class="mb-1" id="modalExampleDemoLabel">Dati aziendali </h4>
          </div>
          <div class="h-100 p-4 scrollbar" style="overflow-y: scroll;">
            <div class="border border-1 border-300 rounded-2 p-3 position-relative mb-3">
              <div class="d-flex align-items-center mb-3"><i class="fa-regular fa-briefcase"></i>
                <h5 class="fs--1 text-600 mb-0 ps-3 user-select-none">Intestazione</h5>
              </div>
              <div class="fs--1 text-800">GFA SRL<br />CONTRADA DECANO SNC SS122<br />CALTANISSETTA (CL) 93100<br /><b>P. IVA</b>: IT02095720856</div>
            </div>
            <div class="border border-1 border-300 rounded-2 p-3 position-relative mb-3">
              <div class="d-flex align-items-center mb-3"><i class="fa-regular fa-file-invoice"></i>
                <h5 class="fs--1 text-600 mb-0 ps-3 user-select-none">Fatturazione</h5>
              </div>
              <h5 class="fs--1 text-800"><b>PEC</b>: gfa-srl@arubapec.it<br /><b>COD. UNIVOCO</b>: SKUA8Y6</h5>
            </div>
            <div class="border border-1 border-300 rounded-2 p-3 position-relative mb-3">
              <div class="d-flex align-items-center mb-3"><i class="fa-regular fa-address-book"></i>
                <h5 class="fs--1 text-600 mb-0 ps-3 user-select-none">Contatti</h5>
              </div>
              <h5 class="fs--1 text-800">
                <b>Pasquale</b>: amministrazione@scifostore.com<br />
                <b>Salvatore</b>: info@scifostore.com<br />
                <b>Calogero</b>: assistenza@scifostore.com<br />
                <b>Spedizione</b>: spedizione@scifostore.com<br />
                <b>Ricambi</b>: ricambi@scifostore.com<br />
                <b>Fisso</b>: 0934 ******<br />
                <b>CELLULARE</b>: 350 568 8846
              </h5>
            </div>
            <div class="border border-1 border-300 rounded-2 p-3 position-relative mb-3">
              <div class="d-flex align-items-center mb-3"><i class="fa-regular fa-money-check-dollar-pen"></i>
                <h5 class="fs--1 text-600 mb-0 ps-3 user-select-none">Banca</h5>
              </div>
              <h5 class="fs--1 text-800">
                <b>UNICREDIT</b>: IT 44 J 02008 16701 000106331644<br />
                <b>CREDEM</b>: IT 64 N 03032 16700 010000719898<br />
                <b>SAN PAOLO</b>: IT 42 J 03069 16702 100000014906
              </h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="tms-modalsys" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="tms-modalsyslbl" aria-hidden="true"></div>
  <div class="modal fade" id="prch-mailmodal" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="prch-modallbl" aria-hidden="true"></div>
  <!-- ===============================================-->
  <!--    JavaScripts-->
  <!-- ===============================================-->
  <script src="vendors/popper/popper.min.js"></script>
  <script src="vendors/bootstrap/bootstrap.min.js"></script>
  <script src="vendors/anchorjs/anchor.min.js"></script>
  <script src="vendors/is/is.min.js"></script>
  <script src="vendors/echarts/echarts.min.js"></script>
  <script src="vendors/fontawesome/all.min.js"></script>
  <script src="vendors/lodash/lodash.min.js"></script>
  <script src="vendors/flatpickr/flatpickr.js"></script>
  <script src="vendors/countup/countUp.umd.js"></script>
  <script src="vendors/tinymce/tinymce.min.js"></script>
  <script src="vendors/choices/choices.min.js"></script>
  <script src="vendors/quagga/quagga.min.js"></script>
  <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
  <script src="vendors/list.js/list.min.js"></script>
  <script src="app/js/blockui.min.js"></script>
  <script src="app/js/autocomplete.min.js"></script>
  <script src="app/js/elevatezoom.min.js"></script>
  <script src="app/js/sweetalert2.all.min.js"></script>
  <script src="app/js/parent.js"></script>
  <script src="app/js/jquery.dataTables.min.js"></script>
  <script src="app/js/dataTables.bootstrap4.min.js"></script>
  <script src="app/bpz/BrowserPrint-3.0.216.min.js"></script>
  <script src="app/js/theme.js"></script>
  <?php include("page/footer.php"); ?>
</body>
<script>
  $.getMultiScripts = function(e, s) {
    var t = $.map(e, function(e) {
      return $.getScript((s || "") + e)
    });
    return t.push($.Deferred(function(e) {
      $(e.resolve)
    })), $.when.apply($, t)
  };
  var script_arr = ["Vendita.js", "CaricaPagamento.js", "Catalogo.js", "Dipendente.js", "SchermataOrdine.js", "SchedaProdotto.js", "Documenti.js", "Fatture.js", "feedback.js", "HomeControl.js", "impostazioni.js", "listaclienti.js", "listaordini.js", "nuovordine.js", "ordine.js", "ordinefornitore.js", "Pratiche.js", "preventivi.js", "quagga.min.js", "riparazione.js", "scadenze.js", "statistiche-ordine.js", "ticket.js", "usato.js", "MailModalSystem.js", "Segnalazione.js", "SchedaFornitore.js", "SchedaCliente.js"];
  $.getMultiScripts(script_arr, currentURL + "assets/js/").done(function() {
    console.log("PXS: Script caricati")
  });
</script>

</html>