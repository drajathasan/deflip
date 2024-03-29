<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-03-29 07:19:21
 * @modify date 2022-03-29 12:37:22
 * @license GPLv3
 * @desc [description]
 */

if (!function_exists('dflipUrl')) {
    function dflipUrl(string $additionalUrl = '')
    {
        return slimsUrl('plugins/' . basename(__DIR__) . '/' . $additionalUrl);
    }
}

if (!function_exists('slimsUrl')) {
    function slimsUrl(string $additionalUrl = '')
    {
        return trim(SWB . $additionalUrl);
    }
}

if (!function_exists('getCurrentUrl')) {
    function getCurrentUrl($query = [])
    {

        return $_SERVER['PHP_SELF'] . '?' . http_build_query(array_merge(['mod' => $_GET['mod']??null, 'id' => $_GET['id']??null], $query));
    }
}

if (!function_exists('redirect')) {
    function redirect(string $Url)
    {
        header("Refresh:0; url={$Url}");
        exit;
    }
}

if (!function_exists('accessCount')) {
    function accessCount($fileID, $memberID, $userID, $guestID, $clientIP)
    {
        \SLiMS\DB::getInstance()
            ->prepare('insert into files_read set file_id = ?, member_id = ?, user_id = ?, guest_id = ?, client_ip = ?')
            ->execute(func_get_args());
    }
}

if (!function_exists('getDefaultField')) {
    function getDefaultField()
    {
        return [
            [
                'tag' => 'input',
                'type' => 'text',
                'label' => 'Name',
                'column' => 'name',
            ],
            [
                'tag' => 'input',
                'type' => 'text',
                'label' => 'Institution',
                'column' => 'institution'
            ],
            [
                'tag' => 'input',
                'type' => 'number',
                'label' => 'Phone Number',
                'column' => 'phonenumber'
            ]
        ];
    }
}

if (!function_exists('dd'))
{
    function dd($input)
    {
        echo '<pre>';
        var_dump($input);
        echo '</pre>';
        exit;
    }
}
