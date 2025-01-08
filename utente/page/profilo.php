<?php
session_start();
error_reporting(0);

if (isset($_SESSION['session_id']) || $_COOKIE["login"] == "OK") {
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
    $session_id = htmlspecialchars($_SESSION['session_id']);
    $session_ipt = htmlspecialchars($_SESSION['session_ipt']);
    $session_idu = htmlspecialchars($_SESSION['session_idu']);
    $session_mail = htmlspecialchars($_SESSION['session_email']);
    $session_uname = htmlspecialchars($_SESSION['session_nome']);
    $session_ruolo = htmlspecialchars($_SESSION['session_ruolo']);
    $session_attivo = htmlspecialchars($_SESSION['session_attivo']);
    header('Access-Control-Allow-Origin: *', false);
} else {
    header("location: utente/auth/login.php");
}



// <div class="row">
//     <div class="col-12">
//         <div class="card mb-3 btn-reveal-trigger">
//             <div class="card-header position-relative min-vh-25 mb-8">
//                 <div class="cover-image">
//                     <div class="bg-holder rounded-3 rounded-bottom-0" style="background-image:url(assets/img/generic/4.jpg);"></div>
//                     <input class="d-none" id="upload-cover-image" type="file"><label class="cover-image-file-input" for="upload-cover-image"><svg class="svg-inline--fa fa-camera fa-w-16 me-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="camera" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
//                             <path fill="currentColor" d="M512 144v288c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48h88l12.3-32.9c7-18.7 24.9-31.1 44.9-31.1h125.5c20 0 37.9 12.4 44.9 31.1L376 96h88c26.5 0 48 21.5 48 48zM376 288c0-66.2-53.8-120-120-120s-120 53.8-120 120 53.8 120 120 120 120-53.8 120-120zm-32 0c0 48.5-39.5 88-88 88s-88-39.5-88-88 39.5-88 88-88 88 39.5 88 88z"></path>
//                         </svg><span>Cambia copertina</span></label>
//                 </div>
//                 <div class="avatar avatar-5xl avatar-profile shadow-sm img-thumbnail rounded-circle">
//                     <div class="h-100 w-100 rounded-circle overflow-hidden position-relative"> <img src="assets/img/team/<?php echo $session_user .svg" width="200" alt="" data-dz-thumbnail="data-dz-thumbnail"><input class="d-none" id="profile-image" type="file"><label class="mb-0 overlay-icon d-flex flex-center" for="profile-image"><span class="bg-holder overlay overlay-0"></span><span class="z-index-1 text-white dark__text-white text-center fs--1"><svg class="svg-inline--fa fa-camera fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="camera" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
//                                     <path fill="currentColor" d="M512 144v288c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48h88l12.3-32.9c7-18.7 24.9-31.1 44.9-31.1h125.5c20 0 37.9 12.4 44.9 31.1L376 96h88c26.5 0 48 21.5 48 48zM376 288c0-66.2-53.8-120-120-120s-120 53.8-120 120 53.8 120 120 120 120-53.8 120-120zm-32 0c0 48.5-39.5 88-88 88s-88-39.5-88-88 39.5-88 88-88 88 39.5 88 88z"></path>
//                                 </svg><span class="d-block">Aggiorna</span></span></label></div>
//                 </div>
//             </div>
//         </div>
//     </div>
// </div>
?>
<div class="row g-0">
    <div class="col-lg-8 pe-lg-2">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Profile Settings</h5>
            </div>
            <div class="card-body bg-light">
                <form class="row g-3">
                    <div class="col-lg-6"> <label class="form-label" for="ruolo">Ruolo</label><input class="form-control" id="ruolo" type="text" value="<?php echo $session_ruolo ?>" disabled></div>
                    <div class="col-lg-6"> <label class="form-label" for="status">Stato</label><select class="form-control" name="status" value="<?php echo $session_attivo ?>" disabled>
                            <option value="1" selected="">attivo</option>
                            <option value="0">disattivato</option>
                        </select></div>
                    <div class="col-lg-6"> <label class="form-label" for="nome">Nome</label><input class="form-control" id="nome" type="text" value="<?php echo $session_uname ?>" onchange="aggiornacampo('nome')"></div>
                    <div class="col-lg-6"> <label class="form-label" for="email">Contatto</label><input class="form-control" id="email" type="mail" value="<?php echo $session_mail ?>" onchange="aggiornacampo('email')"></div>
                    <div class="col-lg-6"><label class="form-label" for="country">Paese</label>
                        <select name="country" class="form-control form-control-inline-block" readonly>
                            <option value="Afghanistan">Afghanistan</option>
                            <option value="Akrotiri">Akrotiri</option>
                            <option value="Albania">Albania</option>
                            <option value="Algeria">Algeria</option>
                            <option value="American Samoa">American Samoa</option>
                            <option value="Andorra">Andorra</option>
                            <option value="Angola">Angola</option>
                            <option value="Anguilla">Anguilla</option>
                            <option value="Antarctica">Antarctica</option>
                            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                            <option value="Argentina">Argentina</option>
                            <option value="Armenia">Armenia</option>
                            <option value="Aruba">Aruba</option>
                            <option value="Ashmore and Cartier Is.">Ashmore and Cartier Is.</option>
                            <option value="Australia">Australia</option>
                            <option value="Austria">Austria</option>
                            <option value="Azerbaijan">Azerbaijan</option>
                            <option value="Bahamas">Bahamas</option>
                            <option value="Bahrain">Bahrain</option>
                            <option value="Baikonur">Baikonur</option>
                            <option value="Bajo Nuevo Bank">Bajo Nuevo Bank</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Barbados">Barbados</option>
                            <option value="Belarus">Belarus</option>
                            <option value="Belgium">Belgium</option>
                            <option value="Belize">Belize</option>
                            <option value="Benin">Benin</option>
                            <option value="Bermuda">Bermuda</option>
                            <option value="Bhutan">Bhutan</option>
                            <option value="Bolivia">Bolivia</option>
                            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                            <option value="Botswana">Botswana</option>
                            <option value="Bouvet Island">Bouvet Island</option>
                            <option value="Brazil">Brazil</option>
                            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                            <option value="British Virgin Islands">British Virgin Islands</option>
                            <option value="Brunei">Brunei</option>
                            <option value="Bulgaria">Bulgaria</option>
                            <option value="Burkina Faso">Burkina Faso</option>
                            <option value="Burundi">Burundi</option>
                            <option value="Cambodia">Cambodia</option>
                            <option value="Cameroon">Cameroon</option>
                            <option value="Canada">Canada</option>
                            <option value="Cape Verde">Cape Verde</option>
                            <option value="Caribbean Netherlands">Caribbean Netherlands</option>
                            <option value="Cayman Islands">Cayman Islands</option>
                            <option value="Central African Republic">Central African Republic</option>
                            <option value="Chad">Chad</option>
                            <option value="Chile">Chile</option>
                            <option value="China">China</option>
                            <option value="Christmas Island">Christmas Island</option>
                            <option value="Clipperton I.">Clipperton I.</option>
                            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                            <option value="Colombia">Colombia</option>
                            <option value="Comoros">Comoros</option>
                            <option value="Cook Islands">Cook Islands</option>
                            <option value="Coral Sea Is.">Coral Sea Is.</option>
                            <option value="Costa Rica">Costa Rica</option>
                            <option value="Croatia">Croatia</option>
                            <option value="Cuba">Cuba</option>
                            <option value="Curaçao">Curaçao</option>
                            <option value="Cyprus">Cyprus</option>
                            <option value="Cyprus U.N. Buffer Zone">Cyprus U.N. Buffer Zone</option>
                            <option value="Czechia">Czechia</option>
                            <option value="DR Congo">DR Congo</option>
                            <option value="Denmark">Denmark</option>
                            <option value="Dhekelia">Dhekelia</option>
                            <option value="Djibouti">Djibouti</option>
                            <option value="Dominica">Dominica</option>
                            <option value="Dominican Republic">Dominican Republic</option>
                            <option value="Ecuador">Ecuador</option>
                            <option value="Egypt">Egypt</option>
                            <option value="El Salvador">El Salvador</option>
                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                            <option value="Eritrea">Eritrea</option>
                            <option value="Estonia">Estonia</option>
                            <option value="Eswatini">Eswatini</option>
                            <option value="Ethiopia">Ethiopia</option>
                            <option value="Europe Union">Europe Union</option>
                            <option value="Falkland Islands">Falkland Islands</option>
                            <option value="Faroe Islands">Faroe Islands</option>
                            <option value="Fiji">Fiji</option>
                            <option value="Finland">Finland</option>
                            <option value="France">France</option>
                            <option value="French Guiana">French Guiana</option>
                            <option value="French Polynesia">French Polynesia</option>
                            <option value="French Southern and Antarctic Lands">French Southern and Antarctic Lands</option>
                            <option value="Gabon">Gabon</option>
                            <option value="Gambia">Gambia</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Germany">Germany</option>
                            <option value="Ghana">Ghana</option>
                            <option value="Gibraltar">Gibraltar</option>
                            <option value="Greece">Greece</option>
                            <option value="Greenland">Greenland</option>
                            <option value="Grenada">Grenada</option>
                            <option value="Guadeloupe">Guadeloupe</option>
                            <option value="Guam">Guam</option>
                            <option value="Guatemala">Guatemala</option>
                            <option value="Guernsey">Guernsey</option>
                            <option value="Guinea">Guinea</option>
                            <option value="Guinea-Bissau">Guinea-Bissau</option>
                            <option value="Guyana">Guyana</option>
                            <option value="Haiti">Haiti</option>
                            <option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
                            <option value="Honduras">Honduras</option>
                            <option value="Hong Kong">Hong Kong</option>
                            <option value="Hungary">Hungary</option>
                            <option value="Iceland">Iceland</option>
                            <option value="India">India</option>
                            <option value="Indian Ocean Ter.">Indian Ocean Ter.</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Iran">Iran</option>
                            <option value="Iraq">Iraq</option>
                            <option value="Ireland">Ireland</option>
                            <option value="Isle of Man">Isle of Man</option>
                            <option value="Israel">Israel</option>
                            <option value="Italy" selected="">Italy</option>
                            <option value="Ivory Coast">Ivory Coast</option>
                            <option value="Jamaica">Jamaica</option>
                            <option value="Japan">Japan</option>
                            <option value="Jersey">Jersey</option>
                            <option value="Jordan">Jordan</option>
                            <option value="Kazakhstan">Kazakhstan</option>
                            <option value="Kenya">Kenya</option>
                            <option value="Kiribati">Kiribati</option>
                            <option value="Kosovo">Kosovo</option>
                            <option value="Kuwait">Kuwait</option>
                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                            <option value="Laos">Laos</option>
                            <option value="Latvia">Latvia</option>
                            <option value="Lebanon">Lebanon</option>
                            <option value="Lesotho">Lesotho</option>
                            <option value="Liberia">Liberia</option>
                            <option value="Libya">Libya</option>
                            <option value="Liechtenstein">Liechtenstein</option>
                            <option value="Lithuania">Lithuania</option>
                            <option value="Luxembourg">Luxembourg</option>
                            <option value="Macau">Macau</option>
                            <option value="Madagascar">Madagascar</option>
                            <option value="Malawi">Malawi</option>
                            <option value="Malaysia">Malaysia</option>
                            <option value="Maldives">Maldives</option>
                            <option value="Mali">Mali</option>
                            <option value="Malta">Malta</option>
                            <option value="Marshall Islands">Marshall Islands</option>
                            <option value="Martinique">Martinique</option>
                            <option value="Mauritania">Mauritania</option>
                            <option value="Mauritius">Mauritius</option>
                            <option value="Mayotte">Mayotte</option>
                            <option value="Mexico">Mexico</option>
                            <option value="Micronesia">Micronesia</option>
                            <option value="Moldova">Moldova</option>
                            <option value="Monaco">Monaco</option>
                            <option value="Mongolia">Mongolia</option>
                            <option value="Montenegro">Montenegro</option>
                            <option value="Montserrat">Montserrat</option>
                            <option value="Morocco">Morocco</option>
                            <option value="Mozambique">Mozambique</option>
                            <option value="Myanmar">Myanmar</option>
                            <option value="N. Cyprus">N. Cyprus</option>
                            <option value="Namibia">Namibia</option>
                            <option value="Nauru">Nauru</option>
                            <option value="Nepal">Nepal</option>
                            <option value="Netherlands">Netherlands</option>
                            <option value="New Caledonia">New Caledonia</option>
                            <option value="New Zealand">New Zealand</option>
                            <option value="Nicaragua">Nicaragua</option>
                            <option value="Niger">Niger</option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="Niue">Niue</option>
                            <option value="Norfolk Island">Norfolk Island</option>
                            <option value="North Korea">North Korea</option>
                            <option value="North Macedonia">North Macedonia</option>
                            <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                            <option value="Norway">Norway</option>
                            <option value="Oman">Oman</option>
                            <option value="Pakistan">Pakistan</option>
                            <option value="Palau">Palau</option>
                            <option value="Palestine">Palestine</option>
                            <option value="Panama">Panama</option>
                            <option value="Papua New Guinea">Papua New Guinea</option>
                            <option value="Paraguay">Paraguay</option>
                            <option value="Peru">Peru</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Pitcairn Islands">Pitcairn Islands</option>
                            <option value="Poland">Poland</option>
                            <option value="Portugal">Portugal</option>
                            <option value="Puerto Rico">Puerto Rico</option>
                            <option value="Qatar">Qatar</option>
                            <option value="Republic of the Congo">Republic of the Congo</option>
                            <option value="Romania">Romania</option>
                            <option value="Russia">Russia</option>
                            <option value="Rwanda">Rwanda</option>
                            <option value="Réunion">Réunion</option>
                            <option value="Saint Barthélemy">Saint Barthélemy</option>
                            <option value="Saint Helena, Ascension and Tristan da Cunha">Saint Helena, Ascension and Tristan da Cunha</option>
                            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                            <option value="Saint Lucia">Saint Lucia</option>
                            <option value="Saint Martin">Saint Martin</option>
                            <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                            <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                            <option value="Samoa">Samoa</option>
                            <option value="San Marino">San Marino</option>
                            <option value="Saudi Arabia">Saudi Arabia</option>
                            <option value="Scarborough Reef">Scarborough Reef</option>
                            <option value="Senegal">Senegal</option>
                            <option value="Serbia">Serbia</option>
                            <option value="Serranilla Bank">Serranilla Bank</option>
                            <option value="Seychelles">Seychelles</option>
                            <option value="Siachen Glacier">Siachen Glacier</option>
                            <option value="Sierra Leone">Sierra Leone</option>
                            <option value="Singapore">Singapore</option>
                            <option value="Sint Maarten">Sint Maarten</option>
                            <option value="Slovakia">Slovakia</option>
                            <option value="Slovenia">Slovenia</option>
                            <option value="Solomon Islands">Solomon Islands</option>
                            <option value="Somalia">Somalia</option>
                            <option value="Somaliland">Somaliland</option>
                            <option value="South Africa">South Africa</option>
                            <option value="South Georgia">South Georgia</option>
                            <option value="South Korea">South Korea</option>
                            <option value="South Sudan">South Sudan</option>
                            <option value="Spain">Spain</option>
                            <option value="Spratly Is.">Spratly Is.</option>
                            <option value="Sri Lanka">Sri Lanka</option>
                            <option value="Sudan">Sudan</option>
                            <option value="Suriname">Suriname</option>
                            <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                            <option value="Sweden">Sweden</option>
                            <option value="Switzerland">Switzerland</option>
                            <option value="Syria">Syria</option>
                            <option value="São Tomé and Príncipe">São Tomé and Príncipe</option>
                            <option value="Taiwan">Taiwan</option>
                            <option value="Tajikistan">Tajikistan</option>
                            <option value="Tanzania">Tanzania</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Timor-Leste">Timor-Leste</option>
                            <option value="Togo">Togo</option>
                            <option value="Tokelau">Tokelau</option>
                            <option value="Tonga">Tonga</option>
                            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                            <option value="Tunisia">Tunisia</option>
                            <option value="Turkey">Turkey</option>
                            <option value="Turkmenistan">Turkmenistan</option>
                            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                            <option value="Tuvalu">Tuvalu</option>
                            <option value="USNB Guantanamo Bay">USNB Guantanamo Bay</option>
                            <option value="Uganda">Uganda</option>
                            <option value="Ukraine">Ukraine</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="United States">United States</option>
                            <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                            <option value="United States Virgin Islands">United States Virgin Islands</option>
                            <option value="Uruguay">Uruguay</option>
                            <option value="Uzbekistan">Uzbekistan</option>
                            <option value="Vanuatu">Vanuatu</option>
                            <option value="Vatican City">Vatican City</option>
                            <option value="Venezuela">Venezuela</option>
                            <option value="Vietnam">Vietnam</option>
                            <option value="Wallis and Futuna">Wallis and Futuna</option>
                            <option value="Western Sahara">Western Sahara</option>
                            <option value="Yemen">Yemen</option>
                            <option value="Zambia">Zambia</option>
                            <option value="Zimbabwe">Zimbabwe</option>
                            <option value="Åland Islands">Åland Islands</option>
                        </select>
                    </div>
                    <div class="col-lg-6"> <label class="form-label" for="IPTelefono">IP Telefono</label><input class="form-control" id="IPTelefono" type="text" value="<?php echo $session_ipt ?>" onchange="aggiornacampo('IPTelefono')"></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4 ps-lg-2">
        <div class="sticky-sidebar">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Modifica login</h5>
                </div>
                <div class="card-body bg-light">
                    <form>
                        <div class="mb-3"><label class="form-label" for="username">Nuova password</label><input class="form-control" id="username" type="text" value="<?php echo $session_user ?>"></div>
                        <div class="mb-3"><label class="form-label" for="password">Nuova password</label><input class="form-control" id="password" type="password" placeholder="Lascia vuoto se non vuoi modificare"></div>
                        <div class="mb-3"><label class="form-label" for="password_check">Conferma password</label><input class="form-control" id="password_check" type="password" placeholder="Lascia vuoto se non vuoi modificare"></div>
                        <button class="btn btn-primary d-block w-100 AggionaPassword" type="submit">Aggiorna</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function aggiornacampo(campo) {
        var valore = $('#' + campo).val();
        $.post(currentURL + "assets/inc/profilo.php", {
            aggiornau: '',
            campo: campo,
            valore: valore,
            idu: '<?php echo $session_idu; ?>'
        }, function(response) {
            if (response == 'si') {
                Toast.fire({
                    icon: 'success',
                    title: 'Modificato con successo'
                });
            } else {
                Toast.fire({
                    icon: 'danger',
                    title: 'Errore: ' + response
                });
            }
        })
    }

    $(document).on('click', '.aggiorna', function() {
        var password = $('#password').val();
        var password_check = $('#password_check').val();

        if (password != password_check) {
            Toast.fire({
                icon: 'warning',
                title: 'Le password devono combaciare'
            });
        } else if (password != '' && password_check == '') {
            Toast.fire({
                icon: 'warning',
                title: 'Perfavore, conferma la password'
            });
        } else if (password != '' && password_check != '') {
            $.post(currentURL + "assets/inc/profilo.php", {
                update: '',
                idu: '<?php echo $session_idu ?>',
                user: $('#username').val(),
                newpass: password
            }, function(response) {
                if (response == 'si') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Modificato con successo'
                    });
                } else {
                    Toast.fire({
                        icon: 'danger',
                        title: 'Errore: ' + response
                    });
                }
            })
        } else if (password == '' && password_check == '') {
            $.post(currentURL + "assets/inc/profilo.php", {
                updatesp: '',
                idu: '<?php echo $session_idu ?>',
                user: $('#username').val()
            }, function(response) {
                if (response == 'si') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Modificato con successo'
                    });
                } else {
                    Toast.fire({
                        icon: 'danger',
                        title: 'Errore: ' + response
                    });
                }
            })
        }
    });
</script>