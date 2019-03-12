<?php

namespace App\Helpers;

/**
 * @file
 * Content administration and module settings UI.
 */
require_once realpath(__DIR__) . '/Spout/src/Box/Spout/Autoloader/autoload.php';

use Box\Spout\Export;

define('UPLOAD_TEMP', storage_path() . '/tmp');

class Cexport
{
    public static function test() {
        $arrData = [
            [
                'id'        => 1,
                'full_name' => 'Nguyễn Văn A',
                'email'		=> 'abc@gmail.com',
                'address' 	=> 'Quận 7, Hồ Chí Minh',
                'created_date' => '2017-06-05 15:36:27'
            ]
        ];

        $i = 1;
        
        $oExport = new Export('cerelac-tot-nghiep_' . date('Y-m-d')); // Tên file
        $oExport->setTitle('Danh sách thành viên') // Title cell
                ->setHeader(['STT', 'Họ tên', 'Email', 'Địa chỉ', 'Ngày tạo'])
                ->setFieldDisplay(['id', 'full_name', 'email', 'address', 'created_date'])
                ->setData($arrData)
                ->customizeData([
                    'id' => function() use ($i) {
                        return $i++;
                    },
                    'created_date' => function($val) {
                        return date('d/m/y H:i', strtotime($val));
                    }
                ]);

        $oExport->save();
        die();
    }

    public static function export($filename, $title, $header, $fields, $data, $customizeData = []) {

        $oExport = new Export( $filename ); // Tên file
        $oExport->setTitle( $title ) // Title cell
                ->setHeader( $header )
                ->setFieldDisplay( $fields )
                ->setData( $data );

        if (!empty($customizeData)) {
            $oExport->customizeData($customizeData);
        }
                
        $oExport->save();
        die();
    }
}