<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

<div class="container body">
    <div class="main_container">
        <!-- page content -->
        <div class="right_col" role="main">
            <!-- content -->
            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_content fixedHeader-locked">
                            <div class="x_content">
                                <div class="col-md-12 col-sm-12">
                                    <div class="x_panel text-dark border-dark">
                                        <div class="x_title border-dark  ">
                                            <h2>Reporte de ventas (Búsquedas)</h2>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div id="filtros" class="x_content">
                                            <div class="col-md-3 col-sm-3">
                                                <label for="txtAsesor"><b>Código Campaña</b></label>
                                                <select id="txtAsesor" name="txtAsesor" class="form-control" required>
                                                    <option value="">TODOS</option>
                                                    <?php
                                                    require '../config/connection.php';
                                                    $result = ejecutarConsulta("SELECT Id FROM cck.user where state='1' ORDER BY Id");
                                                    while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
                                                        echo '<option value="' . $row["Id"] . '">' . $row["Id"] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label for="txtFechaInicio"><b>Fecha Inicio</b></label>
                                                <fieldset class="">
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <div class="col-md-12 col-sm-12 xdisplay_inputx form-group row has-feedback">
                                                                <input type="text" class="form-control has-feedback-left" id="txtFechaInicio" name="txtFechaInicio" required>
                                                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                                                <span id="inputSuccess2Status4" class="sr-only">(success)</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <label for="txtFechaFin"><b>Fecha Fin</b></label>
                                                <fieldset class="">
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <div class="col-md-12 col-sm-12 xdisplay_inputx form-group row has-feedback">
                                                                <input type="text" class="form-control has-feedback-left" id="txtFechaFin" name="txtFechaFin" required>
                                                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                                                <span id="inputSuccess2Status4" class="sr-only">(success)</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-5 col-sm-5">
                                                <br>
                                            </div>
                                            <div class="col-md-2 col-sm-2">
                                                <br>
                                                <button id="btnBuscar" type="button" class="btn-sm btn-primary">Buscar</button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                                <!--                                <div class="col-md-4 col-sm-4 ">
                                                                    <div class="x_panel tile fixed_height_320 overflow_hidden border-dark">
                                                                        <div class="x_title border-dark">
                                                                            <h2 class="text-dark">Reporte de efectivas</h2>
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                        <div class="x_content">
                                                                            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                                <div class="col-sm-12 col-md-12">
                                    <div class="clearfix"><hr></div>
                                    <div id="listadoRegistros" class="table-responsive">
                                        <table id="tblListado" class="table table-striped" style="width:100%">
                                            <thead>
                                            <th>ASESOR</th>
                                            <th>FECHA INICIO</th>
                                            <th>FECHA FIN</th>
                                            <th>HORAS TRABAJADAS</th>
                                            </thead>
                                            <tbody>        
                                            </tbody>
                                            <tfoot>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content -->
        </div>
        <!-- /page content -->
    </div>
</div>
<?php require 'footer.php'; ?>
<script src="scripts/reporteLogueo.js" type="text/javascript"></script>
<script src="scripts/functions.js" type="text/javascript"></script>