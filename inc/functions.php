<?php

function uploadImage($file, $path)
{
    if (!$file['name']) return null;

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = time() . "_" . rand(1000,9999) . "." . $ext;

    $target = $path . $newName;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        return $newName;
    }

    return null;
}

function slugify($text)
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
}

function alert($msg)
{
    echo "<div class='alert'>{$msg}</div>";
}

function deleteFile($folder, $file)
{
    if ($file != "" && file_exists($folder . $file)) {
        unlink($folder . $file);
    }
}
