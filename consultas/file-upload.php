<?php
 //please note that request will fail if you upload a file larger
 //than what is supported by your PHP or Webserver settings
 sleep(1);//to simulate some delay for local host
 
 //is this an ajax request or sent via iframe(IE9 and below)?
 $ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';

 //our operation result including `status` and `message` which will be sent to browser 
 $result = array();
 $file = $_FILES['file'];
 $ruta_acrhivos=$_POST['ruta'];

 
 if( is_string($file['name']) ) {
	//single file upload, file['name'], $file['type'] will be a string
	$result[] = validateAndSave($file, $ruta_acrhivos);
 }
 else if( is_array($file['name']) ) {
	//multiple files uploaded
	$file_count = count($file['name']);

    //in PHP if you upload multiple files with `avatar[]` name, $file['name'], $file['type'], etc will be an array
	for($i = 0; $i < $file_count; $i++) {
		$file_info = array(
			    'name' => $file['name'][$i],
			    'type' => $file['type'][$i],
			    'size' => $file['size'][$i],
			'tmp_name' => $file['tmp_name'][$i],
			   'error' => $file['error'][$i]
		);
		$result[] = validateAndSave($file_info, $ruta_acrhivos);
	}
 }

 $result = json_encode($result);
 if($ajax) {
	//if request was ajax(modern browser), just echo it back
	echo $result;
 }
 else {
	//if request was from an older browser not supporting ajax upload
	//then we have used an iframe instead and the response is sent back to the iframe as a script
	echo '<script language="javascript" type="text/javascript">';
	echo 'window.top.window.jQuery("#'.$_POST['temporary-iframe-id'].'").data("deferrer").resolve('.$result.');';
	echo '</script>';
 }


 function validateAndSave($file, $ruta_acrhivos) {
	 $result = array();
 	 if(!preg_match('/\.(jpe?g|gif|png|ico|svg|pdf|doc|docx|xls|xlsx|ppt|pptx)$/' , $file['name'])
		 //or extension is not valid
	 )
	 {
		//then there is an error
		$result['status'] = 'ERR';
		$result['message'] = 'Formato de Archivo Invalido!';
	 }else if($file['size'] > 2097152) {
		//if size is larger than what we expect
		$result['status'] = 'ERR';
		$result['message'] = 'Elija un archivo más pequeño!';
	 }
	 else if($file['error'] != 0 || !is_uploaded_file($file['tmp_name'])) {
		//if there is an unknown error or temporary uploaded file is not what we thought it was
		$result['status'] = 'ERR';
		$result['message'] = 'Error no especificado!';
	 }
	 else {
		//save file inside current directory using a safer version of its name
		$save_path = preg_replace('/[^\w\.\- ]/', '', $file['name']);
		if(
			//if we were not able to move the uploaded file from its temporary location to our desired path
			!move_uploaded_file($file['tmp_name'] , $ruta_acrhivos.$save_path)
		  )
		{
			$result['status'] = 'ERR';
			$result['message'] = 'No se pudo guardar el archivo!';
		}
		else {
			//everything seems OK
			$result['status'] = 'OK';
			$result['message'] = 'Archivo cargado correctamente!';
			$result['nombre_archivo'] = $save_path;
			
			//include new thumbnails `url` in our result and send to browser
			//$result['url'] = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/'.$save_path;
		}
	 }
	 
	 return $result;
 }


