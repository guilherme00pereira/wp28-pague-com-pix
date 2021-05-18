<?php

require "vendor/autoload.php";

$js_dir_path = 'src/Assets/js/';
chdir($js_dir_path);
$js_files = glob('*[!min].js');
foreach ($js_files as $file){
    //echo $file . PHP_EOL;
    $minifier = new MatthiasMullie\Minify\CSS($file);
    $minifier->minify(str_replace('.js', '.min.js', $file));
}

$css_dir_path = '../../../src/Assets/css/';
chdir($css_dir_path);
$css_files = glob('*[!min].css');
foreach ($css_files as $file){
    //echo $file . PHP_EOL;
    $minifier = new MatthiasMullie\Minify\CSS($file);
    $minifier->minify(str_replace('.css', '.min.css', $file));
}




