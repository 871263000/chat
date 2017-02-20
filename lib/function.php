<?php
/*
 +----------------------------------------------------------
 * 公共函数库
 +----------------------------------------------------------
 * 文 件 名 function.php
 +----------------------------------------------------------
 * 文件描述 存放公共函数
 +----------------------------------------------------------
 * 作   者 上海斯瑞科技有限公司
 +----------------------------------------------------------
 * 时   间 2015-07-10
 +--
 --------------------------------------------------------
 */
 
/* 
测试用,在config.inc.php已经定义过了
define('DOCUMENT_ROOT', dirname(__FILE__));
define('DOCUMENT_URL', 'http://'. $_SERVER['HTTP_HOST']);
*/

/*
 * 文件上传
 *
 * @access public
 * @param $file 上传文件input的name
 * @param $path 上传文件存放文件夹
 * @return string 返回一个url字符串
 */
function uploadFile($file, $path = 'upload/files')
{
	$upload_file = $_FILES[$file];
	$upload_file_temp_name = $upload_file['tmp_name'];
	
	$upload_file_suffix_array = explode('.', $upload_file['name']);
	$upload_file_suffix = end($upload_file_suffix_array);
	
	$upload_file_new_name = date('YmdHis') . uniqid() .'.'. $upload_file_suffix;
	
	move_uploaded_file($upload_file_temp_name, DOCUMENT_ROOT .'/'. $path .'/'. $upload_file_new_name);
	$upload_file_url = DOCUMENT_URL .'/'. $path .'/'. $upload_file_new_name;
	
	return $upload_file_url;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
 * 此函数把上传的文件转为swf文件,然后用flexpaper插件在浏览器中预览，类似百度文库的功能.
 * 只能单文件上传，可以上传图片，"doc", "docx",'xls','xlsx','ppt','pptx','txt',"pdf"结尾的文件
 * @access public
 * @param $file 上传文件input的name
 * @param $source_path 上传文件存放文件夹 
 * @param $pdf_path 中间文件 *.pdf存放文件夹 
 * @param $swf_path 终极文件 *.swf存放文件夹
 * @return array 返回一个 $arr
 * @返回值有多种情况
 */
function uploadView($file,$source_dir = 'upload/files',$pdf_dir = 'upload/pdf',$swf_dir = 'upload/swf'){
	//对参数进行处理
	$source_dir = rtrim($source_dir,'/');
	$pdf_dir = rtrim($pdf_dir,'/');
	$swf_dir = rtrim($swf_dir,'/');

	//限制上传文件的类型
	$allowedExts = array("doc", "docx",'xls','xlsx','ppt','pptx','txt',"pdf","jpeg","png","jpg");
	$temp = explode(".", $_FILES[$file]["name"]);
	$extension = end($temp);

	//文件上传   上传文件大小限制看情况 $_FILES[$file]["size"]
	if ($_FILES[$file]["error"] > 0 || !in_array($extension, $allowedExts)){
		return '上传错误,或文件类型不合法!错误代码：'.$_FILES[$file]["error"].'<br>';
	}else{
		$source_file_path = uploadFile($file, $source_dir);
		//数据表中`source_path` `上传源文件的路径` 的生产
		//$arr['source_path'] = $source_file_path;

		//生成上传文件重命名后的新名字
		$temp = explode('/',$source_file_path);
		$source_name = end($temp);
		//权宜	
		$arr['source_path'] = $source_name;
		$arr['swf_path'] = 'upload/files/'.$source_name;

		$source_file_path = $source_dir.'/'.$source_name;
		$temp = explode('.', $source_name);
		$source_name = $temp[0];
		//$arr['source_path'] = DOCUMENT_ROOT.'/'.$source_dir."/{$source_name}.swf";
	}

	//如果上传的是图片
	$picture = array("jpeg","png","jpg");
	if(in_array($extension, $picture)){
		return $arr;

	//如果上传的是pdf文件	
	}elseif($extension == 'pdf'){
		//?判断是否缺少参数
		$swf = DOCUMENT_ROOT.'/'.$swf_dir."/{$source_name}.swf";
		//$swf1 = DOCUMENT_URL.'/'.$swf_dir."/{$source_name}.swf";
		$pdf2swf = 'sudo /usr/local/bin/pdf2swf -o '.$swf.' -T -z -t -f '.$source_file_path.' -s flashversion=9';
		exec($pdf2swf);

		$arr['swf_path'] = $source_name.'.swf';
		return $arr;

	//如果上传的是合格的其他文件	
	}else{
		//？判断是否缺少参数
		$pdf = DOCUMENT_ROOT.'/'.$pdf_dir."/{$source_name}.pdf";
		//$pdf1 = DOCUMENT_URL.'/'.$pdf_dir."/{$source_name}.pdf";
		$arr['pdf_path'] = $source_name.'.pdf';

		$swf = DOCUMENT_ROOT.'/'.$swf_dir."/{$source_name}.swf";
		//$swf1 = DOCUMENT_URL.'/'.$swf_dir."/{$source_name}.swf";
		$arr['swf_path'] = $source_name.'.swf';

		$doc2pdf = 'sudo /usr/java/jdk1.8.0_60/bin/java -jar /opt/jodconverter-2.2.2/lib/jodconverter-cli-2.2.2.jar '.$source_file_path.' '.$pdf;
		exec($doc2pdf);
		$pdf2swf = 'sudo /usr/local/bin/pdf2swf -o '.$swf.' -T -z -t -f '.$pdf.' -s flashversion=9';
		exec($pdf2swf);
		//return $arr;
	}

}
/*
 * 此函数把上传的文件转为swf文件,然后用flexpaper插件在浏览器中预览，类似百度文库的功能.
 * 只能单文件上传，可以上传图片，"doc", "docx",'xls','xlsx','ppt','pptx','txt',"pdf"结尾的文件
 * @access public
 * @param $file 上传文件input的name
 * @param $source_path 上传文件存放文件夹 
 * @param $pdf_path 中间文件 *.pdf存放文件夹 
 * @param $swf_path 终极文件 *.swf存放文件夹
 * @return array 返回一个 $arr
 * @返回值有多种情况
 */
function uploadView1($file,$source_dir = 'upload/files',$pdf_dir = 'upload/pdf',$swf_dir = 'upload/swf'){
	//对参数进行处理
	$source_dir = rtrim($source_dir,'/');
	$pdf_dir = rtrim($pdf_dir,'/');
	$swf_dir = rtrim($swf_dir,'/');

	//限制上传文件的类型
	$allowedExts = array("doc", "docx",'xls','xlsx','ppt','pptx','txt',"pdf","jpeg","png","jpg");
	$temp = explode(".", $_FILES[$file]["name"]);
	$extension = end($temp);

	//文件上传   上传文件大小限制看情况 $_FILES[$file]["size"]
	// var_dump($_FILES);exit();
	if ($_FILES[$file]["error"] > 0 || !in_array($extension, $allowedExts)){
		return '上传错误,或文件类型不合法!错误代码：'.$_FILES[$file]["error"].'<br>';
	}else{
		$source_file_path = uploadFile($file, $source_dir);
		//数据表中`source_path` `上传源文件的路径` 的生产
		//$arr['source_path'] = $source_file_path;

		//生成上传文件重命名后的新名字
		$temp = explode('/',$source_file_path);
		$source_name = end($temp);
		//权宜	
		$arr['source_path'] = $source_name;
		$arr['swf_path'] = 'upload/files/'.$source_name;

		$source_file_path = $source_dir.'/'.$source_name;
		$temp = explode('.', $source_name);
		$source_name = $temp[0];
		//$arr['source_path'] = DOCUMENT_ROOT.'/'.$source_dir."/{$source_name}.swf";
	}

	//如果上传的是图片或pdf
	$picture = array("jpeg","png","jpg","pdf");
	if(in_array($extension, $picture)){
		return $arr;

	//如果上传的是pdf文件	
	}//elseif($extension == 'pdf'){
	// 	//?判断是否缺少参数
	// 	$swf = DOCUMENT_ROOT.'/'.$swf_dir."/{$source_name}.swf";
	// 	//$swf1 = DOCUMENT_URL.'/'.$swf_dir."/{$source_name}.swf";
	// 	$pdf2swf = 'sudo /usr/local/bin/pdf2swf -o '.$swf.' -T -z -t -f '.$source_file_path.' -s flashversion=9';
	// 	exec($pdf2swf);

	// 	$arr['swf_path'] = $swf;
	// 	return $arr;

	// //如果上传的是合格的其他文件	
	// }else{
	// 	//？判断是否缺少参数
	// 	$pdf = DOCUMENT_ROOT.'/'.$pdf_dir."/{$source_name}.pdf";
	// 	//$pdf1 = DOCUMENT_URL.'/'.$pdf_dir."/{$source_name}.pdf";
	// 	$arr['pdf_path'] = $source_name.'.pdf';

	// 	$swf = DOCUMENT_ROOT.'/'.$swf_dir."/{$source_name}.swf";
	// 	//$swf1 = DOCUMENT_URL.'/'.$swf_dir."/{$source_name}.swf";
	// 	$arr['swf_path'] = $source_name.'.swf';

	// 	$doc2pdf = 'sudo /usr/java/jdk1.8.0_60/bin/java -jar /opt/jodconverter-2.2.2/lib/jodconverter-cli-2.2.2.jar '.$source_file_path.' '.$pdf;
	// 	exec($doc2pdf);
	// 	$pdf2swf = 'sudo /usr/local/bin/pdf2swf -o '.$swf.' -T -z -t -f '.$pdf.' -s flashversion=9';
	// 	exec($pdf2swf);
	// 	//return $arr;
	// }

}