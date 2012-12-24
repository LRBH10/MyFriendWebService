<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Alert
 *
 * @author bibouh
 */
class Alert {

    /**
     * @param string $content
     */
    public static function information($content) {
        ?>
        <script> 
            function log (content){
                alert(content);
            }
            log('<?php echo $content;?>');
        </script>

        <?php
    }

}
?>
