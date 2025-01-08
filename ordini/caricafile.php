<?php
function dir_list($directory = FALSE)
{
    $files = array();
    if ($handle = opendir("./" . $directory)) {
        while ($file = readdir($handle)) {
            if ($file != "." & $file != "..") $files[] = $file;
        }
    }
    closedir($handle);
    reset($files);
    sort($files);
    reset($files);
    $d = 0;
    $f = 0;
    while (list($key, $value) = each($files)) {
        $f++;
        echo '
    <div class="border px-2 rounded-3 d-flex flex-between-center bg-white dark__bg-1000 my-1 fs--1 ms-3" id="tms_' . $f . '">
        <span class="fs-1 fa-solid fa-paperclip"></span>  
        <span style="cursor:pointer;" class="ms-2 ApriAllegato_tms" all="' . $value . '" idp="' . $_GET['id'] . '">' . $value . '</span>
        <a class="text-300 p-1 ms-3" href="javascript:void()" title="" onclick="EliminaAllegato_tms(\'' . $value . '\', \'' . $f . '\',  \'' . $_GET['id'] . '\')">
            <span class="fs-1 fa-solid fa-trash"></span>  
        </a>
    </div>';
    }
    if (!$f) $f = "0";
}
dir_list('../upload/segnalazione/' . $_GET['id'] . '/');
