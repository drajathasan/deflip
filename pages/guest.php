<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-03-29 10:37:21
 * @modify date 2022-03-29 12:56:45
 * @license GPLv3
 * @desc [description]
 */

use SLiMS\DB;

defined('INDEX_AUTH') OR die('Direct access not allowed!');

$fields = getDefaultField();

$Error = '';
if (isset($_POST['saveData']))
{
    if (!\Volnix\CSRF\CSRF::validate($_POST)) {
        $Error = 'Invalid login form!';
    }
    else if (isset($_POST['agree']))
    {
        $field = [];
        $column = [];
        foreach ($fields as $attribute) {
            $name = strtolower(str_replace(' ', '', $attribute['label']));
            if (isset($_POST[$name])) 
            {
                $field[] = $_POST[$name];
                $column[] = $attribute['column'];
            }
        }
        $field[] = date('Y-m-d H:i:s');

        $DB = DB::getInstance();
        $Insert = $DB->prepare('insert into files_read_guest set ' . (implode(' = ?,', $column))  . ' = ?, created_at = ?');
        $Insert->execute($field);
        
        if ($Insert) 
        {
            $_SESSION['guestReadEbook'] = [
                'id' => $DB->lastInsertId(),
                'books' => [
                    ($_GET['fid']??0) => ['startread' => date('Y-m-d H:i:s')]
                ]
            ];

            redirect(slimsUrl('?' . $_SERVER['QUERY_STRING']));
            exit;
        }
        else
        {
            $Error = $DB->errorInfo()[2]??'Error';
        }
    }
    else
    {
        $Error = 'Anda harus menyetujui syarat dan ketentuan kami';
    }
    
}

?>
<!DOCTYPE Html>
<html>
    <head>
        <title>Guest Form</title>
        <link href="<?= slimsUrl('css/bootstrap.min.css') ?>" rel="stylesheet"/>
        <script src="<?= JWB . 'jquery.js' ?>"></script>
    </head>
    <body>
        <form class="my-3 mx-5" action="<?= slimsUrl('?' . $_SERVER['QUERY_STRING']); ?>" method="POST" target="_self">
            <?php
            if (!empty($Error))
            {
                echo <<<HTML
                    <div class="alert alert-danger">
                        {$Error}
                    </div>
                HTML;
            }
            ?>
            <!-- Csrf -->
            <?= \Volnix\CSRF\CSRF::getHiddenInputString() ?>
            <!-- Form -->
            <div class="mb-3">
                <h1>Form Tamu</h1>
                <p>Sebelum membaca sumberdaya kami, anda diwajibkan untuk mengisi form dibawah berikut dan menyetujui <a href="#" class="openTOS">syarat & kententuan</a> kami.</p>
                <ol class="d-none tos">
                    <li>Identitas anda kami gunakan untuk keperluan internal kami</li>
                    <li>Anda bersedia untuk <strong>tidak mempermasalahkan</strong> apa yang anda lihat jika anda merasa tidak setuju dengan apa yang kami sediakan.</li>
                </ol>
            </div>
            <?php 
                foreach($fields as $attribute):
                    extract($attribute);
                    $name = strtolower(str_replace(' ', '', $label));
                    echo <<<HTML
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">{$label}</label>
                            <{$tag} name="{$name}" type="{$type}" class="form-control">
                        </div>
                    HTML;
                endforeach; 
            ?>
            <input type="checkbox" name="agree"/> Saya setuju dengan syarat & kentuan yang berlaku.<br>
            <button name="saveData" type="submit" class="mt-2 btn btn-primary">Submit</button>
        </form>
        <script>
            jQuery('.openTOS').click(function(){
                jQuery('.tos').removeClass('d-none');
                jQuery('.tos').slideDown();
            })
        </script>
    </body>
</html>
<?php exit; ?>