<?php
if (unlink($_POST['file'])) {
    echo 'ok';
} else {
    print_r(error_get_last());
}
exit;
