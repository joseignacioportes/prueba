<?php
include_once("../datos/dtotojson/DtoToJson.Class.php");
include_once("../datos/json/JsonEncod.Class.php");
include_once("../datos/json/JsonDecod.Class.php");
include_once("../datos/connect/Proveedor.Class.php");
class consultas {
	public function __construct() {
	}
	public function cmb_giros(){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from clientes_giros where cliente_giro_status<>'E'
		";
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$Data= array(
						"cliente_giro_id" => rtrim(ltrim($row["cliente_giro_id"])),
						"cliente_giro_desc" => rtrim(ltrim($row["cliente_giro_desc"])),
						"cliente_giro_status" => rtrim(ltrim($row["cliente_giro_status"])),
						"cliente_giro_modifico" => rtrim(ltrim($row["cliente_giro_modifico"])),
						"cliente_giro_time_stamp" => rtrim(ltrim($row["cliente_giro_time_stamp"]))
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
	
	public function cmb_tipos(){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from clientes_tipos where cliente_tipo_status<>'E'
		";
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$Data= array(
						"cliente_tipo_id" => rtrim(ltrim($row["cliente_tipo_id"])),
						"cliente_tipo_desc" => rtrim(ltrim($row["cliente_tipo_desc"])),
						"cliente_tipo_status" => rtrim(ltrim($row["cliente_tipo_status"])),
						"cliente_tipo_modifico" => rtrim(ltrim($row["cliente_tipo_modifico"])),
						"cliente_tipo_time_stamp" => rtrim(ltrim($row["cliente_tipo_time_stamp"]))
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
	
	public function cmb_vendedores(){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select * from usuarios where usr_status<>'E' and usr_id_perfil in(1,2)
		";
		
		
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					
					$Data= array(
						"usr_id" => rtrim(ltrim($row["usr_id"])),
						"usr_nombre_completo" => rtrim(ltrim($row["usr_nombre_completo"]))
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

	public function consultar_clientes($cliente_id){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();
		$sql=" 
			select 
				*
			,clientes_tipos.cliente_tipo_desc
			,CASE
					WHEN cliente_regimen =1 THEN 'Régimen de incorporación fiscal (RIF)'
					WHEN cliente_regimen =2 THEN 'Régimen de actividad empresarial'
					WHEN cliente_regimen =3 THEN 'Régimen de arrendamiento de inmuebles'
					WHEN cliente_regimen =4 THEN 'Régimen de actividades profesionales (honorarios)'
					WHEN cliente_regimen =5 THEN 'Régimen de Asalariado'
			END as regimen_desc
			,cliente_giro_desc
			,usr_nombre_completo
			from clientes 
			left join clientes_tipos on clientes.cliente_tipo_id=clientes_tipos.cliente_tipo_id
			left join clientes_giros on clientes.cliente_giro_id=clientes_giros.cliente_giro_id
			left join usuarios on clientes.cliente_vendedor_id=usuarios.usr_id
			where cliente_status<>'E'
		";
		
		if($cliente_id!=""){
			$sql.=" 
				and cliente_id=".$cliente_id."
			";				
		}
		
		
		
		$proveedor->execute($sql);
		if (!$proveedor->error()){
			if ($proveedor->rows($proveedor->stmt) > 0) {
				while ($row = $proveedor->fetch_array($proveedor->stmt, 0)) {
					$cliente_tipo_id="";
					if(rtrim(ltrim($row["cliente_tipo_id"]))!="NULL"){
						$cliente_tipo_id=rtrim(ltrim($row["cliente_tipo_id"]));
					}
					$cliente_giro_id="";
					if(rtrim(ltrim($row["cliente_giro_id"]))!="NULL"){
						$cliente_giro_id=rtrim(ltrim($row["cliente_giro_id"]));
					}
					$cliente_lista_precios_id="";
					if(rtrim(ltrim($row["cliente_lista_precios_id"]))!="NULL"){
						$cliente_lista_precios_id=rtrim(ltrim($row["cliente_lista_precios_id"]));
					}
					$cliente_vendedor_id="";
					if(rtrim(ltrim($row["cliente_vendedor_id"]))!="NULL"){
						$cliente_vendedor_id=rtrim(ltrim($row["cliente_vendedor_id"]));
					}
					
					
					$cliente_limite_credito="";
					if(rtrim(ltrim($row["cliente_limite_credito"]))!="NULL"){
						$cliente_limite_credito=rtrim(ltrim($row["cliente_limite_credito"]));
					}
					$cliente_saldo="";
					if(rtrim(ltrim($row["cliente_saldo"]))!="NULL"){
						$cliente_saldo=rtrim(ltrim($row["cliente_saldo"]));
					}
					$cliente_fecha_alta="";
					if(rtrim(ltrim($row["cliente_fecha_alta"]))!="NULL"){
						$cliente_fecha_alta=rtrim(ltrim($row["cliente_fecha_alta"]));
					}
					$cliente_fecha_baja="";
					if(rtrim(ltrim($row["cliente_fecha_baja"]))!="NULL"){
						$cliente_fecha_baja=rtrim(ltrim($row["cliente_fecha_baja"]));
					}
					
					$Data= array(
						"cliente_id" => $row["cliente_id"],
						"cliente_tipo_id" => $cliente_tipo_id,
						"cliente_giro_id" => $cliente_giro_id,
						"cliente_lista_precios_id" => $cliente_lista_precios_id,
						"cliente_vendedor_id" => $cliente_vendedor_id,
						"cliente_razon_social" => rtrim(ltrim($row["cliente_razon_social"])),
						"cliente_rfc" => rtrim(ltrim($row["cliente_rfc"])),
						"cliente_regimen" => rtrim(ltrim($row["cliente_regimen"])),
						"cliente_calle" => rtrim(ltrim($row["cliente_calle"])),
						"cliente_numero_ext" => rtrim(ltrim($row["cliente_numero_ext"])),
						"cliente_numero_int" => rtrim(ltrim($row["cliente_numero_int"])),
						"cliente_colonia" => rtrim(ltrim($row["cliente_colonia"])),
						"cliente_municipio" => rtrim(ltrim($row["cliente_municipio"])),
						"cliente_estado" => rtrim(ltrim($row["cliente_estado"])),
						"cliente_cp" => rtrim(ltrim($row["cliente_cp"])),
						"cliente_pais" => rtrim(ltrim($row["cliente_pais"])),
						"cliente_nacionalidad" => rtrim(ltrim($row["cliente_nacionalidad"])),
						"cliente_website" => rtrim(ltrim($row["cliente_website"])),
						"cliente_facebook" => rtrim(ltrim($row["cliente_facebook"])),
						"cliente_tel" => rtrim(ltrim($row["cliente_tel"])),
						"cliente_dir_entrega" => rtrim(ltrim($row["cliente_dir_entrega"])),
						"cliente_cond_pago" => rtrim(ltrim($row["cliente_cond_pago"])),
						"cliente_dias_revision" => rtrim(ltrim($row["cliente_dias_revision"])),
						"cliente_horario_revision" => rtrim(ltrim($row["cliente_horario_revision"])),
						"cliente_dias_pago" => rtrim(ltrim($row["cliente_dias_pago"])),
						"cliente_limite_credito" => $cliente_limite_credito,
						"cliente_saldo" => $cliente_saldo,
						"cliente_fecha_alta" => $cliente_fecha_alta,
						"cliente_fecha_baja" => $cliente_fecha_baja,
						"cliente_status" => rtrim(ltrim($row["cliente_status"])),
						"cliente_modifico" => rtrim(ltrim($row["cliente_modifico"])),
						"cliente_time_stamp" => rtrim(ltrim($row["cliente_time_stamp"])),
						"cliente_tipo_desc" => rtrim(ltrim($row["cliente_tipo_desc"])),
						"regimen_desc" => rtrim(ltrim($row["regimen_desc"])),
						"cliente_giro_desc" => rtrim(ltrim($row["cliente_giro_desc"])),
						"usr_nombre_completo" => rtrim(ltrim($row["usr_nombre_completo"]))
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
		
	public function guardar_clientes($cliente_tipo_id, $cliente_giro_id, $cliente_lista_precios_id, $cliente_vendedor_id, $cliente_razon_social, $cliente_rfc, $cliente_regimen, $cliente_calle, $cliente_numero_ext, $cliente_numero_int, $cliente_colonia, $cliente_municipio, $cliente_estado, $cliente_cp, $cliente_pais, $cliente_nacionalidad, $cliente_website, $cliente_facebook, $cliente_tel, $cliente_dir_entrega, $cliente_cond_pago, $cliente_dias_revision, $cliente_horario_revision, $cliente_dias_pago, $cliente_limite_credito, $cliente_saldo, $cliente_fecha_alta, $cliente_fecha_baja, $cliente_status, $cliente_modifico){
		$Data = array();
		$Data_Envia = array();
		$respuesta = array();
		$Error=false;
		$Idmaximo="";
		
		
		
		$repetidos = new Proveedor('mysql', 'inavtek');
		$repetidos->connect();
		//Obtengo el Id Maximo
		$sql_repetidos=" select * from clientes where rtrim(ltrim(cliente_rfc))='".$cliente_rfc."' and cliente_status<>'E' ";
		$repetidos->execute($sql_repetidos);
		
		if (!$repetidos->error()){
			if ($repetidos->rows($repetidos->stmt) == 0) {
				$proveedor_M = new Proveedor('mysql', 'inavtek');
				$proveedor_M->connect();
				//Obtengo el Id Maximo
				$valormaximo=" select CASE when max(cliente_id+1) IS NULL then 1 else (max(cliente_id + 1)) end as IndiceMaximo from clientes ";
				$proveedor_M->execute($valormaximo);
				
				if (!$proveedor_M->error()){
					if ($proveedor_M->rows($proveedor_M->stmt) > 0) {
						$row_max = $proveedor_M->fetch_array($proveedor_M->stmt, 0);
						$Idmaximo=$row_max["IndiceMaximo"];
						
						$proveedor = new Proveedor('mysql', 'inavtek');
						$proveedor->connect();	
						$strSQL="insert into clientes ";
						$strSQL.="(
									cliente_id, 
									cliente_tipo_id, 
									cliente_giro_id, 
									cliente_lista_precios_id, 
									cliente_vendedor_id, 
									cliente_razon_social, 
									cliente_rfc, 
									cliente_regimen, 
									cliente_calle, 
									cliente_numero_ext, 
									cliente_numero_int, 
									cliente_colonia, 
									cliente_municipio, 
									cliente_estado, 
									cliente_cp, 
									cliente_pais, 
									cliente_nacionalidad, 
									cliente_website, 
									cliente_facebook, 
									cliente_tel, 
									cliente_dir_entrega, 
									cliente_cond_pago, 
									cliente_dias_revision, 
									cliente_horario_revision, 
									cliente_dias_pago, 
									cliente_limite_credito, 
									cliente_saldo, 
									cliente_fecha_alta, 
									cliente_fecha_baja, 
									cliente_status, 
									cliente_modifico, 
									cliente_time_stamp) "; 
						$strSQL.="VALUES ";
						$strSQL.="(".$Idmaximo.",";
						if($cliente_tipo_id!=""){
						$strSQL.=  $cliente_tipo_id.",";
						}else{ $strSQL.="NULL,"; }	
						if($cliente_giro_id!=""){
						$strSQL.=  $cliente_giro_id.","; 
						}else{ $strSQL.="NULL,"; }
						if($cliente_lista_precios_id!=""){
						$strSQL.=  $cliente_lista_precios_id.",";
						}else{ $strSQL.="NULL,"; }
						if($cliente_vendedor_id!=""){
						$strSQL.=  $cliente_vendedor_id.",";
						}else{ $strSQL.="NULL,"; }
						$strSQL.="
								   '".$cliente_razon_social."',
								   '".$cliente_rfc."',
								   '".$cliente_regimen."',
								   '".$cliente_calle."',
								   '".$cliente_numero_ext."',
								   '".$cliente_numero_int."',
								   '".$cliente_colonia."',
								   '".$cliente_municipio."',
								   '".$cliente_estado."',
								   '".$cliente_cp."',
								   '".$cliente_pais."',
								   '".$cliente_nacionalidad."',
								   '".$cliente_website."',
								   '".$cliente_facebook."',
								   '".$cliente_tel."',
								   '".$cliente_dir_entrega."',
								   '".$cliente_cond_pago."',
								   '".$cliente_dias_revision."',
								   '".$cliente_horario_revision."',
								   '".$cliente_dias_pago."',
							";
							if($cliente_limite_credito){	
							$strSQL.="'".$cliente_limite_credito."',";
							}else{ $strSQL.="NULL,"; }
							if($cliente_saldo){
							$strSQL.="'".$cliente_saldo."',";
							}else{ $strSQL.="NULL,"; }
							if($cliente_fecha_alta){
							$strSQL.="'".$cliente_fecha_alta."',";
							}else{ $strSQL.="NULL,"; }
							if($cliente_fecha_baja){
							$strSQL.="'".$cliente_fecha_baja."',";
							}else{ $strSQL.="NULL,"; }
							$strSQL.="
								   '".$cliente_status."',
								   '".$cliente_modifico."',
								   now()
								   )"; 
						
						
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
					$respuesta = array("totalCount" => "1","perfil_id" =>$Idmaximo, "estatus" => "ok", "mensaje" => "Se ha registrado correctamente");
				}else{
					$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al registrar");
				}
				
			}else{
				$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "El RFC ya se encuentra registrado.");
			}
		}else{
			$respuesta = array("totalCount" => "0", "estatus" => "ok", "mensaje" => "Ocurrio un error al buscar repetidos.");
		}
		
		
		
		$jsonDto = new Encode_JSON();
		return $jsonDto->encode($respuesta);
	}

	public function editar_clientes($cliente_id, $cliente_tipo_id, $cliente_giro_id, $cliente_lista_precios_id, $cliente_vendedor_id, $cliente_razon_social, $cliente_rfc, $cliente_regimen, $cliente_calle, $cliente_numero_ext, $cliente_numero_int, $cliente_colonia, $cliente_municipio, $cliente_estado, $cliente_cp, $cliente_pais, $cliente_nacionalidad, $cliente_website, $cliente_facebook, $cliente_tel, $cliente_dir_entrega, $cliente_cond_pago, $cliente_dias_revision, $cliente_horario_revision, $cliente_dias_pago, $cliente_limite_credito, $cliente_saldo, $cliente_fecha_alta, $cliente_fecha_baja, $cliente_status, $cliente_modifico){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update clientes set ";
		if($cliente_tipo_id!=""){
			$strSQL.="cliente_tipo_id=".$cliente_tipo_id.", ";
		}else{ $strSQL.="cliente_tipo_id=NULL,"; }		
		if($cliente_giro_id!=""){
			$strSQL.="cliente_giro_id=".$cliente_giro_id.", ";	
		}else{ $strSQL.="cliente_giro_id=NULL,"; }		
		if($cliente_lista_precios_id!=""){
			$strSQL.="cliente_lista_precios_id=".$cliente_lista_precios_id.", ";			
		}else{ $strSQL.="cliente_lista_precios_id=NULL,"; }
		if($cliente_vendedor_id!=""){
			$strSQL.="cliente_vendedor_id=".$cliente_vendedor_id.", ";			
		}else{ $strSQL.="cliente_vendedor_id=NULL,"; }
		$strSQL.="	
					cliente_razon_social='".$cliente_razon_social."', 
					cliente_rfc='".$cliente_rfc."', 
					cliente_regimen='".$cliente_regimen."', 
					cliente_calle='".$cliente_calle."', 
					cliente_numero_ext='".$cliente_numero_ext."', 
					cliente_numero_int='".$cliente_numero_int."', 
					cliente_colonia='".$cliente_colonia."', 
					cliente_municipio='".$cliente_municipio."', 
					cliente_estado='".$cliente_estado."', 
					cliente_cp='".$cliente_cp."', 
					cliente_pais='".$cliente_pais."', 
					cliente_nacionalidad='".$cliente_nacionalidad."', 
					cliente_website='".$cliente_website."', 
					cliente_facebook='".$cliente_facebook."', 
					cliente_tel='".$cliente_tel."', 
					cliente_dir_entrega='".$cliente_dir_entrega."', 
					cliente_cond_pago='".$cliente_cond_pago."', 
					cliente_dias_revision='".$cliente_dias_revision."', 
					cliente_horario_revision='".$cliente_horario_revision."', 
					cliente_dias_pago='".$cliente_dias_pago."', 
		";
		if($cliente_limite_credito!=""){
			$strSQL.="	cliente_limite_credito='".$cliente_limite_credito."', ";
		}else{ $strSQL.="cliente_limite_credito=NULL,"; }
		if($cliente_saldo!=""){
			$strSQL.="	cliente_saldo='".$cliente_saldo."', ";
		}else{ $strSQL.="cliente_saldo=NULL,"; }
		if($cliente_fecha_alta!=""){
			$strSQL.="	cliente_fecha_alta='".$cliente_fecha_alta."',";
		}else{ $strSQL.="cliente_fecha_alta=NULL,"; }
		if($cliente_fecha_baja!=""){
			$strSQL.="	cliente_fecha_baja='".$cliente_fecha_baja."',";
		}else{ $strSQL.="cliente_fecha_baja=NULL,"; }
		$strSQL.="	cliente_status='".$cliente_status."', 
					cliente_modifico='".$cliente_modifico."', 
					cliente_time_stamp=now() ";
		$strSQL.="where ";
		$strSQL.="cliente_id=".$cliente_id; 
		
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

	public function eliminar_clientes($cliente_id, $cliente_status, $cliente_modifico){
		
		$respuesta = array();
		$Error=false;
		
		$proveedor = new Proveedor('mysql', 'inavtek');
		$proveedor->connect();	
		$strSQL="update clientes ";
		$strSQL.="set cliente_status='".$cliente_status."', cliente_modifico='".$cliente_modifico."', cliente_time_stamp=now() "; 
		$strSQL.="where ";
		$strSQL.="cliente_id=".$cliente_id; 
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

@$cliente_id=$_POST["cliente_id"];
@$cliente_tipo_id=$_POST["cliente_tipo_id"];
@$cliente_giro_id=$_POST["cliente_giro_id"];
@$cliente_lista_precios_id=$_POST["cliente_lista_precios_id"];
@$cliente_vendedor_id=$_POST["cliente_vendedor_id"];
@$cliente_razon_social=$_POST["cliente_razon_social"];
@$cliente_rfc=$_POST["cliente_rfc"];
@$cliente_regimen=$_POST["cliente_regimen"];
@$cliente_calle=$_POST["cliente_calle"];
@$cliente_numero_ext=$_POST["cliente_numero_ext"];
@$cliente_numero_int=$_POST["cliente_numero_int"];
@$cliente_colonia=$_POST["cliente_colonia"];
@$cliente_municipio=$_POST["cliente_municipio"];
@$cliente_estado=$_POST["cliente_estado"];
@$cliente_cp=$_POST["cliente_cp"];
@$cliente_pais=$_POST["cliente_pais"];
@$cliente_nacionalidad=$_POST["cliente_nacionalidad"];
@$cliente_website=$_POST["cliente_website"];
@$cliente_facebook=$_POST["cliente_facebook"];
@$cliente_tel=$_POST["cliente_tel"];
@$cliente_dir_entrega=$_POST["cliente_dir_entrega"];
@$cliente_cond_pago=$_POST["cliente_cond_pago"];
@$cliente_dias_revision=$_POST["cliente_dias_revision"];
@$cliente_horario_revision=$_POST["cliente_horario_revision"];
@$cliente_dias_pago=$_POST["cliente_dias_pago"];
@$cliente_limite_credito=$_POST["cliente_limite_credito"];
@$cliente_saldo=$_POST["cliente_saldo"];
@$cliente_fecha_alta=$_POST["cliente_fecha_alta"];
@$cliente_fecha_baja=$_POST["cliente_fecha_baja"];
@$cliente_status=$_POST["cliente_status"];
@$cliente_modifico=$_POST["cliente_modifico"];
@$cliente_time_stamp=$_POST["cliente_time_stamp"];





@$accion=$_POST["accion"];
$Consultas = new consultas();
if($accion=="consultar_clientes"){
	$perfiles=$Consultas->consultar_clientes($cliente_id);
	echo $perfiles; 
}else if($accion=="guardar_clientes"){	
	$usuarios=$Consultas->guardar_clientes($cliente_tipo_id, $cliente_giro_id, $cliente_lista_precios_id, $cliente_vendedor_id, $cliente_razon_social, $cliente_rfc, $cliente_regimen, $cliente_calle, $cliente_numero_ext, $cliente_numero_int, $cliente_colonia, $cliente_municipio, $cliente_estado, $cliente_cp, $cliente_pais, $cliente_nacionalidad, $cliente_website, $cliente_facebook, $cliente_tel, $cliente_dir_entrega, $cliente_cond_pago, $cliente_dias_revision, $cliente_horario_revision, $cliente_dias_pago, $cliente_limite_credito, $cliente_saldo, $cliente_fecha_alta, $cliente_fecha_baja, $cliente_status, $cliente_modifico);
	echo $usuarios;
}else if($accion=="editar_clientes"){	
	$usuarios=$Consultas->editar_clientes($cliente_id, $cliente_tipo_id, $cliente_giro_id, $cliente_lista_precios_id, $cliente_vendedor_id, $cliente_razon_social, $cliente_rfc, $cliente_regimen, $cliente_calle, $cliente_numero_ext, $cliente_numero_int, $cliente_colonia, $cliente_municipio, $cliente_estado, $cliente_cp, $cliente_pais, $cliente_nacionalidad, $cliente_website, $cliente_facebook, $cliente_tel, $cliente_dir_entrega, $cliente_cond_pago, $cliente_dias_revision, $cliente_horario_revision, $cliente_dias_pago, $cliente_limite_credito, $cliente_saldo, $cliente_fecha_alta, $cliente_fecha_baja, $cliente_status, $cliente_modifico);
	echo $usuarios;
}else if($accion=="eliminar_clientes"){	
	$usuarios=$Consultas->eliminar_clientes($cliente_id, $cliente_status, $cliente_modifico);
	echo $usuarios;
}else if($accion=="cmb_giros"){	
	$usuarios=$Consultas->cmb_giros();
	echo $usuarios;
}else if($accion=="cmb_tipos"){	
	$usuarios=$Consultas->cmb_tipos();
	echo $usuarios;
}else if($accion=="cmb_vendedores"){	
	$usuarios=$Consultas->cmb_vendedores();
	echo $usuarios;
}




?>