<?php

/**
 * Возвращает путь до корневой папки
 * @param string $path
 * @return string
 */
function base_path($path = '') {
    return "{$_SERVER['DOCUMENT_ROOT']}{$path}";
}

/**
 * Возвращает путь до папки "public"
 * @param string $path
 * @return string
 */
function public_path($path = '') {
    return base_path("/public{$path}");
}

/**
 * Возвращает путь до папки "resources"
 * @param string $path
 * @return string
 */
function resources_path($path = '') {
    return base_path("/resources{$path}");
}

/**
 * Возвращает путь до папки "vendors"
 * @param string $path
 * @return string
 */
function vendors_path($path = '') {
    return base_path("/vendors{$path}");
}

/**
 * Возвращает файл предстовления
 * @param $name
 * @return false|string
 */
function view($name) {
    return file_get_contents(
        resources_path("/views/{$name}.view.php")
    );
}

/**
 * Возвращает данные о приложении из файла "appdata.json"
 * @return false|string
 */
function api_appdata() {
    $section = $_GET['section'];
    $appData = file_get_contents(base_path('/appdata.json'));
    $appData = json_decode($appData);

    if (!empty($appData->$section)) {
        return json_encode($appData->$section);
    }

    return json_encode($appData);
}

/**
 * Возвращает JSON объект с сообщение о том, что API не найден
 * @return false|string
 */
function api_not_found() {
    return json_encode([
        'status' => false,
        'message' => 'Запрашиваемый API не найден.'
    ]);
}

function api_server_info() {
    require __DIR__.'/../vendors/SourceQuery/bootstrap.php';

    $query = new \xPaw\SourceQuery\SourceQuery();
    $server = (object) [
        'ip' => explode(':', $_GET['address'])[0],
        'port' => explode(':', $_GET['address'])[1],
        'platform' => $_GET['platform'],
        'timeout' => 1,
        'engine' => null
    ];

    if ($server->platform === 'css') {
        $server->engine = \xPaw\SourceQuery\SourceQuery::SOURCE;
    } else if ($server->platform === 'csgo') {
        $server->engine = \xPaw\SourceQuery\SourceQuery::GOLDSOURCE;
    }

    $query->Connect(
        $server->ip,
        $server->port,
        $server->timeout,
        $server->engine
    );

    return json_encode($query->GetInfo());
}
