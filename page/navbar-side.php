<div class="navbar-vertical-content scrollbar">
    <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
        <?php
        session_start();

        echo ($_SESSION['session_perm'][4] == '0;0' ?
            '<li class="nav-item">
            <a class="nav-link" href="javascript:void(0)" role="button" aria-expanded="false"
                onclick="cambiopagina(\'\', \'home\',\'\')">
                <div class="d-flex align-items-center"><span class="nav-link-icon"><i
                            class="fa-regular fa-house"></i></span><span class="nav-link-text ps-1">Panoramica</span>
                </div>
            </a>
        </li>' : '');

        // <!-- ========== ONLINE ========= -->

        echo ($_SESSION['session_perm'][0][0] == '+' ?
            '<li class="nav-item">
            <!-- label-->
            <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                <div class="col-auto navbar-vertical-label">Online</div>
                <div class="col ps-0">
                    <hr class="mb-0 navbar-vertical-divider" />
                </div>
            </div>

            ' . (($_SESSION['session_perm'][0][1] == 1 || $_SESSION['session_perm'][0][2] == 1 || $_SESSION['session_perm'][0][3] == 1) ?
                '<li class="nav-item"><a class="nav-link dropdown-indicator" href="#spedizioni" data-bs-toggle="collapse" aria-expanded="false" aria-controls="e-commerce">
                <div class="d-flex align-items-center"><i class="fa-regular fa-globe"></i><span class="nav-link-text ps-1">Spedizioni</span></div>
            </a>
            <ul class="nav collapse false" id="spedizioni">
            ' . ($_SESSION['session_perm'][0][1] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" aria-expanded="false" onclick="cambiopagina(\'ordini\', \'lista\',\'\')">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Lista ordini</span>
                        </div>
                    </a>
                </li>' : '')
                . ($_SESSION['session_perm'][0][3] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'ordini\', \'sinistri\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Sinistri</span></div>
                    </a>
                </li>' : '') .
                '</ul>
        </li>' : '')
            . ($_SESSION['session_perm'][0][4] == 1 ?
                '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'crm\', \'feedback\',\'\')" aria-expanded="false">
                <div class="d-flex align-items-center"><i class="fa-regular fa-star"></i><span class="nav-link-text ps-1">Feedback</span></div>
            </a>
        </li>' : '')
            . ($_SESSION['session_perm'][0][5] == 1 ?
                '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'crm\', \'segnalazioni\',\'\')" aria-expanded="false">
                <div class="d-flex align-items-center"><i class="fa-regular fa-user-clock"></i><span class="nav-link-text ps-1">Segnalazioni</span></div>
            </a>
        </li>' : '') : '');

        ?>

        <?php
        // <!-- ========== PUNTI VENDITA ========= -->
        echo ($_SESSION['session_perm'][1][0] == '+' ?
            '<li class="nav-item">
            <!-- label-->
            <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                <div class="col-auto navbar-vertical-label">Punti vendita</div>
                <div class="col ps-0">
                    <hr class="mb-0 navbar-vertical-divider" />
                </div>
            </div>'
            . ($_SESSION['session_perm'][1][1] == 1 || $_SESSION['session_perm'][1][2] == 1 || $_SESSION['session_perm'][1][3] == 1 || $_SESSION['session_perm'][1][4] == 1 ?

                '<li class="nav-item"><a class="nav-link dropdown-indicator" href="#officina" data-bs-toggle="collapse" aria-expanded="false" aria-controls="punti-vendita">
                <div class="d-flex align-items-center"><i class="fa-regular fa-screwdriver-wrench"></i><span class="nav-link-text ps-1">Officina</span></div>
            </a>
            <ul class="nav collapse false" id="officina">'
                . ($_SESSION['session_perm'][1][3] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'officina\', \'usato\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1 text-danger">Usato</span></div>
                    </a>
                </li>' : '')
                . ($_SESSION['session_perm'][1][2] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'officina\', \'spaccati\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1 ">Spaccati</span></div>
                    </a>
                </li>' : '')
                . ($_SESSION['session_perm'][1][1] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'officina\', \'preventivi\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1 text-warning">Preventivi</span>
                        </div>
                    </a>
                </li>' : '')
                . ($_SESSION['session_perm'][1][4] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'officina\', \'riparazioni\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1 text-danger">Lista schede</span>
                        </div>
                    </a>
                </li>' : '')
                . ($_SESSION['session_perm'][1][11] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'officina\', \'listagaranzie\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Lista garanzie</span>
                        </div>
                    </a>
                </li>' : '')
                . '</ul>
            </li>' : '')

            .
            (($_SESSION['session_perm'][1][5] == 1 || $_SESSION['session_perm'][1][6] == 1 || $_SESSION['session_perm'][1][7] == 1 || $_SESSION['session_perm'][1][8] == 1) ?
                '<li class="nav-item"><a class="nav-link dropdown-indicator" href="#negozio" data-bs-toggle="collapse" aria-expanded="false" aria-controls="punti-vendita">
                <div class="d-flex align-items-center"><i class="fa-regular fa-basket-shopping"></i><span class="nav-link-text ps-1">Negozio</span></div>
            </a>
            <ul class="nav collapse false" id="negozio">'
                . ($_SESSION['session_perm'][1][5] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'magazzino\', \'vendita\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Vendita</span>
                        </div>
                    </a>
                </li>' : '')
                . ($_SESSION['session_perm'][1][6] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'magazzino\', \'catalogo\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Catalogo</span></div>
                    </a>
                </li>' : '')
                . ($_SESSION['session_perm'][1][8] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'magazzino\', \'report\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Lista vendite</span>
                        </div>
                    </a>
                </li>' : '')
                . ($_SESSION['session_perm'][1][7] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'magazzino\', \'ordinefornitore\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Ordine fornitore</span>
                        </div>
                    </a>
                </li>' : '')
                . '</ul>
        </li>' : '')
            . (($_SESSION['session_perm'][1][9] == 1 || $_SESSION['session_perm'][1][10] == 1) ?
                ' <li class="nav-item"><a class="nav-link dropdown-indicator" href="#clienti" data-bs-toggle="collapse" aria-expanded="false" aria-controls="punti-vendita">
                <div class="d-flex align-items-center"><i class="fa-regular fa-user-tag"></i><span class="nav-link-text ps-1">Clienti</span></div>
            </a>
            <ul class="nav collapse false" id="clienti">'
                .  ($_SESSION['session_perm'][1][9] == 1 ?
                    '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'negozio\', \'documenti\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Documenti</span>
                        </div>
                    </a>
                </li>' : '')
                . ($_SESSION['session_perm'][1][10] == 1 ?
                    ' <li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'negozio\', \'clienti\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Clienti</span></div>
                    </a>
                </li>' : '')
                . '</ul>
        </li>' : '')
            . '</li>' : '');
        ?>

        <?php
        // <!-- ========== AMMINISTRAZIONE ========= -->
        echo ($_SESSION['session_perm'][2][0] == '+' ?
            '<li class="nav-item">
            <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                <div class="col-auto navbar-vertical-label">Amministrazione</div>
                <div class="col ps-0">
                    <hr class="mb-0 navbar-vertical-divider" />
                </div>
            </div>'

            . (($_SESSION['session_perm'][2][1] == 1 || $_SESSION['session_perm'][2][2] == 1 || $_SESSION['session_perm'][2][3] == 1) ? '<li class="nav-item"><a class="nav-link dropdown-indicator" href="#contabilita" data-bs-toggle="collapse" aria-expanded="false" aria-controls="amministrazione">
                <div class="d-flex align-items-center"><i class="fa-regular fa-calculator"></i><span class="nav-link-text ps-1">Contabilità</span></div>
            </a>
            <ul class="nav collapse false" id="contabilita">'
                . ($_SESSION['session_perm'][2][1] == 1 ? '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'contabilita\', \'scadenze\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Scadenze</span>
                        </div>
                    </a>
                </li>' : '')
                .  ($_SESSION['session_perm'][2][2] == 1 ? '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'contabilita\', \'fatture\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Fatture</span></div>
                    </a>
                </li>' : '')
                . ($_SESSION['session_perm'][2][3] == 1 ? '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'contabilita\', \'fornitori\',\'\')" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Fornitori</span></div>
                    </a>
                </li>' : '')
                . '</ul>
             </li>' : '')

            . ($_SESSION['session_perm'][2][4] == 1 ? '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'amministrazione\', \'listapratiche\',\'\')" aria-expanded="false">
                <div class="d-flex align-items-center"><i class="fa-solid fa-scale-balanced"></i><span class="nav-link-text ps-1">Pratiche legali</span></div></a>
               </li>' : '')

            . '</li>'


            . ($_SESSION['session_perm'][2][5] == 1 ? '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'amministrazione\', \'dipendenti\',\'\')" aria-expanded="false">
            <div class="d-flex align-items-center"><i class="fa-solid fa-briefcase"></i><span class="nav-link-text ps-1">Dipendenti</span></div></a>
           </li>' : '') : '');
        ?>
        <!-- ========== ALTRO ========= -->
        <?php
        echo '<li class="nav-item">
            <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                <div class="col-auto navbar-vertical-label">Funzionalità</div>
                <div class="col ps-0">
                    <hr class="mb-0 navbar-vertical-divider" />
                </div>
            </div>'
            . ($_SESSION['session_perm'][3][0] == '+' && $_SESSION['session_perm'][3][1] == 1 ? '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'funzionalita\', \'utenti\',\'\')" aria-expanded="false">
                <div class="d-flex align-items-center"><i class="fa-regular fa-user-pen"></i><span class="nav-link-text ps-1">Lista utenti</span></div>
            </a>
        </li>' : '')
            . '<li class="nav-item"><a class="nav-link" href="javascript:void(0)" onclick="cambiopagina(\'funzionalita\', \'mail\',\'\')" aria-expanded="false">
                <div class="d-flex align-items-center"><i class="fa-regular fa-envelope-dot"></i><span class="nav-link-text ps-1">Casella di posta</span></div>
            </a>
        </li>'
            . '</li>';
        ?>
    </ul>
    <div class="settings mb-3">
        <div class="card alert p-0 shadow-none" role="alert">
            <div class="btn-close-falcon-container">
                <div class="btn-close-falcon" aria-label="Close" data-bs-dismiss="alert"></div>
            </div>
            <div class="card-body text-center"><img src="../assets/img/icons/spot-illustrations/navbar-vertical.png" alt="" width="80">
                <p class="fs--2 mt-2" id="MessaggioBarraLaterale">Caricamento messaggio ...</p>
            </div>
        </div>
    </div>
</div>