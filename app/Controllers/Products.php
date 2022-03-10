<?php

namespace App\Controllers;

use App\Models\RolesModel;

class Roles extends BaseController
{
	protected $modelName = 'App\Models\RolesModel';

    public function __construct(){
        $this->validation   = \Config\Services::validation();
        $this->db           = db_connect();
    }

    public function index(){
        return 'hola2';
    }

    public function list(){
        $roles = $this->model>findAll();
        
        return $this->response(['roles' => $roles]);
    }

    public function show($id = null){
        $input = $this->request->getPost();

        $pagenum = $input['pagenum'];
        $pagesize = $input['pagesize'];
        $start = $pagenum * $pagesize;
        
        $orderby = "order by 1 desc";
        if (isset($input['sortdatafield'])) $orderby = "order by ".$input['sortdatafield']." ".$input['sortorder'];
        
        $query = "SELECT SQL_CALC_FOUND_ROWS p.id_producto, p.producto, p.peso, p.codigo, p.precio, p.costo, p.iva, p.id_estado_producto, p.precio_mayorista,
                p.id_usuario_carga, p.fecha_carga, p.id_proveedor, p.iva, p.etiqueta, p.descripcion, p.link ,p.cantidad_minima,p.medida,concat('<span class=\"btn btn-xs btn-primary \" onclick=\"ver(',p.id_producto,')\">Ver</span>') as ver,p.produccion,p.caducidad	
                , e.marca
                , c.categoria, c.id_categoria
                , l.id_local, l.local
                , dep.stock
                , u.nombre_usuario
                , pr.nombre_fantasia
                , pf2.foto
                , ep.estado
                , rs.id_producto_retirar, rs.cantidad_retirar
                , cd.productos, cd.precio_base, cd.porcentaje_descuento
                , pv1.video as video_corto, pv2.video as video_producto
                FROM productos p
                left join (SELECT SUM(stock) as stock,id_producto,id_local from deposito group by id_producto,id_local ) as dep on dep.id_producto = p.id_producto
                LEFT JOIN (
                        SELECT GROUP_CONCAT(cat.categoria SEPARATOR ',') AS categoria,id_producto,cat.id_categoria 
                        FROM categorias cat 
                        JOIN productos_categorias pr ON pr.id_categoria=cat.id_categoria 
                        GROUP BY pr.id_producto
                            ) c ON c.id_producto = p.id_producto
                LEFT JOIN estado_producto ep on ep.id_estado_producto = p.id_estado_producto
                LEFT JOIN locales l on l.id_local = dep.id_local
                LEFT JOIN retiro_stock rs on rs.id_producto = p.id_producto
                LEFT JOIN combos_detalles cd on cd.id_producto = p.id_producto
                join empresas e on e.id_empresa = l.id_empresa
                LEFT JOIN usuarios u on u.id_usuario = p.id_usuario_carga
                LEFT JOIN proveedores pr on p.id_proveedor = pr.id_proveedor
                LEFT JOIN productos_videos pv1 ON pv1.id_producto = p.id_producto AND pv1.tipo = 1
                LEFT JOIN productos_videos pv2 ON pv2.id_producto = p.id_producto AND pv2.tipo = 2
                LEFT JOIN ( 
                        SELECT pf.id_producto, pf.foto, pf.estado 
                        FROM productos_fotos pf WHERE pf.estado = (select min(pf3.estado) 
                                                                    from productos_fotos pf3 
                                                                    where pf3.id_producto = pf.id_producto)
                        group by pf.id_producto order by pf.estado asc
                            ) pf2 ON pf2.id_producto = p.id_producto
                $validar_rol
                group by p.id_producto 
                $orderby LIMIT $start, $pagesize";

        // filter data.
        if (isset($_REQUEST['filterscount'])){
            $filterscount = $_REQUEST['filterscount'];
            
            if ($filterscount > 0){
                $where = $validar_rol." and (";
                $tmpdatafield = "";
                $tmpfilteroperator = "";
                for ($i=0; $i < $filterscount; $i++){
                    // get the filter's column.
                    $filterdatafield = $_REQUEST["filterdatafield" . $i];
                    $filtervalue = $_REQUEST["filtervalue" . $i];
                    
                    // get the filter's condition.
                    $filtercondition = $_REQUEST["filtercondition" . $i];
                    
                    //PARA CAMPOS COMUNES EN UN INNER JOIN o RANGOS DE FECHAS
                    switch ($filterdatafield){							
                        case "fecha_venta":
                            $filtervalue = fechaMYSQLHora($_REQUEST["filtervalue" . $i]);
                            break;
    
                        case "categoria":
                            $filterdatafield = "c.categoria";
                            break;
                        case "proveedor":
                            $filterdatafield = "pr.proveedor";
                            break;
                        case "local":
                            $filterdatafield = "l.local";
                            break;
                        case "estado":
                            $filterdatafield = "ep.estado";
                            break;
                        case "nombre_usuario":
                            $filterdatafield = "u.nombre_usuario";
                            break;	
                        case "iva":
                            $filterdatafield = "p.iva";
                            break;		
                    }
                    
                    // get the filter's operator.
                    $filteroperator = $_REQUEST["filteroperator" . $i];
                    
                    if ($tmpdatafield == ""){
                        $tmpdatafield = $filterdatafield;			
                    }
                    else if ($tmpdatafield <> $filterdatafield){
                        $where .= ")AND(";
                    }
                    else if ($tmpdatafield == $filterdatafield){
                        if ($tmpfilteroperator == 0){
                            $where .= " AND ";
                        }else{
                            $where .= " OR ";	
                        }
                    }
                    
                    // build the "WHERE" clause depending on the filter's condition, value and datafield.
                    switch($filtercondition){
                        case "NOT_EMPTY":
                        case "NOT_NULL":
                            $where .= " " . $filterdatafield . " NOT LIKE '" . "" ."'";
                            break;
                        case "EMPTY":
                        case "NULL":
                            $where .= " " . $filterdatafield . " LIKE '" . "" ."'";
                            break;
                        case "CONTAINS_CASE_SENSITIVE":
                            $where .= " BINARY  " . $filterdatafield . " LIKE '%" . $filtervalue ."%'";
                            break;
                        case "CONTAINS":
                            $where .= " " . $filterdatafield . " LIKE '%" . $filtervalue ."%'";
                            break;
                        case "DOES_NOT_CONTAIN_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " NOT LIKE '%" . $filtervalue ."%'";
                            break;
                        case "DOES_NOT_CONTAIN":
                            $where .= " " . $filterdatafield . " NOT LIKE '%" . $filtervalue ."%'";
                            break;
                        case "EQUAL_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " = '" . $filtervalue ."'";
                            break;
                        case "EQUAL":
                            $where .= " " . $filterdatafield . " = '" . $filtervalue ."'";
                            break;
                        case "NOT_EQUAL_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " <> '" . $filtervalue ."'";
                            break;
                        case "NOT_EQUAL":
                            $where .= " " . $filterdatafield . " <> '" . $filtervalue ."'";
                            break;
                        case "GREATER_THAN":
                            $where .= " " . $filterdatafield . " > '" . $filtervalue ."'";
                            break;
                        case "LESS_THAN":
                            $where .= " " . $filterdatafield . " < '" . $filtervalue ."'";
                            break;
                        case "GREATER_THAN_OR_EQUAL":
                            $where .= " " . $filterdatafield . " >= '" . $filtervalue ."'";
                            break;
                        case "LESS_THAN_OR_EQUAL":
                            $where .= " " . $filterdatafield . " <= '" . $filtervalue ."'";
                            break;
                        case "STARTS_WITH_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " LIKE '" . $filtervalue ."%'";
                            break;
                        case "STARTS_WITH":
                            $where .= " " . $filterdatafield . " LIKE '" . $filtervalue ."%'";
                            break;
                        case "ENDS_WITH_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " LIKE '%" . $filtervalue ."'";
                            break;
                        case "ENDS_WITH":
                            $where .= " " . $filterdatafield . " LIKE '%" . $filtervalue ."'";
                            break;
                    }
                                    
                    if ($i == $filterscount - 1){
                        $where .= ")";
                    }
                    
                    $tmpfilteroperator = $filteroperator;
                    $tmpdatafield = $filterdatafield;			
                }
                // build the query.
                $query = "SELECT SQL_CALC_FOUND_ROWS p.id_producto, p.producto, p.peso, p.codigo, p.precio, p.costo, p.iva, p.id_estado_producto, p.precio_mayorista,p.id_usuario_carga, p.fecha_carga, p.id_proveedor, p.iva, p.etiqueta, p.descripcion, p.link ,p.cantidad_minima,p.medida,concat('<span class=\"btn btn-xs btn-primary \" onclick=\"ver(',p.id_producto,')\">Ver</span>') as ver,
                    , p.produccion,p.caducidad
                    , pr.nombre_fantasia
                    , e.marca
                    , ep.estado
                    , pf2.foto
                    , c.categoria
                    , l.id_local, l.local
                    , dep.stock
                    , u.nombre_usuario
                    , rs.id_producto_retirar, rs.cantidad_retirar
                    , cd.productos, cd.precio_base, cd.porcentaje_descuento
                    , pv1.video as video_corto, pv2.video as video_producto
                    FROM productos p
                    join (SELECT SUM(stock) as stock,id_producto,id_local from deposito group by id_producto,id_local ) as dep on dep.id_producto = p.id_producto
                    LEFT JOIN (SELECT GROUP_CONCAT(cat.categoria SEPARATOR ',') AS categoria,id_producto FROM categorias cat JOIN productos_categorias pr ON pr.id_categoria=cat.id_categoria
                    GROUP BY pr.id_producto) c ON c.id_producto = p.id_producto
                    LEFT JOIN estado_producto ep on ep.id_estado_producto = p.id_estado_producto
                    LEFT JOIN retiro_stock rs on rs.id_producto = p.id_producto
                    LEFT JOIN combos_detalles cd on cd.id_producto = p.id_producto
                    LEFT JOIN locales l on l.id_local = dep.id_local
                    join empresas e on e.id_empresa = l.id_empresa
                    LEFT JOIN usuarios u on u.id_usuario = p.id_usuario_carga
                    LEFT JOIN proveedores pr on p.id_proveedor = pr.id_proveedor
                    LEFT JOIN productos_videos pv1 ON pv1.id_producto = p.id_producto AND pv1.tipo = 1
                    LEFT JOIN productos_videos pv2 ON pv2.id_producto = p.id_producto AND pv2.tipo = 2
                    LEFT JOIN ( SELECT pf.id_producto, pf.foto, pf.estado 
                                FROM productos_fotos pf 
                                WHERE pf.estado = (select min(pf3.estado) 
                                                from productos_fotos pf3 
                                                where pf3.id_producto = pf.id_producto)
                                group by pf.id_producto 
                                order by pf.estado asc ) pf2 ON pf2.id_producto = p.id_producto 
                    ".$where." group by p.id_producto order by 1 desc LIMIT $start, $pagesize";
            }
        }
        
        $db = DataBase::conectar();
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        
        $db->setQuery("SELECT FOUND_ROWS() AS `found_rows`");
        $r = $db->loadObject();
        $total_registros = $r->found_rows;
                        
        $salida[] = array(
            'TotalRows' => $total_registros,
            'Rows' => $rows,
            'sql' => $query
        );

        return $this->respond($salida);
    }

    public function create(){
        $input = $this->request->getPost();

        $userID = $this->model->insert($input);
        
        return $this->response(['userID' => $userID]);
    }

    public function update($id = null){
        if($id == null) return $this->fail('No se proporciono ninguna idenficacion de usuario');

        $oldData = $this->model->find($id);
        if(empty($oldData)) return $this->fail('El usuario no existe');

        $input = $this->request->getPost();

        $this->model->update($id, $input);
        
        return $this->response(['userID' => $id]);
    }

    public function delete($id = null){
        if($id == null) return $this->fail('No se proporciono ninguna idenficacion de usuario');

        $oldData = $this->model->find($id);
        if(empty($oldData)) return $this->fail('El usuario no existe');

        $this->model->delete($id);
        
        return $this->response(['userID' => $id]);
    }
}
