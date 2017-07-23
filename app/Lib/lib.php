<?php
function getManifest()
{
  $key = 'manifest.json';

  if (isset($GLOBALS[$key])) {
    return $GLOBALS[$key];
  }

  $manifest = Cache::remember($key, 1, function () {
    $manifest = file_get_contents(app_path().'/../public/manifest.json');
    if ($manifest !== false) {
      return json_decode($manifest);
    }
    $error = 'Resource manifest file not found.';
    throw new Exception($error);
  });

  $GLOBALS[$key] = $manifest;
  return $manifest;
}

function getResourcePath($file)
{
  if (App::environment('production')) {
    $manifest = getManifest();
    $filepath = $manifest->$file;
    return $filepath;
  }

  return $file;
}

function js($file)
{
  echo '<script src="'.getResourcePath($file).'"></script>';
}

function css($file)
{
  echo '<link rel="stylesheet" href="'.getResourcePath($file).'"></link>';
}
