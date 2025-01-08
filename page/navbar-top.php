<button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarStandard" aria-controls="navbarStandard" aria-expanded="false" aria-label="Attiva/Disattiva navigazione"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
<a class="navbar-brand me-1 me-sm-3" href="javascript:void(0)" onlick="cambiopagina('','home','')">
    <div class="d-flex align-items-center">
        <h3 class="text-primary fw-bold mb-0">Pixel<span class="text-info fw-bold">Smart</span></h3>
    </div>
</a>

<div class="collapse navbar-collapse scrollbar" id="navbarStandard">
    <ul class="navbar-nav" data-top-nav-dropdowns="data-top-nav-dropdowns">
        <?php
        session_start();

        // <!-- ========== ONLINE ========= -->

        echo ($_SESSION['session_perm'][0][0] == '+' ?
            '<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="online">Online</a>
            <div class="dropdown-menu dropdown-menu-card border-0 mt-0" aria-labelledby="online">
                
            <div class="card navbar-card-app shadow-none dark__bg-1000">
                    <div class="card-body scrollbar max-h-dropdown"><img class="img-dropdown"
                            src="assets/img/icons/spot-illustrations/authentication-corner.png" width="130" alt="" />
                        <div class="row">'
            . ($_SESSION['session_perm'][0][1] == 1 || $_SESSION['session_perm'][0][2] == 1 || $_SESSION['session_perm'][0][3] == 1 ? '<div class="col-6 col-md-5">
                                <div class="nav flex-column">
                                    <p class="nav-link text-700 mb-0 fw-bold">Spedizioni</p>'
                . ($_SESSION['session_perm'][0][1] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'ordini\', \'lista\',\'\')">Lista ordini</a>' : '')
                . ($_SESSION['session_perm'][0][3] == 1 ? ' <a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'ordini\', \'sinistri\',\'\')">Sinistri</a>' : '')
                . '</div>
                            </div>' : '') .
            '<div class="col-6 col-md-4">
                                <div class="nav flex-column">'
            . ($_SESSION['session_perm'][0][4] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'crm\', \'feedback\',\'\')">Feedback</a>' : '')
            . ($_SESSION['session_perm'][0][5] == 1 ? ' <a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'crm\', \'index\',\'\')">Segnalazioni</a>' : '')
            . '</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>' : '');

        // <!-- ========== PUNTI VENDITA ========= -->
        echo ($_SESSION['session_perm'][1][0] == '+' ?
            '<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="puntovendita">Punto vendita</a>
            <div class="dropdown-menu dropdown-menu-card border-0 mt-0" aria-labelledby="puntovendita">
                <div class="card navbar-card-app shadow-none dark__bg-1000">
                    <div class="card-body scrollbar max-h-dropdown">
                        <img class="img-dropdown" src="assets/img/icons/spot-illustrations/authentication-corner.png" width="130" alt="" />
                        <div class="row">
                            <div class="col-4 col-md-4">
                                <div class="nav flex-column">'
            . ($_SESSION['session_perm'][1][1] == 1 || $_SESSION['session_perm'][1][2] == 1 || $_SESSION['session_perm'][1][3] == 1 || $_SESSION['session_perm'][1][4] == 1 ?
                '<p class="nav-link text-700 mb-0 fw-bold">Officina</p>'
                . ($_SESSION['session_perm'][1][3] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'officina\', \'usato\',\'\')">Usato</a>' : '')
                . ($_SESSION['session_perm'][1][2] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'officina\', \'spaccati\',\'\')">Spaccati</a>' : '')
                . ($_SESSION['session_perm'][1][1] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'officina\', \'preventivi\',\'\')">Preventivi</a>' : '')
                . ($_SESSION['session_perm'][1][4] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'officina\', \'riparazioni\',\'\')">Lista schede</a>' : '')
                . ($_SESSION['session_perm'][1][11] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'officina\', \'listagaranzie\',\'\')">Lista garanzie</a>' : '') : '')

            . '</div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="nav flex-column">'
            . (($_SESSION['session_perm'][1][5] == 1 || $_SESSION['session_perm'][1][6] == 1 || $_SESSION['session_perm'][1][7] == 1 || $_SESSION['session_perm'][1][8] == 1) ?
                '<p class="nav-link text-700 mb-0 fw-bold">Negozio</p>'
                . ($_SESSION['session_perm'][1][5] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'magazzino\', \'vendita\',\'\')">Vendita</a>' : '')
                . ($_SESSION['session_perm'][1][6] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'magazzino\', \'catalogo\',\'\')">Catalogo</a>' : '')
                . ($_SESSION['session_perm'][1][8] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'magazzino\', \'report\',\'\')">Lista vendite</a>' : '')
                . ($_SESSION['session_perm'][1][7] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'magazzino\', \'ordinefornitore\',\'\')">Ordine fornitore</a>' : '') : '')
            . '</div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="nav flex-column">'
            . (($_SESSION['session_perm'][1][9] == 1 || $_SESSION['session_perm'][1][10] == 1) ?
                '<p class="nav-link text-700 mb-0 fw-bold">Clienti</p>'
                .  ($_SESSION['session_perm'][1][9] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'negozio\', \'documenti\',\'\')">Documenti</a>' : '')
                . ($_SESSION['session_perm'][1][10] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'negozio\', \'clienti\',\'\')">Clienti</a>' : '') : '')
            . '</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>' : '');

        // <!-- ========== AMMINISTRAZIONE ========= -->
        echo ($_SESSION['session_perm'][2][0] == '+' ?
            '<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="amministrazione">Amministrazione</a>
            <div class="dropdown-menu dropdown-menu-card border-0 mt-0" aria-labelledby="amministrazione">
                <div class="card navbar-card-app shadow-none dark__bg-1000">
                    <div class="card-body scrollbar max-h-dropdown">
                        <img class="img-dropdown" src="assets/img/icons/spot-illustrations/authentication-corner.png" width="130" alt="" />
                        <div class="row">
                            <div class="col-6 col-md-5">
                                <div class="nav flex-column">'
            . ($_SESSION['session_perm'][2][1] == 1 || $_SESSION['session_perm'][2][2] == 1 || $_SESSION['session_perm'][2][3] == 1 ? '<p class="nav-link text-700 mb-0 fw-bold">Contabilità</p>'
                . ($_SESSION['session_perm'][2][1] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'contabilita\', \'scadenze\',\'\')">Scadenze</a>' : '')
                .  ($_SESSION['session_perm'][2][2] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'contabilita\', \'fatture\',\'\')">Fatture</a>' : '')
                . ($_SESSION['session_perm'][2][3] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'contabilita\', \'fornitori\',\'\')">Fornitori</a>' : '') : '')
            . '</div>
                            </div>'
            . ($_SESSION['session_perm'][2][4] == 1 ? '<div class="col-6 col-md-4">
                                <div class="nav flex-column">
                                    <a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'amministrazione\', \'listapratiche\',\'\')">Pratiche legali</a>
                                </div>
                            </div>' : '')
            . '</div>
                    </div>
                </div>
            </div>
        </li>' : '');

        // <!-- ========== ALTRO ========= -->
        echo '<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="funzionalita">Funzionalità</a>
            <div class="dropdown-menu dropdown-menu-card border-0 mt-0" aria-labelledby="funzionalita">
                <div class="card navbar-card-app shadow-none dark__bg-1000">
                    <div class="card-body scrollbar max-h-dropdown">
                        <img class="img-dropdown" src="assets/img/icons/spot-illustrations/authentication-corner.png" width="130" alt="" />
                        <div class="row">
                            <div class="col-4 col-md-4">
                                <div class="nav flex-column">'
            . ($_SESSION['session_perm'][3][0] == '+' && $_SESSION['session_perm'][3][1] == 1 ? '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'funzionalita\', \'utenti\',\'\')">Lista utenti</a>' : '')
            . '<a class="nav-link py-1 link-600 fw-medium" href="javascript:void(0)" onclick="cambiopagina(\'funzionalita\', \'mail\',\'\')">Casella di posta</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>'
        ?>
    </ul>
</div>