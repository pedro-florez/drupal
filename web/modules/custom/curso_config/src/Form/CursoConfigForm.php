<?php

namespace Drupal\curso_config\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Psr\Container\ContainerInterface;

use Drupal\curso_module\Services\RepetirPalabraService;

class CursoConfigForm extends ConfigFormBase {

    /**
     * @var RepetirPalabraService
     */
    private $repetir;

    /**
     ** Inyectar Servicio al ConfigForm
     ** Ya que el (ConfigFormBase) tiene inyeccion de servicios
     * Constructs a \Drupal\system\ConfigFormBase object.
     *
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     *   The factory for configuration objects.
     */
    public function __construct( 
        ConfigFactoryInterface $config_factory, 
        RepetirPalabraService $repetir
    ) {

        # LLamar el constructor Padre de ( ConfigFormBase )
        parent::__construct( $config_factory );

        $this->repetir = $repetir;
    }

    /**
     * {@inheritdoc}
     */
    public static function create( ContainerInterface $container ) {

        return new static(
            $container->get('config.factory'),
            $container->get('curso_module.repetir_palabras')
        );
    }

    protected function getEditableConfigNames() {

        return ['curso_config.custom_config'];
    }

    public function getFormId() {

        return 'curso_config_custom_form';
    }

    public function buildForm( array $form, FormStateInterface $form_state ) {

        # Obtener Datos de la configuracion del sistema Drupal
        $config = $this->config('curso_config.custom_config');

        # Obteniendo el resultado del Servicio Personalizado (RepetirPalabraService)
        $resultado = $this->repetir->repetir( 'Drupal ', 5 );

        $form['name'] = [
            '#type'          => 'textfield',
            '#title'         => 'Nombre',
            '#required'      => TRUE,
            '#default_value' => $config->get('name')
        ];

        $form['label'] = [
            '#type'          => 'textfield',
            '#title'         => 'Etiqueta',
            '#required'      => TRUE,
            '#default_value' => $config->get('label')
        ];

        $form['palabras'] = [
            '#type'          => 'textfield',
            '#title'         => 'Palabras',
            '#required'      => TRUE,
            '#default_value' => $resultado
        ];

        $form['actions']['submit'] = [
            '#type'  => 'submit',
            '#value' => 'Guardar Configuración'
        ];

        # Crea el Boton Submit por Default
        # return parent::buildForm( $form, $form_state );

        return $form;
    }

    public function submitForm( array &$form, FormStateInterface $form_state ) {

        parent::submitForm( $form, $form_state );

        $config = $this->config('curso_config.custom_config');

        # Setear los datos
        $config->set( 'name', $form_state->getValue('name') );
        $config->set( 'label', $form_state->getValue('label') );

        # Guardar
        $config->save();
    }
}