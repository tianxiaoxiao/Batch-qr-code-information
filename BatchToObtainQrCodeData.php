<?php
    // +----------------------------------------------------------------------
    // | superfreak'gadgets [JUST DO IT ]
    // +----------------------------------------------------------------------
    // | Copyright (c) 2016/9/19   23:53:07
    // +----------------------------------------------------------------------
    // | Author: 田枭 <cengjingdexuwei@live.cn>
    // +----------------------------------------------------------------------
	require './Classes/PHPExcel.php';                   //添加读取excel表格类
	$objPHPExcel = new PHPExcel();                      //实例化一个PHPExcel()对象
        $objSheet = $objPHPExcel->getActiveSheet();         //选取当前的sheet对象
        $objSheet->setTitle('helen');                       //对当前sheet对象命名

	$file_obj = new AccessFile();                       //实例化二维码图片文件路径类

	$qrcode_obj = new IdentifyQrCode();                 //实例化调取二维码识别类

	$file_name = $file_obj->file_name();
	$size = count($file_name);

	for($begin=0;$begin<$size;$begin++){                                     //开始遍历文件
		$key = $begin+1;
		$qrcode = $qrcode_obj->ldentify('./qrcode/'.$file_name[$begin]);

		$column_name_A = "A".$key;                                           //组合列名
        $column_name_B = "B".$key;

		$objSheet->setCellValue($column_name_A,$file_name[$begin])->setCellValue($column_name_B,$qrcode);   //利用setCellValues()填充数据
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');   //设定写入excel的类型
        $objWriter->save('./demo.xlsx');                                           //保存文件
		echo $column_name_A."\n";
	}
	
	//读取文件名集合类
	class AccessFile{
		var  $hostdir;
		function __construct(){
		 	$this->hostdir = dirname(__FILE__)."/qrcode";
		 }
		//所有文件名，存入数组
		public function file_name(){
			return scandir($this->hostdir,1);
		} 
	}
	//类名： 识别二维码类
	class  IdentifyQrCode{
		public function ldentify($image_name){
            $image = new ZBarCodeImage($image_name);         //新建一个图像对象
            $scanner = new ZBarCodeScanner();                // 创建一个二维码识别器
            $barcode = $scanner->scan($image);               //识别图像
		    if (!empty($barcode)) {                          //循环输出二维码信息
		        foreach ($barcode as $code) {
		        	return $code['data'];
		        }  
		    } 
		}
	}
