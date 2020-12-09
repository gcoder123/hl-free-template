<?php

require_once "../app/config.php";
require_once "../app/functions.php";

if (substr(URI, 0, 3) === 'api') {
    switch (URI) {
        case 'api/appdata/get':
            echo api_appdata();
        break;

        case 'api/serverdata/get':
            echo api_server_info();
        break;

        default:
            echo api_not_found();
        break;
    }
} else {
    echo view('guest');
}
