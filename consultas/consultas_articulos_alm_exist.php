<?php
include_once("../datos/dtotojson/DtoToJson.Class.php");
include_once("../datos/json/JsonEncod.Class.php");
include_once("../datos/json/JsonDecod.Class.php");
include_once("../datos/connect/Proveedor.Class.php");
class consultas {
	public function __construct() {
	}
	
	
	public function cmb_articulos(){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from articulos where articulo_status<>'E'
		";
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$Data= array(
						"articulo_id" => rtrim(ltrim($row["articulo_id"])),
						"articulo_cod_barras" => rtrim(ltrim($row["articulo_cod_barras"])),
						"articulo_cod_interno" => rtrim(ltrim($row["articulo_cod_interno"])),
						"articulo_descripcion" => rtrim(ltrim($row["articulo_descripcion"]))
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
	
	public function cmb_almacenes(){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from articulos_almacenes where art_alm_exst_almancen_status<>'E'
		";
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$Data= array(
						"art_alm_exst_almancen_id" => rtrim(ltrim($row["art_alm_exst_almancen_id"])),
						"art_alm_exst_almancen_codigo" => rtrim(ltrim($row["art_alm_exst_almancen_codigo"])),
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
	
	public function cmb_estados(){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from articulos_estados where art_alm_exst_estado_status<>'E'
		";
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$Data= array(
						"art_alm_exst_estado_id" => rtrim(ltrim($row["art_alm_exst_estado_id"])),
						"art_alm_exst_estado_descripcion" => rtrim(ltrim($row["art_alm_exst_estado_descripcion"])),
						"art_alm_exst_estado_status" => rtrim(ltrim($row["art_alm_exst_estado_status"])),
						"art_alm_exst_estado_modifico" => rtrim(ltrim($row["art_alm_exst_estado_modifico"])),
						"art_alm_exst_estado_time_stamp" => rtrim(ltrim($row["art_alm_exst_estado_time_stamp"]))
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
	
	public function consulta_articulos_alm_exist($art_alm_exst_id, $art_alm_exst_articulo_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select 
			Alm.art_alm_exst_id, 
			Alm.art_alm_exst_articulo_id, Alm.art_alm_exst_almancen_id, Alm.art_alm_exst_estado_id, Alm.art_alm_exst_ubicacion, Alm.art_alm_exst_existencia, Alm.art_alm_exst_maximo, Alm.art_alm_exst_minimo, Alm.art_alm_exst_punto_reorden, Alm.art_alm_exst_precio, Alm.art_alm_exst_status, Alm.art_alm_exst_modifico, Alm.art_alm_exst_time_stamp, Alm.art_alm_exst_serie, Alm.art_alm_exst_cod_barras
			,A.articulo_descripcion
			,M.art_alm_exst_almancen_codigo
			,M.art_alm_exst_almancen_descripcion
			,E.art_alm_exst_estado_descripcion
			from articulos_alm_exist Alm
			left join articulos A on Alm.art_alm_exst_articulo_id=A.articulo_id
			left join articulos_almacenes M on Alm.art_alm_exst_almancen_id=M.art_alm_exst_almancen_id 
			left join articulos_estados E on Alm.art_alm_exst_estado_id=E.art_alm_exst_estado_id
			where art_alm_exst_status<>'E'
		";
		
		if($art_alm_exst_id!=""){
			$sql.=" 
				and art_alm_exst_id=".$art_alm_exst_id."
			";				
		}
		
		if($art_alm_exst_articulo_id!=""){
			$sql.=" 
				and art_alm_exst_articulo_id=".$art_alm_exst_articulo_id."
			";				
		}
		
		
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					$art_alm_exst_articulo_id="";
					if(rtrim(ltrim($row["art_alm_exst_articulo_id"]))!="NULL"){$art_alm_exst_articulo_id=rtrim(ltrim($row["art_alm_exst_articulo_id"]));}
					$art_alm_exst_almancen_id="";
					if(rtrim(ltrim($row["art_alm_exst_almancen_id"]))!="NULL"){$art_alm_exst_almancen_id=rtrim(ltrim($row["art_alm_exst_almancen_id"]));}
					$art_alm_exst_estado_id="";
					if(rtrim(ltrim($row["art_alm_exst_estado_id"]))!="NULL"){$art_alm_exst_estado_id=rtrim(ltrim($row["art_alm_exst_estado_id"]));}
					$art_alm_exst_existencia="";
					if(rtrim(ltrim($row["art_alm_exst_existencia"]))!="NULL"){$art_alm_exst_existencia=rtrim(ltrim($row["art_alm_exst_existencia"]));}
					$art_alm_exst_maximo="";
					if(rtrim(ltrim($row["art_alm_exst_maximo"]))!="NULL"){$art_alm_exst_maximo=rtrim(ltrim($row["art_alm_exst_maximo"]));}
					$art_alm_exst_minimo="";
					if(rtrim(ltrim($row["art_alm_exst_minimo"]))!="NULL"){$art_alm_exst_minimo=rtrim(ltrim($row["art_alm_exst_minimo"]));}
					$art_alm_exst_punto_reorden="";
					if(rtrim(ltrim($row["art_alm_exst_punto_reorden"]))!="NULL"){$art_alm_exst_punto_reorden=rtrim(ltrim($row["art_alm_exst_punto_reorden"]));}
					$art_alm_exst_precio="";
					if(rtrim(ltrim($row["art_alm_exst_precio"]))!="NULL"){$art_alm_exst_precio=rtrim(ltrim($row["art_alm_exst_precio"]));}
					$articulo_descripcion="";
					if(rtrim(ltrim($row["articulo_descripcion"]))!="NULL"){$articulo_descripcion=rtrim(ltrim($row["articulo_descripcion"]));}
					$art_alm_exst_almancen_codigo="";
					if(rtrim(ltrim($row["art_alm_exst_almancen_codigo"]))!="NULL"){$art_alm_exst_almancen_codigo=rtrim(ltrim($row["art_alm_exst_almancen_codigo"]));}
					$art_alm_exst_almancen_descripcion="";
					if(rtrim(ltrim($row["art_alm_exst_almancen_descripcion"]))!="NULL"){$art_alm_exst_almancen_descripcion=rtrim(ltrim($row["art_alm_exst_almancen_descripcion"]));}
					$art_alm_exst_estado_descripcion="";
					if(rtrim(ltrim($row["art_alm_exst_estado_descripcion"]))!="NULL"){$art_alm_exst_estado_descripcion=rtrim(ltrim($row["art_alm_exst_estado_descripcion"]));}
					$Data= array(
						"art_alm_exst_id" => $row["art_alm_exst_id"],
						"art_alm_exst_articulo_id" => $art_alm_exst_articulo_id,
						"art_alm_exst_almancen_id" => $art_alm_exst_almancen_id,
						"art_alm_exst_estado_id" => $art_alm_exst_estado_id,
						"art_alm_exst_ubicacion" => rtrim(ltrim($row["art_alm_exst_ubicacion"])),
						"art_alm_exst_existencia" => $art_alm_exst_existencia,
						"art_alm_exst_maximo" => $art_alm_exst_maximo,
						"art_alm_exst_minimo" => $art_alm_exst_minimo,
						"art_alm_exst_punto_reorden" => $art_alm_exst_punto_reorden,
						"art_alm_exst_precio" => $art_alm_exst_precio,
						"art_alm_exst_status" => $row["art_alm_exst_status"],
						"art_alm_exst_modifico" => $row["art_alm_exst_modifico"],
						"articulo_descripcion" => $articulo_descripcion,
						"art_alm_exst_almancen_codigo" => $art_alm_exst_almancen_codigo,
						"art_alm_exst_almancen_descripcion" => $art_alm_exst_almancen_descripcion,
						"art_alm_exst_estado_descripcion" => $art_alm_exst_estado_descripcion,
						"art_alm_exst_serie" => $row["art_alm_exst_serie"],
						"art_alm_exst_cod_barras" => $row["art_alm_exst_cod_barras"],
						"art_alm_exst_time_stamp" => rtrim(ltrim($row["art_alm_exst_time_stamp"]))
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
	
	public function guardar_articulos_alm_exist($art_alm_exst_id, $art_alm_exst_articulo_id, $art_alm_exst_almancen_id, $art_alm_exst_estado_id, $art_alm_exst_ubicacion, $art_alm_exst_existencia, $art_alm_exst_maximo, $art_alm_exst_minimo, $art_alm_exst_punto_reorden, $art_alm_exst_precio, $art_alm_exst_status, $art_alm_exst_modifico, $art_alm_exst_serie, $art_alm_exst_cod_barras){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$Error=false;
		$Idmaximo="";
		
		
		$proveedor_M = new Proveedor('mysql', 'inavtek');
		$proveedor_M->connect();
		//Obtengo el Id Maximo
		$valormaximo=" select CASE when max(art_alm_exst_id+1) IS NULL then 1 else (max(art_alm_exst_id + 1)) end as IndiceMaximo from articulos_alm_exist ";
		$proveedor_M->execute($valormaximo);
		
		if (!$proveedor_M->error()){
			if ($proveedor_M->rows($proveedor_M->stmt) > 0) {
				$row_max = $proveedor_M->fetch_array($proveedor_M->stmt, 0);
				$Idmaximo=$row_max["IndiceMaximo"];
				
				$proveedor = new Proveedor('mysql', 'inavtek');
				$proveedor->connect();	
				$strSQL="insert into articulos_alm_exist ";
				$strSQL.="(
										art_alm_exst_id, 
										art_alm_exst_articulo_id,  
										art_alm_exst_almancen_id, 
										art_alm_exst_estado_id, 
										art_alm_exst_ubicacion,
										art_alm_exst_existencia,
										art_alm_exst_maximo,
										art_alm_exst_minimo,
										art_alm_exst_punto_reorden,
										art_alm_exst_precio,
										art_alm_exst_status,
										art_alm_exst_modifico,
										art_alm_exst_time_stamp,
										art_alm_exst_serie,
										art_alm_exst_cod_barras
									) "; 
				$strSQL.="VALUES ";
				$strSQL.="(";
				$strSQL.=	"".$Idmaximo.",";
				if($art_alm_exst_articulo_id!=""){$strSQL.=  $art_alm_exst_articulo_id.",";}else{ $strSQL.="NULL,"; }	
				if($art_alm_exst_almancen_id!=""){$strSQL.=  $art_alm_exst_almancen_id.",";}else{ $strSQL.="NULL,"; }	
				if($art_alm_exst_estado_id!=""){$strSQL.=  $art_alm_exst_estado_id.",";}else{ $strSQL.="NULL,"; }
				$strSQL.=	"'".$art_alm_exst_ubicacion."',";
				if($art_alm_exst_existencia!=""){$strSQL.=  "'".$art_alm_exst_existencia."',";}else{ $strSQL.="NULL,"; }
				if($art_alm_exst_maximo!=""){$strSQL.=  "'".$art_alm_exst_maximo."',";}else{ $strSQL.="NULL,"; }
				if($art_alm_exst_minimo!=""){$strSQL.=  "'".$art_alm_exst_minimo."',";}else{ $strSQL.="NULL,"; }
				if($art_alm_exst_punto_reorden!=""){$strSQL.=  "'".$art_alm_exst_punto_reorden."',";}else{ $strSQL.="NULL,"; }
				if($art_alm_exst_precio!=""){$strSQL.=  "'".$art_alm_exst_precio."',";}else{ $strSQL.="NULL,"; }
				$strSQL.=	"'".$art_alm_exst_status."',";
				$strSQL.=	"'".$art_alm_exst_modifico."',";
				$strSQL.=	"now(),";
				$strSQL.=	"'".$art_alm_exst_serie."',";
				$strSQL.=	"'".$art_alm_exst_cod_barras."'";					
				$strSQL.=")";
				
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
			$respuesta = array("totalCount" => "1","art_alm_exst_id" =>$Idmaximo, "estatus" => "ok", "mensaje" => "Se ha registrado correctamente");
		}else{
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al registrar");
		}
				
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}

	public function editar_articulos_alm_exist($art_alm_exst_id, $art_alm_exst_articulo_id, $art_alm_exst_almancen_id, $art_alm_exst_estado_id, $art_alm_exst_ubicacion, $art_alm_exst_existencia, $art_alm_exst_maximo, $art_alm_exst_minimo, $art_alm_exst_punto_reorden, $art_alm_exst_precio, $art_alm_exst_status, $art_alm_exst_modifico, $art_alm_exst_serie, $art_alm_exst_cod_barras){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update articulos_alm_exist ";
		$strSQL.="set "; 
		
		if($art_alm_exst_articulo_id!=""){
			$strSQL.="art_alm_exst_articulo_id=".$art_alm_exst_articulo_id.",";
		}else{ $strSQL.="art_alm_exst_articulo_id=NULL,"; }
		
		if($art_alm_exst_almancen_id!=""){
			$strSQL.="art_alm_exst_almancen_id=".$art_alm_exst_almancen_id.",";
		}else{ $strSQL.="art_alm_exst_almancen_id=NULL,"; }	
		
		if($art_alm_exst_estado_id!=""){
			$strSQL.="art_alm_exst_estado_id=".$art_alm_exst_estado_id.",";
		}else{ $strSQL.="art_alm_exst_estado_id=NULL,"; }
		
		$strSQL.="	art_alm_exst_ubicacion='".$art_alm_exst_ubicacion."', ";
		
		if($art_alm_exst_existencia!=""){
		$strSQL.="art_alm_exst_existencia='".$art_alm_exst_existencia."',";
		}else{ $strSQL.="art_alm_exst_existencia=NULL,"; }
		
		if($art_alm_exst_maximo!=""){
		$strSQL.="art_alm_exst_maximo='".$art_alm_exst_maximo."',";
		}else{ $strSQL.="art_alm_exst_maximo=NULL,"; }
		
		if($art_alm_exst_minimo!=""){
		$strSQL.="art_alm_exst_minimo='".$art_alm_exst_minimo."',";
		}else{ $strSQL.="art_alm_exst_minimo=NULL,"; }
		
		if($art_alm_exst_punto_reorden!=""){
		$strSQL.="art_alm_exst_punto_reorden='".$art_alm_exst_punto_reorden."',";
		}else{ $strSQL.="art_alm_exst_punto_reorden=NULL,"; }
		
		if($art_alm_exst_precio!=""){
		$strSQL.="art_alm_exst_precio='".$art_alm_exst_precio."',";
		}else{ $strSQL.="art_alm_exst_precio=NULL,"; }
		
		$strSQL.="	art_alm_exst_status='".$art_alm_exst_status."', ";
		$strSQL.="	art_alm_exst_modifico='".$art_alm_exst_modifico."', ";
		$strSQL.="	art_alm_exst_serie='".$art_alm_exst_serie."', ";
		$strSQL.="	art_alm_exst_cod_barras='".$art_alm_exst_cod_barras."', ";
		$strSQL.="	art_alm_exst_time_stamp=now() ";
		
		$strSQL.="where ";
		$strSQL.="art_alm_exst_id=".$art_alm_exst_id; 
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

	public function eliminar_articulos_alm_exist($art_alm_exst_id, $art_alm_exst_status, $art_alm_exst_modifico){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update articulos_alm_exist ";
		$strSQL.="set art_alm_exst_status='".$art_alm_exst_status."', art_alm_exst_modifico='".$art_alm_exst_modifico."', art_alm_exst_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="art_alm_exst_id=".$art_alm_exst_id; 
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

@$art_alm_exst_id =$_POST["art_alm_exst_id"];
@$art_alm_exst_articulo_id=$_POST["art_alm_exst_articulo_id"];
@$art_alm_exst_almancen_id=$_POST["art_alm_exst_almancen_id"];
@$art_alm_exst_estado_id=$_POST["art_alm_exst_estado_id"];
@$art_alm_exst_ubicacion=$_POST["art_alm_exst_ubicacion"];
@$art_alm_exst_existencia =$_POST["art_alm_exst_existencia"];
@$art_alm_exst_maximo=$_POST["art_alm_exst_maximo"];
@$art_alm_exst_minimo=$_POST["art_alm_exst_minimo"];
@$art_alm_exst_punto_reorden=$_POST["art_alm_exst_punto_reorden"];
@$art_alm_exst_precio=$_POST["art_alm_exst_precio"];
@$art_alm_exst_status=$_POST["art_alm_exst_status"];
@$art_alm_exst_modifico=$_POST["art_alm_exst_modifico"];
@$art_alm_exst_time_stamp=$_POST["art_alm_exst_time_stamp"];
@$art_alm_exst_serie=$_POST["art_alm_exst_serie"];
@$art_alm_exst_cod_barras=$_POST["art_alm_exst_cod_barras"];

@$accion=$_POST["accion"];
$Consultas = new consultas();
if($accion=="consulta_articulos_alm_exist"){
	$articulos_alm_exist=$Consultas->consulta_articulos_alm_exist($art_alm_exst_id, $art_alm_exst_articulo_id);
	echo $articulos_alm_exist; 
}else if($accion=="guardar_articulos_alm_exist"){	
	$articulos_alm_exist=$Consultas->guardar_articulos_alm_exist($art_alm_exst_id, $art_alm_exst_articulo_id, $art_alm_exst_almancen_id, $art_alm_exst_estado_id, $art_alm_exst_ubicacion, $art_alm_exst_existencia, $art_alm_exst_maximo, $art_alm_exst_minimo, $art_alm_exst_punto_reorden, $art_alm_exst_precio, $art_alm_exst_status, $art_alm_exst_modifico, $art_alm_exst_serie, $art_alm_exst_cod_barras);
	echo $articulos_alm_exist;
}else if($accion=="editar_articulos_alm_exist"){	
	$articulos_alm_exist=$Consultas->editar_articulos_alm_exist($art_alm_exst_id, $art_alm_exst_articulo_id, $art_alm_exst_almancen_id, $art_alm_exst_estado_id, $art_alm_exst_ubicacion, $art_alm_exst_existencia, $art_alm_exst_maximo, $art_alm_exst_minimo, $art_alm_exst_punto_reorden, $art_alm_exst_precio, $art_alm_exst_status, $art_alm_exst_modifico, $art_alm_exst_serie, $art_alm_exst_cod_barras);
	echo $articulos_alm_exist;
}else if($accion=="eliminar_articulos_alm_exist"){	
	$articulos_alm_exist=$Consultas->eliminar_articulos_alm_exist($art_alm_exst_id, $art_alm_exst_status, $art_alm_exst_modifico);
	echo $articulos_alm_exist;
}else if($accion=="cmb_articulos"){
	$articulos_alm_exist=$Consultas->cmb_articulos();
	echo $articulos_alm_exist;
}else if($accion=="cmb_almacenes"){
	$articulos_alm_exist=$Consultas->cmb_almacenes();
	echo $articulos_alm_exist;
}else if($accion=="cmb_estados"){
	$articulos_alm_exist=$Consultas->cmb_estados();
	echo $articulos_alm_exist;
}




?>