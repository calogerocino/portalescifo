<?php
error_reporting(0);
include("assets/inc/database.php");

// INIZIO METEO
$googleApiUrl = "https://api.openweathermap.org/data/2.5/weather?id=2525448&appid=f1fccf3ce50cc2cc826760928366d05d&lang=it&units=metric";

$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

curl_close($ch);
$data = json_decode($response);


?>
<div class="row mb-3">
    <div class="col">
        <div class="card bg-100 shadow-none border">
            <div class="row gx-0 flex-between-center">
                <div class="col-sm-auto d-flex align-items-center"><img class="ms-n2" src="assets/img/illustrations/crm-bar-chart.png" alt="" width="90">
                    <div>
                        <h6 class="text-primary fs--1 mb-0">Benvenuto su </h6>
                        <h4 class="text-primary fw-bold mb-0">Pixel <span class="text-info fw-medium">Smart</span></h4>
                    </div><img class="ms-n4 d-md-none d-lg-block" src="assets/img/illustrations/crm-line-chart.png" alt="" width="150">
                </div>
                <div class="col-md-auto p-3">
                    <form class="row align-items-center g-3">
                        <div class="col-auto">
                            <h6 class="text-700 mb-0">Cerca per data: </h6>
                        </div>
                        <div class="col-md-auto position-relative"><input class="form-control form-control-sm datetimepicker ps-4 flatpickr-input" id="CRMDateRange" type="text" data-options="{" mode":"range","dateFormat":"M d","disableMobile":true , "defaultDate" : ["Sep 12", "Sep 19" ] }" readonly="readonly"><svg class="svg-inline--fa fa-calendar-alt fa-w-14 text-primary position-absolute top-50 translate-middle-y ms-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="calendar-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                <path fill="currentColor" d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm320-196c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM192 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM64 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z"></path>
                            </svg>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-xxl-6 col-xl-12">
        <div class="row g-3">
            <div class="col-12">
                <div class="card bg-transparent-50 overflow-hidden">
                    <div class="card-header position-relative">
                        <div class="bg-holder d-none d-md-block bg-card z-index-1" style="background-image:url(assets/img/illustrations/ecommerce-bg.png);background-size:230px;background-position:right bottom;z-index:-1;"></div>
                        <div class="position-relative z-index-2">
                            <div>
                                <h3 class="text-primary mb-1">Bentornato, <?php echo $_SESSION['session_nome']; ?>!</h3>
                                <p>Ecco cosa sta succedendo oggi al tuo negozio</p>
                            </div>
                            <div class="d-flex py-3">
                                <div class="pe-3">
                                    <p class="text-600 fs--1 fw-medium">Ordini di oggi </p>
                                    <h4 class="text-800 mb-0 a1">0</h4>
                                </div>
                                <div class="ps-3">
                                    <p class="text-600 fs--1">Vendite totali </p>
                                    <h4 class="text-800 mb-0 a2">€ 0,00</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="mb-0 list-unstyled">
                            <li class="alert mb-0 rounded-0 py-3 px-card alert-warning border-x-0 border-top-0">
                                <div class="row flex-between-center">
                                    <div class="col">
                                        <div class="d-flex">
                                            <svg class="svg-inline--fa fa-circle fa-w-16 mt-1 fs--2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                            </svg>
                                            <p class="fs--1 ps-2 mb-0"><strong class="a3"></strong> sono fuori stock</p>
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex align-items-center"><a class="alert-link fs--1 fw-medium b1" href="javascript:void()">Visualizza prodotti<svg class="svg-inline--fa fa-chevron-right fa-w-10 ms-1 fs--2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path>
                                            </svg></a></div>
                                </div>
                            </li>
                            <li class="alert mb-0 rounded-0 py-3 px-card greetings-item border-top border-x-0 border-top-0">
                                <div class="row flex-between-center">
                                    <div class="col">
                                        <div class="d-flex">
                                            <svg class="svg-inline--fa fa-circle fa-w-16 mt-1 fs--2 text-primary" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                            </svg>
                                            <p class="fs--1 ps-2 mb-0"><strong class="a4"></strong> sono stati importati</p>
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex align-items-center"><a class="alert-link fs--1 fw-medium b2" href="javascript:void()">Visualizza ordini<svg class="svg-inline--fa fa-chevron-right fa-w-10 ms-1 fs--2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path>
                                            </svg></a></div>
                                </div>
                            </li>
                            <li class="alert mb-0 rounded-0 py-3 px-card greetings-item border-top  border-0">
                                <div class="row flex-between-center">
                                    <div class="col">
                                        <div class="d-flex">
                                            <svg class="svg-inline--fa fa-circle fa-w-16 mt-1 fs--2 text-primary" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                                            </svg>
                                            <p class="fs--1 ps-2 mb-0"><strong class="a5"></strong> devono essere spediti</p>
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex align-items-center"><a class="alert-link fs--1 fw-medium b3" href="javascript:void()">Visualizza ordini<svg class="svg-inline--fa fa-chevron-right fa-w-10 ms-1 fs--2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path>
                                            </svg></a></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-md-100 ecommerce-card-min-width">
                            <div class="card-header pb-0">
                                <h6 class="mb-0 mt-2 d-flex align-items-center">Ultimi 7 giorni<span class="ms-1 text-400" id="PoPM-" tt="Calcolato in base alle vendite degli ultimi 7 giorni!"><svg class="svg-inline--fa fa-question-circle fa-w-16" data-fa-transform="shrink-1" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;">
                                            <g transform="translate(256 256)">
                                                <g transform="translate(0, 0)  scale(0.9375, 0.9375)  rotate(0 0 0)">
                                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z" transform="translate(-256 -256)"></path>
                                                </g>
                                            </g>
                                        </svg></span></h6>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-end">
                                <div class="row">
                                    <div class="col">
                                        <p class="font-sans-serif lh-1 mb-1 fs-2 a6">€ 0,00</p><span class="badge badge-soft-success rounded-pill fs--2">+0%</span>
                                    </div>
                                    <div class="col-auto ps-0">
                                        <div class="echart-bar-weekly-sales h-100 echart-bar-weekly-sales-smaller-width" style="-webkit-tap-highlight-color: transparent; user-select: none; position: relative;" _echarts_instance_="ec_1635504762158">
                                            <div style="position: relative; width: 104px; height: 51px; padding: 0px; margin: 0px; border-width: 0px;"><canvas data-zr-dom-id="zr_0" width="104" height="51" style="position: absolute; left: 0px; top: 0px; width: 104px; height: 51px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div>
                                            <div class=""></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-md-100 h-100">
                            <div class="card-body">
                                <div class="row h-100 justify-content-between g-0">
                                    <div class="col-5 col-sm-6 col-xxl pe-2">
                                        <h6 class="mt-1">Prodotti pubblicati<span class="ms-1 text-400" id="PoPM-" tt="Percentuale di prodotti pubblicati sui Marketplace!"><svg class="svg-inline--fa fa-question-circle fa-w-16" data-fa-transform="shrink-1" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;">
                                            <g transform="translate(256 256)">
                                                <g transform="translate(0, 0)  scale(0.9375, 0.9375)  rotate(0 0 0)">
                                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z" transform="translate(-256 -256)"></path>
                                                </g>
                                            </g>
                                        </svg></span></h6>
                                        <div class="fs--2 mt-3">
                                            <div class="d-flex flex-between-center mb-1">
                                                <div class="d-flex align-items-center"><span class="dot bg-primary"></span><span class="fw-semi-bold">Online</span></div>
                                            </div>
                                            <div class="d-flex flex-between-center mb-1">
                                                <div class="d-flex align-items-center"><span class="dot bg-info"></span><span class="fw-semi-bold">Offline</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto position-relative">
                                        <div class="echart-bandwidth-saved" style="-webkit-tap-highlight-color: transparent; user-select: none; position: relative;">
                                            <div style="position: relative; width: 106px; height: 106px; padding: 0px; margin: 0px; border-width: 0px;"><canvas data-zr-dom-id="zr_0" width="106" height="106" style="position: absolute; left: 0px; top: 0px; width: 106px; height: 106px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div>
                                            <div class=""></div>
                                        </div>
                                        <div class="position-absolute top-50 start-50 translate-middle text-dark fs-2 a7">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-md-100 h-100">
                            <div class="card-body">
                                <div class="row h-100 justify-content-between g-0">
                                    <div class="col-5 col-sm-6 col-xxl pe-2">
                                        <h6 class="mt-1">Marketplace<span class="ms-1 text-400" id="PoPM-" tt="Distribuzione ordini dei vari marketplace!"><svg class="svg-inline--fa fa-question-circle fa-w-16" data-fa-transform="shrink-1" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;">
                                            <g transform="translate(256 256)">
                                                <g transform="translate(0, 0)  scale(0.9375, 0.9375)  rotate(0 0 0)">
                                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z" transform="translate(-256 -256)"></path>
                                                </g>
                                            </g>
                                        </svg></span></h6>
                                        <div class="fs--2 mt-3">
                                            <div class="d-flex flex-between-center mb-1">
                                                <div class="d-flex align-items-center"><span class="dot bg-primary"></span><span class="fw-semi-bold">Sito</span></div>
                                                <div class="d-xxl-none">0%</div>
                                            </div>
                                            <div class="d-flex flex-between-center mb-1">
                                                <div class="d-flex align-items-center"><span class="dot bg-info"></span><span class="fw-semi-bold">ManoMano</span></div>
                                                <div class="d-xxl-none">0%</div>
                                            </div>
                                            <div class="d-flex flex-between-center mb-1">
                                                <div class="d-flex align-items-center"><span class="dot bg-warning"></span><span class="fw-semi-bold">eBay</span></div>
                                                <div class="d-xxl-none">0%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto position-relative">
                                        <div class="echart-product-share" _echarts_instance_="ec_1635504762160" style="-webkit-tap-highlight-color: transparent; user-select: none; position: relative;">
                                            <div style="position: relative; width: 106px; height: 106px; padding: 0px; margin: 0px; border-width: 0px;"><canvas data-zr-dom-id="zr_0" width="106" height="106" style="position: absolute; left: 0px; top: 0px; width: 106px; height: 106px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div>
                                            <div class=""></div>
                                        </div>
                                        <div class="position-absolute top-50 start-50 translate-middle text-dark fs-2 a8">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header pb-0">
                                <h6 class="mb-0 mt-2 d-flex align-items-center">Ultimi 4 mesi<span class="ms-1 text-400" id="PoPM-" tt="Totale importi ordini ultimi 4 mesi!"><svg class="svg-inline--fa fa-question-circle fa-w-16" data-fa-transform="shrink-1" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;">
                                            <g transform="translate(256 256)">
                                                <g transform="translate(0, 0)  scale(0.9375, 0.9375)  rotate(0 0 0)">
                                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z" transform="translate(-256 -256)"></path>
                                                </g>
                                            </g>
                                        </svg></span></h6>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col">
                                        <p class="font-sans-serif lh-1 mb-1 fs-2 a9">0</p>
                                        <div class="badge badge-soft-primary rounded-pill fs--2"><svg class="svg-inline--fa fa-caret-up fa-w-10 me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path>
                                            </svg>0%</div>
                                    </div>
                                    <div class="col-auto ps-0">
                                        <div class="total-order-ecommerce" style="-webkit-tap-highlight-color: transparent; user-select: none; position: relative;">
                                            <div style="position: relative; width: 144px; height: 64px; padding: 0px; margin: 0px; border-width: 0px;"><canvas data-zr-dom-id="zr_0" width="144" height="64" style="position: absolute; left: 0px; top: 0px; width: 144px; height: 64px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div>
                                            <div class=""></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6 col-xl-12">
        <div class="card py-3 mb-3">
            <div class="card-body py-3">
                <div class="row g-0">
                    <div class="col-6 col-md-4 border-200 border-bottom border-end pb-4">
                        <h6 class="pb-1 text-700">Oggetti venduti <span class="ms-1 text-400" id="PoPM-" tt="Oggetti venduti nell'ultimo mese!"><svg class="svg-inline--fa fa-question-circle fa-w-16" data-fa-transform="shrink-1" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;">
                                            <g transform="translate(256 256)">
                                                <g transform="translate(0, 0)  scale(0.9375, 0.9375)  rotate(0 0 0)">
                                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z" transform="translate(-256 -256)"></path>
                                                </g>
                                            </g>
                                        </svg></span></h6>
                        <p class="font-sans-serif lh-1 mb-1 fs-2 a10">0 </p>
                        <div class="d-flex align-items-center">
                            <h6 class="fs--1 text-500 mb-0 a11">0 </h6>
                            <h6 class="fs--2 ps-3 mb-0 text-primary"><svg class="svg-inline--fa fa-caret-up fa-w-10 me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path>
                                </svg>0%</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 border-200 border-md-200 border-bottom border-md-end pb-4 ps-3">
                        <h6 class="pb-1 text-700">Ordini in ritardo <span class="ms-1 text-400" id="PoPM-" tt="Ordini che sono in ritardo di oltre 15 giorni!"><svg class="svg-inline--fa fa-question-circle fa-w-16" data-fa-transform="shrink-1" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;">
                                            <g transform="translate(256 256)">
                                                <g transform="translate(0, 0)  scale(0.9375, 0.9375)  rotate(0 0 0)">
                                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z" transform="translate(-256 -256)"></path>
                                                </g>
                                            </g>
                                        </svg></span></h6>
                        <p class="font-sans-serif lh-1 mb-1 fs-2 a12">0 </p>
                        <div class="d-flex align-items-center">
                            <h6 class="fs--2 ps-3 mb-0 text-warning"><svg class="svg-inline--fa fa-caret-up fa-w-10 me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path>
                                </svg>0%</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 border-200 border-bottom border-end border-md-end-0 pb-4 pt-4 pt-md-0 ps-md-3">
                        <h6 class="pb-1 text-700">Rimborsi <span class="ms-1 text-400" id="PoPM-" tt="Rimborsi effettuati questo mese rispetto al mese precedente!"><svg class="svg-inline--fa fa-question-circle fa-w-16" data-fa-transform="shrink-1" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;">
                                            <g transform="translate(256 256)">
                                                <g transform="translate(0, 0)  scale(0.9375, 0.9375)  rotate(0 0 0)">
                                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z" transform="translate(-256 -256)"></path>
                                                </g>
                                            </g>
                                        </svg></span></h6>
                        <p class="font-sans-serif lh-1 mb-1 fs-2 a13">€ 0,00 </p>
                        <div class="d-flex align-items-center">
                            <h6 class="fs--1 text-500 mb-0 a14">0 </h6>
                            <h6 class="fs--2 ps-3 mb-0 text-success"><svg class="svg-inline--fa fa-caret-up fa-w-10 me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path>
                                </svg>0%</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 border-200 border-md-bottom-0 border-end pt-4 pb-md-0 ps-md-3">
                        <h6 class="pb-1 text-700">Spese spedizione <span class="ms-1 text-400" id="PoPM-" tt="Spese di spedizioni sostenute dai clienti e totale pacchi spediti!"><svg class="svg-inline--fa fa-question-circle fa-w-16" data-fa-transform="shrink-1" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;">
                                            <g transform="translate(256 256)">
                                                <g transform="translate(0, 0)  scale(0.9375, 0.9375)  rotate(0 0 0)">
                                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z" transform="translate(-256 -256)"></path>
                                                </g>
                                            </g>
                                        </svg></span></h6>
                        <p class="font-sans-serif lh-1 mb-1 fs-2 a15">€ 0,00 </p>
                        <div class="d-flex align-items-center">
                            <h6 class="fs--1 text-500 mb-0 a16">0 </h6>
                            <h6 class="fs--2 ps-3 mb-0 text-success"><svg class="svg-inline--fa fa-caret-up fa-w-10 me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path>
                                </svg>0%</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 border-200 border-md-bottom-0 border-end pt-4 pb-md-0 ps-md-3">
                        <h6 class="pb-1 text-700">Costi spedizione <span class="ms-1 text-400" id="PoPM-" tt="Totale costi di spedizione da sostenere ai corrieri!"><svg class="svg-inline--fa fa-question-circle fa-w-16" data-fa-transform="shrink-1" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="" style="transform-origin: 0.5em 0.5em;">
                                            <g transform="translate(256 256)">
                                                <g transform="translate(0, 0)  scale(0.9375, 0.9375)  rotate(0 0 0)">
                                                    <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z" transform="translate(-256 -256)"></path>
                                                </g>
                                            </g>
                                        </svg></span></h6>
                        <p class="font-sans-serif lh-1 mb-1 fs-2 a17">€ 0,00 </p>
                        <div class="d-flex align-items-center">
                            <h6 class="fs--2 ps-3 mb-0 text-danger"><svg class="svg-inline--fa fa-caret-up fa-w-10 me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path>
                                </svg>0%</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 pb-0 pt-4 ps-3">
                        <div class="row g-0 align-items-center">
                            <div class="col">
                                <div class="d-flex align-items-center"><img src="https://openweathermap.org/img/w/<?php echo $data->weather[0]->icon ?>.png" alt="Previsioni meteo">
                                    <div>
                                        <h6 class="pb-1 text-700">San Cataldo (CL)</h6>
                                        <div class="fs--2 fw-semi-bold">
                                            <div class="text-warning"><?php echo $data->weather[0]->description ?></div>Vento <?php echo $data->wind->speed ?> km/h
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-center ps-2">
                                <div class="fs-4 fw-normal font-sans-serif text-primary mb-1 lh-1"><?php echo $data->main->temp ?> °</div>
                                <div class="fs--1 text-800"><?php echo $data->main->temp_min ?> ° / <?php echo $data->main->temp_max ?> °</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row flex-between-center g-0">
                    <div class="col-auto me-5">
                        <h6 class="mb-0">Vendite totali</h6>
                    </div>
                    <div class="col-auto d-flex ms-3 me-auto">
                      <div class="form-check mb-0 d-flex"><input class="form-check-input form-check-input-primary" id="ecommerceThisYear" type="checkbox" checked="checked"><label class="form-check-label ps-2 fs--2 text-600 mb-0" for="ecommerceLastMonth">Anno attuale</label></div>
                      <div class="form-check mb-0 d-flex ps-0 ps-md-3"><input class="form-check-input ms-2 form-check-input-warning opacity-75" id="ecommercePrevYear" type="checkbox" checked="checked"><label class="form-check-label ps-2 fs--2 text-600 mb-0" for="ecommercePrevYear">Anno precedente</label></div>
                    </div>
                </div>
            </div>
            <div class="card-body pe-xxl-0">
                <div class="echart-line-total-sales-ecommerce" data-echart-responsive="true" data-options='{"optionOne":"ecommerceThisYear","optionTwo":"ecommercePrevYear"}'  style="-webkit-tap-highlight-color: transparent; user-select: none; position: relative;">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    HomeVar();
</script>