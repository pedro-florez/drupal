<?php

namespace Drupal\curso_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountProxyInterface;

use Drupal\curso_module\Services\RepetirPalabraService;

class CursoController extends ControllerBase {

    /**
     * @var RepetirPalabraService
     */
    private $repetir;

    /**
     * @var AccountProxyInterface
     */
    private $accountProxy;       

    /**
     ** Inyectar Servicios en Controladores buenas practicas
     */
    public function __construct( 
        RepetirPalabraService $repetir,
        ConfigFactoryInterface $configFactory,
        AccountProxyInterface $accountProxy
    ) {
        $this->repetir       = $repetir;
        $this->configFactory = $configFactory;
        $this->accountProxy  = $accountProxy;
    }

    /**
     ** Instanciar la clase de los servicios
     */
    public static function create( ContainerInterface $container ) {
        
        return new static(
            $container->get('curso_module.repetir_palabras'),
            $container->get('config.factory'),
            $container->get('current_user')
        );
    }

    public function index() {

        /* return new Response('<h1>Respuesta desde el controlador CursoController</h1>'); */

        // Devolver Render Arrar (una vista con las propiedades  #markup | #plain_text )
        /* return [            
            '#markup' => 'El #markup del controlador'
            //'#plain_text' => 'El #plain_text del controlador'
        ]; */

        /**
         ** Inyectar Servicios en Controlador no es recomendable
         * Solo se puede Inyectar en los Hooks
         * $repetir = \Drupal::service('curso_module.repetir_palabras'); 
        */

        $resultado = $this->repetir->repetir( 'curso ', 5 );

        // Devolver Template
        return [         
            '#theme'       => 'curso_plantilla', // Nombre Template
            '#etiqueta'    => 'Productos Elegantes',
            '#tipo'        => $resultado,
            '#autor'       => 'Pedro Florez',
            '#descripcion' => 'Este es el template de productos.'
        ];
    }

    /**
     ** Crear Formulario
     */
    public function crear() {

        /**
         ** Agregar Permisos al Metodo
         ** Nombre del Permiso 'curso permiso limitado'
         */
        if ( !$this->accountProxy->hasPermission('curso permiso limitado') ) {
            
            return [
                '#markup' => '<h2>Aun no tienes permiso para acceder a esta pagina.</h2>'
            ];
        }

        // Obtener Formulario
        $form = $this->formBuilder()
                     ->getForm('\Drupal\curso_module\Form\CursoForm');

        //return $form;

        // Agregar informacion al formulario
        $builder = [];

        /**
         ** Metodo Para Traducir loo textos $this->t( $string );
         ** // TODO: Pendiente por revisar por que la version 9.1.5 
         ** // TODO: no tiene el modulo 'Interface Translation'
         */
        $builder[] = ['#markup' => "<h2>This is the page of the form</h2>"];
        $builder[] = $form;
                
        return $builder;
    }

    /**
     * @param $node Numero de la Pagina  
     */
    public function show( $node ) {
        
        // Devolver Render Arrar
        return [
            '#markup' => "La etiqueta del Nodo es: $node"
        ];
    }

    public function editar( $token ) {
        
        // Devolver Render Arrar
        return [
            '#markup' => "El producto a editar es: $token"
        ];
    }

    /**
     * Obtener la configuracion del sistema Drupal
     */
    public function configCustom() {
        
        # Nombre de la configuracion del sistema
        $configName = 'system.site';

        /**
         ** Utilizando el Servicio (Config) que trae el controlador
         * Este solo es de lectura, no permite editar
         */

        /* $config = $this->config( $configName );

        dpm( $config, 'config' );
        dpm( $config->get('name'), 'Nombre del Sitio' ); */
        
        /**
         ** Utilizando el Servicio (config.factory)
         * Este permite leer y editar
         */

        /* $configFactory = \Drupal::service('config.factory');

        $config = $configFactory->get( $configName );

        dpm( $config, 'configFactory' );
        dpm( $config->get('name'), 'Nombre del Sitio' ); */

        /**
         ** Utilizando el Servicio (config.factory) Inyectado
         */

        # Obtener
        # $config = $this->configFactory->get( $configName );

        # Editar
        $configEdit = $this->configFactory->getEditable( $configName );

        //dpm( $configEdit, 'configEdit' );

        # Setear los datos
        $configEdit->set( 'name', 'Master Drupal 9' );
        $configEdit->set( 'slogan', 'Nuevo lema editado' );

        # Guardar
        $configEdit->save();

        return [
            '#markup' => 'Configuracion del Sistema'
        ];        
    }

}