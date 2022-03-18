<?php

require_once "vendor/autoload.php";
error_reporting(E_ERROR | E_PARSE);

if (!isset($_POST['adCategory'], $_POST['adTitle'], $_POST['adEmail'], $_POST['adText'])) {
    return;
}

$googleClient = new Google_Client();
$googleClient->setApplicationName('Google Sheets API Implementation');
$googleClient->setScopes(Google_Service_Sheets::SPREADSHEETS);
$googleClient->setAuthConfig('credentials.json');
$googleClient->setAccessType('offline');
$googleClient->setPrompt('select_account consent');

$sheetsService = new Google_Service_Sheets($googleClient);
$spreadsheetId = "15gjiH1i8wByDsj6qMKL8JXwdOfWCOmdGneH6G8TJppA";
namespace crt;

$categories = opendir('categories');
$allcategories = [];
$adsDict = [];
while ($categoryName = readdir($categories)) {
    if (is_dir('categories/' . $categoryName) && $categoryName != '.' && $categoryName != '..') {
        $allcategories[] = $categoryName;
        $adsDict[$categoryName] = [];
    }
}

foreach ($allcategories as $categoryName) {
    $categoryDir = opendir('categories/' . $categoryName);
    while ($fileName = readdir($categoryDir)) {
        if ($fileName != '.' && $fileName != '..') {
            $filePathName = 'categories/' . $categoryName . '/' . $fileName;
            $file = fopen($filePathName, 'r');
            $title = substr($fileName, 0, strlen($fileName) - 4);
            $email = fgets($file);
            $text = "";
            while (!feof($file)) {
                $text .= fgets($file) . "<br>";
            }
            fclose($file);
            $adsDict[$categoryName][] = ['title' => $title, 'email' => $email, 'text' => $text];
        }
    }
}