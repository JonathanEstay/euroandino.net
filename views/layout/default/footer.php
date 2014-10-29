

            <footer role="contentinfo">
                <div class="clearfix">
                    <ul class="list-unstyled list-inline pull-left">
                        <li><?php echo APP_COMPANY; ?> &copy; <?php echo date('Y'); ?></li>
                    </ul>
                    <ul class="list-unstyled list-inline pull-right">
                        <li>
                            <div>Iconos, <a href="http://www.flaticon.com" target="_blank" title="Flaticon">www.flaticon.com</a></div>
                        </li>
                    </ul>
                </div>
            </footer>

        </div> <!-- page-container -->


        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>funciones.js'></script>
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>validarut.js'></script>
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>jquery-1.10.2.min.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>jqueryui-1.10.3.min.js'></script> 
        
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>jquery.poshytip.js'></script> <!-- TOOLTIP -->
        
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>bootstrap.min.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>enquire.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>jquery.cookie.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>jquery.nicescroll.min.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>prettify.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>jquery.easypiechart.min.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>jquery.sparklines.min.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>toggle.min.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>jquery.dataTables.min.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>dataTables.bootstrap.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>demo-datatables.js'></script> 
        
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>fileinput.min.js'></script> 
        
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>jquery.knob.min.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>skylo.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>demo-slider.js'></script>
        
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>placeholdr.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>application.js'></script> 
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>demo.js'></script>
        
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>jquery.stepy.js'></script>
        <script type='text/javascript' src='<?php echo $_layoutParams['ruta_js']; ?>demo-formwizard.js'></script> 
        
        <?php if($this->procesoTerminado){ sleep(1); ?>
        <script>
            if($(':contains("{fechaSalida}")').length >= 18)
            {
                $("#accordion").html('<div id="divAlertWarHot" class="alert alert-dismissable alert-warning"><strong>Atenci&oacute;n!</strong><br>Ha ocurrido un error en el armado de programas. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo BASE_URL ?>system/programas" class="btn btn-primary">Volver a cargar datos</a></div>');
            }
        </script>
        <?php } ?>
    </body>
</html>