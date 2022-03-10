<script src="plugins/jquery/jquery.js"></script>
<script src="js/app.min.js"></script>
<script src="js/theme/material.min.js"></script>
<?php
    if(isset($script)){
        echo '<script src="'.$script.'"></script>';
    }
    
    if(isset($scripts)){
        foreach($scripts as $script){
            echo '<script src="'.$script.'"></script>';
        }
    }
?>