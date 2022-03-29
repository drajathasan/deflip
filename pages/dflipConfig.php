<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-03-29 08:22:40
 * @modify date 2022-03-29 10:31:22
 * @license GPLv3
 * @desc [description]
 */
defined('INDEX_AUTH') OR die('Direct access not allowed!');

// IP based access limitation
require LIB . 'ip_based_access.inc.php';
do_checkIP('smc');
do_checkIP('smc-membership');
// start the session
require SB . 'admin/default/session.inc.php';
// set dependency
require SIMBIO . 'simbio_GUI/table/simbio_table.inc.php';
require SIMBIO . 'simbio_GUI/form_maker/simbio_form_table_AJAX.inc.php';
require SIMBIO . 'simbio_GUI/paging/simbio_paging.inc.php';
require SIMBIO . 'simbio_DB/datagrid/simbio_dbgrid.inc.php';
require __DIR__ . '/../helper.php';
// end dependency

// Load config
utility::loadSettings($dbs);

// Extract config
$meta = $sysconf['dflipConfig']??[];

if (isset($_POST['saveData']))
{
    if (count($meta) === 0)
    {
        $Insert = \SLiMS\DB::getInstance()->prepare('insert into setting set setting_name = ?, setting_value = ?');
        $Insert->execute(['dflipConfig', serialize([
            'guestForm' => $_POST['guestForm'],
            'allowDownload' => $_POST['allowDownload']
        ])]);
    }
    else
    {
        $Update = \SLiMS\DB::getInstance()->prepare('update setting set setting_value = ? where setting_name = ?');
        $Update->execute([serialize([
            'guestForm' => $_POST['guestForm'],
            'allowDownload' => $_POST['allowDownload']
        ]), 'dflipConfig']);
    }

    utility::jsAlert('Berhasil menyimpan data');
    exit;
}

?>

<div class="menuBox">
    <div class="menuBoxInner memberIcon">
        <div class="per_title">
            <h2>Dflip Settings</h2>
        </div>
    </div>
</div>

<?php

// create new instance
$form = new simbio_form_table_AJAX('mainForm', $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'], 'post');
$form->submit_button_attr = 'name="saveData" value="' . __('Save') . '" class="s-btn btn btn-default"';
// form table attributes
$form->table_attr = 'id="dataList" cellpadding="0" cellspacing="0"';
$form->table_header_attr = 'class="alterCell"';
$form->table_content_attr = 'class="alterCell2"';

/* Form Element(s) */

// Guest form is active?
$form->addSelectList('guestForm', 'Aktifkan Daftar Tamu?', [['0','Tidak'],['1','Ya']], $meta['guestForm'] ?? '', 'class="select2"', 'Aktifkan atau tidak');
// Guest form is active?
$form->addSelectList('allowDownload', 'Perbolehkan Download?', [['0','Tidak'],['1','Ya']], $meta['allowDownload'] ?? '', 'class="allowDownload select2"', 'Aktifkan atau tidak');

// print out the form object
echo $form->printOut();
?>

<script>
    $('.allowDownload').change((e) => {
        alert('Opsi ini tidak akan berguna jika pengguna/pembaca memiliki aplikasi Download Manager yang dapat mengunduh berkas PDF secara otomatis, namun ampuh untuk mengurangi tindak unduh via tombol.')
    })
</script>