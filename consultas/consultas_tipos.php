<?php
include_once("../datos/dtotojson/DtoToJson.Class.php");
include_once("../datos/json/JsonEncod.Class.php");
include_once("../datos/json/JsonDecod.Class.php");
include_once("../datos/connect/Proveedor.Class.php");
class consultas {
	public function __construct() {
	}
	
	public function consulta_tipos($cliente_tipo_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from clientes_tipos where cliente_tipo_status<>'E'
		";
		
		if($cliente_tipo_id!=""){
			$sql.=" 
				and cliente_tipo_id=".$cliente_tipo_id."
			";				
		}
		
		
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					
					
					$Data= array(
						"cliente_tipo_id" => $row["cliente_tipo_id"],
						"cliente_tipo_desc" => rtrim(ltrim($row["cliente_tipo_desc"]))
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
	
	public function guardar_tipos($cliente_tipo_id, $cliente_tipo_desc, $cliente_tipo_modifico, $cliente_tipo_status){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$Error=false;
		$Idmaximo="";
		
		
		
		$repetidos = new Proveedor('mysql', 'inavtek');
		$repetidos->connect();
		//Obtengo el Id Maximo
		$sql_repetidos=" select * from clientes_tipos where rtrim(ltrim(cliente_tipo_desc))='".$cliente_tipo_desc."' and cliente_tipo_status<>'E' ";
		$repetidos->execute($sql_repetidos);
		
		if (!$repetidos->error()){
			if ($repetidos->rows($repetidos->stmt) == 0) {
				$proveedor_M = new Proveedor('mysql', 'inavtek');
				$proveedor_M->connect();
				//Obtengo el Id Maximo
				$valormaximo=" select CASE when max(cliente_tipo_id+1) IS NULL then 1 else (max(cliente_tipo_id + 1)) end as IndiceMaximo from clientes_tipos ";
				$proveedor_M->execute($valormaximo);
				
				if (!$proveedor_M->error()){
					if ($proveedor_M->rows($proveedor_M->stmt) > 0) {
						$row_max = $proveedor_M->fetch_array($proveedor_M->stmt, 0);
						$Idmaximo=$row_max["IndiceMaximo"];
						
						$proveedor = new Proveedor('mysql', 'inavtek');
						$proveedor->connect();	
						$strSQL="insert into clientes_tipos ";
						$strSQL.="(cliente_tipo_id, cliente_tipo_desc,  cliente_tipo_status, cliente_tipo_modifico, cliente_tipo_time_stamp) "; 
						$strSQL.="VALUES ";
						$strSQL.="(".$Idmaximo.", '".$cliente_tipo_desc."',  '".$cliente_tipo_status."', '".$cliente_tipo_modifico."', now())"; 
						
						
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
					$respuesta = array("totalCount" => "1","cliente_tipo_id" =>$Idmaximo, "estatus" => "ok", "mensaje" => "Se ha registrado correctamente");
				}else{
					$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al registrar");
				}
				
			}else{
				$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "El tipo ya se encuentra registrado.");
			}
		}else{
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al buscar repetidos.");
		}
		
		
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}

	public function editar_tipos($cliente_tipo_id, $cliente_tipo_desc, $cliente_tipo_modifico, $cliente_tipo_status){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update clientes_tipos ";
		$strSQL.="set cliente_tipo_desc='".$cliente_tipo_desc."', cliente_tipo_status='".$cliente_tipo_status."', cliente_tipo_modifico='".$cliente_tipo_modifico."', cliente_tipo_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="cliente_tipo_id=".$cliente_tipo_id; 
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

	public function eliminar_tipos($cliente_tipo_id, $cliente_tipo_status, $cliente_tipo_modifico){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update clientes_tipos ";
		$strSQL.="set cliente_tipo_status='".$cliente_tipo_status."', cliente_tipo_modifico='".$cliente_tipo_modifico."', cliente_tipo_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="cliente_tipo_id=".$cliente_tipo_id; 
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

@$cliente_tipo_id=$_POST["cliente_tipo_id"];
@$cliente_tipo_desc=$_POST["cliente_tipo_desc"];
@$cliente_tipo_time_stamp=$_POST["cliente_tipo_time_stamp"];
@$cliente_tipo_modifico=$_POST["cliente_tipo_modifico"];
@$cliente_tipo_status=$_POST["cliente_tipo_status"];

@$accion=$_POST["accion"];
$Consultas = new consultas();
if($accion=="consulta_tipos"){
	$tipos=$Consultas->consulta_tipos($cliente_tipo_id);
	echo $tipos; 
}else if($accion=="guardar_tipos"){	
	$tipos=$Consultas->guardar_tipos($cliente_tipo_id, $cliente_tipo_desc, $cliente_tipo_modifico, $cliente_tipo_status);
	echo $tipos;
}else if($accion=="editar_tipos"){	
	$tipos=$Consultas->editar_tipos($cliente_tipo_id, $cliente_tipo_desc, $cliente_tipo_modifico, $cliente_tipo_status);
	echo $tipos;
}else if($accion=="eliminar_tipos"){	
	$tipos=$Consultas->eliminar_tipos($cliente_tipo_id, $cliente_tipo_status, $cliente_tipo_modifico);
	echo $tipos;
}




?>