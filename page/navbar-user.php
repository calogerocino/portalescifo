<?php session_start(); ?>
<!-- <li class="nav-item">
    <div class="theme-control-toggle fa-icon-wait px-2"><i class="fa-solid fa-question"></i></div>
</li> -->

<li class="nav-item">
    <div class="theme-control-toggle fa-icon-wait px-2"><input class="form-check-input ms-0 theme-control-toggle-input" id="themeControlToggle" type="checkbox" data-theme-control="theme" value="dark" /><label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to light theme"><span class="fas fa-sun fs-0"></span></label><label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to dark theme"><span class="fas fa-moon fs-0"></span></label></div>
</li>
<li class="nav-item">
    <a class="nav-link px-0" href="#tutorial-offcanvas" data-bs-toggle="offcanvas"><i class="fa-regular fa-graduation-cap text-success" data-fa-transform="shrink-7" style="font-size: 1.5rem;"></i></a>
</li>
<?= (false ? '<li class="nav-item">
    <a class="nav-link px-0 notification-indicator notification-indicator-warning notification-indicator-fill fa-icon-wait"
        href="#"><span class="fas fa-shopping-cart" data-fa-transform="shrink-7" style="font-size: 33px;"></span><span
            class="notification-indicator-number">1</span></a>
</li>' : ''); ?>
<li class="nav-item dropdown">
    <a class="nav-link notification-indicator notification-indicator-primary px-0 fa-icon-wait" id="navbarDropdownNotification" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fas fa-bell" data-fa-transform="shrink-6" style="font-size: 33px;"></span></a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-card dropdown-menu-notification" aria-labelledby="navbarDropdownNotification">
        <div class="card card-notification shadow-none">
            <div class="card-header">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                        <h6 class="card-header-title mb-0">Centro notifiche</h6>
                    </div>
                    <?= (false ? '<div class="col-auto ps-0 ps-sm-3"><a class="card-link fw-normal" href="#">Segna come letto</a>' : ''); ?>
                </div>
            </div>
        </div>
        <div class="scrollbar-overlay" style="max-height:19rem">
            <div class="list-group list-group-flush fw-normal fs--1">
                <div class="list-group-title border-bottom" id="CentroMailSupport">Mail non lette</div>
                <div class="list-group-title border-bottom">Nessuna altra nuova notifica</div>
                <?= (false ? '<div class="list-group-item">
                    <a class="notification notification-flush" href="#!">
                        <div class="notification-avatar">
                            <div class="avatar avatar-2xl me-3">
                                <img class="rounded-circle" src="assets/img/icons/weather-sm.jpg" alt="" />
                            </div>
                        </div>
                        <div class="notification-body">
                            <p class="mb-1">INSERISCI TEMPERATURA</p>
                            <span class="notification-time"><span class="me-2" role="img" aria-label="Emoji">🌤️</span>ORA</span>
                        </div>
                    </a>
                </div>' : ''); ?>
            </div>
        </div>
        <div class="card-footer text-center border-top"><a class="card-link d-block" href="javascript:void(0)" onclick="cambiopagina('funzionalita', 'mail','')">Visualizza tutto</a>
        </div>
    </div>
    </div>
</li>
<li class="nav-item dropdown"><a class="nav-link pe-0" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div class="avatar avatar-xl">
            <img class="rounded-circle" src="assets/img/team/<?= $_SESSION['session_user'] ?>.svg" alt="" />
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="navbarDropdownUser">
        <div class="bg-white dark__bg-1000 rounded-2 py-2">
            <a class="dropdown-item" href="javascript:void(0)" onclick="cambiopagina('utente/page', 'profilo','')">Profilo</a>
            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#business-modal">Azienda</a>
            <a class="dropdown-item" href="javascript:void(0)" onclick="cambiopagina('utente/page', 'attivita','')">Attività</a>
            <div class="dropdown-divider"></div>
            <?= ($_SESSION['session_perm'][3][3] == 1 ? '<a class="dropdown-item text-warning" onclick="Debug()" id="CheckDebug" debug="0" href="javascript:void(0)"></span></a>' : ''); ?>
            <?= ($_SESSION['session_perm'][3][3] == 1 ? '<a class="dropdown-item" href="https://linp133.arubabusiness.it:8443/phpMyAdmin/index.php?db=oxy80n60_itman" target="_blank">Database</a>' : ''); ?>
            <a class="dropdown-item" href="javascript:void(0)" onclick="cambiopagina('app', 'impostazioni','')">Impostazioni</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="utente/auth/logout.php">Logout</a>
        </div>
    </div>
</li>