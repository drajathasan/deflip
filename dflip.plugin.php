<?php
/**
 * Plugin Name: DearFlip
 * Plugin URI: -
 * Description: Flipbook
 * Version: 1.0.0
 * Author: Heru Subekti
 * Author URI: https://www.facebook.com/heroe.soebekti
 * Plugin Packager: Drajat Hasan & Arif Syamsudin
 */

// get plugin instance
$plugin = \SLiMS\Plugins::getInstance();

// Config menu
$plugin->registerMenu('system', 'DeFlip', __DIR__ . '/pages/dflipConfig.php');

// Reporting Menu
$plugin->registerMenu('reporting', 'DeFlip Download Counter', __DIR__ . '/pages/dl_counter.php');

// Hook for force SLiMS PDF Viewer to use DearFlip
$plugin->register('fstream_pdf_before_download', function($data){
    extract($data);
    // Set global file location url
    $file_loc_url = SWB . 'index.php?p=fstream-pdf&fid=' . $fileID . '&bid=' . $biblioID;

    // Meta
    $meta = config('dflipConfig')??[];

    // Require helper
    require __DIR__ . '/helper.php';

    // Reset latest session for guest
    if (\utility::isMemberLogin())
    {
        $_SESSION['guestReadEbook'] = [];
        $_SESSION['memberReadBook'] = [
            'books' => [
                ($_GET['fid']??0) => ['startread' => date('Y-m-d H:i:s')]
            ]
        ];
    }

    // Guest checking
    $guest = (isset($meta['guestForm']) && 
              (bool)$meta['guestForm'] === true && 
              (!isset($_SESSION['guestReadEbook'])));

    if ($guest)
    {
        include __DIR__ . '/pages/guest.php';
    }

    // Include viewer
    include __DIR__ . '/viewer/index.php';

    $guestId = 0;
    if (isset($_SESSION['guestReadEbook'])) $guestId = $_SESSION['guestReadEbook']['id']??0;

    // Counting access
    accessCount($fileID, $memberID, $userID, $guestId, $_SERVER['REMOTE_ADDR']);
    exit;
});
