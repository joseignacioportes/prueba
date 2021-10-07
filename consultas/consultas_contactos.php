<?php
include_once("../datos/dtotojson/DtoToJson.Class.php");
include_once("../datos/json/JsonEncod.Class.php");
include_once("../datos/json/JsonDecod.Class.php");
include_once("../datos/connect/Proveedor.Class.php");
class consultas {
	public function __construct() {
	}
	
	
	
	public function consultar_contactos($contacto_id, $contacto_cliente_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from contactos where contacto_status<>'E'
		";
		
		if($contacto_id!=""){
			$sql.=" 
				and contacto_id=".$contacto_id."
			";				
		}
		
		if($contacto_cliente_id!=""){
			$sql.=" 
				and contacto_cliente_id=".$contacto_cliente_id."
			";				
		}
		
		
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					$contacto_principal="";
					if(rtrim(ltrim($row["contacto_principal"]))!="NULL"){
						$contacto_principal=rtrim(ltrim($row["contacto_principal"]));
					}
					$contacto_fecha_nac="";
					if(rtrim(ltrim($row["contacto_fecha_nac"]))!="NULL"){
						$contacto_fecha_nac=rtrim(ltrim($row["contacto_fecha_nac"]));
					}
					$contacto_fecha_aniv="";
					if(rtrim(ltrim($row["contacto_fecha_aniv"]))!="NULL"){
						$contacto_fecha_aniv=rtrim(ltrim($row["contacto_fecha_aniv"]));
					}
					
					
					$Data= array(
						"contacto_id" => rtrim(ltrim($row["contacto_id"])),
						"contacto_cliente_id" => rtrim(ltrim($row["contacto_cliente_id"])),
						"contacto_principal" => $contacto_principal,
						"contacto_nombre" => rtrim(ltrim($row["contacto_nombre"])),
						"contacto_paterno" => rtrim(ltrim($row["contacto_paterno"])),
						"contacto_materno" => rtrim(ltrim($row["contacto_materno"])),
						"contacto_sexo" => rtrim(ltrim($row["contacto_sexo"])),
						"contacto_fecha_nac" => $contacto_fecha_nac,
						"contacto_fecha_aniv" => $contacto_fecha_aniv,
						"contacto_sucursal" => rtrim(ltrim($row["contacto_sucursal"])),
						"contacto_departamento" => rtrim(ltrim($row["contacto_departamento"])),
						"contacto_puesto" => rtrim(ltrim($row["contacto_puesto"])),
						"contacto_tel" => rtrim(ltrim($row["contacto_tel"])),
						"contacto_email" => rtrim(ltrim($row["contacto_email"])),
						"contacto_linkedin" => rtrim(ltrim($row["contacto_linkedin"])),
						"contacto_observaciones" => rtrim(ltrim($row["contacto_observaciones"])),
						"contacto_status" => rtrim(ltrim($row["contacto_status"])),
						"contacto_modifico" => rtrim(ltrim($row["contacto_modifico"])),
						"contacto_time_stamp" => rtrim(ltrim($row["contacto_time_stamp"])),
						"contacto_titulo" => rtrim(ltrim($row["contacto_titulo"]))
						
					);
					array_push($Data_Envia, $Data);
				}
			}
		}else{
			$Error=true;
		}
		$proveedor->close();
		
		$respuesta = array("totalCount" => count($Data_Envia),"data" => $Data_Envia,"estatus" => "ok", "mensaje" => "Registros Encontrados");
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}
	
	public function guardar_contactos($contacto_cliente_id, $contacto_principal, $contacto_nombre, $contacto_paterno, $contacto_materno, $contacto_sexo, $contacto_fecha_nac, $contacto_fecha_aniv, $contacto_sucursal, $contacto_departamento, $contacto_puesto, $contacto_tel, $contacto_email, $contacto_linkedin, $contacto_observaciones, $contacto_status, $contacto_modifico, $contacto_titulo){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$Error=false;
		$Idmaximo="";
		
		
		
		$repetidos = new Proveedor('mysql', 'inavtek');
		$repetidos->connect();
		//Obtengo el Id Maximo
		$sql_repetidos=" select * from contactos where rtrim(ltrim(contacto_nombre))='".$contacto_nombre."' and rtrim(ltrim(contacto_paterno))='".$contacto_paterno."' and rtrim(ltrim(contacto_materno))='".$contacto_materno."' and  contacto_status<>'E' ";
		$repetidos->execute($sql_repetidos);
		
		if (!$repetidos->error()){
			if ($repetidos->rows($repetidos->stmt) == 0) {
				$proveedor_M = new Proveedor('mysql', 'inavtek');
				$proveedor_M->connect();
				//Obtengo el Id Maximo
				$valormaximo=" select CASE when max(contacto_id+1) IS NULL then 1 else (max(contacto_id + 1)) end as IndiceMaximo from contactos ";
				$proveedor_M->execute($valormaximo);
				
				if (!$proveedor_M->error()){
					if ($proveedor_M->rows($proveedor_M->stmt) > 0) {
						$row_max = $proveedor_M->fetch_array($proveedor_M->stmt, 0);
						$Idmaximo=$row_max["IndiceMaximo"];
						
						$proveedor = new Proveedor('mysql', 'inavtek');
						$proveedor->connect();	
						$strSQL="insert into contactos ";
						$strSQL.="(
									contacto_id, 
									contacto_cliente_id, 
									contacto_principal, 
									contacto_nombre, 
									contacto_paterno, 
									contacto_materno, 
									contacto_sexo, 
									contacto_fecha_nac, 
									contacto_fecha_aniv, 
									contacto_sucursal, 
									contacto_departamento, 
									contacto_puesto, 
									contacto_tel, 
									contacto_email, 
									contacto_linkedin, 
									contacto_observaciones, 
									contacto_status, 
									contacto_modifico, 
									contacto_time_stamp,
									contacto_titulo
									) ";
									
									
						$strSQL.="VALUES ";
						
						
						$strSQL.="(".$Idmaximo.",";
						if($contacto_cliente_id!=""){
						$strSQL.=  $contacto_cliente_id.",";
						}else{ $strSQL.="NULL,"; }
						
						if($contacto_principal!=""){
						$strSQL.=  $contacto_principal.","; 
						}else{ $strSQL.="NULL,"; }
						
						
						$strSQL.="
								   '".$contacto_nombre."',
								   '".$contacto_paterno."',
								   '".$contacto_materno."',
								   '".$contacto_sexo."',
						";
						
						if($contacto_fecha_nac!=""){
						$strSQL.=  "'".$contacto_fecha_nac."',";
						}else{ $strSQL.="NULL,"; }
						
						if($contacto_fecha_aniv!=""){
						$strSQL.=  "'".$contacto_fecha_aniv."',"; 
						}else{ $strSQL.="NULL,"; }
						
						$strSQL.="	
									 '".$contacto_sucursal."',
								   '".$contacto_departamento."',
								   '".$contacto_puesto."',
								   '".$contacto_tel."',
								   '".$contacto_email."',
								   '".$contacto_linkedin."',
								   '".$contacto_observaciones."',
								   '".$contacto_status."',
								   '".$contacto_modifico."',
										now(),
									 '".$contacto_titulo."'
								   )"; 
						
						//echo "<pre>";
						//echo $strSQL;
						//echo "</pre>";
						$proveedor->execute($strSQL);
						
						if (!$proveedor->error()){
						}else{
							$Error=true;
						}
						
						$proveedor->close();
					
					}
				}else{
					$Error=true;
				}
				
				$proveedor_M->close();
				
				
				
				if($Error==false){
					$respuesta = array("totalCount" => "1","id" =>$Idmaximo, "estatus" => "ok", "mensaje" => "Se ha registrado correctamente");
				}else{
					$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al registrar");
				}
				
			}else{
				$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "El cliente ya se encuentra registrado.");
			}
		}else{
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al buscar repetidos.");
		}
		
		
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}

	public function editar_contactos($contacto_id, $contacto_cliente_id, $contacto_principal, $contacto_nombre, $contacto_paterno, $contacto_materno, $contacto_sexo, $contacto_fecha_nac, $contacto_fecha_aniv, $contacto_sucursal, $contacto_departamento, $contacto_puesto, $contacto_tel, $contacto_email, $contacto_linkedin, $contacto_observaciones, $contacto_status, $contacto_modifico, $contacto_titulo){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update contactos set ";
		//if($contacto_cliente_id!=""){
		//	$strSQL.="contacto_cliente_id=".$contacto_cliente_id.", ";
		//}else{ $strSQL.="contacto_cliente_id=NULL,"; }	

		
		if($contacto_principal!=""){
			$strSQL.="contacto_principal=".$contacto_principal.", ";	
		}else{ $strSQL.="contacto_principal=NULL,"; }	

		
		$strSQL.="	
					contacto_nombre='".$contacto_nombre."', 
					contacto_paterno='".$contacto_paterno."', 
					contacto_materno='".$contacto_materno."', 
					contacto_sexo='".$contacto_sexo."', 
		";
		
		if($contacto_fecha_nac!=""){
			$strSQL.="contacto_fecha_nac='".$contacto_fecha_nac."', ";
		}else{ $strSQL.="contacto_fecha_nac=NULL,"; }

		
		if($contacto_fecha_aniv!=""){
			$strSQL.="contacto_fecha_aniv='".$contacto_fecha_aniv."', ";	
		}else{ $strSQL.="contacto_fecha_aniv=NULL,"; }	
					
		$strSQL.="			
					contacto_sucursal='".$contacto_sucursal."', 
					contacto_departamento='".$contacto_departamento."', 
					contacto_puesto='".$contacto_puesto."', 
					contacto_tel='".$contacto_tel."', 
					contacto_email='".$contacto_email."', 
					contacto_linkedin='".$contacto_linkedin."', 
					contacto_observaciones='".$contacto_observaciones."', 
					contacto_status='".$contacto_status."', 
					contacto_modifico='".$contacto_modifico."', 
					contacto_time_stamp=now(),
					contacto_titulo='".$contacto_titulo."'
					
		";
		
		$strSQL.="where ";
		$strSQL.="contacto_id=".$contacto_id; 
		
		//echo "<pre>";
		//echo $strSQL;
		//echo "</pre>";
		$proveedor->execute($strSQL);
		
		if (!$proveedor->error()){
		}else{
			$Error=true;
		}
		
		$proveedor->close();
		
		
		
		if($Error==false){
			$respuesta = array("totalCount" => "1", "estatus" => "ok", "mensaje" => "Se ha actualizado el registro correctamente");
		}else{
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al actualizar información");
		}
		
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}	

	public function eliminar_contactos($contacto_id, $contacto_status, $contacto_modifico){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update contactos ";
		$strSQL.="set contacto_status='".$contacto_status."', contacto_modifico='".$contacto_modifico."', contacto_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="contacto_id=".$contacto_id; 
		//echo $strSQL;
		$proveedor->execute($strSQL);
		
		if (!$proveedor->error()){
		}else{
			$Error=true;
		}
		
		$proveedor->close();
		
		
		
		if($Error==false){
			$respuesta = array("totalCount" => "1", "estatus" => "ok", "mensaje" => "Se ha eliminado correctamente");
		}else{
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al eliminar");
		}
		
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}
}

@$contacto_id=$_POST["contacto_id"];
@$contacto_cliente_id=$_POST["contacto_cliente_id"];
@$contacto_principal=$_POST["contacto_principal"];
@$contacto_nombre=$_POST["contacto_nombre"];
@$contacto_paterno=$_POST["contacto_paterno"];
@$contacto_materno=$_POST["contacto_materno"];
@$contacto_sexo=$_POST["contacto_sexo"];
@$contacto_fecha_nac=$_POST["contacto_fecha_nac"];
@$contacto_fecha_aniv=$_POST["contacto_fecha_aniv"];
@$contacto_sucursal=$_POST["contacto_sucursal"];
@$contacto_departamento=$_POST["contacto_departamento"];
@$contacto_puesto=$_POST["contacto_puesto"];
@$contacto_tel=$_POST["contacto_tel"];
@$contacto_email=$_POST["contacto_email"];
@$contacto_linkedin=$_POST["contacto_linkedin"];
@$contacto_observaciones=$_POST["contacto_observaciones"];
@$contacto_status=$_POST["contacto_status"];
@$contacto_modifico=$_POST["contacto_modifico"];
@$contacto_time_stamp=$_POST["contacto_time_stamp"];
@$contacto_titulo=$_POST["contacto_titulo"];



@$accion=$_POST["accion"];
$Consultas = new consultas();
if($accion=="consultar_contactos"){
	$perfiles=$Consultas->consultar_contactos($contacto_id, $contacto_cliente_id);
	echo $perfiles; 
}else if($accion=="guardar_contactos"){	
	$usuarios=$Consultas->guardar_contactos($contacto_cliente_id, $contacto_principal, $contacto_nombre, $contacto_paterno, $contacto_materno, $contacto_sexo, $contacto_fecha_nac, $contacto_fecha_aniv, $contacto_sucursal, $contacto_departamento, $contacto_puesto, $contacto_tel, $contacto_email, $contacto_linkedin, $contacto_observaciones, $contacto_status, $contacto_modifico, $contacto_titulo);
	echo $usuarios;
}else if($accion=="editar_contactos"){	
	$usuarios=$Consultas->editar_contactos($contacto_id, $contacto_cliente_id, $contacto_principal, $contacto_nombre, $contacto_paterno, $contacto_materno, $contacto_sexo, $contacto_fecha_nac, $contacto_fecha_aniv, $contacto_sucursal, $contacto_departamento, $contacto_puesto, $contacto_tel, $contacto_email, $contacto_linkedin, $contacto_observaciones, $contacto_status, $contacto_modifico, $contacto_titulo);
	echo $usuarios;
}else if($accion=="eliminar_contactos"){	
	$usuarios=$Consultas->eliminar_contactos($contacto_id, $contacto_status, $contacto_modifico);
	echo $usuarios;
}




?>