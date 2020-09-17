<?php

session_start();

require '../models/reportesModel.php';
$reportes = new reportesM();
date_default_timezone_set("America/Lima");
$date = date('Y-m-d H:i:s');
$userId = $_SESSION['usu1'];
$region = $_SESSION['Region1'];
$agencias = $_SESSION['Agencias1'];
$rol = $_SESSION['workgroup1'];

switch ($_GET["action"]) {
    //***************************FILTROS PARA INICAR LAS BÚSQUEDAS
    case 'selectAll':
        $txtCampania = isset($_POST["txtCampania"]) ? LimpiarCadena($_POST["txtCampania"]) : "";
        $txtFechaInicio = isset($_POST["txtFechaInicio"]) ? LimpiarCadena($_POST["txtFechaInicio"]) : "";
        ejecutarConsulta("CREATE TEMPORARY TABLE bancopichinchaincrementos.TMP AS (
                            SELECT C.CAMPAIGN, C.GOALS, G.CODIGO_CAMPANIA, substr(G.TMSTMP,1,10) AS TMSTMP, 
                            G.ResultLevel1, G.ResultLevel2, AGENT AS AGENT
                            FROM bancopichinchaincrementos.GESTIONFINAL G, CCK.GOALSBYCAMPAIGN C
                            WHERE C.CampaignCode = G.CODIGO_CAMPANIA and CODIGO_CAMPANIA = '$txtCampania'
                            AND G.TmStmp LIKE '%$txtFechaInicio%' );");
        
        ejecutarConsulta("INSERT INTO bancopichinchaincrementos.TMP
                            SELECT C.CAMPAIGN, C.GOALS, G.CODIGO_CAMPANIA, substr(G.TMSTMP,1,10) AS TMSTMP, 
                            G.ResultLevel1, G.ResultLevel2, AGENT AS AGENT
                            FROM bancopichinchaMO.GESTIONFINAL G, CCK.GOALSBYCAMPAIGN C
                            WHERE C.CampaignCode = G.CODIGO_CAMPANIA and CODIGO_CAMPANIA = '$txtCampania'
                            AND G.TmStmp LIKE '%$txtFechaInicio%';");
        
        ejecutarConsulta("INSERT INTO bancopichinchaincrementos.TMP
                            SELECT C.CAMPAIGN, C.GOALS, G.CODIGO_CAMPANIA, substr(G.TMSTMP,1,10) AS TMSTMP, 
                            G.ResultLevel1, G.ResultLevel2, AGENT AS AGENT
                            FROM bancopichinchaencuesta.GESTIONFINAL G, CCK.GOALSBYCAMPAIGN C
                            WHERE C.CampaignCode = G.CODIGO_CAMPANIA and CODIGO_CAMPANIA = '$txtCampania'
                            AND G.TmStmp LIKE '%$txtFechaInicio%';");
        
        $sql = "SELECT AGENT, B.CAMPAIGN, B.GOALS,
                IFNULL((SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA AND Agent = B.AGENT 
                                AND ResultLevel1 LIKE '%CU1 A%' AND TmStmp = B.TMSTMP GROUP BY AGENT),0) AS EXITOSOS,
                IFNULL((SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA AND Agent = B.AGENT 
                                AND ResultLevel1 LIKE '%%' AND TmStmp = B.TMSTMP GROUP BY AGENT),0) AS GESTIONADOS,
                IFNULL((SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA AND Agent = B.AGENT 
                                AND ResultLevel1 LIKE '%CU%' AND TmStmp = B.TMSTMP GROUP BY AGENT),0) AS CONTACTADOS,
                ROUND(IFNULL(((SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA AND Agent = B.AGENT 
                                AND ResultLevel1 LIKE '%CU%' AND TmStmp = B.TMSTMP GROUP BY AGENT)*100/
                                (SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA AND Agent = B.AGENT 
                                AND ResultLevel1 LIKE '%%' AND TmStmp = B.TMSTMP GROUP BY AGENT)),0),2) AS CONTACTABILIDAD,
                ROUND(IFNULL(((SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA AND Agent = B.AGENT 
                                AND ResultLevel1 LIKE '%CU1 A%' AND TmStmp = B.TMSTMP GROUP BY AGENT)*100/
                                (SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA AND Agent = B.AGENT 
                                AND ResultLevel1 LIKE '%%' AND TmStmp = B.TMSTMP GROUP BY AGENT)),0),2) AS EFECTIVIDAD
                FROM bancopichinchaincrementos.TMP B
                GROUP BY AGENT
                UNION ALL
                SELECT 'TOTAL' AS AGENT, B.CAMPAIGN, B.GOALS,
                IFNULL((SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA 
                                AND ResultLevel1 LIKE '%CU1 A%' AND TmStmp = B.TMSTMP),0) AS EXITOSOS,
                IFNULL((SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA
                                AND ResultLevel1 LIKE '%%' AND TmStmp = B.TMSTMP),0) AS GESTIONADOS,
                IFNULL((SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA
                                AND ResultLevel1 LIKE '%CU%' AND TmStmp = B.TMSTMP),0) AS CONTACTADOS,
                ROUND(IFNULL(((SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA
                                AND ResultLevel1 LIKE '%CU%' AND TmStmp = B.TMSTMP)*100/
                                (SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA
                                AND ResultLevel1 LIKE '%%' AND TmStmp = B.TMSTMP)),0),2) AS CONTACTABILIDAD,
                ROUND(IFNULL(((SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA
                                AND ResultLevel1 LIKE '%CU1 A%' AND TmStmp = B.TMSTMP)*100/
                                (SELECT COUNT(RESULTLEVEL1) FROM bancopichinchaincrementos.TMP
                                WHERE CODIGO_CAMPANIA = B.CODIGO_CAMPANIA
                                AND ResultLevel1 LIKE '%%' AND TmStmp = B.TMSTMP)),0),2) AS EFECTIVIDAD
                FROM bancopichinchaincrementos.TMP B
                GROUP BY Campaign, GOALS;";
        
        $respuesta = ejecutarConsulta($sql);
        
        $datos = Array(); /* cre;a un aray para guardar los resultados */
        while ($row = mysqli_fetch_array($respuesta, MYSQLI_BOTH)) {
            $datos[] = array(/* llena los resultados con los datos */
                "0" => $row["AGENT"],
                "1" => $row["CAMPAIGN"],
                "2" => $row["GOALS"],
                "3" => $row["EXITOSOS"],
                "4" => $row["GESTIONADOS"],
                "5" => $row["CONTACTADOS"],
                "6" => $row["CONTACTABILIDAD"],
                "7" => $row["EFECTIVIDAD"],
            );
        }
        $resultados = array(
            "sEcho" => 1, /* informacion para la herramienta datatables */
            "iTotalRecords" => count($datos), /* envía el total de columnas a visualizar */
            "iTotalDisplayRecords" => count($datos), /* envia el total de filas a visualizar */
            "aaData" => $datos /* envía el arreglo completo que se llenó con el while */
        );
        echo json_encode($resultados);
        
        ejecutarConsulta("DROP TABLE BANCOPICHINCHAINCREMENTOS.TMP");
        
        break;
        
        case 'selectAllLogueos':
        $txtAsesor = isset($_POST["txtAsesor"]) ? LimpiarCadena($_POST["txtAsesor"]) : "";
        $txtFechaInicio = isset($_POST["txtFechaInicio"]) ? LimpiarCadena($_POST["txtFechaInicio"]) : "";
        $txtFechaFin = isset($_POST["txtFechaFin"]) ? LimpiarCadena($_POST["txtFechaFin"]) : "";
        ejecutarConsulta("CREATE TEMPORARY TABLE CCK.TMP AS (
                                SELECT Actor,
                                (select min(StartDate) FROM cck.actorstatedetail b where b.Actor = a.Actor
                                and substr(b.startdate,1,10) = substr(a.startdate,1,10) limit 1) as 'FECHA_INICIO_LOGUEO',
                                (select max(StartDate) FROM cck.actorstatedetail b where b.Actor = a.Actor
                                and substr(b.startdate,1,10) = substr(a.startdate,1,10) limit 1) as 'FECHA_FIN_LOGUEO'
                                FROM cck.actorstatedetail a
                                where actor like '%$txtAsesor%' and (state = 'login' OR state = 'logOUT')
                                and TmStmp between '$txtFechaInicio 00:00:00' and  '$txtFechaFin 23:59:59'
                                GROUP BY actor, (select min(StartDate) FROM cck.actorstatedetail b where b.Actor = a.Actor
                                and substr(b.startdate,1,10) = substr(a.startdate,1,10) limit 1),
                                (select max(StartDate) FROM cck.actorstatedetail b where b.Actor = a.Actor
                                and substr(b.startdate,1,10) = substr(a.startdate,1,10) limit 1)
                        );");
        
        $sql = "SELECT *, timediff(FECHA_FIN_LOGUEO,FECHA_INICIO_LOGUEO) AS TIEMPO FROM CCK.TMP "
                . "WHERE Actor IN (SELECT ID FROM CCK.USER WHERE STATE = 1 AND (USERGROUP = 3 OR USERGROUP = 4) and ID <> 'mviera' ) ";
        
        $respuesta = ejecutarConsulta($sql);
        
        $datos = Array(); /* cre;a un aray para guardar los resultados */
        while ($row = mysqli_fetch_array($respuesta, MYSQLI_BOTH)) {
            $datos[] = array(/* llena los resultados con los datos */
                "0" => $row["Actor"],
                "1" => $row["FECHA_INICIO_LOGUEO"],
                "2" => $row["FECHA_FIN_LOGUEO"],
                "3" => $row["TIEMPO"],
            );
        }
        $resultados = array(
            "sEcho" => 1, /* informacion para la herramienta datatables */
            "iTotalRecords" => count($datos), /* envía el total de columnas a visualizar */
            "iTotalDisplayRecords" => count($datos), /* envia el total de filas a visualizar */
            "aaData" => $datos /* envía el arreglo completo que se llenó con el while */
        );
        echo json_encode($resultados);
        
        ejecutarConsulta("DROP TABLE CCK.TMP");
        
        break;
        
    case 'selectAllConexion':
        $txtAsesor = isset($_POST["txtAsesor"]) ? LimpiarCadena($_POST["txtAsesor"]) : "";
        $txtFechaInicio = isset($_POST["txtFechaInicio"]) ? LimpiarCadena($_POST["txtFechaInicio"]) : "";
        $txtFechaFin = isset($_POST["txtFechaFin"]) ? LimpiarCadena($_POST["txtFechaFin"]) : "";
        ejecutarConsulta("CREATE TEMPORARY TABLE CCK.TMP AS (
                                SELECT Actor,
                                (select min(StartDate) FROM cck.actorstatedetail b where b.Actor = a.Actor
                                and substr(b.startdate,1,10) = substr(a.startdate,1,10) limit 1) as 'FECHA_INICIO_LOGUEO',
                                (select max(StartDate) FROM cck.actorstatedetail b where b.Actor = a.Actor
                                and substr(b.startdate,1,10) = substr(a.startdate,1,10) limit 1) as 'FECHA_FIN_LOGUEO'
                                FROM cck.actorstatedetail a
                                where actor like '%$txtAsesor%' and (state = 'login' OR state = 'logOUT')
                                and TmStmp LIKE '$txtFechaInicio%'
                                GROUP BY actor, (select min(StartDate) FROM cck.actorstatedetail b where b.Actor = a.Actor
                                and substr(b.startdate,1,10) = substr(a.startdate,1,10) limit 1),
                                (select max(StartDate) FROM cck.actorstatedetail b where b.Actor = a.Actor
                                and substr(b.startdate,1,10) = substr(a.startdate,1,10) limit 1)
                        );");
        
        $sql = "SELECT *, timediff(FECHA_FIN_LOGUEO,FECHA_INICIO_LOGUEO) AS TIEMPO FROM CCK.TMP "
                . "WHERE Actor IN (SELECT ID FROM CCK.USER WHERE STATE = 1 AND (USERGROUP = 3 OR USERGROUP = 4) and ID <> 'mviera' )"
                . "AND FECHA_INICIO_LOGUEO LIKE '%$txtFechaInicio%' ";
        
        $respuesta = ejecutarConsulta($sql);
        
        $datos = Array(); /* cre;a un aray para guardar los resultados */
        while ($row = mysqli_fetch_array($respuesta, MYSQLI_BOTH)) {
            $datos[] = array(/* llena los resultados con los datos */
                "0" => $row["Actor"],
                "1" => $row["FECHA_INICIO_LOGUEO"],
                "2" => $row["FECHA_FIN_LOGUEO"],
                "3" => $row["TIEMPO"],
            );
        }
        $resultados = array(
            "sEcho" => 1, /* informacion para la herramienta datatables */
            "iTotalRecords" => count($datos), /* envía el total de columnas a visualizar */
            "iTotalDisplayRecords" => count($datos), /* envia el total de filas a visualizar */
            "aaData" => $datos /* envía el arreglo completo que se llenó con el while */
        );
        echo json_encode($resultados);
        
        ejecutarConsulta("DROP TABLE CCK.TMP");
        
        break;
}
?>