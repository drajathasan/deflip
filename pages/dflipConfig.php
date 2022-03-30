<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-03-29 08:22:40
 * @modify date 2022-03-30 18:36:50
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
            'allowDownload' => $_POST['allowDownload'],
            'tos' => $_POST['tos']
        ])]);
    }
    else
    {
        $Update = \SLiMS\DB::getInstance()->prepare('update setting set setting_value = ? where setting_name = ?');
        $Update->execute([serialize([
            'guestForm' => $_POST['guestForm'],
            'allowDownload' => $_POST['allowDownload'],
            'tos' => $_POST['tos']
        ]), 'dflipConfig']);
    }

    utility::jsAlert('Data saved!');
    exit;
}



?>

<div class="menuBox">
    <div class="menuBoxInner memberIcon">
        <div class="per_title">
            <h2>DeFlip Settings</h2>
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
$form->addSelectList('guestForm', 'Activate guest access?', [['0','No'],['1','Yes']], $meta['guestForm'] ?? '', 'class="select2"', 'Activate or not');
// Guest form is active?
$form->addSelectList('allowDownload', 'Allow download?', [['1','Yes'],['0','No']], $meta['allowDownload'] ?? '', 'class="allowDownload select2"', 'Activate or not');
// TOS
$tos = <<<HTML
    <ol>
        <li>Your ID will be used for internal use.</li>
        <li>You are willing to accept all forms of use of the data that you have submitted and then will not dispute the use of the data even if you consider that the use of the data is not in accordance with your expectations.</li>
    </ol>
HTML;
$form->addTextField('textarea', 'tos', 'Term Of Service', htmlentities($meta['tos']??$tos, ENT_QUOTES), 'class="texteditor form-control" style="height: 500px;"');

// print out the form object
echo $form->printOut();
?>

<script>
    $(document).ready(function() {
        CKEDITOR.replace('tos');
        CKEDITOR.config.toolbar = [['Bold','Italic','Underline','StrikeThrough','NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']];
        $(document).bind('formEnabled', function() {
            CKEDITOR.instances.contentDesc.setReadOnly(false);
        });
    });

    $('.allowDownload').change((e) => {
        alert('This option will not be useful if the user/reader has a Download Manager application that can download PDF files automatically, but it is effective for reducing downloads via buttons.')
    })
</script>