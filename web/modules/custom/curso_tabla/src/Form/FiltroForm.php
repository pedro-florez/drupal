<?php

namespace Drupal\curso_tabla\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeTypeInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FiltroForm extends FormBase {

    /**
    * @var EntityTypeManagerInterface
    */
    private $entityTypeManager;

    /**
    * @var SessionInterface
    */
    private $session;

    /**
    ** Inyectar Servicios en Controlador
    */
    public function __construct( 
        EntityTypeManagerInterface $entityTypeManager,
        SessionInterface $session
    ) {

        $this->entityTypeManager = $entityTypeManager;
        $this->session           = $session;
    }

    /**
    ** Agregar el ID de los servicios
    */
    public static function create( ContainerInterface $container ) {

        return new static(
            $container->get('entity_type.manager'),
            $container->get('session')
        );
    }

    public function getFormId() {

        return 'curso_tabla_filter_form';
    }

    public function buildForm( array $form, FormStateInterface $form_state ) {    

        /**
         ** Obtener los datos de la Session del Usuario
         */
        $data_filtro = $this->session->get( 'curso_tabla_filtros', [] );

        $type_options = [
            'none' => '- Ninguno -'
        ];

        /**
         ** Obtener los Tipos
         * @var NodeTypeInterface[] $node_types
         */
        $node_types = $this->entityTypeManager
                           ->getStorage('node_type')
                           ->loadMultiple();

        /**
         ** Agregar las opciones al select
         */
        foreach ( $node_types as $key => $node_type ) {

            $type_options[$key] = $node_type->label();
        }

        /**
         ** Crear Formulario de Filtro
         */
        $form['titulo'] = [
            '#type'          => 'textfield',
            '#title'         => $this->t('Titulo'),
            //'#required'      => TRUE,
            '#default_value' => $data_filtro['titulo'] ?? NULL,
        ];

        $form['tipo'] = [
            '#type'    => 'select',
            '#title'   => $this->t('Seleccione tipo'),
            '#options' => $type_options,
            '#default_value' => $data_filtro['tipo'] ?? 'none',
        ];

        /**
         ** Enviar los datos 
         */
        $form['actions']['submit'] = [
            '#type'  => 'submit',
            '#value' => 'Filtrar'
        ];

        /**
         ** Crear Otro Boton de Accion Submit
         ** '#submit' => ['::resetSubmit'] llama a un metodo custom
         ** ['::resetSubmit'] Es un Array ya que pueden haber mas botones Submit
         */
        $form['actions']['reset'] = [
            '#type'   => 'submit',
            '#value'  => 'Reset',
            '#submit' => ['::resetSubmit']
        ];

        return $form;
    }

    public function validateForm( array &$form, FormStateInterface $form_state ) { }

    public function submitForm( array &$form, FormStateInterface $form_state ) {

        $filtro = [];

        $filtro['titulo'] = $form_state->getValue('titulo');
        $filtro['tipo']   = $form_state->getValue('tipo');

        /**
         ** Almacenar los datos en la Session del Usuario
         */        
        $this->session->set( 'curso_tabla_filtros', $filtro );
    }

    public function resetSubmit( array &$form, FormStateInterface $form_state ) {        

        /**
         ** Almacenar los datos vacios
         */        
        $this->session->set( 'curso_tabla_filtros', [] );
    }
}