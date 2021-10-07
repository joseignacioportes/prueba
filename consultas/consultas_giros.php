<?php
include_once("../datos/dtotojson/DtoToJson.Class.php");
include_once("../datos/json/JsonEncod.Class.php");
include_once("../datos/json/JsonDecod.Class.php");
include_once("../datos/connect/Proveedor.Class.php");
class consultas {
	public function __construct() {
	}
	
	public function consulta_giros($cliente_giro_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from clientes_giros where cliente_giro_status<>'E'
		";
		
		if($cliente_giro_id!=""){
			$sql.=" 
				and cliente_giro_id=".$cliente_giro_id."
			";				
		}
		
		
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {	
					$Data= array(
						"cliente_giro_id" => $row["cliente_giro_id"],
						"cliente_giro_desc" => rtrim(ltrim($row["cliente_giro_desc"]))
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
	
	public function guardar_giros($cliente_giro_id, $cliente_giro_desc, $cliente_giro_modifico, $cliente_giro_status){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$Error=false;
		$Idmaximo="";
		
		
		
		$repetidos = new Proveedor('mysql', 'inavtek');
		$repetidos->connect();
		//Obtengo el Id Maximo
		$sql_repetidos=" select * from clientes_giros where rtrim(ltrim(cliente_giro_desc))='".$cliente_giro_desc."' and cliente_giro_status<>'E' ";
		$repetidos->execute($sql_repetidos);
		
		if (!$repetidos->error()){
			if ($repetidos->rows($repetidos->stmt) == 0) {
				$proveedor_M = new Proveedor('mysql', 'inavtek');
				$proveedor_M->connect();
				//Obtengo el Id Maximo
				$valormaximo=" select CASE when max(cliente_giro_id+1) IS NULL then 1 else (max(cliente_giro_id + 1)) end as IndiceMaximo from clientes_giros ";
				$proveedor_M->execute($valormaximo);
				
				if (!$proveedor_M->error()){
					if ($proveedor_M->rows($proveedor_M->stmt) > 0) {
						$row_max = $proveedor_M->fetch_array($proveedor_M->stmt, 0);
						$Idmaximo=$row_max["IndiceMaximo"];
						
						$proveedor = new Proveedor('mysql', 'inavtek');
						$proveedor->connect();	
						$strSQL="insert into clientes_giros ";
						$strSQL.="(cliente_giro_id, cliente_giro_desc,  cliente_giro_status, cliente_giro_modifico, cliente_giro_time_stamp) "; 
						$strSQL.="VALUES ";
						$strSQL.="(".$Idmaximo.", '".$cliente_giro_desc."',  '".$cliente_giro_status."', '".$cliente_giro_modifico."', now())"; 
						
						
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
					$respuesta = array("totalCount" => "1","cliente_giro_id" =>$Idmaximo, "estatus" => "ok", "mensaje" => "Se ha registrado correctamente");
				}else{
					$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al registrar");
				}
				
			}else{
				$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "El giro ya se encuentra registrado.");
			}
		}else{
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al buscar repetidos.");
		}
		
		
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}

	public function editar_giros($cliente_giro_id, $cliente_giro_desc, $cliente_giro_modifico, $cliente_giro_status){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update clientes_giros ";
		$strSQL.="set cliente_giro_desc='".$cliente_giro_desc."', cliente_giro_status='".$cliente_giro_status."', cliente_giro_modifico='".$cliente_giro_modifico."', cliente_giro_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="cliente_giro_id=".$cliente_giro_id; 
		//echo $strSQL;
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

	public function eliminar_giros($cliente_giro_id, $cliente_giro_status, $cliente_giro_modifico){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update clientes_giros ";
		$strSQL.="set cliente_giro_status='".$cliente_giro_status."', cliente_giro_modifico='".$cliente_giro_modifico."', cliente_giro_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="cliente_giro_id=".$cliente_giro_id; 
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

@$cliente_giro_id=$_POST["cliente_giro_id"];
@$cliente_giro_desc=$_POST["cliente_giro_desc"];
@$cliente_giro_time_stamp=$_POST["cliente_giro_time_stamp"];
@$cliente_giro_modifico=$_POST["cliente_giro_modifico"];
@$cliente_giro_status=$_POST["cliente_giro_status"];

@$accion=$_POST["accion"];
$Consultas = new consultas();
if($accion=="consulta_giros"){
	$giros=$Consultas->consulta_giros($cliente_giro_id);
	echo $giros; 
}else if($accion=="guardar_giros"){	
	$giros=$Consultas->guardar_giros($cliente_giro_id, $cliente_giro_desc, $cliente_giro_modifico, $cliente_giro_status);
	echo $giros;
}else if($accion=="editar_giros"){	
	$giros=$Consultas->editar_giros($cliente_giro_id, $cliente_giro_desc, $cliente_giro_modifico, $cliente_giro_status);
	echo $giros;
}else if($accion=="eliminar_giros"){	
	$giros=$Consultas->eliminar_giros($cliente_giro_id, $cliente_giro_status, $cliente_giro_modifico);
	echo $giros;
}




?>