<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-03-29 12:25:34
 * @modify date 2022-03-29 12:38:44
 * @license GPLv3
 * @desc [description]
 */

use SLiMS\DB;

class CreateAndAlter extends \SLiMS\Migration\Migration
{
    public function up()
    {
        $columnExists = DB::getInstance()->query('describe files_read guest_id')->fetch(\PDO::FETCH_ASSOC);

        if (!$columnExists)
        {
            DB::getInstance()->query("ALTER TABLE `files_read`
                CHANGE `date_read` `date_read` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP AFTER `file_id`,
                ADD `guest_id` int(11) NULL AFTER `user_id`;");
        }
        
        DB::getInstance()->query("CREATE TABLE IF NOT EXISTS `files_read_guest` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(50) COLLATE utf8mb4_bin NOT NULL,
            `institution` varchar(255) COLLATE utf8mb4_bin NOT NULL,
            `phonenumber` varchar(15) COLLATE utf8mb4_bin NOT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;");
    }

    public function down()
    {
        
    }
}