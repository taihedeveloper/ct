<?php
/**
 * @name Cttask_Excel
 * @desc Excel公共工具类
 * @author 冯新(fengxin@taihe.com)
 */

require_once ("excel/PHPExcel.php");

class Cttask_Excel
{

	/**
	 * 读取excel $filename 路径文件名 $encode 返回数据的编码 默认为utf8
	 *以下基本都不要修改
	 */
	public function Import($filename,$encode='utf-8'){
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($filename);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$excelData = array();
		for ($row = 1; $row <= $highestRow; $row++) {
			for ($col = 0; $col < $highestColumnIndex; $col++) {
				$excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
			}
		}
		return $excelData;
	}

	public function read($path){
		//$file = "test.csv";

		$type = strtolower( pathinfo($path, PATHINFO_EXTENSION) );

		//$path = __YOUR_FILE_PATH__.'/'.$file;

		if (!file_exists($path)) {
			die('no file!');
		}

		//根据不同类型分别操作
		if( $type=='xlsx'||$type=='xls' ){
			$objPHPExcel = PHPExcel_IOFactory::load($path);
		}else if( $type=='csv' ){
			$objReader = PHPExcel_IOFactory::createReader('CSV')
					->setDelimiter(',')
					->setInputEncoding('GBK') //不设置将导致中文列内容返回boolean(false)或乱码
					->setEnclosure('"')
					->setLineEnding("\r\n")
					->setSheetIndex(0);
			$objPHPExcel = $objReader->load($path);

		}else{
			die('Not supported file types!');
		}


		//选择标签页

		$sheet = $objPHPExcel->getSheet(0);

		//获取行数与列数,注意列数需要转换
		$highestRowNum = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		$highestColumnNum = PHPExcel_Cell::columnIndexFromString($highestColumn);

		//取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
		$filed = array();
		for($i=0; $i<$highestColumnNum;$i++){
			$cellName = PHPExcel_Cell::stringFromColumnIndex($i).'1';
			$cellVal = $sheet->getCell($cellName)->getValue();//取得列内容
			$filed []= $cellVal;
		}

		//开始取出数据并存入数组
		$data = array();
		for($i=2;$i<=$highestRowNum;$i++){//ignore row 1
			$row = array();
			for($j=0; $j<$highestColumnNum;$j++){
				$cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
				$cellVal = $sheet->getCell($cellName)->getValue();
				$row[ $filed[$j] ] = $cellVal;
			}
			$data []= $row;
		}


		return $data;
	}
}
