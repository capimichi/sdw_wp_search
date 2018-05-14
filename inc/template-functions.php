<?php

/**
 * @param $name
 * @param array $arguments
 * @return string
 */
function fs_get_template($name, $arguments = [])
{
    $templatePath = FS_TEMPLATES_DIR . DIRECTORY_SEPARATOR . $name . ".php";
    ob_start();
    extract($arguments);
    require $templatePath;
    $content = ob_get_clean();
    return $content;
}