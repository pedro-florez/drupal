<?php

namespace Drupal\curso_config\Controller;

use Drupal\Core\Controller\ControllerBase;

class ConfigController extends ControllerBase {

    public function index() {

        /**
         ** Obtener Datos de la configuracion del sistema Drupal
         * Colocar el Nombre del archivo que esta 
         * en la ruta curso_config\config\install (curso_config.custom_config)
         */        
        $config = $this->config('curso_config.custom_config');

        //dpm( $config, 'Configuracion' );

        return [
            '#markup' => 'Mostrando el Controlador ConfigController'
        ];
    }
}