<?php
// Disattiva la visualizzazione degli errori a schermo per non rompere la risposta AJAX "ok"
error_reporting(0); 
ini_set('display_errors', 0);

require_once('../assets/inc/database.php');

// LOGICA DI SALVATAGGIO
if (isset($_POST['azione']) && $_POST['azione'] == 'salva_permessi') {
    $idu = $_POST['idu'];
    
    // CORREZIONE ERRORE SCREENSHOT: 
    // Controlliamo prima se esiste la chiave 'perm' in generale, e poi se esiste per questo utente.
    $p = [];
    if (isset($_POST['perm']) && is_array($_POST['perm']) && isset($_POST['perm'][$idu])) {
        $p = $_POST['perm'][$idu];
    }

    // Funzione helper per costruire la stringa "val1,val2,val3"
    function buildStr($arr) {
        return isset($arr) ? implode(',', $arr) : '0';
    }

    // Ricostruzione stringhe (utilizzando l'array $p sicuro)
    
    // 1. ONLINE (s_Online)
    $p_online_1 = buildStr([
        !empty($p['ordini']) ? 1 : 0,
        !empty($p['manomano']) ? 1 : 0,
        !empty($p['sinistri']) ? 1 : 0
    ]);
    $p_online_2 = !empty($p['feedback']) ? 1 : 0;
    $p_online_3 = !empty($p['segnalazioni']) ? 1 : 0;
    $s_Online = "0;" . $p_online_1 . ";" . $p_online_2 . ";" . $p_online_3;

    // 2. VENDITA (s_PVend)
    $p_vend_1 = buildStr([
        !empty($p['preventivi']) ? 1 : 0,
        !empty($p['spaccati']) ? 1 : 0,
        !empty($p['usato']) ? 1 : 0,
        !empty($p['listaschede']) ? 1 : 0
    ]);
    $p_vend_2 = buildStr([
        !empty($p['vendita']) ? 1 : 0,
        !empty($p['catalogo']) ? 1 : 0,
        !empty($p['ordineprod']) ? 1 : 0,
        !empty($p['listavendite']) ? 1 : 0
    ]);
    $p_vend_3 = buildStr([
        !empty($p['documenti']) ? 1 : 0,
        !empty($p['clienti']) ? 1 : 0
    ]);
    $s_PVend = "0;" . $p_vend_1 . ";" . $p_vend_2 . ";" . $p_vend_3;

    // 3. AMMINISTRAZIONE (s_Amm)
    $p_amm_1 = buildStr([
        !empty($p['scadenze']) ? 1 : 0,
        !empty($p['fatture']) ? 1 : 0,
        !empty($p['fornitori']) ? 1 : 0
    ]);
    $p_amm_2 = !empty($p['pratiche']) ? 1 : 0;
    $p_amm_3 = !empty($p['listaamm']) ? 1 : 0;
    $s_Amm = "0;" . $p_amm_1 . ";" . $p_amm_2 . ";" . $p_amm_3;

    // 4. ALTRO (s_Altro)
    $p_altro_1 = buildStr([
        !empty($p['utenti']) ? 1 : 0,
        !empty($p['task']) ? 1 : 0
    ]);
    $p_altro_2 = buildStr([
        !empty($p['db']) ? 1 : 0,
        !empty($p['backup']) ? 1 : 0
    ]);
    $s_Altro = "0;" . $p_altro_1 . ";" . $p_altro_2;

    // UPDATE DATABASE
  $check = $conn->query("SELECT * FROM app_permessi WHERE IDU = $idu");
    
    if($check && $check->num_rows > 0){
        // UTENTE ESISTENTE: Aggiorna
        $sql = "UPDATE app_permessi SET s_Online='$s_Online', s_PVend='$s_PVend', s_Amm='$s_Amm', s_Altro='$s_Altro' WHERE IDU=$idu";
    } else {
        // NUOVO UTENTE: Inserisci (Senza colonna 'home' e senza 'ID')
        $sql = "INSERT INTO app_permessi (IDU, s_Online, s_PVend, s_Amm, s_Altro) VALUES ($idu, '$s_Online', '$s_PVend', '$s_Amm', '$s_Altro')";
    }
    
    if($conn->query($sql)){
        echo "ok";
    } else {
        // Stampiamo l'errore esatto se capita ancora qualcosa
        echo "Errore SQL: " . $conn->error;
    }
    exit;
}

// RECUPERO LISTA UTENTI E PERMESSI (Il resto del file rimane uguale da qui in giù)
$sql = "SELECT u.id, u.nome, u.user, p.* FROM app_utenti u 
        LEFT JOIN app_permessi p ON u.id = p.IDU 
        WHERE u.Attivo = 1 ORDER BY u.nome ASC";
$result = $conn->query($sql);
?>

<div class="card mb-3">
    <div class="card-header">
        <h5>Gestione Permessi Utenti</h5>
    </div>
    <div class="card-body">
        <div class="accordion" id="accordionPermessi">
            <?php while($row = $result->fetch_assoc()) { 
                $uid = $row['id'];
                
                // Parsing Permessi Esistenti
                // Online
                $on = explode(';', $row['s_Online'] ?? '0;0,0,0;0;0');
                $on_1 = explode(',', $on[1] ?? '0,0,0');
                
                // Vendita
                $vn = explode(';', $row['s_PVend'] ?? '0;0,0,0,0;0,0,0,0;0,0');
                $vn_1 = explode(',', $vn[1] ?? '0,0,0,0');
                $vn_2 = explode(',', $vn[2] ?? '0,0,0,0');
                $vn_3 = explode(',', $vn[3] ?? '0,0');

                // Amministrazione
                $am = explode(';', $row['s_Amm'] ?? '0;0,0,0;0;0');
                $am_1 = explode(',', $am[1] ?? '0,0,0');

                // Altro
                $al = explode(';', $row['s_Altro'] ?? '0;0,0;0,0');
                $al_1 = explode(',', $al[1] ?? '0,0');
                $al_2 = explode(',', $al[2] ?? '0,0');
            ?>
            
            <div class="accordion-item border rounded mb-2">
                <h2 class="accordion-header" id="head<?php echo $uid; ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $uid; ?>">
                        <span class="fas fa-user me-2"></span> <?php echo $row['nome']; ?> (<?php echo $row['user']; ?>)
                    </button>
                </h2>
                <div id="collapse<?php echo $uid; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionPermessi">
                    <div class="accordion-body">
                        <form id="formPermessi_<?php echo $uid; ?>">
                            <input type="hidden" name="azione" value="salva_permessi">
                            <input type="hidden" name="idu" value="<?php echo $uid; ?>">
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <h6 class="text-primary">Online / Ordini</h6>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][ordini]" <?php echo ($on_1[0] ?? 0) ? 'checked' : ''; ?>> <label>Lista Ordini</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][manomano]" <?php echo ($on_1[1] ?? 0) ? 'checked' : ''; ?>> <label>ManoMano</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][sinistri]" <?php echo ($on_1[2] ?? 0) ? 'checked' : ''; ?>> <label>Sinistri</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][feedback]" <?php echo ($on[2] ?? 0) ? 'checked' : ''; ?>> <label>Feedback</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][segnalazioni]" <?php echo ($on[3] ?? 0) ? 'checked' : ''; ?>> <label>Segnalazioni</label></div>
                                </div>

                                <div class="col-md-3">
                                    <h6 class="text-primary">Vendita / Officina</h6>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][preventivi]" <?php echo ($vn_1[0] ?? 0) ? 'checked' : ''; ?>> <label>Preventivi</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][spaccati]" <?php echo ($vn_1[1] ?? 0) ? 'checked' : ''; ?>> <label>Spaccati</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][usato]" <?php echo ($vn_1[2] ?? 0) ? 'checked' : ''; ?>> <label>Usato</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][listaschede]" <?php echo ($vn_1[3] ?? 0) ? 'checked' : ''; ?>> <label>Lista Schede</label></div>
                                    <hr>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][vendita]" <?php echo ($vn_2[0] ?? 0) ? 'checked' : ''; ?>> <label>Vendita Banco</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][catalogo]" <?php echo ($vn_2[1] ?? 0) ? 'checked' : ''; ?>> <label>Catalogo</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][ordineprod]" <?php echo ($vn_2[2] ?? 0) ? 'checked' : ''; ?>> <label>Ordine Forn.</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][documenti]" <?php echo ($vn_3[0] ?? 0) ? 'checked' : ''; ?>> <label>Documenti</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][clienti]" <?php echo ($vn_3[1] ?? 0) ? 'checked' : ''; ?>> <label>Clienti</label></div>
                                </div>

                                <div class="col-md-3">
                                    <h6 class="text-primary">Amministrazione</h6>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][scadenze]" <?php echo ($am_1[0] ?? 0) ? 'checked' : ''; ?>> <label>Scadenze</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][fatture]" <?php echo ($am_1[1] ?? 0) ? 'checked' : ''; ?>> <label>Fatture</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][fornitori]" <?php echo ($am_1[2] ?? 0) ? 'checked' : ''; ?>> <label>Fornitori</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][pratiche]" <?php echo ($am[2] ?? 0) ? 'checked' : ''; ?>> <label>Pratiche Legali</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][listaamm]" <?php echo ($am[3] ?? 0) ? 'checked' : ''; ?>> <label>Buste/Lista</label></div>
                                </div>
                                
                                <div class="col-md-3">
                                    <h6 class="text-primary">Sistema</h6>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][utenti]" <?php echo ($al_1[0] ?? 0) ? 'checked' : ''; ?>> <label>Gest. Utenti</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][task]" <?php echo ($al_1[1] ?? 0) ? 'checked' : ''; ?>> <label>Task</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][db]" <?php echo ($al_2[0] ?? 0) ? 'checked' : ''; ?>> <label>Database</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="perm[<?php echo $uid; ?>][backup]" <?php echo ($al_2[1] ?? 0) ? 'checked' : ''; ?>> <label>Backup</label></div>
                                </div>
                            </div>
                            <div class="mt-3 text-end">
                                <button type="button" class="btn btn-primary btn-sm salva-permessi" data-uid="<?php echo $uid; ?>">Salva Modifiche</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
$('.salva-permessi').click(function(){
    var uid = $(this).data('uid');
    var form = $('#formPermessi_' + uid);
    
    $.post('amministrazione/gestione-permessi.php', form.serialize(), function(res){
        if(res.trim() == 'ok'){
            Swal.fire({icon: 'success', title: 'Permessi aggiornati!'});
        } else {
            Swal.fire({icon: 'error', title: 'Errore', text: res});
        }
    });
});
</script>