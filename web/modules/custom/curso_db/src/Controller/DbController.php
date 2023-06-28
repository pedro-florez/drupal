<?php

namespace Drupal\curso_db\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DbController extends ControllerBase {

    /**
     * @var Connection
     */
    private $db;

    public function __construct( Connection $database ) {
        $this->db = $database;
    }

    public static function create( ContainerInterface $container ) {

        return new static(
            $container->get('database')
        );
    }

    public function queryEstatica() {

        /**
         ** Agregar las llaves {curso_db} al nombre de la tabla para evitar
         ** problemas del Prefijo en las tablas
         ** Alias (:name) para enviar los valores
         */
        //$querySave = "INSERT INTO {curso_db} ( name, value, nid ) VALUES ( :name, :value, :nid )";
        $querySave = "UPDATE {curso_db} SET name=:name, value=:value, nid=:nid WHERE id=:id";

        # Realizar Insert a la DB
        $guardar = $this->db->query(
            $querySave,
            [
                ':name'  => 'Pedro Florez', 
                ':value' => 'Laravel',
                ':nid'   => 1,
                ':id'    => 1
            ]
        );

        //dpm( $guardar );

        /* $query = $this->db->query("SELECT * FROM {curso_db}");
        dpm( $query->fetchAll() ); */

        return ['#markup' => 'Consultas a base de datos estaticas.'];
    }

    public function selectDinamico() {

        $campos = ['name', 'value'];

        /**
         * Alia de la Tabla curso_db (c)
         */
        $query = $this->db->select( 'curso_db', 'c' )
                          #->fields( 'c' ); // Select All
                          ->fields( 'c', $campos )
                          #->condition('c.name', 'Argelia Pacheco', '=') // Where
                          ->orderBy('name', 'desc')
                          ;

        $result = $query->execute();

        //dpm( $result->fetchAll() );

        return ['#markup' => 'Select dinamico.'];
    }

    public function insertDinamico() {

        $values = [
            'name' => 'Tatiana Duque',
            'value' => 'Practicando CSS',
            'nid' => 1,
        ];

        # Insert
        $result = $this->db->insert( 'curso_db' )
                            ->fields( $values )
                            ->execute();

        return ['#markup' => 'Insert dinamico.'];
    }

    public function updateDinamico() {

        $values = [            
            'value' => 'Pruebas unitarias',
            'nid' => 1,
        ];

        # Update
        $this->db->update( 'curso_db' )
                ->fields( $values )
                ->condition( 'id', 1 )
                ->execute();


        return ['#markup' => 'Update dinamico.'];
    }

    public function deleteDinamico() {

        # Delete
        $this->db->delete('curso_db')
                ->condition('id', 4)
                ->execute();

        return ['#markup' => 'Delete dinamico.'];
    }

    /**
     ** Tipo de Consulta Especial:
     ** Si la consulta devuelve True, hace un Update
     ** Si la consulta devuelve False, hace un Insert
     */
    public function mergeDinamico() {

        $values = [
            'name' => 'Tatiana Duque',
            'value' => 'Practicando Sass',
            'nid' => 1,
        ];

        $this->db->merge('curso_db')
                    ->key('name', 'Tatiana Duque') // Condicion
                    ->fields( $values )
                    ->execute();


        return ['#markup' => 'Merge dinamico.'];
    }
}
