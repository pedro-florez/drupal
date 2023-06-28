<?php

namespace Drupal\curso_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Psr\Container\ContainerInterface;

use Drupal\curso_module\Services\RepetirPalabraService;

class CursoForm extends FormBase {

    /**
     * @var RepetirPalabraService
     */
    private $repetir;

    /**
     * @var EntityTypeManagerInterface
     */
    private $entityTypeManager;

    /**
     * Inyectar Servicios en Controladores buenas practicas
     */
    public function __construct( 
        RepetirPalabraService $repetir, 
        EntityTypeManagerInterface $entityTypeManager
    ) {
        $this->repetir           = $repetir;
        $this->entityTypeManager = $entityTypeManager;
    }    

    /**
     * Instanciar la clase de los servicios
     */
    public static function create( ContainerInterface $container ) {
        
        return new static(
            $container->get('curso_module.repetir_palabras'),
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

        # Nombre_Machine & nombre personalizado
        return 'curso_module_curso_form';
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

        # Crear Formulario
        $form['nombre'] = [
            '#type'     => 'textfield',
            '#title'    => 'Nombre',
            '#required' => TRUE
        ];

        $form['telefono'] = [
            '#type' => 'tel',
            '#title' => 'Telefono',
            '#required' => TRUE
        ];

        $form['categoria'] = [
            '#type'  => 'select',
            '#title' => 'Seleccione categoria',
            '#options' => [
                '1' => 'Programacion',
                '2' => 'Desarrollo Web',
                '3' => 'Inteligencia Artificial',
                '4' => 'Desarrollo Mobil',
            ],
        ];

        $form['genero'] = [
            '#type' => 'checkbox',
            '#title' => 'Genero'
        ];       

        $form['actions']['submit'] = [
            '#type'  => 'submit',
            '#value' => 'Guardar Curso'
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
    public function validateForm( array &$form, FormStateInterface $form_state ) {

        # Validat formulario
        if ( strlen($form_state->getValue('nombre')) <= 2 ) {
            $form_state->setErrorByName('nombre', 'El nombre ingresado no es valido.');
        }        
    }

    /**
     * Form submission handler.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        $values = $form_state->getValues();

        //dpm( $values, 'valores' );
        
        $telefono = $form_state->getValue('telefono');
        
        $this->messenger()->addStatus('Nombre: '. $values['nombre']);
        $this->messenger()->addStatus('Telefono: '. $telefono);
        $this->messenger()->addStatus('Categoria: '. $values['categoria']);

        // TODO: Preparar y Guardar los datos..
    }

}
