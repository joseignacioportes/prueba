<?php
include_once("../datos/dtotojson/DtoToJson.Class.php");
include_once("../datos/json/JsonEncod.Class.php");
include_once("../datos/json/JsonDecod.Class.php");
include_once("../datos/connect/Proveedor.Class.php");
require_once("CURL.php");
class consultas {
	public function __construct() {
	}
	
	
	public function kardex($tipo_movimiento, $fecha_inicial, $fecha_final, $realizo){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select 
				*,
				(select articulo_descripcion from articulos where articulos.articulo_id=articulos_kardex.articulo_id) as Desc_Articulo,
				(select usr_nombre_completo from usuarios where usuarios.usr_usuario=articulos_kardex.articulo_kardex_modifico) as Usuario_Mod,
				case when articulos_kardex_tipo_mov='S' then 'Salida' when articulos_kardex_tipo_mov='E' then 'Entrada' end as Movimiento
			from 
			articulos_kardex
		";
		
		if($tipo_movimiento!="" || $fecha_inicial!="" || $fecha_final!="" || $realizo!=""){
			$sql.=" where articulo_kardex_time_stamp BETWEEN '".$fecha_inicial." 00:00:00' and '".$fecha_final." 00:00:00' ";
			
			
			
			if($tipo_movimiento!=""){
				$sql.=" 
					and articulos_kardex_tipo_mov='".$tipo_movimiento."'
				";
			}
			
			if($realizo!=""){
				$sql.=" 
					and articulo_kardex_modifico='".$realizo."'
				";
			}
		}
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {	
					$Data= array(
						"articulos_kardex_id" => $row["articulos_kardex_id"],
						"articulo_id" => $row["articulo_id"],
						"Desc_Articulo" => $row["Desc_Articulo"],
						"Usuario_Mod" => $row["Usuario_Mod"],
						"Movimiento" => $row["Movimiento"],
						"articulos_kardex_cantidad" => $row["articulos_kardex_cantidad"],
						"articulos_kardex_tipo_mov" => $row["articulos_kardex_tipo_mov"],
						"articulos_kardex_concepto" => $row["articulos_kardex_concepto"],
						"articulo_kardex_modifico" => $row["articulo_kardex_modifico"],
						"articulo_kardex_time_stamp" => $row["articulo_kardex_time_stamp"]
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
		
	public function consulta_remision($chh_id, $chh_tipo){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from cotizador_hist_maestro where chh_status<>'E'
		";
		
		if($chh_id!=""){
			$sql.=" 
				and chh_id=".$chh_id."
			";				
		}
		
		if($chh_tipo!=""){
			$sql.=" 
				and chh_tipo='".$chh_tipo."'
			";				
		}
		
		
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {	
				
					$chh_cliente_id="";
					if(rtrim(ltrim($row["chh_cliente_id"]))!="NULL"){$chh_cliente_id=rtrim(ltrim($row["chh_cliente_id"]));}
					$chh_prospecto_empresa="";
					if(rtrim(ltrim($row["chh_prospecto_empresa"]))!="NULL"){$chh_prospecto_empresa=rtrim(ltrim($row["chh_prospecto_empresa"]));}
					$chh_prospecto_nombre="";
					if(rtrim(ltrim($row["chh_prospecto_nombre"]))!="NULL"){$chh_prospecto_nombre=rtrim(ltrim($row["chh_prospecto_nombre"]));}
					$chh_prospecto_tel="";
					if(rtrim(ltrim($row["chh_prospecto_tel"]))!="NULL"){$chh_prospecto_tel=rtrim(ltrim($row["chh_prospecto_tel"]));}
					$chh_prospecto_to_correo="";
					if(rtrim(ltrim($row["chh_prospecto_to_correo"]))!="NULL"){$chh_prospecto_to_correo=rtrim(ltrim($row["chh_prospecto_to_correo"]));}
					$chh_prospecto_cc_correo="";
					if(rtrim(ltrim($row["chh_prospecto_cc_correo"]))!="NULL"){$chh_prospecto_cc_correo=rtrim(ltrim($row["chh_prospecto_cc_correo"]));}
					$chh_comentarios="";
					if(rtrim(ltrim($row["chh_comentarios"]))!="NULL"){$chh_comentarios=rtrim(ltrim($row["chh_comentarios"]));}
					$chh_fecha_hora="";
					if(rtrim(ltrim($row["chh_fecha_hora"]))!="NULL"){$chh_fecha_hora=rtrim(ltrim($row["chh_fecha_hora"]));}
					$chh_total_cotizacion="";
					if(rtrim(ltrim($row["chh_total_cotizacion"]))!="NULL"){$chh_total_cotizacion=rtrim(ltrim($row["chh_total_cotizacion"]));}
					$chh_num_factura="";
					if(rtrim(ltrim($row["chh_num_factura"]))!="NULL"){$chh_num_factura=rtrim(ltrim($row["chh_num_factura"]));}
					$chh_total_facturacion="";
					if(rtrim(ltrim($row["chh_total_facturacion"]))!="NULL"){$chh_total_facturacion=rtrim(ltrim($row["chh_total_facturacion"]));}
					$chh_prospecto_clientes_ids="";
					if(rtrim(ltrim($row["chh_prospecto_clientes_ids"]))!="NULL"){$chh_prospecto_clientes_ids=rtrim(ltrim($row["chh_prospecto_clientes_ids"]));}
				
					$Data= array(
						"chh_id" => $row["chh_id"],
						"chh_cliente_id" => $chh_cliente_id,
						"chh_prospecto_empresa" => rtrim(ltrim($chh_prospecto_empresa)),
						"chh_prospecto_nombre" => rtrim(ltrim($chh_prospecto_nombre)),
						"chh_prospecto_tel" => rtrim(ltrim($chh_prospecto_tel)),
						"chh_prospecto_clientes_ids" => rtrim(ltrim($chh_prospecto_clientes_ids)),
						"chh_prospecto_to_correo" => rtrim(ltrim($chh_prospecto_to_correo)),
						"chh_prospecto_cc_correo" => rtrim(ltrim($chh_prospecto_cc_correo)),
						"chh_comentarios" => rtrim(ltrim($chh_comentarios)),
						"chh_fecha_hora" => rtrim(ltrim($chh_fecha_hora)),
						"chh_subtotal_cotizacion" => rtrim(ltrim($row["chh_subtotal_cotizacion"])),
						"chh_subtotal_desc_cotizacion" => rtrim(ltrim($row["chh_subtotal_desc_cotizacion"])),
						"chh_total_cotizacion" => rtrim(ltrim($chh_total_cotizacion)),
						"chh_num_factura" => rtrim(ltrim($chh_num_factura)),
						"chh_total_facturacion" => rtrim(ltrim($chh_total_facturacion)),
						"chh_status" => rtrim(ltrim($row["chh_status"])),
						"chh_tipo" => rtrim(ltrim($row["chh_tipo"])),
						"chh_modificado_por" => rtrim(ltrim($row["chh_modificado_por"])),
						"chh_time_stamp" => rtrim(ltrim($row["chh_time_stamp"])),
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
	
	public function guardar_remision($chh_id, $chh_cliente_id, $chh_prospecto_empresa, $chh_prospecto_nombre, $chh_prospecto_tel, $chh_prospecto_clientes_ids, $chh_prospecto_to_correo, $chh_prospecto_cc_correo, $chh_comentarios, $chh_subtotal_cotizacion, $chh_subtotal_desc_cotizacion, $chh_total_cotizacion, $chh_num_factura, $chh_total_facturacion, $chh_status, $chh_tipo, $chh_modificado_por, $Array_Padre_Cotizacion){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$Error=false;
		$Idmaximo="";
		
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$proveedor->beginTransaction();
		//Obtengo el Id Maximo
		$valormaximo=" select CASE when max(chh_id+1) IS NULL then 1 else (max(chh_id + 1)) end as IndiceMaximo from cotizador_hist_maestro ";
		$proveedor->execute($valormaximo);
		$Cliente="";
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				$row_max = $proveedor->fetch_array($proveedor->stmt, 0);
				$Idmaximo=$row_max["IndiceMaximo"];
				$strSQL="insert into cotizador_hist_maestro ";
				
				//Se agrega cliente prospecto
				if($chh_cliente_id==""){
					$strSQL.="(
											chh_id, 
											chh_prospecto_empresa,  
											chh_prospecto_nombre, 	
											chh_prospecto_tel, 
											chh_prospecto_clientes_ids,
											chh_prospecto_to_correo, 
											chh_prospecto_cc_correo,
											chh_comentarios,
											chh_subtotal_cotizacion,
											chh_subtotal_desc_cotizacion,
											chh_total_cotizacion,
											chh_num_factura,
											chh_fecha_hora,
											chh_status,
											chh_tipo,
											chh_modificado_por,
											chh_time_stamp
										) "; 
					$strSQL.="VALUES ";
					$strSQL.="(
											".$Idmaximo.", 
											'".$chh_prospecto_empresa."',  
											'".$chh_prospecto_nombre."', 
											'".$chh_prospecto_tel."',
											'".$chh_prospecto_clientes_ids."',
											'".$chh_prospecto_to_correo."', 
											'".$chh_prospecto_cc_correo."', 
											'".$chh_comentarios."', 
											'".$chh_subtotal_cotizacion."', 
											'".$chh_subtotal_desc_cotizacion."', 
											'".$chh_total_cotizacion."',
											'".$chh_num_factura."',
											now(),
											'A',
											'".$chh_tipo."',
											'".$chh_modificado_por."',
											now()
										)";
				}else{
					//Se agrega cliente cotización
					$strSQL.="(
											chh_id, 
											chh_cliente_id,
											chh_prospecto_clientes_ids,
											chh_prospecto_to_correo, 
											chh_prospecto_cc_correo,
											chh_comentarios,
											chh_subtotal_cotizacion,
											chh_subtotal_desc_cotizacion,
											chh_total_cotizacion,
											chh_num_factura,
											chh_fecha_hora,
											chh_status,
											chh_tipo,
											chh_modificado_por,
											chh_time_stamp
										) "; 
					$strSQL.="VALUES ";
					$strSQL.="(
											".$Idmaximo.", 
											".$chh_cliente_id.",
											'".$chh_prospecto_clientes_ids."', 
											'".$chh_prospecto_to_correo."', 
											'".$chh_prospecto_cc_correo."', 
											'".$chh_comentarios."',
											'".$chh_subtotal_cotizacion."', 
											'".$chh_subtotal_desc_cotizacion."', 
											'".$chh_total_cotizacion."',
											'".$chh_num_factura."',
											now(),
											'A',
											'".$chh_tipo."',
											'".$chh_modificado_por."',
											now()
										)";
				}
				$proveedor->execute($strSQL);
				
				if (!$proveedor->error()){
					$Error=$this->guardar_remision_detalle($Idmaximo, $chh_modificado_por, $Array_Padre_Cotizacion, $proveedor, "guardar", $chh_cliente_id, $chh_prospecto_empresa);
					
				}else{
					$Error=true;
				}
				
				//$proveedor->close();
			
			}
		}else{
			$Error=true;
		}
		
		
		if($Error==false){
			$proveedor->commit();
			$respuesta = array("totalCount" => "1","chh_id" =>$Idmaximo, "estatus" => "ok", "mensaje" => "Se ha registrado correctamente");
		}else{
			$proveedor->rollback();
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al registrar");
		}
				
		$proveedor->close();
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}
	
	public function guardar_remision_detalle($chh_id, $chd_modificado_por, $Array_Padre_Cotizacion, $proveedor, $tipo_guardar_editar, $chh_cliente_id, $chh_prospecto_empresa){
		$respuesta = array();
		$Error=false;
		$Idmaximo="";
		
		for($i=0; $i<count($Array_Padre_Cotizacion);$i++){
			$strSQL="insert into cotizador_hist_detalle ";
			$strSQL.="(
									chh_id,
									chd_sku_desc_detalle,
			";
			if($Array_Padre_Cotizacion[$i][0]!=""){
				$strSQL.="
										chd_sku,
										chd_sku_desc,
										chd_sku_almacen,
										chd_sku_alm_exst_ubicacion,
										chd_sku_linea,
										chd_sku_familia,
										chd_sku_categoria,
										chd_sku_cod_barras,
										chd_sku_cod_interno,
										chd_sku_marca,
										chd_sku_modelo,
										chd_sku_serie,
										chd_sku_fabricante,
				";
			}else{
				$strSQL.="				chd_sku_desc,"; 	
			}
			
			$strSQL.="	
									chd_sku_cantidad,
									chd_sku_descuento,
									chd_sku_precio,
									chd_sku_sort,
									chd_status, 
									chd_modificado_por, 
									chd_time_stamp
								) "; 
			$strSQL.="VALUES ";
			
			$strSQL.="(	 
									".$chh_id.",
									'".$Array_Padre_Cotizacion[$i][9]."',
			";		
			
			if($Array_Padre_Cotizacion[$i][0]!=""){
				$strSQL.="	
										(select articulo_sku from articulos where articulo_id=".$Array_Padre_Cotizacion[$i][0]."),
										(select articulo_descripcion from articulos where articulo_id=".$Array_Padre_Cotizacion[$i][0]."),
										(select (select art_alm_exst_almancen_codigo from articulos_almacenes where articulos_alm_exist.art_alm_exst_id=articulos_almacenes.art_alm_exst_almancen_id) from articulos_alm_exist where art_alm_exst_id=".$Array_Padre_Cotizacion[$i][1]."),
										(select art_alm_exst_ubicacion from articulos_alm_exist where art_alm_exst_id=".$Array_Padre_Cotizacion[$i][1]."),
										(select (select articulo_linea_descripcion from articulos_lineas where articulos_lineas.articulo_linea_id=articulos.articulo_linea_id) from articulos where articulo_id=".$Array_Padre_Cotizacion[$i][0]."),
										(select (select articulo_familia_descripcion from articulos_familias where articulos_familias.articulo_familia_id=articulos.articulo_familia_id) from articulos where articulo_id=".$Array_Padre_Cotizacion[$i][0]."),
										(select (select articulo_categoria_descripcion from articulos_categorias where articulos_categorias.articulo_categoria_id=articulos.articulo_categoria_id) from articulos where articulo_id=".$Array_Padre_Cotizacion[$i][0]."),
										(select art_alm_exst_cod_barras from articulos_alm_exist where 	art_alm_exst_id=".$Array_Padre_Cotizacion[$i][1]."),
										(select articulo_cod_interno from articulos where articulo_id=".$Array_Padre_Cotizacion[$i][0]."),
										(select articulo_marca from articulos where articulo_id=".$Array_Padre_Cotizacion[$i][0]."),
										(select articulo_modelo from articulos where articulo_id=".$Array_Padre_Cotizacion[$i][0]."),
										(select art_alm_exst_serie from articulos_alm_exist where art_alm_exst_id=".$Array_Padre_Cotizacion[$i][1]."),
										(select articulo_fabricante from articulos where articulo_id=".$Array_Padre_Cotizacion[$i][0]."),
				";
			}else{
				$strSQL.="				'".$Array_Padre_Cotizacion[$i][2]."',";
			}
			
			$strSQL.="	
									'".$Array_Padre_Cotizacion[$i][3]."',
									'".$Array_Padre_Cotizacion[$i][5]."',
									'".$Array_Padre_Cotizacion[$i][4]."',
									'".$Array_Padre_Cotizacion[$i][8]."',
									'A',
									'".$chd_modificado_por."', 
									now()
								)";
								
			$proveedor->execute($strSQL);
			if (!$proveedor->error()){
				if($tipo_guardar_editar=="guardar"){
					if($Array_Padre_Cotizacion[$i][0]!=""){
						$strSQL2="insert into articulos_kardex (
							articulo_id,
							almacen_id,	
							articulos_kardex_cantidad, 
							articulos_kardex_tipo_mov, 
							articulos_kardex_concepto, 
							articulo_kardex_modifico, 
							articulo_kardex_time_stamp
						)values(
							".$Array_Padre_Cotizacion[$i][0].",
							".$Array_Padre_Cotizacion[$i][1].",
							'".$Array_Padre_Cotizacion[$i][3]."',
							'S',
						";
						
						if($chh_cliente_id!=""){
							$strSQL2.="
								(select concat('Remisión Número: ".$chh_id." / Cliente: ', cliente_razon_social, ' / Entrega de ítems') from clientes where cliente_id=".$chh_cliente_id."),
							";
						}else{
							$strSQL2.="
								'Remisión Número: ".$chh_id." / Cliente: ".$chh_prospecto_empresa." / Entrega de ítems', 
							";	
						}
						$strSQL2.="	
							'".$chd_modificado_por."', 
							now()
						) ";
					
						$proveedor->execute($strSQL2);
						if (!$proveedor->error()){
							$strSQL3="update articulos_alm_exist set art_alm_exst_existencia=((select (art_alm_exst_existencia-".$Array_Padre_Cotizacion[$i][3].") from articulos_alm_exist where art_alm_exst_id =".$Array_Padre_Cotizacion[$i][1].")) where art_alm_exst_id=".$Array_Padre_Cotizacion[$i][1];
							$proveedor->execute($strSQL3);
						}else{
							$Error=true;
						}	
					}		
				}
			}else{
				$Error=true;
			}
		}
		return $Error;
	}
	
	public function editar_remision($chh_id, $chh_cliente_id, $chh_prospecto_empresa, $chh_prospecto_nombre, $chh_prospecto_tel, $chh_prospecto_clientes_ids, $chh_prospecto_to_correo, $chh_prospecto_cc_correo, $chh_comentarios, $chh_subtotal_cotizacion, $chh_subtotal_desc_cotizacion, $chh_total_cotizacion, $chh_num_factura, $chh_total_facturacion, $chh_status, $chh_tipo, $chh_modificado_por, $Array_Padre_Cotizacion){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$proveedor->beginTransaction();
		
		//Se edita cliente prospecto
		if($chh_cliente_id==""){
			$strSQL="update cotizador_hist_maestro set 
									chh_prospecto_empresa='".$chh_prospecto_empresa."', 
									chh_prospecto_nombre='".$chh_prospecto_nombre."',
									chh_prospecto_tel='".$chh_prospecto_tel."',
									chh_prospecto_clientes_ids='".$chh_prospecto_clientes_ids."',
									chh_prospecto_to_correo='".$chh_prospecto_to_correo."', 
									chh_prospecto_cc_correo='".$chh_prospecto_cc_correo."',
									chh_comentarios='".$chh_comentarios."',
									chh_subtotal_cotizacion='".$chh_subtotal_cotizacion."',
									chh_subtotal_desc_cotizacion='".$chh_subtotal_desc_cotizacion."',
									chh_total_cotizacion='".$chh_total_cotizacion."',
									chh_num_factura='".$chh_num_factura."',
									chh_status='M',
									chh_tipo='".$chh_tipo."',
									chh_modificado_por='".$chh_modificado_por."',
									chh_time_stamp=now()
							where chh_id=".$chh_id.""; 
		}else{
			//Se edita cliente cotización
			$strSQL="update cotizador_hist_maestro set 
									chh_prospecto_clientes_ids='".$chh_prospecto_clientes_ids."',
									chh_prospecto_to_correo='".$chh_prospecto_to_correo."', 
									chh_prospecto_cc_correo='".$chh_prospecto_cc_correo."',
									chh_comentarios='".$chh_comentarios."',
									chh_subtotal_cotizacion='".$chh_subtotal_cotizacion."',
									chh_subtotal_desc_cotizacion='".$chh_subtotal_desc_cotizacion."',
									chh_total_cotizacion='".$chh_total_cotizacion."',
									chh_num_factura='".$chh_num_factura."',
									chh_status='M',
									chh_tipo='".$chh_tipo."',
									chh_modificado_por='".$chh_modificado_por."',
									chh_time_stamp=now()
								where chh_id=".$chh_id.""; 
		}
		$proveedor->execute($strSQL);
		
		if (!$proveedor->error()){
			$strSQL="update cotizador_hist_detalle set chd_status='E' where chh_id=".$chh_id.""; 
			$proveedor->execute($strSQL);
			if (!$proveedor->error()){
				$Error=$this->guardar_remision_detalle($chh_id, $chh_modificado_por, $Array_Padre_Cotizacion, $proveedor, "editar", $chh_cliente_id, $chh_prospecto_empresa);
			}else{
				$Error=true;
			}
		}else{
			$Error=true;
		}
		
		if($Error==false){
			$proveedor->commit();
			$respuesta = array("totalCount" => "1","chh_id" =>$chh_id, "estatus" => "ok", "mensaje" => "Se ha editado correctamente");
		}else{
			$proveedor->rollback();
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al editar");
		}
				
		$proveedor->close();
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}
	
	public function eliminar_remision($chh_id, $chh_modificado_por){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update cotizador_hist_maestro ";
		$strSQL.="set chh_status='E', chh_modificado_por='".$chh_modificado_por."', chh_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="chh_id=".$chh_id; 
		//echo $strSQL;
		$proveedor->execute($strSQL);
		
		if (!$proveedor->error()){
			$proveedor_d = new Proveedor('mysql', 'inavtek');
			$proveedor_d->connect();	
			$strSQL="update cotizador_hist_detalle ";
			$strSQL.="set chd_status='E', chd_modificado_por='".$chh_modificado_por."', chd_time_stamp=now() "; 
			$strSQL.="where ";
			$strSQL.="chh_id=".$chh_id; 
			//echo $strSQL;
			$proveedor_d->execute($strSQL);
			
			if (!$proveedor_d->error()){
			}else{
				$Error=true;
			}
			
			$proveedor_d->close();
		
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

	public function tabla_remisiones($chh_id, $chh_tipo){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			SELECT 
				M.chh_id,
				M.chh_cliente_id,
				M.chh_prospecto_empresa,
				M.chh_prospecto_nombre,
				M.chh_prospecto_tel,
				M.chh_prospecto_clientes_ids,
				M.chh_prospecto_to_correo,
				M.chh_prospecto_cc_correo,
				M.chh_comentarios,
				M.chh_fecha_hora,
				M.chh_subtotal_cotizacion,
				M.chh_subtotal_desc_cotizacion,
				M.chh_total_cotizacion,
				M.chh_num_factura,
				M.chh_total_facturacion,
				M.chh_status,
				M.chh_tipo,
				M.chh_modificado_por,
				M.chh_time_stamp,
				(select cliente_giro_desc from clientes_giros where clientes_giros.cliente_giro_id=clientes.cliente_giro_id) as Giro,
				(select cliente_tipo_desc from clientes_tipos where clientes_tipos.cliente_tipo_id=clientes.cliente_tipo_id) as Tipo,
				clientes.cliente_razon_social,
				clientes.cliente_rfc,
				case when chh_cliente_id is null then
				'Prospecto'
				else
				'Cliente'
				end as Cliente_Prospecto
			FROM cotizador_hist_maestro M
				left join clientes on M.chh_cliente_id=clientes.cliente_id
			where M.chh_status<>'E' 
		";
		
		if($chh_id!=""){
			$sql.=" 
				and M.chh_id=".$chh_id."
			";				
		}
		
		if($chh_tipo!=""){
			$sql.=" 
				and M.chh_tipo='".$chh_tipo."'
			";				
		}
		
		
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {	
				
					$chh_cliente_id="";
					if(rtrim(ltrim($row["chh_cliente_id"]))!="NULL"){$chh_cliente_id=rtrim(ltrim($row["chh_cliente_id"]));}
					$chh_prospecto_empresa="";
					if(rtrim(ltrim($row["chh_prospecto_empresa"]))!="NULL"){$chh_prospecto_empresa=rtrim(ltrim($row["chh_prospecto_empresa"]));}
					$chh_prospecto_nombre="";
					if(rtrim(ltrim($row["chh_prospecto_nombre"]))!="NULL"){$chh_prospecto_nombre=rtrim(ltrim($row["chh_prospecto_nombre"]));}
					$chh_prospecto_tel="";
					if(rtrim(ltrim($row["chh_prospecto_tel"]))!="NULL"){$chh_prospecto_tel=rtrim(ltrim($row["chh_prospecto_tel"]));}
					$chh_prospecto_clientes_ids="";
					if(rtrim(ltrim($row["chh_prospecto_clientes_ids"]))!="NULL"){$chh_prospecto_clientes_ids=rtrim(ltrim($row["chh_prospecto_clientes_ids"]));}
					$chh_prospecto_to_correo="";
					if(rtrim(ltrim($row["chh_prospecto_to_correo"]))!="NULL"){$chh_prospecto_to_correo=rtrim(ltrim($row["chh_prospecto_to_correo"]));}
					$chh_prospecto_cc_correo="";
					if(rtrim(ltrim($row["chh_prospecto_cc_correo"]))!="NULL"){$chh_prospecto_cc_correo=rtrim(ltrim($row["chh_prospecto_cc_correo"]));}
					$chh_comentarios="";
					if(rtrim(ltrim($row["chh_comentarios"]))!="NULL"){$chh_comentarios=rtrim(ltrim($row["chh_comentarios"]));}
					$chh_fecha_hora="";
					if(rtrim(ltrim($row["chh_fecha_hora"]))!="NULL"){$chh_fecha_hora=rtrim(ltrim($row["chh_fecha_hora"]));}
					$chh_subtotal_cotizacion="";
					if(rtrim(ltrim($row["chh_subtotal_cotizacion"]))!="NULL"){$chh_subtotal_cotizacion=rtrim(ltrim($row["chh_subtotal_cotizacion"]));}
					$chh_subtotal_desc_cotizacion="";
					if(rtrim(ltrim($row["chh_subtotal_desc_cotizacion"]))!="NULL"){$chh_subtotal_desc_cotizacion=rtrim(ltrim($row["chh_subtotal_desc_cotizacion"]));}
					$chh_total_cotizacion="";
					if(rtrim(ltrim($row["chh_total_cotizacion"]))!="NULL"){$chh_total_cotizacion=rtrim(ltrim($row["chh_total_cotizacion"]));}
					$chh_num_factura="";
					if(rtrim(ltrim($row["chh_num_factura"]))!="NULL"){$chh_num_factura=rtrim(ltrim($row["chh_num_factura"]));}
					$chh_total_facturacion="";
					if(rtrim(ltrim($row["chh_total_facturacion"]))!="NULL"){$chh_total_facturacion=rtrim(ltrim($row["chh_total_facturacion"]));}
					$Giro="";
					if(rtrim(ltrim($row["Giro"]))!="NULL"){$Giro=rtrim(ltrim($row["Giro"]));}
					$Tipo="";
					if(rtrim(ltrim($row["Tipo"]))!="NULL"){$Tipo=rtrim(ltrim($row["Tipo"]));}
					$cliente_razon_social="";
					if(rtrim(ltrim($row["cliente_razon_social"]))!="NULL"){$cliente_razon_social=rtrim(ltrim($row["cliente_razon_social"]));}
					$cliente_rfc="";
					if(rtrim(ltrim($row["cliente_rfc"]))!="NULL"){$cliente_rfc=rtrim(ltrim($row["cliente_rfc"]));}
				
					
					$Data= array(
						"chh_id" => $row["chh_id"],
						"chh_cliente_id" => $chh_cliente_id,
						"chh_prospecto_empresa" => rtrim(ltrim($chh_prospecto_empresa)),
						"chh_prospecto_nombre" => rtrim(ltrim($chh_prospecto_nombre)),
						"chh_prospecto_tel" => rtrim(ltrim($chh_prospecto_tel)),
						"chh_prospecto_clientes_ids" => rtrim(ltrim($chh_prospecto_clientes_ids)),
						"chh_prospecto_to_correo" => rtrim(ltrim($chh_prospecto_to_correo)),
						"chh_prospecto_cc_correo" => rtrim(ltrim($chh_prospecto_cc_correo)),
						"chh_comentarios" => rtrim(ltrim($chh_comentarios)),
						"chh_fecha_hora" => rtrim(ltrim($chh_fecha_hora)),
						"chh_subtotal_cotizacion" => number_format(floatval($chh_subtotal_cotizacion), 2, '.', ','),
						"chh_subtotal_desc_cotizacion" => number_format(floatval($chh_subtotal_desc_cotizacion), 2, '.', ','),
						"chh_total_cotizacion" => number_format(floatval($chh_total_cotizacion), 2, '.', ','),
						"chh_num_factura" => rtrim(ltrim($chh_num_factura)),
						"chh_total_facturacion" => rtrim(ltrim($chh_total_facturacion)),
						"chh_status" => rtrim(ltrim($row["chh_status"])),
						"chh_tipo" => rtrim(ltrim($row["chh_tipo"])),
						"chh_modificado_por" => rtrim(ltrim($row["chh_modificado_por"])),
						"chh_time_stamp" => rtrim(ltrim($row["chh_time_stamp"])),
						"Giro" => rtrim(ltrim($Giro)),
						"Tipo" => rtrim(ltrim($Tipo)),
						"cliente_razon_social" => rtrim(ltrim($cliente_razon_social)),
						"cliente_rfc" => rtrim(ltrim($cliente_rfc)),
						"Cliente_Prospecto" => rtrim(ltrim($row["Cliente_Prospecto"]))
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
	
	public function crear_remision($chh_id, $chh_modificado_por){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$Error=false;
		$Idmaximo="";
		
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$proveedor->beginTransaction();
		//Obtengo el Id Maximo
		$valormaximo=" select CASE when max(chh_id+1) IS NULL then 1 else (max(chh_id + 1)) end as IndiceMaximo from cotizador_hist_maestro ";
		$proveedor->execute($valormaximo);
		
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				$row_max = $proveedor->fetch_array($proveedor->stmt, 0);
				$Idmaximo=$row_max["IndiceMaximo"];
				$strSQL="insert into cotizador_hist_maestro ";
				$strSQL.="(
										chh_id,
										chh_cliente_id,
										chh_prospecto_empresa,  
										chh_prospecto_nombre, 	
										chh_prospecto_tel, 
										chh_prospecto_to_correo, 
										chh_prospecto_cc_correo,
										chh_prospecto_clientes_ids,
										chh_comentarios,
										chh_fecha_hora,
										chh_subtotal_cotizacion,
										chh_subtotal_desc_cotizacion,
										chh_total_cotizacion,
										chh_num_factura,
										chh_total_facturacion,
										chh_status,
										chh_tipo,
										chh_modificado_por,
										chh_time_stamp
									) ";
				$strSQL.="
										select 
											".$Idmaximo.",
											chh_cliente_id,
											chh_prospecto_empresa,  
											chh_prospecto_nombre, 	
											chh_prospecto_tel, 
											chh_prospecto_to_correo, 
											chh_prospecto_cc_correo,
											chh_prospecto_clientes_ids,
											chh_comentarios,
											chh_fecha_hora,
											chh_subtotal_cotizacion,
											chh_subtotal_desc_cotizacion,
											chh_total_cotizacion,
											chh_num_factura,
											chh_total_facturacion,
											'A',
											'R',
											'".$chh_modificado_por."',
											now()
										from 	cotizador_hist_maestro where chh_id=".$chh_id."
									";
				//echo "<pre>";
				//echo $strSQL;
				//echo "</pre>";
				$proveedor->execute($strSQL);
				
				if (!$proveedor->error()){
					$strSQL="insert into cotizador_hist_detalle ";
					$strSQL.="(
										chh_id,
										chd_sku,  
										chd_sku_desc, 	
										chd_sku_almacen, 
										chd_sku_alm_exst_ubicacion, 
										chd_sku_linea,
										chd_sku_familia,
										chd_sku_categoria,
										chd_sku_cod_barras,
										chd_sku_cod_interno,
										chd_sku_marca,
										chd_sku_modelo,
										chd_sku_serie,
										chd_sku_fabricante,
										chd_sku_cantidad,
										chd_sku_descuento,
										chd_sku_costo,
										chd_sku_precio,
										chd_sku_sort,
										chd_status,
										chd_modificado_por,
										chd_time_stamp
									) ";
					$strSQL.="
										select 
											".$Idmaximo.",
											chd_sku,  
											chd_sku_desc, 	
											chd_sku_almacen, 
											chd_sku_alm_exst_ubicacion, 
											chd_sku_linea,
											chd_sku_familia,
											chd_sku_categoria,
											chd_sku_cod_barras,
											chd_sku_cod_interno,
											chd_sku_marca,
											chd_sku_modelo,
											chd_sku_serie,
											chd_sku_fabricante,
											chd_sku_cantidad,
											chd_sku_descuento,
											chd_sku_costo,
											chd_sku_precio,
											chd_sku_sort,
											'A',
											'".$chh_modificado_por."',
											now()
										from 	cotizador_hist_detalle where chh_id=".$chh_id." and chd_status<>'E'
									";
					//echo $strSQL;				
									
					$proveedor->execute($strSQL);
					if (!$proveedor->error()){
					}else{
						$Error=true;
					}
				}else{
					$Error=true;
				}
				
			}
		}else{
			$Error=true;
		}
		
		
		if($Error==false){
			$proveedor->commit();
			$respuesta = array("totalCount" => "1","chh_id" =>$Idmaximo, "estatus" => "ok", "mensaje" => "Se ha registrado correctamente");
		}else{
			$proveedor->rollback();
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al registrar");
		}
				
		$proveedor->close();
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}
	
	public function listar_archivos($id){
		$respuesta = array();
		$Archivos="";
		
		//if($id!=""){
		//	$path="archivos_remisiones";
		//	$ftp_server = "ftp.bsolutionsmx.com";
		//  $conn_id = ftp_connect($ftp_server);
		//  $ftp_user_name = "bsolutio";
		//  $ftp_user_pass = "tc#Inc(I32*01";
		//  $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
		//  $contents = ftp_nlist($conn_id, 'httpdocs\inavtek\archivos_remisiones');
		//  for ($i = 0 ; $i < count($contents) ; $i++){
		//		$elemento=$contents[$i];
		//		if($contents[$i] != "." && $contents[$i] != ".."){
		//			$elemento_p = explode("-_-", $contents[$i]);
		//			if(count($elemento_p)>0){
		//				if($elemento_p[0]==$id){
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
		//								$Archivos.='<img src="./images/pdf.gif" border="0">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
		//							}else{
		//								if($extension[1]=="jpg"||$extension[1]=="JPG"){
		//									$Archivos.='<img src="./images/icon_jpg.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
		//								}else{
		//									if($extension[1]=="png"||$extension[1]=="PNG"){
		//										$Archivos.='<img src="./images/png.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
		//									}else{
		//										if($extension[1]=="doc"||$extension[1]=="DOC"||$extension[1]=="docx"||$extension[1]=="DOCX"){
		//											$Archivos.='<img src="./images/word.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
		//										}else{
		//											if($extension[1]=="xlsx"||$extension[1]=="XLSX"||$extension[1]=="xls"||$extension[1]=="xls"){
		//												$Archivos.='<img src="./images/xls.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
		//											}else{
		//												$Archivos.='<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',"'.$path.'/'.$elemento.'") class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br>';	
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
		
		
		if($id!=""){
			$path="../archivos_remisiones";
			$dir = opendir("../archivos_remisiones");
			
			
			// Leo todos los ficheros de la carpeta
			while ($elemento = readdir($dir)){
				// Tratamos los elementos . y .. que tienen todas las carpetas
				if( $elemento != "." && $elemento != ".."){
					
					$elemento_p = explode("-_-", $elemento);
					
					if(count($elemento_p)>0){
						if($elemento_p[0]==$id){
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
										$Archivos.='<img src="../images/pdf.gif" border="0">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
									}else{
										if($extension[1]=="jpg"||$extension[1]=="JPG"){
											$Archivos.='<img src="../images/icon_jpg.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
										}else{
											if($extension[1]=="png"||$extension[1]=="PNG"){
												$Archivos.='<img src="../images/png.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
											}else{
												if($extension[1]=="doc"||$extension[1]=="DOC"||$extension[1]=="docx"||$extension[1]=="DOCX"){
													$Archivos.='<img src="../images/word.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
												}else{
													if($extension[1]=="xlsx"||$extension[1]=="XLSX"||$extension[1]=="xls"||$extension[1]=="xls"){
														$Archivos.='<img src="../images/xls.png" border="0" widt="10px">&nbsp;<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br><br>';
													}else{
														$Archivos.='<a href="'.$path.'/'.$elemento.'" target="_blank">'.$extension[0].'</a> <a onclick="borrararchivo('.$id.',\''.$elemento.'\')" class="red" title="Eliminar" href="#noir"><i class="ace-icon fa fa-trash-o bigger-130" title="Eliminar"></i></a><br>';	
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
		$Ruta_Archivo="httpdocs/inavtek/archivos_remisiones/".$Archivo;
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

@$chh_id=$_POST["chh_id"];
@$chh_cliente_id=$_POST["chh_cliente_id"];
@$chh_prospecto_empresa=$_POST["chh_prospecto_empresa"];
@$chh_prospecto_nombre=$_POST["chh_prospecto_nombre"];
@$chh_prospecto_tel=$_POST["chh_prospecto_tel"];
@$chh_prospecto_clientes_ids=$_POST["chh_prospecto_clientes_ids"];
@$chh_prospecto_to_correo=$_POST["chh_prospecto_to_correo"];
@$chh_prospecto_cc_correo=$_POST["chh_prospecto_cc_correo"];
@$chh_comentarios=$_POST["chh_comentarios"];
@$chh_total_cotizacion=$_POST["chh_total_cotizacion"];
@$chh_num_factura=$_POST["chh_num_factura"];
@$chh_subtotal_cotizacion=$_POST["chh_subtotal_cotizacion"];
@$chh_subtotal_desc_cotizacion=$_POST["chh_subtotal_desc_cotizacion"];
@$chh_total_facturacion=$_POST["chh_total_facturacion"];
@$chh_status=$_POST["chh_status"];
@$chh_tipo=$_POST["chh_tipo"];
@$chh_modificado_por=$_POST["chh_modificado_por"];
@$Array_Padre_Cotizacion=$_POST["Array_Padre_Cotizacion"];
@$Archivo=$_POST["Archivo"];

@$tipo_movimiento=$_POST["tipo_movimiento"];
@$fecha_inicial=$_POST["fecha_inicial"];
@$fecha_final=$_POST["fecha_final"];
@$realizo=$_POST["realizo"];

@$accion=$_POST["accion"];
$Consultas = new consultas();
if($accion=="consulta_remision"){
	$remision=$Consultas->consulta_remision($chh_id, $chh_tipo);
	echo $remision; 
}else if($accion=="guardar_remision"){	
	$remision=$Consultas->guardar_remision($chh_id, $chh_cliente_id, $chh_prospecto_empresa, $chh_prospecto_nombre, $chh_prospecto_tel, $chh_prospecto_clientes_ids, $chh_prospecto_to_correo, $chh_prospecto_cc_correo, $chh_comentarios, $chh_subtotal_cotizacion, $chh_subtotal_desc_cotizacion, $chh_total_cotizacion, $chh_num_factura, $chh_total_facturacion, $chh_status, $chh_tipo, $chh_modificado_por, $Array_Padre_Cotizacion);
	echo $remision;
}else if($accion=="editar_remision"){	
	$remision=$Consultas->editar_remision($chh_id, $chh_cliente_id, $chh_prospecto_empresa, $chh_prospecto_nombre, $chh_prospecto_tel, $chh_prospecto_clientes_ids, $chh_prospecto_to_correo, $chh_prospecto_cc_correo, $chh_comentarios, $chh_subtotal_cotizacion, $chh_subtotal_desc_cotizacion, $chh_total_cotizacion, $chh_num_factura, $chh_total_facturacion, $chh_status, $chh_tipo, $chh_modificado_por, $Array_Padre_Cotizacion);
	echo $remision;
}else if($accion=="eliminar_cot_remi"){	
	$remision=$Consultas->eliminar_remision($chh_id, $chh_modificado_por);
	echo $remision;
}else if($accion=="tabla_remisiones"){	
	$remision=$Consultas->tabla_remisiones($chh_id, $chh_tipo);
	echo $remision;
}else if($accion=="crear_remision"){	
	$remision=$Consultas->crear_remision($chh_id, $chh_modificado_por);
	echo $remision;
}else if($accion=="listar_archivos"){	
	$remision=$Consultas->listar_archivos($chh_id);
	echo $remision;
}else if($accion=="borrar_archivo"){
	$comp=$Consultas->borrar_archivo($Archivo);
	echo $comp; 
}else if($accion=="kardex"){
	$comp=$Consultas->kardex($tipo_movimiento, $fecha_inicial, $fecha_final, $realizo);
	echo $comp; 
}




?>