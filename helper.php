<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-03-29 07:19:21
 * @modify date 2022-03-29 12:37:22
 * @license GPLv3
 * @desc [description]
 */

function dflipUrl(string $additionalUrl = '')
{
    return slimsUrl('plugins/' . basename(__DIR__) . '/' . $additionalUrl);
}

function slimsUrl(string $additionalUrl = '')
{
    return trim(SWB . $additionalUrl);
}

function getCurrentUrl($query = [])
{
    
    return $_SERVER['PHP_SELF'] . '?' . http_build_query(array_merge(['mod' => $_GET['mod']??null, 'id' => $_GET['id']??null], $query));
}

function redirect(string $Url)
{
    header("Refresh:0; url={$Url}");
    exit;
}

function accessCount($fileID, $memberID, $userID, $guestID, $clientIP)
{
    \SLiMS\DB::getInstance()
        ->prepare('insert into files_read set file_id = ?, member_id = ?, user_id = ?, guest_id = ?, client_ip = ?')
        ->execute(func_get_args());
}

function getDefaultField()
{
    return [
        [
            'tag' => 'input',
            'type' => 'text',
            'label' => 'Nama Anda',
            'column' => 'name',
        ],
        [
            'tag' => 'input',
            'type' => 'text',
            'label' => 'Instansi',
            'column' => 'institution'
        ],
        [
            'tag' => 'input',
            'type' => 'number',
            'label' => 'Nomor HP',
            'column' => 'phonenumber'
        ]
    ];
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