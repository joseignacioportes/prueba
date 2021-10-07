<?php
include_once("../datos/dtotojson/DtoToJson.Class.php");
include_once("../datos/json/JsonEncod.Class.php");
include_once("../datos/json/JsonDecod.Class.php");
include_once("../datos/connect/Proveedor.Class.php");
class consultas {
	public function __construct() {
	}
	
	public function consultar($art_alm_exst_almancen_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from articulos_almacenes where art_alm_exst_almancen_status<>'E'
		";
		
		if($art_alm_exst_almancen_id!=""){
			$sql.=" 
				and art_alm_exst_almancen_id=".$art_alm_exst_almancen_id."
			";				
		}
		
		
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					
					
					$Data= array(
						"art_alm_exst_almancen_id" => $row["art_alm_exst_almancen_id"],
						"art_alm_exst_almancen_codigo" => $row["art_alm_exst_almancen_codigo"],
						"art_alm_exst_almancen_descripcion" => rtrim(ltrim($row["art_alm_exst_almancen_descripcion"]))
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
	
	public function guardar($art_alm_exst_almancen_id, $art_alm_exst_almancen_codigo, $art_alm_exst_almancen_descripcion, $art_alm_exst_almancen_modifico, $art_alm_exst_almancen_status){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$Error=false;
		$Idmaximo="";
		
		
		
		$repetidos = new Proveedor('mysql', 'inavtek');
		$repetidos->connect();
		//Obtengo el Id Maximo
		$sql_repetidos=" select * from articulos_almacenes where rtrim(ltrim(art_alm_exst_almancen_codigo))='".$art_alm_exst_almancen_codigo."' and art_alm_exst_almancen_status<>'E' ";
		$repetidos->execute($sql_repetidos);
		
		if (!$repetidos->error()){
			if ($repetidos->rows($repetidos->stmt) == 0) {
				$proveedor_M = new Proveedor('mysql', 'inavtek');
				$proveedor_M->connect();
				//Obtengo el Id Maximo
				$valormaximo=" select CASE when max(art_alm_exst_almancen_id+1) IS NULL then 1 else (max(art_alm_exst_almancen_id + 1)) end as IndiceMaximo from articulos_almacenes ";
				$proveedor_M->execute($valormaximo);
				
				if (!$proveedor_M->error()){
					if ($proveedor_M->rows($proveedor_M->stmt) > 0) {
						$row_max = $proveedor_M->fetch_array($proveedor_M->stmt, 0);
						$Idmaximo=$row_max["IndiceMaximo"];
						
						$proveedor = new Proveedor('mysql', 'inavtek');
						$proveedor->connect();	
						$strSQL="insert into articulos_almacenes ";
						$strSQL.="(art_alm_exst_almancen_id, art_alm_exst_almancen_codigo, art_alm_exst_almancen_descripcion,  art_alm_exst_almancen_status, art_alm_exst_almancen_modifico, art_alm_exst_almancen_time_stamp) "; 
						$strSQL.="VALUES ";
						$strSQL.="(".$Idmaximo.", '".$art_alm_exst_almancen_codigo."', '".$art_alm_exst_almancen_descripcion."', '".$art_alm_exst_almancen_status."', '".$art_alm_exst_almancen_modifico."', now())"; 
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
				$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "El código ya se encuentra registrado.");
			}
		}else{
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al buscar repetidos.");
		}
		
		
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}

	public function editar($art_alm_exst_almancen_id, $art_alm_exst_almancen_codigo, $art_alm_exst_almancen_descripcion, $art_alm_exst_almancen_modifico, $art_alm_exst_almancen_status){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update articulos_almacenes ";
		$strSQL.="set art_alm_exst_almancen_codigo='".$art_alm_exst_almancen_codigo."', art_alm_exst_almancen_descripcion='".$art_alm_exst_almancen_descripcion."', art_alm_exst_almancen_status='".$art_alm_exst_almancen_status."', art_alm_exst_almancen_modifico='".$art_alm_exst_almancen_modifico."', art_alm_exst_almancen_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="art_alm_exst_almancen_id=".$art_alm_exst_almancen_id; 
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

	public function eliminar($art_alm_exst_almancen_id, $art_alm_exst_almancen_status, $art_alm_exst_almancen_modifico){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update articulos_almacenes ";
		$strSQL.="set art_alm_exst_almancen_status='".$art_alm_exst_almancen_status."', art_alm_exst_almancen_modifico='".$art_alm_exst_almancen_modifico."', art_alm_exst_almancen_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="art_alm_exst_almancen_id=".$art_alm_exst_almancen_id; 
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

@$art_alm_exst_almancen_id=$_POST["art_alm_exst_almancen_id"];
@$art_alm_exst_almancen_codigo=$_POST["art_alm_exst_almancen_codigo"];
@$art_alm_exst_almancen_descripcion=$_POST["art_alm_exst_almancen_descripcion"];
@$art_alm_exst_almancen_status=$_POST["art_alm_exst_almancen_status"];
@$art_alm_exst_almancen_modifico=$_POST["art_alm_exst_almancen_modifico"];
@$art_alm_exst_almancen_time_stamp=$_POST["art_alm_exst_almancen_time_stamp"];

@$accion=$_POST["accion"];
$Consultas = new consultas();
if($accion=="consultar"){
	$acc=$Consultas->consultar($art_alm_exst_almancen_id);
	echo $acc; 
}else if($accion=="guardar"){	
	$acc=$Consultas->guardar($art_alm_exst_almancen_id, $art_alm_exst_almancen_codigo, $art_alm_exst_almancen_descripcion, $art_alm_exst_almancen_modifico, $art_alm_exst_almancen_status);
	echo $acc;
}else if($accion=="editar"){	
	$acc=$Consultas->editar($art_alm_exst_almancen_id, $art_alm_exst_almancen_codigo, $art_alm_exst_almancen_descripcion, $art_alm_exst_almancen_modifico, $art_alm_exst_almancen_status);
	echo $acc;
}else if($accion=="eliminar"){	
	$acc=$Consultas->eliminar($art_alm_exst_almancen_id, $art_alm_exst_almancen_status, $art_alm_exst_almancen_modifico);
	echo $acc;
}




?>