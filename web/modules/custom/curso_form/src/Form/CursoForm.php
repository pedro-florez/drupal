<?php

namespace Drupal\curso_form\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CursoForm extends FormBase {

    /**
     * @var EntityTypeManagerInterface
     */
    private $entityTypeManager;

    public function __construct( EntityTypeManagerInterface $entityTypeManager ) {

        $this->entityTypeManager = $entityTypeManager;
    }

    public static function create( ContainerInterface $container ) {

        return new static(
            $container->get('entity_type.manager')
        );
    }

    /**
     * Returns a unique string identifying the form.
     *
     * The returned ID should be a unique string that can be a valid PHP function
     * name, since it's used in hook implementation names such as
     * hook_form_FORM_ID_alter().
     *
     * @return string
     *   The unique string identifying the form.
     */
    public function getFormId() {

        return 'curso_form_curso_form';
    }

    /**
     * Form constructor.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     *
     * @return array
     *   The form structure.
     */
    public function buildForm( array $form, FormStateInterface $form_state ) {

        # Obtener un nodo (Entidad) pasandole el ID del nodo
        $node = $this->entityTypeManager->getStorage('node')->load(2);

        $form['titulo'] = [
            '#type' => 'textfield',
            '#title' => 'Titulo',
        ];

        $form['active_autor'] = [
            '#type' => 'checkbox',
            '#title' => 'Agregar Autor'
        ];

        $form['autor'] = [
            '#type' => 'textfield',
            '#title' => 'Autor',
            /**
             * Estado de los Campos
             * Mostrar el campo si se da clic en el check "active_autor"
             */
            '#states' => [
                'visible' => [
                    ':input[name="active_autor"]' => [
                        'checked' => true
                    ]
                ]
            ]
        ];

        $form['descripcion'] = [
            '#type' => 'textfield',
            '#title' => 'Descripcion',
            # Mostar Campo dependiendo la situacion
            '#access' => /* true | false */ $this->currentUser()->isAuthenticated()
        ];

        $form['etiqueta'] = [
            '#type' => 'entity_autocomplete',
            '#target_type' => 'taxonomy_term' /* 'node' */,
            '#title' => 'Buscar Etiqueta',
            '#tags' => TRUE,
            //'#default_value' => [$node], // Valor por defaul tambien admite array [$node, $node2]
            '#selection_settings' => [
                'target_bundles' => [ 'tags' /* 'article', 'page' */]
            ],
            '#autocreate' => [ // Crear entidad en el caso de que no Exista Ej: Tags
                'bundle' => 'tags'
            ],
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => 'Enviar',
        ];

        return $form;
    }

    /**
     * Form validation handler.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function validateForm( array &$form, FormStateInterface $form_state ) { }

    /**
     * Form submission handler.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function submitForm( array &$form, FormStateInterface $form_state ) {

        //dpm( $form_state->getValue('etiqueta'), 'etiqueta');

        # Guardar entidad Si no Existe Ej: Tags
        $lista_tags = $form_state->getValue('etiqueta');

        foreach ( $lista_tags as $tag ) {

            # Validar si existe en las array Tags
            if ( array_key_exists('entity', $tag) ) {

                # Guardar Tag
                $tag['entity']->save();
            }
        }
    }

}
