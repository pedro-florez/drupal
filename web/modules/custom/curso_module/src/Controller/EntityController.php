<?php

namespace Drupal\curso_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Psr\Container\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class EntityController extends ControllerBase {

    /**
     ** Inyectar Servicios en Controladores
     */
    public function __construct( EntityTypeManagerInterface $entityTypeManager ) {
        $this->entityTypeManager = $entityTypeManager;
    }

    /**
     ** Instanciar la clase de los servicios
     */
    public static function create( ContainerInterface $container ) {

        return new static(
            $container->get('entity_type.manager')
        );
    }

    /**
     ** Cargar Entidades
    */
    public function index() {

        /**
         ** Cargar Usuario con el Id 1
         */
        $user = $this->entityTypeManager
                     ->getStorage('user')
                     ->load(1);

        //dpm( $user, 'usuario' );

        /**
         ** Cargar Todos los Usuarios
         */
        $users = $this->entityTypeManager
                     ->getStorage('user')
                     ->loadMultiple();

        //dpm( $users, 'users' );

        /**
         ** Cargar Node con el Id 1
         */
        $node = $this->entityTypeManager
                     ->getStorage('node')
                     ->load(1);

        //dpm( $node, 'node' );

        /**
         ** Cargar Multiples Nodes por Ids
         */
        $nodes = $this->entityTypeManager
                     ->getStorage('node')
                     ->loadMultiple([ 1, 2, 3 ]);

        //dpm( $nodes, 'nodes' );


        return [
            '#markup' => 'Mostrando el Controlador EntityController'
        ];
    }

    /**
    ** Consultar Entidad
    */
    public function query() {

        /**
        ** getQuery() Por default es AND
        ** Para habilitar la condicion OR 
        ** quedaria de esta manera getQuery( OR )
        */
        $query = $this->entityTypeManager
                      ->getStorage('node')
                      ->getQuery();

        /* # Agregar Condicion | Where              
        $query->condition('type', 'page', '<>');

        # Usuario_ID
        $query->condition('uid', 1);

        # Estado de la publicacion 0 | 1
        $query->condition('status', 1);

        # Validar si el campo no tiene valor
        $query->notExists('field_custom');

        # Ordenamiento
        $query->sort('title', 'desc'); */

        /**
         ** Agregar Condicion OR
         */
        $condicionOR = $query->orConditionGroup();

        $condicionOR->condition('type', 'article');
        $condicionOR->condition('uid', 1);
        
        $query->condition( $condicionOR );

        # Ejecutar la consulta
        $result = $query->execute();

        $nodes = $this->entityTypeManager
                      ->getStorage('node')
                      ->loadMultiple( $result );

        dpm( $nodes );
    
        return [
            '#markup' => 'Mostrando el metodo query'
        ];
    }

    /**
    ** Crear Entidad
    */
    public function crear() {

        /* $values = [
            'title' => 'Instalacion y reparacion de PC',
            'type'  => 'article',
        ]; */

        /**
         ** Crear (page | article )
         */
        /* $node = $this->entityTypeManager
                     ->getStorage('node')
                     ->create( $values );

        $node->save(); */

        /* $valuesUser = [
            'name'   => 'snaider',
            'mail'   => 'snaider@test.com',
            'pass'   => '123456',
            'status' => 1
        ]; */

        /**
         ** Crear (Usuario)
         */
        /* $user = $this->entityTypeManager
                     ->getStorage('user')
                     ->create( $valuesUser );

        $user->save(); */

        $valuesTags = [
            'name' => 'Spring Boot',
            'vid'  => 'tags'
        ];

        /**
         ** Crear Entidad (Terminos | Tags)
         */
        $tag = $this->entityTypeManager
                     ->getStorage('taxonomy_term')
                     ->create( $valuesTags );

        $tag->save();

        return [
            '#markup' => 'Mostrando el metodo crear'
        ];
    }

    /**
    ** Editar Entidad
    */
    public function editar() {

        /**
         ** Editar
         */
        $node = $this->entityTypeManager
                     ->getStorage('node')
                     ->load(7);

        //dpm( $node, 'node' );

        /**
         ** Obtener varios valores
         */
        //$title = $node->get('title')->getValue();

        /**
         ** Obtener un valor
         */
        $title = $node->get('title')->value;
        $body  = $node->get('body')->value;

        //dpm( $title );

        # Setear los valores
        /* $node->set(
            'title',
            'Programacion de Software'
        );

        $node->set(
            'body',
            'Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt maiores quam, id quia illum culpa eveniet in recusandae deserunt, ipsum earum dicta nulla aspernatur rem provident libero accusamus? Blanditiis, praesentium.'
        );

        $node->set(
            'field_custom',
            'Campo creado desde la interface grafica.'
        ); */

        /**
         ** Obtener Taxonomia | Tag
         */
        $tags = $this->entityTypeManager
                     ->getStorage('taxonomy_term')
                     ->loadMultiple();

        //dpm( $tags );

        /**
         ** Agregar y Relacionar (Taxonomia | Tag) con el Article
         ** Mediante el campo "field_tags"
         ** Pasarle el Id de la Tag que esta en el array $tags
         ** Solo se puede pasar una tag a la vez
         */
        /* $node->get('field_tags')->appendItem( $tags[2] );
        $node->get('field_tags')->appendItem( $tags[3] );
        $node->get('field_tags')->appendItem( $tags[4] ); */

        /**
         ** Quitar Taxonomia | Tag del Article
         */
        $node->get('field_tags')->removeItem( 0 );

        # Actualizar Valores
        $node->save();

        return [
            '#markup' => 'Mostrando el metodo editar'
        ];
    }

    /**
    ** Eliminar Entidad
    */
    public function eliminar() {

        /**
         ** Obtener Entidad
         */
        $nodo = $this->entityTypeManager
                     ->getStorage('node')
                     ->load(7);

        # Eliminar
        $nodo->delete();

        return [
            '#markup' => 'Mostrando el metodo eliminar'
        ];
    }
}