<?php
include_once("../datos/dtotojson/DtoToJson.Class.php");
include_once("../datos/json/JsonEncod.Class.php");
include_once("../datos/json/JsonDecod.Class.php");
include_once("../datos/connect/Proveedor.Class.php");
require_once("CURL.php");
class consultas {
	public function __construct() {
	}
	
	
	

	public function login($usr_usuario, $usr_contrasena){
		$json = '{"estatus":"fail"}';
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			SELECT *, (select perfil_nombre from perfiles where usuarios.usr_id_perfil=perfiles.perfil_id) as perfil_nombre FROM usuarios  where usr_usuario='".$usr_usuario."' and usr_contrasena='".$usr_contrasena."'
		";
		
		

		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
					$row = $proveedor->fetch_array($proveedor->stmt, 0);
				
					session_start();	
					$_SESSION['inavtek_usr_id']=$row["usr_id"];
					$_SESSION['inavtek_usr_usuario']=$row["usr_usuario"];
					$_SESSION['inavtek_usr_nombre_completo']=$row["usr_nombre_completo"];
					$_SESSION['inavtek_usr_puesto']=$row["usr_puesto"];
					$_SESSION['inavtek_usr_id_perfil']=$row["usr_id_perfil"];
					$_SESSION['inavtek_usr_url_foto']=$row["usr_url_foto"];
					$_SESSION['inavtek_perfil_nombre']=$row["perfil_nombre"];
					
					
					$json = '{"estatus":"ok","location":"vistas/Inicio.php"}';
					
					
			}
		}else{
			$Error=true;
		}
		$proveedor->close();
		
		echo $json;
		//$respuesta = array("totalCount" => count($Data_Envia),"data" => $Data_Envia,"estatus" => "ok", "mensaje" => "Registros Encontrados");
		//$jsonDto = new Encode_JSON();
		//return $jsonDto->encode($respuesta);
	}
	
	public function envio_mail($usr_email){
		$json = '{"estatus":"fail"}';
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			SELECT * FROM usuarios where usr_email='".$usr_email."' and usr_status<>'E'
		";
		

		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
					$row = $proveedor->fetch_array($proveedor->stmt, 0);
					
					$Subject="Recuperar contrase??a ID: ".$row["usr_id"];
					$Subject=str_replace("??", "a|", $Subject);
					$Subject=str_replace("??", "A|", $Subject);
					$Subject=str_replace("??", "e|", $Subject);
					$Subject=str_replace("??", "E|", $Subject);
					$Subject=str_replace("??", "i|", $Subject);
					$Subject=str_replace("??", "I|", $Subject);
					$Subject=str_replace("??", "o|", $Subject);
					$Subject=str_replace("??", "O|", $Subject);
					$Subject=str_replace("??", "u|", $Subject);
					$Subject=str_replace("??", "U|", $Subject);
					$Subject=str_replace("??", "n|", $Subject);
					$Subject=str_replace("??", "N|", $Subject);
					
					$body='<div width="100%" align="center"><font face="arial" size="3"><b><img src="https://bsolutionsmx.com/inavtek/dist/img/logo.png" style="text-decoration:none"><!-- </img> --></b></font></div><br><br>';
					$body.='<font face="arial" size="2.5">Hola '.$row["usr_nombre_completo"].', tu contrase??a es: <strong>'.$row["usr_contrasena"].'</strong></font><br><br>';
					$body.='<br><br><br><font face="arial" size="1"><i>* Estee es un env??o automatizado, no es necesario responder este correo.</i></font>';
					$body=str_replace("??", "a|", $body);
					$body=str_replace("??", "A|", $body);
					$body=str_replace("??", "e|", $body);
					$body=str_replace("??", "E|", $body);
					$body=str_replace("??", "i|", $body);
					$body=str_replace("??", "I|", $body);
					$body=str_replace("??", "o|", $body);
					$body=str_replace("??", "O|", $body);
					$body=str_replace("??", "u|", $body);
					$body=str_replace("??", "U|", $body);
					$body=str_replace("??", "n|", $body);
					$body=str_replace("??", "N|", $body);
					
					
					$obj = new CURL();
					$url = "https://bsolutionsmx.com/inavtek/envio_correo_externo/send_external_email.asp";                                       
					$data = array('strPassword' => 'I68V57T42', 'strSubject' => $Subject,'strTo'=>rtrim(ltrim($row["usr_email"])).";",'strHTMLBody'=>$body,'strCc'=>'','strBCC'=>'');
					$correoASP = $obj->curlData($url,$data);	
					
					//$_SESSION['inavtek_usr_id']=$row["usr_id"];
					//$_SESSION['inavtek_usr_usuario']=$row["usr_usuario"];
					//$_SESSION['inavtek_usr_nombre_completo']=$row["usr_nombre_completo"];
					//$_SESSION['inavtek_usr_puesto']=$row["usr_puesto"];
					//$_SESSION['inavtek_usr_id_perfil']=$row["usr_id_perfil"];
					//$_SESSION['inavtek_usr_url_foto']=$row["usr_url_foto"];
					//$_SESSION['inavtek_perfil_nombre']=$row["perfil_nombre"];
					
					
					$json = '{"estatus":"ok"}';
					
					
			}
		}else{
			$Error=true;
		}
		$proveedor->close();
		
		echo $json;
		//$respuesta = array("totalCount" => count($Data_Envia),"data" => $Data_Envia,"estatus" => "ok", "mensaje" => "Registros Encontrados");
		//$jsonDto = new Encode_JSON();
		//return $jsonDto->encode($respuesta);
	}

}


@$usr_id=$_POST["usr_id"];
@$usr_nombre_completo=$_POST["usr_nombre_completo"];
@$usr_usuario=$_POST["usr_usuario"];
@$usr_contrasena=$_POST["usr_contrasena"];
@$usr_puesto=$_POST["usr_puesto"];
@$usr_id_perfil=$_POST["usr_id_perfil"];
@$usr_email=$_POST["usr_email"];
@$usr_telefono=$_POST["usr_telefono"];
@$usr_modifico=$_POST["usr_modifico"];
@$usr_time_stamp=$_POST["usr_time_stamp"];
@$usr_status=$_POST["usr_status"];

@$accion=$_POST["accion"];
$Consultas = new consultas();
if($accion=="login"){
	$empleados=$Consultas->login($usr_usuario, $usr_contrasena);
	echo $empleados; 
}else if($accion=="envio_mail"){
	$empleados=$Consultas->envio_mail($usr_email);
	echo $empleados; 
}




?>