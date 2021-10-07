<?php
include_once("../datos/dtotojson/DtoToJson.Class.php");
include_once("../datos/json/JsonEncod.Class.php");
include_once("../datos/json/JsonDecod.Class.php");
include_once("../datos/connect/Proveedor.Class.php");
class consultas {
	public function __construct() {
	}
	
	
	public function cmb_categorias($articulo_linea_id,$articulo_familia_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from articulos_categorias where articulo_categoria_status<>'E'
		";
		
		if($articulo_linea_id!=""){
				$sql.=" 
					and articulo_linea_id=".$articulo_linea_id."
				";
		}
		
		if($articulo_familia_id!=""){
				$sql.=" 
					and articulo_familia_id=".$articulo_familia_id."
				";
		}
		
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$Data= array(
						"articulo_categoria_id" => rtrim(ltrim($row["articulo_categoria_id"])),
						"articulo_categoria_descripcion" => rtrim(ltrim($row["articulo_categoria_descripcion"])),
						"articulo_categoria_status" => rtrim(ltrim($row["articulo_categoria_status"])),
						"articulo_categoria_modifico" => rtrim(ltrim($row["articulo_categoria_modifico"])),
						"articulo_categoria_time_stamp" => rtrim(ltrim($row["articulo_categoria_time_stamp"]))
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
	
	public function cmb_familias($articulo_linea_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from articulos_familias where articulo_familia_status<>'E'
		";
		
		if($articulo_linea_id!=""){
				$sql.=" 
					and articulo_linea_id=".$articulo_linea_id."
				";
		}
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$Data= array(
						"articulo_familia_id" => rtrim(ltrim($row["articulo_familia_id"])),
						"articulo_familia_descripcion" => rtrim(ltrim($row["articulo_familia_descripcion"])),
						"articulo_familia_status" => rtrim(ltrim($row["articulo_familia_status"])),
						"articulo_familia_modifico" => rtrim(ltrim($row["articulo_familia_modifico"])),
						"articulo_familia_time_stamp" => rtrim(ltrim($row["articulo_familia_time_stamp"]))
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
	
	public function cmb_lineas(){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from articulos_lineas where articulo_linea_status<>'E'
		";
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$Data= array(
						"articulo_linea_id" => rtrim(ltrim($row["articulo_linea_id"])),
						"articulo_linea_descripcion" => rtrim(ltrim($row["articulo_linea_descripcion"])),
						"articulo_linea_status" => rtrim(ltrim($row["articulo_linea_status"])),
						"articulo_linea_modifico" => rtrim(ltrim($row["articulo_linea_modifico"])),
						"articulo_linea_time_stamp" => rtrim(ltrim($row["articulo_linea_time_stamp"]))
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
	
	public function cmb_articulos($articulo_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select articulo_id,articulo_sku from articulos where articulo_status<>'E' and articulo_padre is null
		";
		if($articulo_id!=""){
			$sql.="
				and articulo_id<>".$articulo_id."
			";
		}
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$Data= array(
						"articulo_id" => rtrim(ltrim($row["articulo_id"])),
						"articulo_sku" => rtrim(ltrim($row["articulo_sku"]))
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
	
	public function consultar_articulos($articulo_id, $articulo_descripcion, $articulo_sku, $articulo_linea_id, $articulo_familia_id, $articulo_categoria_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select 
				*
			,articulo_linea_descripcion	
			,articulo_familia_descripcion
			,articulo_categoria_descripcion
			,(select articulo_sku from articulos art where art.articulo_id=articulos.articulo_padre) as Desc_Articulo_Padre
			from articulos 
			left join articulos_lineas on articulos.articulo_linea_id=articulos_lineas.articulo_linea_id
			left join articulos_familias on articulos.articulo_familia_id=articulos_familias.articulo_familia_id
			left join articulos_categorias on articulos.articulo_categoria_id=articulos_categorias.articulo_categoria_id
			where articulo_status<>'E'
		";
		
		if($articulo_id!=""){
			$sql.=" 
				and articulo_id=".$articulo_id."
			";				
		}
		
		if($articulo_descripcion!="" || $articulo_sku!="" || $articulo_linea_id!="" || $articulo_familia_id!="" || $articulo_categoria_id!=""){
			$sql.=" 
				and (
			";
			
			if($articulo_descripcion!=""){
				$sql.=" articulos.articulo_descripcion like'%".$articulo_descripcion."%' ";
				if($articulo_sku!="" || $articulo_linea_id!="" || $articulo_familia_id!="" || $articulo_categoria_id!=""){
					$sql.=" and ";
				}
			}
			
			if($articulo_sku!=""){
				$sql.=" articulos.articulo_sku like'%".$articulo_sku."%' ";
				if($articulo_linea_id!="" || $articulo_familia_id!="" || $articulo_categoria_id!=""){
					$sql.=" and ";
				}
			}
			
			if($articulo_linea_id!=""){
				$sql.=" articulos.articulo_linea_id=".$articulo_linea_id." ";
				if($articulo_familia_id!="" || $articulo_categoria_id!=""){
					$sql.=" and ";
				}
			}
			
			if($articulo_familia_id!=""){
				$sql.=" articulos.articulo_familia_id=".$articulo_familia_id." ";
				if($articulo_categoria_id!=""){
					$sql.=" and ";
				}
			}
			
			if($articulo_categoria_id!=""){
				$sql.=" 
					articulos.articulo_categoria_id=".$articulo_categoria_id."
				";
			}
			
			$sql.=" 
				)
			";
		}
		
		//echo "<pre>";
		//echo $sql;
		//echo "</pre>";
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$articulo_linea_id="";
					if(rtrim(ltrim($row["articulo_linea_id"]))!="NULL"){$articulo_linea_id=rtrim(ltrim($row["articulo_linea_id"]));}
					
					$articulo_familia_id="";
					if(rtrim(ltrim($row["articulo_familia_id"]))!="NULL"){$articulo_familia_id=rtrim(ltrim($row["articulo_familia_id"]));}
					
					$articulo_categoria_id="";
					if(rtrim(ltrim($row["articulo_categoria_id"]))!="NULL"){$articulo_categoria_id=rtrim(ltrim($row["articulo_categoria_id"]));}
					
					$articulo_exento_impuesto="";
					if(rtrim(ltrim($row["articulo_exento_impuesto"]))!="NULL"){$articulo_exento_impuesto=rtrim(ltrim($row["articulo_exento_impuesto"]));}
					
					$articulo_factor_alm="";
					if(rtrim(ltrim($row["articulo_factor_alm"]))!="NULL"){$articulo_factor_alm=rtrim(ltrim($row["articulo_factor_alm"]));}
					
					
					
					$articulo_descripcion="";
					$articulo_imagenes="";
					$articulo_compatibilidad="";
					if($articulo_id!=""){
						$articulo_descripcion=rtrim(ltrim($row["articulo_descripcion"]));
						$articulo_imagenes=rtrim(ltrim($row["articulo_imagenes"]));
						$articulo_compatibilidad=rtrim(ltrim($row["articulo_compatibilidad"]));
					}
					
					
					$proveedor_d = new Proveedor('mysql', 'inavtek');
					$proveedor_d->connect();
					$sql=" 
						select 
						art_alm_exst_existencia 
						,art_alm_exst_precio
						,(select art_alm_exst_almancen_codigo from articulos_almacenes where articulos_almacenes.art_alm_exst_almancen_id=articulos_alm_exist.art_alm_exst_almancen_id) as Codigo
						from articulos_alm_exist 
						where art_alm_exst_status<>'E'
					";
					
					$sql.=" 
							and art_alm_exst_articulo_id=".$row["articulo_id"]."
					";				
					
					//echo $sql;
					$contador=0;
					$Precio_Existencia="";	
					$proveedor_d->execute($sql);
					if (!$proveedor_d->error()){
						if ($proveedor_d->rows($proveedor_d->stmt) > 0) {
							while ($row_d = $proveedor_d->fetch_array($proveedor_d->stmt, 0)) {
								$Existencia=$row_d["art_alm_exst_existencia"];
								$Existencia=explode('.', $Existencia);
								if($Existencia[1]=="00"){
									$Existencia=intval($row_d["art_alm_exst_existencia"]);
								}else{
									$Existencia=number_format(floatval($row_d["art_alm_exst_existencia"]), 2, '.', ',');
								}
								
								
								$contador=$contador+1;
								$Precio_Existencia.="<strong> ".$row_d["Codigo"]." </strong> [Precio: $".number_format(floatval($row_d["art_alm_exst_precio"]), 2, '.', ',').", Existencia: ".$Existencia."]<br><br>";
							}
						}
					}
						
					$Data= array(
						"articulo_id" => rtrim(ltrim($row["articulo_id"])),
						"articulo_linea_id" => $articulo_linea_id,
						"articulo_familia_id" => $articulo_familia_id,
						"articulo_categoria_id" => $articulo_categoria_id,
						"articulo_cod_barras" => rtrim(ltrim($row["articulo_cod_barras"])),
						"articulo_cod_interno" => rtrim(ltrim($row["articulo_cod_interno"])),
						"articulo_descripcion" => rtrim(ltrim($row["articulo_descripcion"])),
						"articulo_sku" => rtrim(ltrim($row["articulo_sku"])),
						"articulo_clave_sat" => rtrim(ltrim($row["articulo_clave_sat"])),
						"articulo_exento_impuesto" => $articulo_exento_impuesto,
						"articulo_marca" => rtrim(ltrim($row["articulo_marca"])),
						"articulo_modelo" => rtrim(ltrim($row["articulo_modelo"])),
						"articulo_serie" => rtrim(ltrim($row["articulo_serie"])),
						"articulo_fabricante" => rtrim(ltrim($row["articulo_fabricante"])),
						"articulo_imagenes" => $articulo_imagenes,
						"articulo_um_entrada" => rtrim(ltrim($row["articulo_um_entrada"])),
						"articulo_um_salida" => rtrim(ltrim($row["articulo_um_salida"])),
						"articulo_tiempo_entrega" => rtrim(ltrim($row["articulo_tiempo_entrega"])),
						"articulo_factor_alm" => $articulo_factor_alm,
						"articulo_padre" => rtrim(ltrim($row["articulo_padre"])),
						"articulo_compatibilidad" => rtrim(ltrim($row["articulo_compatibilidad"])),
						"articulo_fecha_alta" => rtrim(ltrim($row["articulo_fecha_alta"])),
						"articulo_fecha_baja" => rtrim(ltrim($row["articulo_fecha_baja"])),
						"articulo_status" => rtrim(ltrim($row["articulo_status"])),
						"articulo_modifico" => rtrim(ltrim($row["articulo_modifico"])),
						"articulo_time_stamp" => rtrim(ltrim($row["articulo_time_stamp"])),
						"articulo_observaciones"=> rtrim(ltrim($row["articulo_observaciones"])),
						"articulo_linea_descripcion" => rtrim(ltrim($row["articulo_linea_descripcion"])),
						"articulo_familia_descripcion" => rtrim(ltrim($row["articulo_familia_descripcion"])),
						"articulo_categoria_descripcion" => rtrim(ltrim($row["articulo_categoria_descripcion"])),
						"Desc_Articulo_Padre" => rtrim(ltrim($row["Desc_Articulo_Padre"])),
						"Precio_Existencia" => $Precio_Existencia
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
	
	public function consultar_articulos_almacen($articulo_id, $articulo_descripcion, $articulo_sku, $articulo_linea_id, $articulo_familia_id, $articulo_categoria_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select 
				*
			,articulos_alm_exist.art_alm_exst_id	
			,articulos_alm_exist.art_alm_exst_serie 
			,articulos_alm_exist.art_alm_exst_existencia 
			,articulos_alm_exist.art_alm_exst_precio
			,articulos_alm_exist.art_alm_exst_cod_barras
			,articulo_linea_descripcion	
			,articulo_familia_descripcion
			,articulo_categoria_descripcion
			,(select articulo_sku from articulos art where art.articulo_id=articulos.articulo_padre) as Desc_Articulo_Padre
			from articulos 
			left join articulos_lineas on articulos.articulo_linea_id=articulos_lineas.articulo_linea_id
			left join articulos_familias on articulos.articulo_familia_id=articulos_familias.articulo_familia_id
			left join articulos_categorias on articulos.articulo_categoria_id=articulos_categorias.articulo_categoria_id
			inner join articulos_alm_exist on articulos.articulo_id=articulos_alm_exist.art_alm_exst_articulo_id
			where articulo_status<>'E' and articulos_alm_exist.art_alm_exst_status<>'E'
		";
		
		if($articulo_id!=""){
			$sql.=" 
				and articulo_id=".$articulo_id."
			";				
		}
		
		if($articulo_descripcion!="" || $articulo_sku!="" || $articulo_linea_id!="" || $articulo_familia_id!="" || $articulo_categoria_id!=""){
			$sql.=" 
				and (
			";
			
			if($articulo_descripcion!=""){
				$sql.=" articulos.articulo_descripcion like'%".$articulo_descripcion."%' ";
				if($articulo_sku!="" || $articulo_linea_id!="" || $articulo_familia_id!="" || $articulo_categoria_id!=""){
					$sql.=" and ";
				}
			}
			
			if($articulo_sku!=""){
				$sql.=" articulos.articulo_sku like'%".$articulo_sku."%' ";
				if($articulo_linea_id!="" || $articulo_familia_id!="" || $articulo_categoria_id!=""){
					$sql.=" and ";
				}
			}
			
			if($articulo_linea_id!=""){
				$sql.=" articulos.articulo_linea_id=".$articulo_linea_id." ";
				if($articulo_familia_id!="" || $articulo_categoria_id!=""){
					$sql.=" and ";
				}
			}
			
			if($articulo_familia_id!=""){
				$sql.=" articulos.articulo_familia_id=".$articulo_familia_id." ";
				if($articulo_categoria_id!=""){
					$sql.=" and ";
				}
			}
			
			if($articulo_categoria_id!=""){
				$sql.=" 
					articulos.articulo_categoria_id=".$articulo_categoria_id."
				";
			}
			
			$sql.=" 
				)
			";
		}
		
		//echo "<pre>";
		//echo $sql;
		//echo "</pre>";
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$articulo_linea_id="";
					if(rtrim(ltrim($row["articulo_linea_id"]))!="NULL"){$articulo_linea_id=rtrim(ltrim($row["articulo_linea_id"]));}
					
					$articulo_familia_id="";
					if(rtrim(ltrim($row["articulo_familia_id"]))!="NULL"){$articulo_familia_id=rtrim(ltrim($row["articulo_familia_id"]));}
					
					$articulo_categoria_id="";
					if(rtrim(ltrim($row["articulo_categoria_id"]))!="NULL"){$articulo_categoria_id=rtrim(ltrim($row["articulo_categoria_id"]));}
					
					$articulo_exento_impuesto="";
					if(rtrim(ltrim($row["articulo_exento_impuesto"]))!="NULL"){$articulo_exento_impuesto=rtrim(ltrim($row["articulo_exento_impuesto"]));}
					
					$articulo_factor_alm="";
					if(rtrim(ltrim($row["articulo_factor_alm"]))!="NULL"){$articulo_factor_alm=rtrim(ltrim($row["articulo_factor_alm"]));}
					
					
					
					$articulo_descripcion="";
					$articulo_imagenes="";
					$articulo_compatibilidad="";
					if($articulo_id!=""){
						$articulo_descripcion=rtrim(ltrim($row["articulo_descripcion"]));
						$articulo_imagenes=rtrim(ltrim($row["articulo_imagenes"]));
						$articulo_compatibilidad=rtrim(ltrim($row["articulo_compatibilidad"]));
					}
					
					
					$Data= array(
						"articulo_id" => rtrim(ltrim($row["articulo_id"])),
						"articulo_linea_id" => $articulo_linea_id,
						"articulo_familia_id" => $articulo_familia_id,
						"articulo_categoria_id" => $articulo_categoria_id,
						"articulo_cod_barras" => rtrim(ltrim($row["articulo_cod_barras"])),
						"articulo_cod_interno" => rtrim(ltrim($row["articulo_cod_interno"])),
						"articulo_descripcion" => rtrim(ltrim($row["articulo_descripcion"])),
						"articulo_sku" => rtrim(ltrim($row["articulo_sku"])),
						"articulo_clave_sat" => rtrim(ltrim($row["articulo_clave_sat"])),
						"articulo_exento_impuesto" => $articulo_exento_impuesto,
						"articulo_marca" => rtrim(ltrim($row["articulo_marca"])),
						"articulo_modelo" => rtrim(ltrim($row["articulo_modelo"])),
						"articulo_serie" => rtrim(ltrim($row["articulo_serie"])),
						"articulo_fabricante" => rtrim(ltrim($row["articulo_fabricante"])),
						"articulo_imagenes" => $articulo_imagenes,
						"articulo_um_entrada" => rtrim(ltrim($row["articulo_um_entrada"])),
						"articulo_um_salida" => rtrim(ltrim($row["articulo_um_salida"])),
						"articulo_tiempo_entrega" => rtrim(ltrim($row["articulo_tiempo_entrega"])),
						"articulo_factor_alm" => $articulo_factor_alm,
						"articulo_padre" => rtrim(ltrim($row["articulo_padre"])),
						"articulo_compatibilidad" => $articulo_compatibilidad,
						"articulo_fecha_alta" => rtrim(ltrim($row["articulo_fecha_alta"])),
						"articulo_fecha_baja" => rtrim(ltrim($row["articulo_fecha_baja"])),
						"articulo_observaciones"=> rtrim(ltrim($row["articulo_observaciones"])),
						"articulo_status" => rtrim(ltrim($row["articulo_status"])),
						"articulo_modifico" => rtrim(ltrim($row["articulo_modifico"])),
						"articulo_time_stamp" => rtrim(ltrim($row["articulo_time_stamp"])),
						"articulo_linea_descripcion" => rtrim(ltrim($row["articulo_linea_descripcion"])),
						"articulo_familia_descripcion" => rtrim(ltrim($row["articulo_familia_descripcion"])),
						"articulo_categoria_descripcion" => rtrim(ltrim($row["articulo_categoria_descripcion"])),
						"art_alm_exst_id" => rtrim(ltrim($row["art_alm_exst_id"])),
						"art_alm_exst_serie" => rtrim(ltrim($row["art_alm_exst_serie"])),
						"art_alm_exst_existencia" => rtrim(ltrim($row["art_alm_exst_existencia"])),
						"art_alm_exst_precio" => rtrim(ltrim($row["art_alm_exst_precio"])),
						"art_alm_exst_precio_formato" => number_format($row["art_alm_exst_precio"], 2, '.', ','),
						"art_alm_exst_cod_barras" => rtrim(ltrim($row["art_alm_exst_cod_barras"])),
						"Desc_Articulo_Padre" => rtrim(ltrim($row["Desc_Articulo_Padre"])
						)
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
	
	
	public function guardar_articulos($articulo_linea_id, $articulo_familia_id, $articulo_categoria_id, $articulo_cod_barras, $articulo_cod_interno, $articulo_descripcion, $articulo_sku, $articulo_clave_sat, $articulo_exento_impuesto, $articulo_marca, $articulo_modelo, $articulo_serie, $articulo_fabricante, $articulo_imagenes, $articulo_um_entrada, $articulo_um_salida, $articulo_tiempo_entrega, $articulo_factor_alm, $articulo_padre, $articulo_compatibilidad, $articulo_status, $articulo_modifico, $articulo_observaciones){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$Error=false;
		$Idmaximo="";
		
		
		
		$repetidos = new Proveedor('mysql', 'inavtek');
		$repetidos->connect();
		//Obtengo el Id Maximo
		$sql_repetidos=" select * from articulos where rtrim(ltrim(articulo_sku))='".$articulo_sku."' and articulo_status<>'E' and  articulo_sku<>''";
		$repetidos->execute($sql_repetidos);
		
		if (!$repetidos->error()){
			if ($repetidos->rows($repetidos->stmt) == 0) {
				$proveedor_M = new Proveedor('mysql', 'inavtek');
				$proveedor_M->connect();
				//Obtengo el Id Maximo
				$valormaximo=" select CASE when max(articulo_id+1) IS NULL then 1 else (max(articulo_id + 1)) end as IndiceMaximo from articulos ";
				$proveedor_M->execute($valormaximo);
				
				if (!$proveedor_M->error()){
					if ($proveedor_M->rows($proveedor_M->stmt) > 0) {
						$row_max = $proveedor_M->fetch_array($proveedor_M->stmt, 0);
						$Idmaximo=$row_max["IndiceMaximo"];
						
						$proveedor = new Proveedor('mysql', 'inavtek');
						$proveedor->connect();	
						$strSQL="insert into articulos ";
						$strSQL.="(
									articulo_id, 
									articulo_linea_id, 
									articulo_familia_id, 
									articulo_categoria_id, 
									articulo_cod_barras, 
									articulo_cod_interno, 
									articulo_descripcion, 
									articulo_sku, 
									articulo_clave_sat, 
									articulo_exento_impuesto, 
									articulo_marca, 
									articulo_modelo, 
									articulo_serie, 
									articulo_fabricante, 
									articulo_imagenes, 
									articulo_um_entrada, 
									articulo_um_salida, 
									articulo_tiempo_entrega, 
									articulo_factor_alm, 
									articulo_padre, 
									articulo_compatibilidad,
									articulo_observaciones,	
									articulo_fecha_alta, 
									articulo_status, 
									articulo_modifico, 
									articulo_time_stamp
								  ) "; 
						$strSQL.="VALUES ";
						$strSQL.="(";
						$strSQL.=	"".$Idmaximo.","; 
						if($articulo_linea_id!=""){
						$strSQL.=  $articulo_linea_id.",";
						}else{ $strSQL.="NULL,"; }	
						
						if($articulo_familia_id!=""){
						$strSQL.=  $articulo_familia_id.",";
						}else{ $strSQL.="NULL,"; }	
						
						if($articulo_categoria_id!=""){
						$strSQL.=  $articulo_categoria_id.",";
						}else{ $strSQL.="NULL,"; }	
						
						$strSQL.=	"'".$articulo_cod_barras."',";
						$strSQL.=	"'".$articulo_cod_interno."',";
						$strSQL.=	"'".$articulo_descripcion."',";
						$strSQL.=	"'".$articulo_sku."',";
						$strSQL.=	"'".$articulo_clave_sat."',";
						
						if($articulo_exento_impuesto!=""){
						$strSQL.=  $articulo_exento_impuesto.",";
						}else{ $strSQL.="NULL,"; }
						
						$strSQL.=	"'".$articulo_marca."',";
						$strSQL.=	"'".$articulo_modelo."',";
						$strSQL.=	"'".$articulo_serie."',";
						$strSQL.=	"'".$articulo_fabricante."',";
						$strSQL.=	"'".$articulo_imagenes."',";
						$strSQL.=	"'".$articulo_um_entrada."',";
						$strSQL.=	"'".$articulo_um_salida."',";
						$strSQL.=	"'".$articulo_tiempo_entrega."',";
						
						if($articulo_factor_alm!=""){
						$strSQL.=  "'".$articulo_factor_alm."',";
						}else{ $strSQL.="NULL,"; }
						if($articulo_padre!=""){
						$strSQL.=  "'".$articulo_padre."',";
						}else{ $strSQL.="NULL,"; }
						
						$strSQL.=	"'".$articulo_compatibilidad."',";
						$strSQL.=	"'".$articulo_observaciones."',";
						$strSQL.=	"now(),";
						$strSQL.=	"'".$articulo_status."',";
						$strSQL.=	"'".$articulo_modifico."',";
						$strSQL.=	"now()";						
						$strSQL.=")"; 
						
						
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
					$respuesta = array("totalCount" => "1","articulo_id" =>$Idmaximo, "estatus" => "ok", "mensaje" => "Se ha registrado correctamente");
				}else{
					$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al registrar");
				}
				
			}else{
				$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "El SKU ya se encuentra registrado.");
			}
		}else{
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al buscar repetidos.");
		}
		
		
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}

	public function editar_articulos($articulo_id, $articulo_linea_id, $articulo_familia_id, $articulo_categoria_id, $articulo_cod_barras, $articulo_cod_interno, $articulo_descripcion, $articulo_sku, $articulo_clave_sat, $articulo_exento_impuesto, $articulo_marca, $articulo_modelo, $articulo_serie, $articulo_fabricante, $articulo_imagenes, $articulo_um_entrada, $articulo_um_salida, $articulo_tiempo_entrega, $articulo_factor_alm, $articulo_padre, $articulo_compatibilidad, $articulo_status, $articulo_modifico, $articulo_observaciones){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update articulos ";
		$strSQL.="set ";
		
		if($articulo_linea_id!=""){
			$strSQL.=" articulo_linea_id=".$articulo_linea_id.",";
		}else{ $strSQL.=" articulo_linea_id=NULL,"; }	
		
		if($articulo_familia_id!=""){
			$strSQL.=" articulo_familia_id=".$articulo_familia_id.",";
		}else{ $strSQL.=" articulo_familia_id=NULL,"; }	
		
		if($articulo_categoria_id!=""){
			$strSQL.=" articulo_categoria_id=".$articulo_categoria_id.",";
		}else{ $strSQL.=" articulo_categoria_id=NULL,"; }	
		
		
		
		
		$strSQL.="	articulo_cod_barras='".$articulo_cod_barras."', ";
		$strSQL.="	articulo_cod_interno='".$articulo_cod_interno."', ";
		$strSQL.="	articulo_descripcion='".$articulo_descripcion."', ";
		$strSQL.="	articulo_sku='".$articulo_sku."', ";
		$strSQL.="	articulo_clave_sat='".$articulo_clave_sat."', ";
		
		if($articulo_exento_impuesto!=""){
		$strSQL.=" articulo_exento_impuesto=".$articulo_exento_impuesto.",";
		}else{ $strSQL.=" articulo_exento_impuesto=NULL,"; }
		
		$strSQL.="	articulo_marca='".$articulo_marca."', ";
		$strSQL.="	articulo_modelo='".$articulo_modelo."', ";
		$strSQL.="	articulo_serie='".$articulo_serie."', ";
		$strSQL.="	articulo_fabricante='".$articulo_fabricante."', ";
		$strSQL.="	articulo_imagenes='".$articulo_imagenes."', ";
		$strSQL.="	articulo_um_entrada='".$articulo_um_entrada."', ";
		$strSQL.="	articulo_um_salida='".$articulo_um_salida."', ";
		$strSQL.="	articulo_tiempo_entrega='".$articulo_tiempo_entrega."', ";
		
		if($articulo_factor_alm!=""){
		$strSQL.=" articulo_factor_alm='".$articulo_factor_alm."',";
		}else{ $strSQL.=" articulo_factor_alm=NULL,"; }
		if($articulo_padre!=""){
		$strSQL.=" articulo_padre='".$articulo_padre."',";
		}else{ $strSQL.="articulo_padre=NULL,"; }
		$strSQL.="	articulo_compatibilidad='".$articulo_compatibilidad."', ";
		$strSQL.="	articulo_observaciones='".$articulo_observaciones."', ";
		$strSQL.="	articulo_status='".$articulo_status."', ";
		$strSQL.="	articulo_modifico='".$articulo_modifico."', ";
		$strSQL.="	articulo_time_stamp=now() ";
		
 
		$strSQL.="where ";
		$strSQL.="articulo_id=".$articulo_id; 
		
		
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
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al actualizar informaci?n");
		}
		
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}	

	public function eliminar_articulos($articulo_id, $articulo_status, $articulo_modifico){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update articulos ";
		$strSQL.="set articulo_status='".$articulo_status."', articulo_modifico='".$articulo_modifico."', articulo_time_stamp=now(), articulo_fecha_baja=now() "; 
		$strSQL.="where ";
		$strSQL.="articulo_id=".$articulo_id; 
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

	public function listar_archivos($articulo_id){
		$respuesta = array();
		$Archivos="";
		//if($articulo_id!=""){
		//	
		//	$path="archivos_articulos";
		//	$ftp_server = "ftp.bsolutionsmx.com";
		//  $conn_id = ftp_connect($ftp_server);
		//  $ftp_user_name = "bsolutio";
		//  $ftp_user_pass = "tc#Inc(I32*01";
		//  $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
		//  $contents = ftp_nlist($conn_id, 'httpdocs\ole\eventos\archivos_articulos');
		//  for ($i = 0 ; $i < count($contents) ; $i++){
		//		$elemento=$contents[$i];
		//		if($contents[$i] != "." && $contents[$i] != ".."){
		//			$elemento_p = explode("-_-", $contents[$i]);
		//			if(count($elemento_p)>0){
		//				if($elemento_p[0]==$articulo_id){
		//					$nombre_ruta="";
		//					
		//					for($l=1;$l<count($elemento_p);$l++){
		//						if((count($elemento_p)-1)==$l){
		//							$nombre_ruta.=$elemento_p[$l];
		//						}else{
		//							$nombre_ruta.=$elemento_p[$l]."_";
		//						}
		//					}
		//					if($nombre_ruta!=""){
		//						//echo "<a href=".$contents[$i].">".$contents[$i]."</a>";
		//						$extension="";
		//						$extension=explode(".", $nombre_ruta);
		//						if($extension!=""){
		//							if($extension[1]=="pdf"||$extension[1]=="PDF"){
		//								$Archivos.='<img src="./images/pdf.gif" border="0">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
		//							}else{
		//								if($extension[1]=="jpg"||$extension[1]=="JPG"){
		//									$Archivos.='<img src="./images/icon_jpg.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
		//								}else{
		//									if($extension[1]=="png"||$extension[1]=="PNG"){
		//										$Archivos.='<img src="./images/png.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
		//									}else{
		//										if($extension[1]=="doc"||$extension[1]=="DOC"||$extension[1]=="docx"||$extension[1]=="DOCX"){
		//											$Archivos.='<img src="./images/word.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
		//										}else{
		//											if($extension[1]=="xlsx"||$extension[1]=="XLSX"||$extension[1]=="xls"||$extension[1]=="xls"){
		//												$Archivos.='<img src="./images/xls.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
		//											}else{
		//												$Archivos.='<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br>';	
		//											}
		//										}
		//									}
		//								}
		//							}
		//						}
		//						
		//					}
		//				}
		//			}
		//		}
		//	}
		//  
		//	ftp_close($conn_id);
		//}
		
		if($articulo_id!=""){
			$path="../archivos_articulos";
			$dir = opendir("../archivos_articulos");
			// Leo todos los ficheros de la carpeta
			while ($elemento = readdir($dir)){
				// Tratamos los elementos . y .. que tienen todas las carpetas
				if( $elemento != "." && $elemento != ".."){
					
					$elemento_p = explode("-_-", $elemento);
					
					if(count($elemento_p)>0){
						if($elemento_p[0]==$articulo_id){
							$nombre_ruta="";
							
							for($i=1;$i<count($elemento_p);$i++){
								if((count($elemento_p)-1)==$i){
									
									$nombre_ruta.=$elemento_p[$i];
								}else{
									$nombre_ruta.=$elemento_p[$i]."_";
								}
							}
							if($nombre_ruta!=""){
								// Muestro el fichero
								
								
								$extension="";
								$extension=explode(".", $nombre_ruta);
								if($extension!=""){
									if($extension[1]=="pdf"||$extension[1]=="PDF"){
										$Archivos.='<img src="../images/pdf.gif" border="0">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
									}else{
										if($extension[1]=="jpg"||$extension[1]=="JPG"){
											$Archivos.='<img src="../images/icon_jpg.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
										}else{
											if($extension[1]=="png"||$extension[1]=="PNG"){
												$Archivos.='<img src="../images/png.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
											}else{
												if($extension[1]=="doc"||$extension[1]=="DOC"||$extension[1]=="docx"||$extension[1]=="DOCX"){
													$Archivos.='<img src="../images/word.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
												}else{
													if($extension[1]=="xlsx"||$extension[1]=="XLSX"||$extension[1]=="xls"||$extension[1]=="xls"){
														$Archivos.='<img src="../images/xls.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
													}else{
														$Archivos.='<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$articulo_id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br>';	
													}
												}
											}
										}
									}
								}
							}
						}	
					}
				}
			}
		}
		if($Archivos!=""){
			$respuesta = array("totalCount"=>1,"Archivos" => $Archivos);
		}else{
			$respuesta = array("totalCount"=>0,"Archivos" => $Archivos);
		}
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}
	
	public function borrar_archivo($Archivo){
		$Ruta_Archivo="httpdocs/inavtek/archivos_articulos/".$Archivo;
		$respuesta = array();
		$ftp_server = "ftp.bsolutionsmx.com";
		$conn_id = ftp_connect($ftp_server);
		$ftp_user_name = "bsolutio";
		$ftp_user_pass = "tc#Inc(I32*01";
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
		$Error=false;
		
		
		if (ftp_delete($conn_id, $Ruta_Archivo)) {
		  //echo "$file se ha eliminado satisfactoriamente\n";
		} else {
		 $Error=true;
		 //echo "No se pudo eliminar $file\n";
		}

		// cerrar la conexi?n ftp
		ftp_close($conn_id);
		
		if($Error==false){
			$respuesta = array("totalCount"=>1,"Mensaje" => "Eliminado Correctamente");
		}else{
			$respuesta = array("totalCount"=>0,"Mensaje" => "Ocurrio un error al Eliminar");
		}
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
		
	}
}

@$articulo_id=$_POST["articulo_id"];
@$articulo_linea_id=$_POST["articulo_linea_id"];
@$articulo_familia_id=$_POST["articulo_familia_id"];
@$articulo_categoria_id=$_POST["articulo_categoria_id"];
@$articulo_cod_barras=$_POST["articulo_cod_barras"];
@$articulo_cod_interno=$_POST["articulo_cod_interno"];
@$articulo_descripcion=$_POST["articulo_descripcion"];
@$articulo_sku=$_POST["articulo_sku"];
@$articulo_clave_sat=$_POST["articulo_clave_sat"];
@$articulo_exento_impuesto=$_POST["articulo_exento_impuesto"];
@$articulo_marca=$_POST["articulo_marca"];
@$articulo_modelo=$_POST["articulo_modelo"];
@$articulo_serie=$_POST["articulo_serie"];
@$articulo_fabricante=$_POST["articulo_fabricante"];
@$articulo_imagenes=$_POST["articulo_imagenes"];
@$articulo_um_entrada=$_POST["articulo_um_entrada"];
@$articulo_um_salida=$_POST["articulo_um_salida"];
@$articulo_tiempo_entrega=$_POST["articulo_tiempo_entrega"];
@$articulo_factor_alm=$_POST["articulo_factor_alm"];
@$articulo_padre=$_POST["articulo_padre"];
@$articulo_compatibilidad=$_POST["articulo_compatibilidad"];
@$articulo_fecha_alta=$_POST["articulo_fecha_alta"];
@$articulo_fecha_baja=$_POST["articulo_fecha_baja"];
@$articulo_status=$_POST["articulo_status"];
@$articulo_modifico=$_POST["articulo_modifico"];
@$articulo_time_stamp=$_POST["articulo_time_stamp"];
@$articulo_observaciones=$_POST["articulo_observaciones"];
@$Archivo=$_POST["Archivo"];



@$accion=$_POST["accion"];
$Consultas = new consultas();
if($accion=="consultar_articulos"){
	$articulos=$Consultas->consultar_articulos($articulo_id, $articulo_descripcion, $articulo_sku, $articulo_linea_id, $articulo_familia_id, $articulo_categoria_id);
	echo $articulos; 
}else if($accion=="consultar_articulos_almacen"){
	$articulos=$Consultas->consultar_articulos_almacen($articulo_id, $articulo_descripcion, $articulo_sku, $articulo_linea_id, $articulo_familia_id, $articulo_categoria_id);
	echo $articulos; 
}else if($accion=="guardar_articulos"){	
	$articulos=$Consultas->guardar_articulos($articulo_linea_id, $articulo_familia_id, $articulo_categoria_id, $articulo_cod_barras, $articulo_cod_interno, $articulo_descripcion, $articulo_sku, $articulo_clave_sat, $articulo_exento_impuesto, $articulo_marca, $articulo_modelo, $articulo_serie, $articulo_fabricante, $articulo_imagenes, $articulo_um_entrada, $articulo_um_salida, $articulo_tiempo_entrega, $articulo_factor_alm, $articulo_padre, $articulo_compatibilidad, $articulo_status, $articulo_modifico, $articulo_observaciones);
	echo $articulos;
}else if($accion=="editar_articulos"){	
	$articulos=$Consultas->editar_articulos($articulo_id, $articulo_linea_id, $articulo_familia_id, $articulo_categoria_id, $articulo_cod_barras, $articulo_cod_interno, $articulo_descripcion, $articulo_sku, $articulo_clave_sat, $articulo_exento_impuesto, $articulo_marca, $articulo_modelo, $articulo_serie, $articulo_fabricante, $articulo_imagenes, $articulo_um_entrada, $articulo_um_salida, $articulo_tiempo_entrega, $articulo_factor_alm, $articulo_padre, $articulo_compatibilidad, $articulo_status, $articulo_modifico, $articulo_observaciones);
	echo $articulos;
}else if($accion=="eliminar_articulos"){
	$articulos=$Consultas->eliminar_articulos($articulo_id, $articulo_status, $articulo_modifico);
	echo $articulos;
}else if($accion=="cmb_categorias"){
	$articulos=$Consultas->cmb_categorias($articulo_linea_id, $articulo_familia_id);
	echo $articulos;
}else if($accion=="cmb_familias"){
	$articulos=$Consultas->cmb_familias($articulo_linea_id);
	echo $articulos;
}else if($accion=="cmb_lineas"){
	$articulos=$Consultas->cmb_lineas();
	echo $articulos;
}else if($accion=="cmb_articulos"){
	$articulos=$Consultas->cmb_articulos($articulo_id);
	echo $articulos;
}else if($accion=="listar_archivos"){
	$comp=$Consultas->listar_archivos($articulo_id);
	echo $comp; 
}else if($accion=="borrar_archivo"){
	$comp=$Consultas->borrar_archivo($Archivo);
	echo $comp; 
}




?>