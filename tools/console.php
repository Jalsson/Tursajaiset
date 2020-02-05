<?php

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}

function notification($message, $type){
        echo "
    <script>$.notify('{$message}', {
      style: 'message',
      className: '{$type}'
    });</script>
    ";
}