<?php
use Cookie;

function readManifestFile($filepath)
{
    $manifest = file_get_contents($filepath);
    if ($manifest !== false) {
        return json_decode($manifest);
    }
    $error = 'Resource manifest file not found.';
    throw new Exception($error);
}

function cacheManifest($key, $filepath)
{
    if (isset($GLOBALS[$key])) {
        return $GLOBALS[$key];
    }

    if (App::environment('APP_ENV', 'production')) {
        $manifest = Cache::remember($key, 1, function () use ($filepath) {
            $manifest = readManifestFile($filepath);
        });
    } else {
        $manifest = readManifestFile($filepath);
    }

    $GLOBALS[$key] = $manifest;
    return $manifest;
}

function getManifest()
{
    $key = 'manifest.json';
    return cacheManifest($key, app_path().'/../public/manifest.json');
}

function getDllManifest()
{
    $key = 'dll-manifest.json';
    return cacheManifest($key, app_path().'/../public/dll-manifest.json');
}

function js($file)
{
    echo '<script src="/'.getManifest()->$file.'"></script>';
}

function css($file)
{
    echo '<link rel="stylesheet" href="/'.getManifest()->$file.'"></link>';
}

function dllJs()
{
    $file = 'main.js';
    echo '<script src="/'.getDllManifest()->$file.'"></script>';
}

function dllCss()
{
    $file = 'main.css';
    echo '<link rel="stylesheet" href="/'.getDllManifest()->$file.'"></link>';
}

function makeCookie($name, $value, $age)
{
    return Cookie::make($name, $value, $age, '/', '.'.config('app.base_domain'));
}
