<?php

namespace Drupal\curso_module\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Psr\Container\ContainerInterface;

use Drupal\curso_module\Services\RepetirPalabraService;

//* La Informacion de Coloca en Anotaciones
/**
 *
 * @Block(
 *  id = "curso_module_block",
 *  admin_label = @Translation("Bloque personalizado"),
 *  category = @Translation("Curso")
 * )
 *
 * Class CursoBlock
 * @package Drupal\curso_module\Plugin\Block
 */
class CursoBlock extends BlockBase implements ContainerFactoryPluginInterface {

    /**
     * @var RepetirPalabraService
     */
    private $repetir;

    /**
     ** Inyectar Servicios en los Bloques
     ** hay que implementar la interface \ContainerFactoryPluginInterface
     ** y los siguientes parametros: 
     * @param array $configuration
     ** A configuration array containing information about the plugin instance.
     * @param string $plugin_id
     ** The plugin ID for the plugin instance.
     * @param mixed $plugin_definition
     ** The plugin implementation definition.
     */
    public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        RepetirPalabraService $repetir
    ) {

        parent::__construct(
            $configuration,
            $plugin_id,
            $plugin_definition
        );

        $this->repetir = $repetir;
    }

    /**
     ** Instanciar la clase de los servicios
     */
    public static function create(
        ContainerInterface $container,
        array $configuration,
        $plugin_id,
        $plugin_definition
    ) {

        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('curso_module.repetir_palabras')
        );
    }

    /**
     * Builds and returns the renderable array for this block plugin.
     *
     * If a block should not be rendered because it has no content, then this
     * method must also ensure to return no content: it must then only return an
     * empty array, or an empty array with #cache set (with cacheability metadata
     * indicating the circumstances for it being empty).
     *
     * @return array
     *   A renderable array representing the content of the block.
     *
     * @see \Drupal\block\BlockViewBuilder
     */
    public function build() {

        # LLamando al Servicio \RepetirPalabraService
        $result = $this->repetir->repetir( 'curso ', 2 );

        // Devolver el Themplate
        return [
            '#theme'       => 'curso_plantilla',
            '#etiqueta'    => $this->configuration['etiqueta'] ?? '',
            '#tipo'        => $this->configuration['tipo'] ?? $result,
            '#autor'       => $this->configuration['autor'] ?? '',
            '#descripcion' => $this->configuration['descripcion'] ?? ''
        ];
    }

    /**
    ** Devuelve la configuracion por default
    */
    public function defaultConfiguration() {

        return [
            'etiqueta' => 'Etiqueta por default',
            'tipo'     => 'Tipo por default',
            'autor'    => 'Pedro Florez',
            'descripcion' => 'Descripcion por default.'
        ];
    }

    /**
    ** Crear Formulario en los Bloques
    */
    public function blockForm( $form, FormStateInterface $form_state ) {

        $form['etiqueta'] = [
            '#type'          => 'textfield',
            '#title'         => 'Etiqueta',
            '#default_value' => $this->configuration['etiqueta'] ?? ''
        ];

        $form['tipo'] = [
            '#type' => 'textfield',
            '#title' => 'Tipo',
            '#default_value' => $this->configuration['tipo'] ?? ''
        ];

        $form['autor'] = [
            '#type'          => 'textfield',
            '#title'         => 'Autor',
            '#default_value' => $this->configuration['autor'] ?? ''
        ];

        $form['descripcion'] = [
            '#type' => 'textfield',
            '#title' => 'Descripción',
            '#default_value' => $this->configuration['descripcion'] ?? ''
        ];

        return $form;
    }

    /**
    ** Validar Formulario
    */
    public function blockValidate( $form, FormStateInterface $form_state ) {

        parent::blockValidate( $form, $form_state );
    }

    /**
    ** Enviar Formulario
    */
    public function blockSubmit( $form, FormStateInterface $form_state ) {

        parent::blockSubmit( $form, $form_state );

        /**
         ** Almacenar los valor por defaul en un array $this->configuration
         */
        $this->configuration['etiqueta']    = $form_state->getValue('etiqueta');
        $this->configuration['tipo']        = $form_state->getValue('tipo');
        $this->configuration['autor']       = $form_state->getValue('autor');
        $this->configuration['descripcion'] = $form_state->getValue('descripcion');
    }

    /**
    ** Permisos Para Visulizar el Bloque
    */
    public function blockAccess( AccountInterface $account ) {

        /**
         * Opciones para Validar si tiene Permisos
         */

        # Opcion 1
        return AccessResult::allowedIfHasPermission( $account, 'curso permiso limitado' );

        # Opcion 2
        /* if ( $account->hasPermission('curso permiso limitado') ) {
            return true;
        }

        return false; */

        # Opcion 3
        /* if ( $account->hasPermission('curso permiso limitado') ) {
            return AccessResult::allowed();
        }

        return AccessResult::forbidden('Aun no tiene acceso a esta seccion.'); */
    }
}