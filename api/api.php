<?php
ini_set('date.timezone','Asia/Shanghai');
require dirname(dirname(__FILE__)).'/config.php';
require dirname(__FILE__).'/medoo.php';
require dirname(__FILE__).'/function.php';
require dirname(__FILE__).'/upload.php';
require dirname(__FILE__).'/jssdk.php';
require dirname(__FILE__).'/phpExcel/PHPExcel.php';

class api
{

    public function get_db()
    {
        try {
            $db = new medoo([
                // 必须配置项
                'database_type' => 'mysql',
                'database_name' => DB_NAME,
                'server' => DB_HOST,
                'username' => DB_USER,
                'password' => DB_PASSWORD,
                'charset' => 'utf8',
                'port' => DB_PORT
            ]);
        } catch (Exception $e) {
            $db = null;
        }

        return $db;
    }

    public function get_upload()
    {
        $upload = new FileUpload();
        return $upload;
    }


    public function get_excel_userList($data,$name){
        // Create new PHPExcel object    
        $objPHPExcel = new PHPExcel();  
        // Set properties    
        $objPHPExcel->getProperties()->setCreator("ctos")  
                ->setLastModifiedBy("ctos")  
                ->setTitle("Office 2007 XLSX Test Document")  
                ->setSubject("Office 2007 XLSX Test Document")  
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")  
                ->setKeywords("office 2007 openxml php")  
                ->setCategory("Test result file");  
      
        // set width    
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20); 
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
       
      
        // 设置行高度    
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
      
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
      
        // 字体和样式  
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setBold(true);  
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
      
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
      
        // 设置水平居中    
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        
      
        //  合并  
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');  
      
        // 表头  
        $objPHPExcel->setActiveSheetIndex(0)  
                ->setCellValue('A1', $name)  
                ->setCellValue('A2', '姓名')  
                ->setCellValue('B2', 'openid') 
                ->setCellValue('C2', '手机号')
                ->setCellValue('D2', 'QQ') 
                ->setCellValue('E2', '地址');
        
        // 内容  
        for ($i = 0, $len = count($data); $i < $len; $i++) {  
            $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $data[$i]['name']);  
            $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), $data[$i]['openid']); 
            $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), $data[$i]['mobile']); 
            $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), $data[$i]['QQ']);  
            $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 3), $data[$i]['address']); 
     
            

            $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':E' . ($i + 3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':E' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
            $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(16);  
        }  
      
        // Rename sheet    
        $objPHPExcel->getActiveSheet()->setTitle($name);  
      
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet    
        $objPHPExcel->setActiveSheetIndex(0);  
      
        // 输出  
        header('Content-Type: application/vnd.ms-excel');  
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');  
        header('Cache-Control: max-age=0');  
      
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
    }

    public function get_excel_awardList($data,$name){
        // Create new PHPExcel object    
        $objPHPExcel = new PHPExcel();  
        // Set properties    
        $objPHPExcel->getProperties()->setCreator("ctos")  
                ->setLastModifiedBy("ctos")  
                ->setTitle("Office 2007 XLSX Test Document")  
                ->setSubject("Office 2007 XLSX Test Document")  
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")  
                ->setKeywords("office 2007 openxml php")  
                ->setCategory("Test result file");  
      
        // set width    
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20); 
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);  

       
      
        // 设置行高度    
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
      
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
      
        // 字体和样式  
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);  
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
      
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
      
        // 设置水平居中    
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
 
        
      
        //  合并  
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');  
      
        // 表头  
        $objPHPExcel->setActiveSheetIndex(0)  
                ->setCellValue('A1', $name)  
                ->setCellValue('A2', '姓名')  
                ->setCellValue('B2', 'openid') 
                ->setCellValue('C2', '奖品名称')
                ->setCellValue('D2', 'QQ')  
                ->setCellValue('E2', '手机号') 
                ->setCellValue('F2', '地址')
                ->setCellValue('G2', '获奖时间');

        
        // 内容  
        for ($i = 0, $len = count($data); $i < $len; $i++) {  
            $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $data[$i]['user_name']);  
            $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), $data[$i]['openid']); 
            $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), $data[$i]['award_name']); 
            $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), $data[$i]['user_QQ']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 3), $data[$i]['user_mobile']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 3), $data[$i]['user_address']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 3), $data[$i]['time']);
            

            $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':G' . ($i + 3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':G' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
            $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(16);  
        }  
      
        // Rename sheet    
        $objPHPExcel->getActiveSheet()->setTitle($name);  
      
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet    
        $objPHPExcel->setActiveSheetIndex(0);  
      
        // 输出  
        header('Content-Type: application/vnd.ms-excel');  
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');  
        header('Cache-Control: max-age=0');  
      
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
    }

    

}
