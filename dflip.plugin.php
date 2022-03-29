<?php
/**
 * Plugin Name: DearFlip
 * Plugin URI: -
 * Description: Flipbook
 * Version: 1.0.0
 * Author: Heru Subekti
 * Author URI: https://www.facebook.com/heroe.soebekti
 * Plugin Packager: Drajat Hasan
 */

// get plugin instance
$plugin = \SLiMS\Plugins::getInstance();

$plugin->registerMenu('system', 'DeFlip', __DIR__ . '/pages/dflipConfig.php');

$plugin->registerMenu('reporting', 'DeFlip Download Counter', __DIR__ . '/pages/dl_counter.php');

$plugin->register('fstream_pdf_before_download', function($data){
    extract($data);
    // Set global file location url
    global $file_loc_url,$sysconf;

    // Meta
    $meta = $sysconf['dflipConfig']??[];

    // Require helper
    require __DIR__ . '/helper.php';

    if (\utility::isMemberLogin())
    {
        $_SESSION['guestReadEbook'] = [];
        $_SESSION['memberReadBook'] = [
            'books' => [
                ($_GET['fid']??0) => ['startread' => date('Y-m-d H:i:s')]
            ]
        ];
    }

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