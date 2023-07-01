<?php

namespace Drupal\curso_tabla\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TablaController extends ControllerBase {

    /**
    * @var SessionInterface
    */
    private $session;

    /**
    ** Inyectar Servicios en Controlador
    */
    public function __construct( SessionInterface $session ) {
        $this->session = $session;
    }

    /**
    ** Agregar el ID de los servicios
    */
    public static function create( ContainerInterface $container ) {

        return new static(
            $container->get('session')
        );
    }

    /**
    ** Metodo Mostrar Formulario & Tabla
    */
    public function index() {

        $build = [];

        /**
         ** Cargar Formulario Filtrar
         */
        $form_filter = $this->formBuilder()
                            ->getForm('\Drupal\curso_tabla\Form\FiltroForm');


        /**
         ** Obtener los datos de la Session del Usuario
         */
        $data_filtro = $this->session->get( 'curso_tabla_filtros', [] );

        /**
         ** Obtener los Nodos
         */
        $query = $this->entityTypeManager()
                      ->getStorage('node')
                      ->getQuery();        

        $title = $data_filtro['titulo'] ?? NULL;
        $type  = $data_filtro['tipo'] ?? NULL;

        # Filtrar Nodos
        if ( !empty($title) ) {
            $query->condition( 'title', $title, 'CONTAINS');
        }

        if ( !empty($type) && $type != 'none' ) {
            $query->condition( 'type', [$type], 'IN');
        }        

        # Ordenar los Nodos por la fecha de creacion
        $query->sort( 'created', 'desc' );

        # Paginar Nodos
        $query->pager( 10 );

        # Ejecutar la consulta
        $result = $query->execute();

        /** @var NodeInterface[] $nodes */
        $nodes = $this->entityTypeManager()
                      ->getStorage('node')
                      ->loadMultiple( $result );

        //dpm( $nodes );

        $titulo_form = [
            '#markup' => 'Filtar Nodos'
        ];

        $cabeceras = [
            'Titulo',
            'Tipo',
            'Autor',
            'Fecha registro'
        ];

        $filas = [];

        foreach ( $nodes as $node ) {

            $filas[] = [
                'data' => [
                    //$node->label(),
                    $node->toLink(),
                    $node->bundle(),
                    //$node->getOwner()->label()
                    $node->getOwner()->toLink(),
                    date( 'd/m/y H:i:s', $node->get('created')->value )
                ]
            ];
        }

        /**
         ** Crear Tabla
         */
        $tabla = [
            '#type'   => 'table',
            '#header' => $cabeceras,
            '#rows'   => $filas
        ];

        /**
         ** Agregar Pagicion
         */
        $paginacion = [
            '#type' => 'pager'
        ];

        /**
         ** Agregar Componentes Render Array
         */
        $build[] = $titulo_form;
        $build[] = $form_filter;
        $build[] = $tabla;
        $build[] = $paginacion;

        return $build;
    }
}