<!DOCTYPE html>
<html lang="it">
<?php include("../assets/inc/ordine.php");

session_start();

if (isset($_SESSION['session_id']) || $_COOKIE["login"] == "OK") {
    $session_idu = htmlspecialchars($_SESSION['session_idu']);
    $session_uname = htmlspecialchars($_SESSION['session_nome']);
    $session_user = htmlspecialchars($_SESSION['session_user']);
} else {
    echo '<span style="color: red;">SI PREGA DI RIEFFETTUARE IL LOGIN.</span>';
} ?>

<head>
    <meta charset=UTF-8>

    <style>
        .dettagliordini-container {
            background: #fff;
            overflow-y: auto;
            height: 100%;
        }

        .header-header {
            height: 56px;
            padding: 12px;
            display: grid;
            grid-column-gap: 32px;
            column-gap: 32px;
            align-items: center;
            grid-template-columns: -webkit-max-content 1fr;
            grid-template-columns: max-content 1fr;
            background-color: #fff;
        }

        .header-close {
            cursor: pointer;
        }

        .header-item,
        .header-header {
            display: grid;
            align-items: center;
            grid-template-columns: -webkit-max-content -webkit-max-content -webkit-max-content -webkit-max-content -webkit-max-content;
            grid-template-columns: max-content max-content max-content max-content max-content;
            justify-content: center;
        }

        .header-item:not(:last-child) {
            border-right: 1px solid #b1b5c0;
        }

        .header-item {
            grid-column-gap: 8px;
            column-gap: 8px;
            color: #505971;
            padding: 0 16px;
            font-size: 16px;
            line-height: 24px;
        }


        .header-stato[data-status=PENDING]:before {
            background: #d8dadf;
        }

        .header-stato[data-status=PREPARATION]:before {
            background: #ffb950;
        }

        .header-stato[data-status=SHIPPED]:before {
            background: #68cf8f;
        }

        .header-stato[data-status=GUARANTEE]:before {
            background: #9153f2;
        }

        .header-stato[data-status=REFUSED]:before {
            background: #f24444;
        }

        .header-stato[data-status=REFUNDED]:before {
            background: #335ab1;
        }

        .header-stato[data-status=REFUNDING]:before {
            background: #91b2ec;
        }

        .header-stato:before {
            content: "";
            display: block;
            background: #0c193a;
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .header-reford-X {
            color: #0c193a;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 0;
            line-height: 28px;
        }

        .header-stato {
            display: grid;
            grid-template-columns: -webkit-min-content 1fr;
            grid-template-columns: min-content 1fr;
            align-items: center;
            color: #0c193a;
            grid-column-gap: 4px;
            column-gap: 4px;
            font-size: 14px;
            line-height: 20px;
        }

        .ordinidettagli-content {
            padding: 16px;
        }

        .sommario-somm {
            display: grid;
            grid-auto-columns: 1fr;
            grid-auto-flow: column;
            align-items: center;
            border-bottom: 1px solid #d7dade;
            padding-bottom: 16px;
        }

        .sommario-item {
            display: grid;
            grid-template-columns: 32px -webkit-max-content;
            grid-template-columns: 32px max-content;
            justify-content: left;
            grid-column-gap: 8px;
            column-gap: 8px;
            justify-content: center;
        }

        .sommario-titolo {
            color: #8b91a1;
            font-size: 14px;
            line-height: 20px;
        }

        .sommario-sommval {
            color: #0c193a;
            font-size: 16px;
            font-weight: 600;
            line-height: 24px;
        }

        .ordinidettagli-content .ordine-dettagli {
            padding: 16px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            grid-column-gap: 32px;
            column-gap: 32px;
            grid-row-gap: 8px;
            row-gap: 8px;
            border-bottom: 1px solid #d8dadf;
        }

        .cliente-dettagli {
            display: grid;
            grid-row-gap: 16px;
            row-gap: 16px;
            padding-bottom: 16px;
        }

        .cliente-item:not(:last-child) {
            border-right: 1px solid #b1b5c0;
        }

        .ClientDetails_client-details__title__TOX99 {
            color: #0c193a;
            font-size: 16px;
            font-weight: 600;
            line-height: 24px;
        }

        .ClientDetails_client-details__content__1kSR_ {
            display: grid;
            grid-row-gap: 16px;
            row-gap: 16px;
            grid-column-gap: 16px;
            column-gap: 16px;
            grid-template-columns: 1fr 1fr;
        }

        .ClientDetails_client-details__field--label__1T_-g {
            color: #8b91a0;
            font-size: 14px;
            font-weight: 600;
            line-height: 20px;

        }

        .ClientDetails_client-details__messages-button__3P7Cj {
            font-family: Open Sans;
            font-weight: 600;
            font-size: 14px;
            line-height: 20px;
            display: grid;
            grid-auto-flow: column;
            grid-column-gap: 8px;
            column-gap: 8px;
            cursor: pointer;
            justify-content: center;
            padding: 8px;
            --line-color: #1e3c87;
            border: 1px solid var(--line-color);
            box-sizing: border-box;
            border-radius: 4px;
            color: var(--line-color);
            fill: var(--line-color);
            background-color: #fff;
            outline: none;
            transition: all .3s ease;
        }

        .ClientDetails_client-details__messages-button__3P7Cj:hover {
            transition: all 0.5s ease 0s;
            background-image: linear-gradient(80deg, rgb(30, 60, 135), rgb(30, 105, 135));
            color: white;
            border-color: transparent;
        }


        .spedizione-titolo {
            color: #0c193a;
            font-size: 16px;
            font-weight: 600;
            line-height: 24px;
        }

        .spedizione-contenuto {
            display: grid;
            padding: 16px 0;
            grid-row-gap: 8px;
            row-gap: 8px;
            grid-template-columns: 1fr;
        }

        .TextField_field__2IBfg {
            position: relative;
            display: grid;
            grid-template-rows: 1fr -webkit-min-content;
            grid-template-rows: 1fr min-content;
            grid-column-gap: 4px;
            column-gap: 4px;
            font-size: 14px;
            align-items: center;
            padding: 4px 16px;
            box-sizing: border-box;
            height: auto;
            border-radius: 4px;
            box-shadow: 0 0 0 0 rgb(12 25 58 / 0%);
            transition: all .3s ease;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background: #fff;
            width: 100%;
            justify-items: start;
            cursor: text;
            border: 1px solid #d8dadf;
        }

        .TextField_field__2IBfg:focus-within .spedizione-labeltext,
        .TextField_field__2IBfg[data-empty=false] .spedizione-labeltext {
            font-size: 12px;
            line-height: 16px;
        }

        .spedizione-labeltext {
            color: #8b91a1;
            font-size: 14px;
            line-height: 20px;
            transition: all .3s ease;
            cursor: text;
        }

        .TextField_field__2IBfg:focus-within .spedizione-input,
        .TextField_field__2IBfg[data-empty=false] .spedizione-input {
            max-height: auto;
        }

        .spedizione-input {
            padding: 0;
            border: none;
            outline: none;
            color: #0c193a;
            font-size: 14px;
            letter-spacing: 0;
            line-height: 20px;
            width: 100%;
        }

        .spedizione-input:disabled {
            background-color: #fff;
        }

        .dettagliordini-prodotti {
            display: grid;
            grid-row-gap: 16px;
            row-gap: 16px;
            padding: 16px;
        }

        .Items_sectionTitle__3uK82 {
            color: #0c193a;
            font-size: 16px;
            font-weight: 600;
            line-height: 24px;
        }

        .Items_product__2NQ_G {
            display: grid;
            grid-template-columns: 128px 1fr;
            grid-column-gap: 16px;
            column-gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid #d8dadf;
        }

        .Items_actions__2cWk0 {
            display: grid;
            grid-template-columns: 1fr;
            grid-row-gap: 16px;
            row-gap: 16px;
            align-content: center;
        }

        .Items_imageContainer__3Zzle {
            box-sizing: content-box;
            border: 1px solid #d8dadf;
            border-radius: 3px;
            background-color: #fff;
        }

        .Items_product__2NQ_G .Items_productDescription__2O_ci {
            display: grid;
            grid-row-gap: 16px;
        }

        .Items_productDetails__2u3lv {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-column-gap: 16px;
            column-gap: 16px;
        }

        .Items_termList__3l1Kt {
            display: grid;
            grid-auto-rows: -webkit-min-content;
            grid-auto-rows: min-content;
            grid-row-gap: 4px;
            row-gap: 4px;
        }

        .Items_term__2Tuen {
            display: grid;
            grid-template-columns: 1fr -webkit-max-content;
            grid-template-columns: 1fr max-content;
            color: #505971;
            font-size: 14px;
            line-height: 20px;
        }

        .OrderDetailsPanel_priceBreakdown__K1e88 {
            padding-left: 216px;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .OrderDetailsPanel_termList__1037Q {
            display: grid;
            grid-auto-rows: -webkit-min-content;
            grid-auto-rows: min-content;
            grid-row-gap: 4px;
            row-gap: 4px;
        }

        .OrderDetailsPanel_term__2ZSh5 {
            display: grid;
            grid-template-columns: 1fr -webkit-max-content;
            grid-template-columns: 1fr max-content;
            color: #505971;
            font-size: 14px;
            line-height: 20px;
        }

        .OrderDetailsPanel_Print__i0Fxq {
            display: flex;
            justify-content: center;
        }

        .PrintLink_Print__link__1wEmP {
            display: flex;
            justify-content: center;
            min-height: 36px;
            padding: 12px 14px;
            text-align: center;
            border: 1px solid #29b9ad;
            border-radius: 3px;
            color: #29b9ad;
            background-color: #fff;
            cursor: pointer;
            outline: 0;
        }

        .Icon_container__3nSZ1 {
            display: flex;
        }

        .toolkit {
            font: 16px Open Sans, mm-main, arial, sans-serif;
            line-height: 1.5;
            color: #0c193a;
        }



        .text-bg-yellow {
            background-color: yellow;
        }

        .status-ok {
            color: green;
        }

        .status-no {
            color: red;
        }

        .status-man {
            color: orange;
        }
    </style>
</head>

<body onload="carica()">
    <div class="dettagliordini-container scrollbar">
        <div class="header-header">
            <div class="header-item">
                <div>Ordine</div>
                <div class="header-reford-X" id="PoP-1" style="cursor:pointer;" cp="<?= $ordine['riferimento']; ?>"><?= $ordine['riferimento']; ?></div>
            </div>
            <div class="header-item">
                <div>ID</div>
                <div class="header-reford-X" id="OrdineID"><?= $ordine['id']; ?></div>
            </div>
            <div class="header-item">
                <div>Stato</div>
                <div data-status="PREPARATION" class="header-stato" id="header-stato"><?= $ordine['stato'] ?></div>
            </div>
            <div class="header-item">
                <div>Segnalazione </div>
                <div data-status="SHIPPED" class="header-stato" id="header-segnalazione"></div>
            </div>
            <div class="header-item">
                <div>Ritardo</div>
                <div data-status="SHIPPED" class="header-stato" id="header-ritardo" title="Nessun ritardo per l'ordine"></div>
            </div>
        </div>
        <div class="ordinidettagli-content">
            <div class="sommario-somm ">
                <div class="sommario-item"><svg width="32px" height="32px" viewBox="0 0 32 32" role="img" fill="#1E3C87">
                        <path d="M25.3333333,5.33333333 L24,5.33333333 L24,4 C24,3.26666667 23.4,2.66666667 22.6666667,2.66666667 C21.9333333,2.66666667 21.3333333,3.26666667 21.3333333,4 L21.3333333,5.33333333 L10.6666667,5.33333333 L10.6666667,4 C10.6666667,3.26666667 10.0666667,2.66666667 9.33333333,2.66666667 C8.6,2.66666667 8,3.26666667 8,4 L8,5.33333333 L6.66666667,5.33333333 C5.2,5.33333333 4,6.53333333 4,8 L4,26.6666667 C4,28.1333333 5.2,29.3333333 6.66666667,29.3333333 L25.3333333,29.3333333 C26.8,29.3333333 28,28.1333333 28,26.6666667 L28,8 C28,6.53333333 26.8,5.33333333 25.3333333,5.33333333 Z M24.5833333,27.3333333 L7.41666667,27.3333333 C6.6375,27.3333333 6,26.7102564 6,25.9487179 L6,10.6666667 L26,10.6666667 L26,25.9487179 C26,26.7102564 25.3625,27.3333333 24.5833333,27.3333333 Z M7.83333333,12 L10.8333333,12 C11.1094757,12 11.3333333,12.2238576 11.3333333,12.5 L11.3333333,15.5 C11.3333333,15.7761424 11.1094757,16 10.8333333,16 L7.83333333,16 C7.55719096,16 7.33333333,15.7761424 7.33333333,15.5 L7.33333333,12.5 C7.33333333,12.2238576 7.55719096,12 7.83333333,12 Z"></path>
                    </svg>
                    <div>
                        <div class="sommario-titolo">Data dell'ordine</div>
                        <div class="sommario-sommval" id="DataOrdine_ov"><?php $res = explode('-', $ordine['dataordine']);
                                                                            echo $res[2] . '/' .  $res[1] . '/' . $res[0]; ?></div>
                    </div>
                </div>
                <div class="sommario-item"><svg width="32px" height="32px" viewBox="0 0 32 32" role="img" fill="#1E3C87">
                        <path xmlns="http://www.w3.org/2000/svg" d="M14.3333333,3.75 C15.2515067,3.75 16.0045257,4.45711027 16.0775321,5.35647279 L16.0833333,5.5 L16.083,7 L18.3988552,7 C18.9985855,7 19.5527502,7.30669797 19.8724823,7.80607513 L19.947422,7.93486758 L22.5485668,12.8764531 C22.654406,13.0775235 22.7198197,13.2967859 22.7417516,13.5218721 L22.75,13.6915855 L22.75,17 C22.75,17.6472087 22.2581253,18.1795339 21.6278052,18.2435464 L21.5,18.25 L19.646327,18.2505538 C19.3196798,19.4045229 18.2585758,20.25 17,20.25 C15.7414242,20.25 14.6803202,19.4045229 14.353673,18.2505538 L9.64632705,18.2505538 C9.31967982,19.4045229 8.25857578,20.25 7,20.25 C5.74142422,20.25 4.68032018,19.4045229 4.35367295,18.2505538 L2.5,18.25 C1.85279131,18.25 1.3204661,17.7581253 1.25645361,17.1278052 L1.25,17 L1.25,5.5 C1.25,4.5818266 1.95711027,3.82880766 2.85647279,3.7558012 L3,3.75 L14.3333333,3.75 Z M7,16.25 C6.30964406,16.25 5.75,16.8096441 5.75,17.5 C5.75,18.1903559 6.30964406,18.75 7,18.75 C7.69035594,18.75 8.25,18.1903559 8.25,17.5 C8.25,16.8096441 7.69035594,16.25 7,16.25 Z M17,16.25 C16.3096441,16.25 15.75,16.8096441 15.75,17.5 C15.75,18.1903559 16.3096441,18.75 17,18.75 C17.6903559,18.75 18.25,18.1903559 18.25,17.5 C18.25,16.8096441 17.6903559,16.25 17,16.25 Z M14.3333333,5.25 L3,5.25 C2.88165327,5.25 2.78251318,5.33223341 2.75660268,5.44267729 L2.75,5.5 L2.75,16.75 L4.35339057,16.7504445 C4.67970826,15.5959647 5.7410613,14.75 7,14.75 C8.2589387,14.75 9.32029174,15.5959647 9.64660943,16.7504445 L14.3533906,16.7504445 C14.6797083,15.5959647 15.7410613,14.75 17,14.75 C18.2589387,14.75 19.3202917,15.5959647 19.6466094,16.7504445 L21.25,16.75 L21.25,13.6915855 C21.25,13.6712976 21.2475309,13.651162 21.2426994,13.6316103 L21.2212238,13.575138 L18.620079,8.63355251 C18.585493,8.56784698 18.5240632,8.52179669 18.4533532,8.5060048 L18.3988552,8.5 L14.5833333,8.5 L14.5833333,5.5 C14.5833333,5.38165327 14.5010999,5.28251318 14.390656,5.25660268 L14.3333333,5.25 Z M17.5192969,9.14799968 C17.9053258,9.14799968 18.2568812,9.37019014 18.4225386,9.71886757 L19.3191385,11.6060405 C19.5561416,12.1048871 19.3438755,12.7014112 18.8450289,12.9384143 C18.7109401,13.0021202 18.5643496,13.0351726 18.4158968,13.0351726 L16.4792221,13.0351726 C15.9269373,13.0351726 15.4792221,12.5874573 15.4792221,12.0351726 L15.4792221,9.14799968 L17.5192969,9.14799968 Z M17.5192969,10.1479997 L16.479,10.147 L16.4792221,12.0351726 L18.4158968,12.0351726 L17.5192969,10.1479997 Z" transform="translate(4.64 4) scale(1.328)" transform-origin="center"></path>
                    </svg>
                    <div>
                        <div class="sommario-titolo">Data di evasione</div>
                        <div class="sommario-sommval"><?= $ordine['dataevasione']; ?></div>
                    </div>
                </div>
                <div class="sommario-item"><svg width="32px" height="32px" viewBox="0 0 32 32" role="img" fill="#1E3C87">
                        <path d="M18,1.5 C20.2686574,1.5 22,3.5 22,5.5 L22,22.5 L18.7081184,20.9528024 L15.8966519,22.5 L12.741085,20.9528024 L10,22.5 L7.19365312,20.9528024 L4,22.5 L4,5.5 C4,3.5 3.33333333,2.16666667 2,1.5 L18,1.5 Z M18,3.5 L5.784,3.501 L5.80745935,3.60282522 C5.91763092,4.10678732 5.98039538,4.64475773 5.99607968,5.21323521 L6,5.5 L6,19.309 L7.25300366,18.7017069 L9.989,20.21 L12.6755336,18.693196 L15.843,20.247 L18.6366138,18.7093047 L20,19.35 L20,5.5 C20,4.53671518 19.1376098,3.5901146 18.1429197,3.50605026 L18,3.5 Z M17,16 C17.2761424,16 17.5,16.2238576 17.5,16.5 C17.5,16.7761424 17.2761424,17 17,17 L9,17 C8.72385763,17 8.5,16.7761424 8.5,16.5 C8.5,16.2238576 8.72385763,16 9,16 L17,16 Z M13.7093236,5 C14.5624619,5 15.3260207,5.17635271 16,5.52905812 L16,5.52905812 L15.4625229,6.65330661 L15.2195784,6.54621743 C14.6606395,6.313001 14.1572212,6.19639279 13.7093236,6.19639279 C13.1078611,6.19639279 12.6162401,6.36472946 12.2344607,6.70140281 C11.8526813,7.03807615 11.5978062,7.54709419 11.4698355,8.22845691 L11.4698355,8.22845691 L14.3875686,8.22845691 L14.3875686,9.15430862 L11.3738574,9.15430862 L11.3610603,9.4248497 L11.3610603,9.75551102 L11.3738574,9.98997996 L13.9844607,9.98997996 L13.9844607,10.9098196 L11.4826325,10.9098196 L11.5299215,11.1020712 C11.8387528,12.2244045 12.5993457,12.7855711 13.8117002,12.7855711 C14.4216941,12.7855711 15.0466179,12.6613226 15.6864717,12.4128257 L15.6864717,12.4128257 L15.6864717,13.6332665 L15.4726205,13.7192197 C14.9636036,13.9064066 14.3843693,14 13.7349177,14 C12.706886,14 11.8718769,13.7354709 11.2298903,13.2064128 C10.5879037,12.6773547 10.1687995,11.9118236 9.9725777,10.9098196 L9.9725777,10.9098196 L9,10.9098196 L9,9.98997996 L9.8702011,9.98997996 L9.85740402,9.76753507 L9.85740402,9.54509018 L9.8702011,9.15430862 L9,9.15430862 L9,8.22845691 L9.95978062,8.22845691 L10.0054146,7.98146293 C10.1914758,7.09235137 10.583638,6.38510354 11.1819013,5.85971944 C11.8345521,5.28657315 12.6770262,5 13.7093236,5 Z" transform="scale(1.2) translate(1, 1)"></path>
                    </svg>
                    <div>
                        <div class="sommario-titolo">Prezzo pagato</div>
                        <div class="sommario-sommval">€ <?= $ordine['importo']; ?></div>
                    </div>
                </div>
                <div class="sommario-item"><svg width="32px" height="32px" viewBox="0 0 32 32" role="img" fill="#1E3C87">
                        <path d="M18.121,9.88l-7.832-7.836c-0.155-0.158-0.428-0.155-0.584,0L1.842,9.913c-0.262,0.263-0.073,0.705,0.292,0.705h2.069v7.042c0,0.227,0.187,0.414,0.414,0.414h3.725c0.228,0,0.414-0.188,0.414-0.414v-3.313h2.483v3.313c0,0.227,0.187,0.414,0.413,0.414h3.726c0.229,0,0.414-0.188,0.414-0.414v-7.042h2.068h0.004C18.331,10.617,18.389,10.146,18.121,9.88 M14.963,17.245h-2.896v-3.313c0-0.229-0.186-0.415-0.414-0.415H8.342c-0.228,0-0.414,0.187-0.414,0.415v3.313H5.032v-6.628h9.931V17.245z M3.133,9.79l6.864-6.868l6.867,6.868H3.133z" transform="scale(1.2) translate(1, 1)"></path>
                    </svg>
                    <div>
                        <div class="sommario-titolo">Marketplace</div>
                        <div class="sommario-sommval" id="Piattaforma_ov"><?= $ordine['piattaforma']; ?></div>
                    </div>
                </div>
            </div>
            <div class="ordine-dettagli">
                <div class="cliente-item">
                    <div class="cliente-dettagli">
                        <div class="ClientDetails_client-details__title__TOX99">Informazioni del cliente</div>
                        <div class="ClientDetails_client-details__content__1kSR_">
                            <div>
                                <div class="ClientDetails_client-details__field--label__1T_-g">Cliente</div>
                                <div id="ClienteNome_ov"><?= $cliente['cliente'] ?></div>
                            </div>
                            <div>
                                <div class="ClientDetails_client-details__field--label__1T_-g">Indirizzo di consegna</div>
                                <div id="IndirizzoCliente_ov"><?= str_replace("'", " ", $cliente['via']) . ', <br />' . $cliente['cap'] . ' ' . str_replace("'", " ", $cliente['citta']); ?></div>
                            </div>

                            <div>
                                <?php
                                if ($cliente['cellulare'] != '') {
                                    $cliente['recapito'] = $cliente['cellulare'];
                                } else if ($cliente['cellulare2'] != '') {
                                    $cliente['recapito'] =  $cliente['cellulare2'];
                                } else if ($cliente['telefono'] != '') {
                                    $cliente['recapito'] =  $cliente['telefono'];
                                }
                                $cliente['recapito'] = str_replace("+39", "", $cliente['recapito']);
                                $cliente['recapito'] = str_replace(" ", "", $cliente['recapito']);
                                echo '<div class="ClientDetails_client-details__field--label__1T_-g">Recapito</div><div class="ClientDetails_client-details__title__TOX99" id="RecapitoCliente_ov">' .  $cliente['recapito'] . '</div>';
                                ?>
                            </div>
                            <div>
                                <div class="ClientDetails_client-details__field--label__1T_-g">Email</div>
                                <div style="width: 200px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"><span id="MailCliente_ov" title="<?= $cliente['email']; ?>"><?= $cliente['email']; ?></span></div>
                            </div>
                        </div>
                        <div class="ClientDetails_client-details__title__TOX99 mt-2" style="border-top: 1px solid #b1b5c0;">
                            <div class="mt-2">Contatto cliente</div>
                        </div>
                        <div style="display:flex;">
                            <?= (false ? '<button aria-busy="false" class="ClientDetails_client-details__messages-button__3P7Cj me-2 chiama" title="Effettua una chiamata al cliente">
                                <i class="fa-solid fa-phone-alt"></i>
                            </button>' : ''); ?>
                            <button aria-busy="false" class="ClientDetails_client-details__messages-button__3P7Cj me-2 InviaWA_ov" title="Invia messaggio WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            <button aria-busy="false" class="ClientDetails_client-details__messages-button__3P7Cj me-2 mms-mailmodal_campi" data-bs-toggle="modal" data-bs-target="#mms-mailmodal" title="Invia una mail">
                                <i class="far fa-envelope"></i>
                            </button>
                            <button aria-busy="false" class="ClientDetails_client-details__messages-button__3P7Cj ApriMappa_ov" title="Informazioni posizione">
                                <i class="fa-solid fa-map-marker-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="cliente-item">
                    <div class="ClientDetails_client-details__title__TOX99">Informazione ordine </div>
                    <div class="ClientDetails_client-details__content__1kSR_">
                        <div>
                            <div class="ClientDetails_client-details__field--label__1T_-g">ID Prestashop</div>
                            <div><?= '&#5125; <a href="javascript:void(0)" id="IDPrestashop_ov">' .  $ordine['idpresta'] . '</a>' ?></div>
                        </div>
                        <?= ($ordine['idmarketplace'] != '') ? '<div>
                                <div class="ClientDetails_client-details__field--label__1T_-g">Rif. Marketplace</div>
                                <div>&#5125; <a href="javascript:void(0)" class="ApriMarketplace_ov" id="Marketplace_ov">' .  $ordine['idmarketplace'] . '</a></div></div>' : ''; ?>

                        <div>
                            <div class="ClientDetails_client-details__field--label__1T_-g">Piattaforma</div>
                            <div id="Piattaforma_ov"><?= $ordine['piattaforma'] ?></div>
                        </div>
                        <div>
                            <div class="ClientDetails_client-details__field--label__1T_-g">Tipo ordine</div>
                            <div><?= $ordine['tipo'] ?></div>
                        </div>
                        <?= ($ordine['noteo'] != '') ? '<div>
                                <div class="ClientDetails_client-details__field--label__1T_-g" style="color:red;">Note</div>
                                <div>' .  $ordine['noteo'] . '</div></div>' : ''; ?>
                    </div>
                    <div class="ClientDetails_client-details__title__TOX99 mt-2 mb-2" style="border-top: 1px solid #b1b5c0;">
                        <div class="mt-2">Funzioni interne</div>
                    </div>
                    <div style="display:flex;">
                        <button aria-busy="false" class="ClientDetails_client-details__messages-button__3P7Cj me-2 StampaOrdine_ov" idord="<?= $ordine['id']; ?>" title="Stampa ordine">
                            <i class="fa-regular fa-print"></i>
                        </button>
                        <button aria-busy="false" class="ClientDetails_client-details__messages-button__3P7Cj me-2" id="PrenotaChiamata_ov" utente="<?= $session_uname; ?>" idu="<?= $session_idu; ?>" onclick="ApriPrenotaChiamata()" title="Prenota una chiamata">
                            <i class="fa-regular fa-calendar-check"></i>
                        </button>
                        <button aria-busy="false" class="ClientDetails_client-details__messages-button__3P7Cj me-2 mms-mailmodal_campi_int" title="Invia una mail" data-bs-toggle="modal" data-bs-target="#mms-mailmodal">
                            <i class="fa-regular fa-at"></i>
                        </button>
                        <button aria-busy="false" class="ClientDetails_client-details__messages-button__3P7Cj me-2" title="Gestisci ticket" onclick="ApriSegnalazione(<?= $ordine['id']; ?>)">
                            <i class="fa-regular fa-file-chart-column"></i>
                        </button>
                    </div>
                </div>
                <div class="cliente-item">
                    <div class="spedizione-titolo">Spedizione </div>
                    <div class="spedizione-contenuto"><label for="zhhxsbwp" class="TextField_field__2IBfg" data-empty="false" data-disabled="false">
                            <div class="spedizione-labeltext">Corriere</div><input class="spedizione-input" id="zhhxsbwp" type="text" value="<?= $ordine['corriere'] ?>" disabled>
                        </label><label for="rlzk3mqu" class="TextField_field__2IBfg" data-empty="false" data-disabled="false">
                            <div class="spedizione-labeltext">URL di Monitoraggio </div><input class="spedizione-input" id="rlzk3mqu" type="text" value="" disabled>
                        </label><label for="mdilkrwh" class="TextField_field__2IBfg" data-empty="false" data-disabled="false">
                            <div class="spedizione-labeltext">Numero di tracking</div><input class="spedizione-input" id="mdilkrwh" type="text" value="<?= $ordine['tracking']; ?>" disabled>
                        </label>
                    </div>
                    <div style="display:flex;">
                        <button aria-busy="false" class="ClientDetails_client-details__messages-button__3P7Cj me-2 TracciaSpedizione">
                            <i class="fa-solid fa-truck-loading"></i>
                        </button>

                    </div>
                </div>
            </div>
            <div></div>
            <div class="dettagliordini-prodotti">
                <div class="Items_sectionTitle__3uK82">Prodotti</div>
                <?php
                $i = 0;
                $sql2 = "SELECT p.ID, p.nome, p.sku, r.quantita, p.DataDisponibilita, p.disponibilita FROM neg_magazzino p INNER JOIN (donl_ordini o INNER JOIN neg_relpo r ON o.ID = r.IDO) ON p.ID = r.IDP WHERE r.IDO=" . $idordine;
                $result2 = $conn->query($sql2);
                if ($result2->num_rows >= 1) {
                    while ($row2 = $result2->fetch_assoc()) {
                        if ($i == 0) {
                            echo '<div class="Items_productDetails__2u3lv">';
                        }

                        $disp = '';
                        if ($row2['DataDisponibilita'] != '0000-00-00') {

                            $oggi = strtotime("now");
                            $dataDisp = strtotime($row2['DataDisponibilita']);
                            if ($dataDisp > $oggi) {
                                $res = explode("-", $row2['DataDisponibilita']);
                                $disp = '<br/><span class="font-italic text-bg-yellow">Disponibilità prodotto: <span class="text-danger font-weight-bold">' . $res[2] . '/' . $res[1] . '/' . $res[0] . '</span></span>';
                            }
                        }

                        if ($row2['quantita'] < $row2['disponibilita']) {
                            $dispo = 'ok';
                        } else if ($row2['quantita'] == $row2['disponibilita']) {
                            $dispo = 'man';
                        } else if ($row2['quantita'] > $row2['disponibilita']) {
                            $dispo = 'no';
                        }

                        echo '
                            <div class="Items_termList__3l1Kt">
                                <div class="Items_product__2NQ_G">
                                    <div class="Items_actions__2cWk0">
                                        <div class="Items_imageContainer__3Zzle" style="height: auto; width: 128px;"><img height="auto" width="128"  src="https://portalescifo.it/upload/image/p/' . $row2['ID'] . '.jpg" alt="' . $row2['nome'] . '" ></div>
                                    </div>
                                    <div class="Items_productDescription__2O_ci">
                                        <div>' . $row2['nome'] . '   <a class="text-primary" href="javascript:void(0);" onclick="InfoOrdineRicambi_ov(' . $row2['ID'] . ', ' . $ordine['id'] . ')"><i class="fa-solid fa-info-circle "></i></a>' . $disp . '</div>
                                        <div class="Items_productDetails__2u3lv">
                                            <div class="Items_term__2Tuen"><span>Riferimento</span><strong><span><a class="text-info font-weight-bold prodottoord" idpr="' . $row2['ID'] . '" href="javascript:void(0);">' . $row2['sku'] . '</a></span></strong></div>
                                        </div>    
                                        <div class="Items_productDetails__2u3lv">    
                                            <div class="Items_term__2Tuen"><span>Quantità</span><strong><span>' . $row2['quantita'] . '</span></strong></div>
                                        </div>
                                        <div class="Items_productDetails__2u3lv">
                                            <div><span>Disponibilità    </span><span class="status-' . $dispo . '">●</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        if ($i == 1) {
                            echo '</div>';
                            $i = 0;
                        } else {
                            $i = ($i + 1);
                        }
                    }
                }



                ?>

            </div>

            <div class="OrderDetailsPanel_priceBreakdown__K1e88">
                <div class="OrderDetailsPanel_termList__1037Q">
                    <div class="OrderDetailsPanel_term__2ZSh5"><span>Costi di spedizione</span><span data-currency="€">€ <?= $spedcorr['prezzoinserito']; ?></span></div><strong class="OrderDetailsPanel_term__2ZSh5"><span>Totale pagato dal cliente</span><span data-currency="€">€ <?= $ordine['importo']; ?></span></strong>
                </div>
            </div>
        </div>
    </div>
</body>
<div class="modal fade" id="mailmodal" tabindex="-1" role="dialog" aria-labelledby="mailmodallbl" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 600px;">
            <div class="modal-body">
                <div class="row mb-4 ml-2">
                    <h5 class="modal-title" id="mailmodallbl"><b>Invia una mail</b></h5>
                </div>
                <div class="row me-2 ml-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="bmd-label">Destinatario</label>
                            <input list="mail_dest-list" id="mail_dest" type="text" class="form-control" autocomplete="off">
                            <datalist id="mail_dest-list">
                                <option value="assistenza">
                                <option value="eprice">
                                <option value="info">
                                <option value="leroymerlin">
                                <option value="manomano">
                                <option value="ricambi">
                                <option value="amministrazione">
                                <option value="spedizioni">
                            </datalist>
                        </div>
                    </div>
                </div>
                <div class="row me-2 ml-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="bmd-label">Oggetto</label>
                            <input id="mail_ogg" type="text" class="form-control" value="" placeholder="Digita qui il tuo oggetto" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="row me-2 ml-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="bmd-label">Messaggio</label>
                            <textarea id="mail_mes" type="text" class="form-control" value="" rows="10" placeholder="Digita qui il tuo messaggio"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row me-2 ml-2">
                    <div class="col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="mail_cliente">
                            <label class="form-check-label" for="mail_cliente">Invia notifica anche al cliente</label>
                        </div>
                    </div>
                </div>
                <div class="row me-2 ml-2">
                    <div class="col-md-12">
                        <label for="attachmentButton" type="button" class="btn btn-solid btn-solid-default" aria-describedby="upload-tooltip">
                            <span><span aria-hidden="true" class="fa-solid fa-paperclip"></span></span>
                            <input type="file" id="attachmentButton" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" style="width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; position: absolute; z-index: -1;">
                            <span>Allega un file</span>
                        </label>
                    </div>
                </div>
                <div class="row mt-2">
                    <button type="button" class="btn btn-secondary ml-auto" data-dismiss="modal">Chiudi</button>
                    <button type="button" class="btn btn-primary ml-2 me-2 mail_send">Invia</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    CaricaVisualizzaOrdine(<?= $ordine['id'] ?>);


    $(document).on('click', '.mms-mailmodal_campi', function() {
        $('#mms-mail_dest').val('<?= $cliente['email'] ?>')
        $('#mms-mail_dest').prop('readonly', true)
    });

    $(document).on('click', '.mms-mailmodal_campi_int', function() {
        var check = document.getElementById('mms_mail_cliente').checked;
        var piatt = $('#Piattaforma_ov').text();
        $('#Impostazioni_mail').prop('hidden', false)
        $('#mms-mail_dest').prop('readonly', false)
        $('#mms-mail_dest').val('')
        $('#mms-mail_mitt').prop('readonly', true)
        $('#mms-mail_mitt').val('support')
        $('#mms-idordine').val($('#OrdineID').text())
        $('#mms-mail_mess-supp').val('Hai ricevuto una mail da parte di: <b><?= $session_uname; ?></b><br />In merito all\'ordine n°: <?= $ordine['riferimento']; ?><br /><br />');


        if ((check == true) && (piatt == 'Sito' || piatt == 'Altro' || piatt == 'eBay')) {
            MailCliente_Invio('<?= $cliente['cliente']; ?>', <?= $ordine['id'] ?>)
        }
    });

    function MailCliente_Invio(cl, ido) {
        //INVIA MAIL AL CLIENTE
        var corpo_cl =
            'Gentile ' + cl + '<br /><br /><br />' +
            'Grazie di aver contattato l\'Assistenza clienti di ScifoStore.<br /><br />' +
            'Siamo consapevoli del fatto che necessiti di una rapida risposta e, in genere, rispondiamo entro 24, 36 ore. Tuttavia, se il problema che ci hai posto, e il numero di richieste ricevute sarà elevato, potresti dover attendere fino a 72 ore per ricevere una risposta.<br /><br />' +
            'Inviare più di un messaggio riguardante lo stesso argomento potrebbe rallentare ulteriormente il processo di assistenza. In ogni caso, faremo tutto il possibile per risponderti quanto prima.<br /><br />' +
            'Ti ringraziamo per la pazienza e la collaborazione dimostrate.';

        var data_cl = new FormData();
        data_cl.append("idord", ido);
        data_cl.append("iduser", '<?= $session_idu; ?>');
        data_cl.append("mittente", 'support');
        data_cl.append("indirizzodest", '<?= $cliente['email'] ?>');
        data_cl.append("oggetto", 'Prenotazione assistenza');
        data_cl.append("corpo", corpo_cl);
        $.ajax({
            url: currentURL + "assets/mail/invio_mail.php",
            method: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: data_cl
        });

    }

    $(document).on('click', '.on_stato', function() {
        if ($('#pulsantiera').prop('hidden') == true) {
            $('#pulsantiera').prop('hidden', false)
        } else {
            $('#pulsantiera').prop('hidden', true)
        }
    });

    $(document).on('click', '.TracciaSpedizione', function() {
        let ApriPagina = function ApriPagina() {
            window.open($('#rlzk3mqu').val(), 'Tracciatura ordine', 'width=900, height=600, resizable, status, scrollbars=1, location')
        };
        let Notifica = function Notifica() {
            Toast.fire({
                icon: 'info',
                title: 'Nessun codice da tracciare'
            })
        };
        ($('#rlzk3mqu').val() == '' ? docReady(Notifica) : docReady(ApriPagina));
    });

    $("#ch_stato").change(function() {
        Toast.fire({
            icon: 'error',
            title: 'Sviluppo in corso!'
        })

        switch ($('#ch_stato option:selected').val()) {
            case 0:
                var id = '<?= $ordine['id']; ?>';
                var idps = '<?= $ordine['idpresta']; ?>';
                var piatt = '<?= $ordine['piattaforma'] ?>';
                var track = '<?= $ordine['tracking'] ?>';
                var idm = '<?= $ordine['idmarketplace'] ?>';
                var corr = '<?= $ordine['corriere'] ?>';
                $.post(currentURL + "assets/inc/modifica_ordine.php", {
                    modifica: 'evadiordine',
                    idordine: id
                }, function(response) {
                    if (response == 'ok') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Spedizione inviata con successo'
                        })
                        aggiornatracking(idps)
                        if (piatt == "ManoMano") {
                            manomano(idm, track, corr)
                        }
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Errore: ' + response
                        })
                    }
                })
                return;
            case 1:
                var id = '<?= $ordine['id']; ?>';
                var idps = '<?= $ordine['idpresta']; ?>';
                $.ajax({
                    url: currentURL + "assets/inc/modifica_ordine.php",
                    method: "POST",
                    data: {
                        modifica: "bloccasped",
                        idordine: id
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response
                        })
                        modrimbsospordine(idps, 43);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Errore nel gestire la richiesta'
                        })

                    }
                });
                return;
            case 2:
                var id = '<?= $ordine['id']; ?>';
                var idps = '<?= $ordine['idpresta']; ?>';
                $.ajax({
                    url: currentURL + "assets/inc/modifica_ordine.php",
                    method: "POST",
                    data: {
                        modifica: "rimborsped",
                        idordine: id
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response
                        })
                        modrimbsospordine(idps, 7);
                        inviomail(id);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Errore nel gestire la richiesta'
                        })

                    }
                });
                return;
            case 3:
                var id = '<?= $ordine['id']; ?>';
                var idps = '<?= $ordine['idpresta']; ?>';
                $.ajax({
                    url: currentURL + "assets/inc/modifica_ordine.php",
                    method: "POST",
                    data: {
                        modifica: "rimborsparzped",
                        idordine: id
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response
                        })
                        modrimbsospordine(idps, 39);
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Errore nel gestire la richiesta'
                        })

                    }
                });
                return;
            case 4:
                var id = '<?= $ordine['id']; ?>';
                $.ajax({
                    url: currentURL + "assets/inc/modifica_ordine.php",
                    method: "POST",
                    data: {
                        modifica: "rientro",
                        idordine: id
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response
                        })

                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Errore nel gestire la richiesta'
                        })

                    }
                });
                return;
            case 5:
                var piattaforma = '<?= $ordine['piattaforma'] ?>';
                var track = '<?= $ordine['tracking'] ?>';
                var idm = '<?= $ordine['idmarketplace'] ?> ';
                var corr = '<?= $ordine['corriere'] ?> ';
                if (piattaforma == "ManoMano") {
                    manomano(idm, track, corr)
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Non è un ordine ManoMano'
                    })
                }
                return;
        }
    });



    // ========== FUNZIONE DI STATO ORDINE ==========
    function modrimbsospordine(idordine, idstato) {
        $.ajax({
            url: currentURL + "assets/inc/prestashop-control.php",
            method: "POST",

            data: {
                azione: 'inviaordine',
                idordine: idordine,
                idstato: idstato
            },
            success: function() {
                Toast.fire({
                    icon: 'success',
                    title: 'Stato ordine aggiornato su PrestaShop'
                })
            },
            error: function() {}

        });
    }


    function aggiornatracking(idordine) {
        $.ajax({
            url: currentURL + "assets/inc/prestashop-control.php",
            method: "POST",

            data: {
                azione: 'inviaordine',
                idordine: idordine,
                idstato: 4
            },
            success: function() {
                Toast.fire({
                    icon: 'success',
                    title: 'Tracking prestashop aggiornato con successo'
                })
            },
            error: function() {}

        });
    }

    function manomano(idordine, tracking, corriere) {
        var trkurl = '';
        if (corriere == 'TNT') {
            trkurl = "https://www.tnt.it/tracking/getTrack?WT=1&ConsigNos=" + tracking;
        } else if (corriere == 'BRT' || corriere == 'BRT 1' || corriere == 'BRT 2') {
            trkurl = "https://vas.brt.it/vas/sped_det_show.hsm?referer=sped_numspe_par.htm&ChiSono=" + tracking;
            corriere = "Bartolini%20(parcel%20ID)"
        } else if (corriere == 'GLS') {
            trkurl = "https://www.gls-italy.com/?option=com_gls&view=track_e_trace&mode=search&numero_spedizione=" + tracking + "&tipo_codice=nazionale";
        } else if (corriere == 'DHL') {
            trkurl = "https://www.dhl.com/it-it/home/tracking/tracking-express.html?submit=1&tracking-id=" + tracking;
            corriere = "DHL%20(IT)"
        } else if (corriere == 'Poste Italiane') {
            trkurl = "https://www.poste.it/cerca/index.html#/risultati-spedizioni/" + tracking;
        } else if (corriere == 'SDA') {
            trkurl = "https://www.sda.it/wps/portal/Servizi_online/dettaglio-spedizione?locale=it&tracing.letteraVettura=" + tracking;
        } else if (corriere == 'SAVISE') {
            trkurl = "https://www.oneexpress.it/it/cerca-spedizione/";
        }


        var settings = {
            "url": "https://partnersapi.manomano.com/orders/v1/shippings",
            "method": "GET",
            "timeout": 0,
            "headers": {
                "x-api-key": "7P2mtEpyvOpTFsDcD8FHVtSdwe2xo6MS",
                "x-thirdparty-name": "PixelSmart_v3"
            },
            "data": "[\n  {\n    \"carrier\": \"" + corriere + "\",\n    \"order_reference\": \"" + idordine + "\",\n    \"seller_contract_id\": 3497732,\n    \"tracking_number\": \"" + tracking + "\",\n    \"tracking_url\": \"" + trkurl + "\",\n ]",
        };

        $.ajax(settings).done(function(response) {
            console.log(response);
            Toast.fire({
                icon: 'warning',
                title: 'Controllare l\'aggiornamento con ManoMano'
            })
        });
        // var href = "https://ws.monechelle.com/?login=vivai.scifo&password=3497732IT2K21&method=create_shipping&order_ref=" + idordine + "&tracking_number=" + tracking + "&tracking_url=" + trkurl + "&carrier=" + corriere
        // $.ajax({
        //     type: "GET",
        //     dataType: 'html',
        //     url: href,
        //     success: function(res) {
        //         Toast.fire({
        //             icon: 'success',
        //             title: 'Aggiornamento confermato con ManoMano'
        //         })
        //     },
        //     error: function(resu) {
        //         Toast.fire({
        //             icon: 'warning',
        //             title: 'Richiesto a ManoMano'
        //         })
        //     }
        // });
    }

    function ApriSegnalazione(ido) {
        $('#tms-modalsys').load(currentURL + 'assets/page/segnalazione.html', function() {
            $('#tms-modalsys').modal('show')
            $('#formvisass').load(currentURL + 'ordini/caricafile.php?id=' + ido)
            docReady(dropzoneInit)
            CaricaSegnalazione();
            docReady(tinymceInit('descrizione_tms'))
            tinymce.get('descrizione_tms').remove()
            docReady(tinymceInit('descrizione_tms'))
            $('#Upload_Allegati').attr('action', 'segnalazione/' + ido)
        })
    }
    (function() {
        $("#tms-modalsys").on("hidden.bs.modal", function() {
            $(this).removeData();
        });
        $("#prch-mailmodal").on("hidden.bs.modal", function() {
            $(this).removeData();
        });
    });

    function ApriPrenotaChiamata() {
        $('#prch-mailmodal').load(currentURL + 'assets/page/prenotachiamata.html', function() {
            $('#prch-mailmodal').modal('show')
            docReady(tinymceInit('prch_note'))
            tinymce.get('prch_note').remove()
            docReady(tinymceInit('prch_note'))
        })
    }
</script>

</html>