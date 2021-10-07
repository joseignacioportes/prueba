<?php
include_once("../datos/dtotojson/DtoToJson.Class.php");
include_once("../datos/json/JsonEncod.Class.php");
include_once("../datos/json/JsonDecod.Class.php");
include_once("../datos/connect/Proveedor.Class.php");
require_once("CURL.php");
class consultas {
	public function __construct() {
	}
	
	public function consulta_cotizacion($chh_id, $chh_tipo){
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
	
	public function guardar_cotizacion($chh_id, $chh_cliente_id, $chh_prospecto_empresa, $chh_prospecto_nombre, $chh_prospecto_tel, $chh_prospecto_clientes_ids, $chh_prospecto_to_correo, $chh_prospecto_cc_correo, $chh_comentarios, $chh_subtotal_cotizacion, $chh_subtotal_desc_cotizacion, $chh_total_cotizacion, $chh_num_factura, $chh_total_facturacion, $chh_status, $chh_tipo, $chh_modificado_por, $Array_Padre_Cotizacion){
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
				
				//$proveedor = new Proveedor('mysql', 'inavtek');
				//$proveedor->connect();	
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
											now(),
											'A',
											'".$chh_tipo."',
											'".$chh_modificado_por."',
											now()
										)";
				}else{
					//Se agrega cliente cotizaci�n
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
											now(),
											'A',
											'".$chh_tipo."',
											'".$chh_modificado_por."',
											now()
										)";
				}
				
				$proveedor->execute($strSQL);
				
				if (!$proveedor->error()){
					$Error=$this->guardar_cotizacion_detalle($Idmaximo, $chh_modificado_por, $Array_Padre_Cotizacion, $proveedor, "guardar", $chh_cliente_id, $chh_prospecto_empresa);
					
					if($Error!=true){
						if($chh_tipo=="C"){
							$this->envio_mail($Idmaximo, $chh_prospecto_to_correo, $chh_prospecto_cc_correo);
						}
					}
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
	
	public function guardar_cotizacion_detalle($chh_id, $chd_modificado_por, $Array_Padre_Cotizacion, $proveedor, $tipo_guardar_editar, $chh_cliente_id, $chh_prospecto_empresa){
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
			}else{
				$Error=true;
			}
		}
		return $Error;
	}
	
	public function editar_cotizacion($chh_id, $chh_cliente_id, $chh_prospecto_empresa, $chh_prospecto_nombre, $chh_prospecto_tel, $chh_prospecto_clientes_ids, $chh_prospecto_to_correo, $chh_prospecto_cc_correo, $chh_comentarios, $chh_subtotal_cotizacion, $chh_subtotal_desc_cotizacion, $chh_total_cotizacion, $chh_num_factura, $chh_total_facturacion, $chh_status, $chh_tipo, $chh_modificado_por, $Array_Padre_Cotizacion){
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
				$Error=$this->guardar_cotizacion_detalle($chh_id, $chh_modificado_por, $Array_Padre_Cotizacion, $proveedor, "editar", $chh_cliente_id, $chh_prospecto_empresa);
			
				if($Error!=true){
					if($chh_tipo=="C"){
						$this->envio_mail($chh_id, $chh_prospecto_to_correo, $chh_prospecto_cc_correo);
					}
				}
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
	

	public function eliminar_cotizacion($chh_id, $chh_modificado_por){
		
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

	public function tabla_cotizaciones($chh_id, $chh_tipo){
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
				(select usr_nombre_completo from usuarios where usuarios.usr_usuario=M.chh_modificado_por) as Usuario_Modifico,
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
						"Cliente_Prospecto" => rtrim(ltrim($row["Cliente_Prospecto"])),
						"Usuario_Modifico" => rtrim(ltrim($row["Usuario_Modifico"]))
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
	
	public function envio_mail($chh_id, $chh_prospecto_to_correo, $chh_prospecto_cc_correo){
		$json = '{"estatus":"fail"}';
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$con_copia="";
		if($chh_prospecto_cc_correo!=""){
			$con_copia="ventas@inavtek.com; ".$chh_prospecto_cc_correo;	
		}else{
			$con_copia="ventas@inavtek.com; ";	
		}
		
		$Subject="INAVTEK: Cotización número: ".$chh_id;
		$Subject=str_replace("á", "a|", $Subject);
		$Subject=str_replace("Á", "A|", $Subject);
		$Subject=str_replace("é", "e|", $Subject);
		$Subject=str_replace("É", "E|", $Subject);
		$Subject=str_replace("í", "i|", $Subject);
		$Subject=str_replace("Í", "I|", $Subject);
		$Subject=str_replace("ó", "o|", $Subject);
		$Subject=str_replace("Ó", "O|", $Subject);
		$Subject=str_replace("ú", "u|", $Subject);
		$Subject=str_replace("Ú", "U|", $Subject);
		$Subject=str_replace("ñ", "n|", $Subject);
		$Subject=str_replace("Ñ", "N|", $Subject);
					
		$body='<div width="100%" align="center"><font face="arial" size="3"><b><img src="https://bsolutionsmx.com/inavtek/dist/img/logo.png" style="text-decoration:none"><!-- </img> --></b></font></div><br><br>';
		$body.='<font face="arial" size="2.5">En atención a su solicitud, le enviamos la siguiente cotización esperando ser favorecidos con su preferencia.</font><br><br>';
		$body.='<font face="arial" size="2.5">Clic en este <a href="https://bsolutionsmx.com/inavtek/vistas/reporte.php?key='.$chh_id.'">link</a> para abrir la cotización.</font><br><br>';
		$body.='<font face="arial" size="2.5">Quedamos atentos a sus comentarios.</font><br><br>';
		$body.='<font face="arial" size="2.5">Saludos.</font><br><br>';
		$body.='<br><br><br><font face="arial" size="1"><i>* Este es un envío automatizado, no es necesario responder este correo.</i></font>';
		//$body.=$chh_prospecto_to_correo."-".$chh_prospecto_cc_correo;
		$body=str_replace("á", "a|", $body);
		$body=str_replace("Á", "A|", $body);
		$body=str_replace("é", "e|", $body);
		$body=str_replace("É", "E|", $body);
		$body=str_replace("í", "i|", $body);
		$body=str_replace("Í", "I|", $body);
		$body=str_replace("ó", "o|", $body);
		$body=str_replace("Ó", "O|", $body);
		$body=str_replace("ú", "u|", $body);
		$body=str_replace("Ú", "U|", $body);
		$body=str_replace("ñ", "n|", $body);
		$body=str_replace("Ñ", "N|", $body);
		
		$obj = new CURL();
		$url = "https://bsolutionsmx.com/inavtek/envio_correo_externo/send_external_email.asp";                                       
		//Productivo
		$data = array('strPassword' => 'I68V57T42', 'strSubject' => $Subject,'strTo'=>$chh_prospecto_to_correo,'strHTMLBody'=>$body,'strCc'=>$con_copia,'strBCC'=>'mramos@bsolutionsmx.com; jose8_23@hotmail.com;');
		//Pruebas
		//$data = array('strPassword' => 'I68V57T42', 'strSubject' => $Subject,'strTo'=>"ventas@inavtek.com;",'strHTMLBody'=>$body,'strCc'=>"",'strBCC'=>'jose8_23@hotmail.com; mramos@bsolutionsmx.com;');
		$correoASP = $obj->curlData($url,$data);	
		$json = '{"estatus":"ok"}';
		//echo $json;
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


@$accion=$_POST["accion"];
$Consultas = new consultas();
if($accion=="consulta_cotizacion"){
	$cotizacion=$Consultas->consulta_cotizacion($chh_id, $chh_tipo);
	echo $cotizacion; 
}else if($accion=="guardar_cotizacion"){	
	$cotizacion=$Consultas->guardar_cotizacion($chh_id, $chh_cliente_id, $chh_prospecto_empresa, $chh_prospecto_nombre, $chh_prospecto_tel, $chh_prospecto_clientes_ids, $chh_prospecto_to_correo, $chh_prospecto_cc_correo, $chh_comentarios, $chh_subtotal_cotizacion, $chh_subtotal_desc_cotizacion, $chh_total_cotizacion, $chh_num_factura, $chh_total_facturacion, $chh_status, $chh_tipo, $chh_modificado_por, $Array_Padre_Cotizacion);
	echo $cotizacion;
}else if($accion=="editar_cotizacion"){	
	$cotizacion=$Consultas->editar_cotizacion($chh_id, $chh_cliente_id, $chh_prospecto_empresa, $chh_prospecto_nombre, $chh_prospecto_tel, $chh_prospecto_clientes_ids, $chh_prospecto_to_correo, $chh_prospecto_cc_correo, $chh_comentarios, $chh_subtotal_cotizacion, $chh_subtotal_desc_cotizacion, $chh_total_cotizacion, $chh_num_factura, $chh_total_facturacion, $chh_status, $chh_tipo, $chh_modificado_por, $Array_Padre_Cotizacion);
	echo $cotizacion;
}else if($accion=="eliminar_cot_remi"){	
	$cotizacion=$Consultas->eliminar_cotizacion($chh_id, $chh_modificado_por);
	echo $cotizacion;
}else if($accion=="tabla_cotizaciones"){	
	$cotizacion=$Consultas->tabla_cotizaciones($chh_id, $chh_tipo);
	echo $cotizacion;
}




?>