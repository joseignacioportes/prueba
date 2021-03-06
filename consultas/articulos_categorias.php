<?php
include_once("../datos/dtotojson/DtoToJson.Class.php");
include_once("../datos/json/JsonEncod.Class.php");
include_once("../datos/json/JsonDecod.Class.php");
include_once("../datos/connect/Proveedor.Class.php");
class consultas {
	public function __construct() {
	}
	
	public function consultar($articulo_categoria_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select 
				*,
				(select articulo_linea_descripcion from articulos_lineas where articulo_linea_status<>'E' and articulos_lineas.articulo_linea_id=articulos_categorias.articulo_linea_id) as Desc_Linea,
				(select articulo_familia_descripcion from articulos_familias where articulo_familia_status<>'E' and articulos_familias.articulo_familia_id=articulos_categorias.articulo_familia_id) as Desc_Familia	
			from articulos_categorias where articulo_categoria_status<>'E'
		";
		
		if($articulo_categoria_id!=""){
			$sql.=" 
				and articulo_categoria_id=".$articulo_categoria_id."
			";				
		}
		
		//echo $sql;
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					
					
					$Data= array(
						"articulo_categoria_id" => $row["articulo_categoria_id"],
						"articulo_linea_id" => $row["articulo_linea_id"],
						"articulo_familia_id" => $row["articulo_familia_id"],
						"articulo_categoria_descripcion" => rtrim(ltrim($row["articulo_categoria_descripcion"])),
						"Desc_Linea" => rtrim(ltrim($row["Desc_Linea"])),
						"Desc_Familia" => rtrim(ltrim($row["Desc_Familia"]))
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
	
	public function guardar($articulo_categoria_id, $articulo_linea_id, $articulo_familia_id, $articulo_categoria_descripcion, $articulo_categoria_modifico, $articulo_categoria_status){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$Error=false;
		$Idmaximo="";
		
		
		
		$repetidos = new Proveedor('mysql', 'inavtek');
		$repetidos->connect();
		//Obtengo el Id Maximo
		$sql_repetidos=" select * from articulos_categorias where rtrim(ltrim(articulo_categoria_descripcion))='".$articulo_categoria_descripcion."' and articulo_linea_id=".$articulo_linea_id." and articulo_familia_id=".$articulo_familia_id." and articulo_categoria_status<>'E' ";
		$repetidos->execute($sql_repetidos);
		
		if (!$repetidos->error()){
			if ($repetidos->rows($repetidos->stmt) == 0) {
				$proveedor_M = new Proveedor('mysql', 'inavtek');
				$proveedor_M->connect();
				//Obtengo el Id Maximo
				$valormaximo=" select CASE when max(articulo_categoria_id+1) IS NULL then 1 else (max(articulo_categoria_id + 1)) end as IndiceMaximo from articulos_categorias ";
				$proveedor_M->execute($valormaximo);
				
				if (!$proveedor_M->error()){
					if ($proveedor_M->rows($proveedor_M->stmt) > 0) {
						$row_max = $proveedor_M->fetch_array($proveedor_M->stmt, 0);
						$Idmaximo=$row_max["IndiceMaximo"];
						
						$proveedor = new Proveedor('mysql', 'inavtek');
						$proveedor->connect();	
						$strSQL="insert into articulos_categorias ";
						$strSQL.="(articulo_categoria_id, articulo_linea_id, articulo_familia_id, articulo_categoria_descripcion,  articulo_categoria_status, articulo_categoria_modifico, articulo_categoria_time_stamp) "; 
						$strSQL.="VALUES ";
						$strSQL.="(".$Idmaximo.", ".$articulo_linea_id.", ".$articulo_familia_id.", '".$articulo_categoria_descripcion."',  '".$articulo_categoria_status."', '".$articulo_categoria_modifico."', now())"; 
						
						
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
				$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "La descripci?n ya se encuentra registrado.");
			}
		}else{
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al buscar repetidos.");
		}
		
		
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}

	public function editar($articulo_categoria_id, $articulo_linea_id, $articulo_familia_id, $articulo_categoria_descripcion, $articulo_categoria_modifico, $articulo_categoria_status){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update articulos_categorias ";
		$strSQL.="set articulo_linea_id=".$articulo_linea_id.", articulo_familia_id=".$articulo_familia_id.", articulo_categoria_descripcion='".$articulo_categoria_descripcion."', articulo_categoria_status='".$articulo_categoria_status."', articulo_categoria_modifico='".$articulo_categoria_modifico."', articulo_categoria_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="articulo_categoria_id=".$articulo_categoria_id; 
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
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al actualizar informaci?n");
		}
		
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}	

	public function eliminar($articulo_categoria_id, $articulo_categoria_status, $articulo_categoria_modifico){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update articulos_categorias ";
		$strSQL.="set articulo_categoria_status='".$articulo_categoria_status."', articulo_categoria_modifico='".$articulo_categoria_modifico."', articulo_categoria_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="articulo_categoria_id=".$articulo_categoria_id; 
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

@$articulo_categoria_id=$_POST["articulo_categoria_id"];
@$articulo_linea_id=$_POST["articulo_linea_id"];
@$articulo_familia_id=$_POST["articulo_familia_id"];
@$articulo_categoria_descripcion=$_POST["articulo_categoria_descripcion"];
@$articulo_categoria_status=$_POST["articulo_categoria_status"];
@$articulo_categoria_modifico=$_POST["articulo_categoria_modifico"];
@$articulo_categoria_time_stamp=$_POST["articulo_categoria_time_stamp"];

@$accion=$_POST["accion"];
$Consultas = new consultas();
if($accion=="consultar"){
	$acc=$Consultas->consultar($articulo_categoria_id);
	echo $acc; 
}else if($accion=="guardar"){	
	$acc=$Consultas->guardar($articulo_categoria_id, $articulo_linea_id, $articulo_familia_id, $articulo_categoria_descripcion, $articulo_categoria_modifico, $articulo_categoria_status);
	echo $acc;
}else if($accion=="editar"){	
	$acc=$Consultas->editar($articulo_categoria_id, $articulo_linea_id, $articulo_familia_id, $articulo_categoria_descripcion, $articulo_categoria_modifico, $articulo_categoria_status);
	echo $acc;
}else if($accion=="eliminar"){	
	$acc=$Consultas->eliminar($articulo_categoria_id, $articulo_categoria_status, $articulo_categoria_modifico);
	echo $acc;
}




?>