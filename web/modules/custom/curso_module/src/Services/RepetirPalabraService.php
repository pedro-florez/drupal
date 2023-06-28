<?php

namespace Drupal\curso_module\Services;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class RepetirPalabraService {

    /** @var MessengerInterface */
    private $messenger;

    /** @var EntityTypeManagerInterface */
    private $entityTypeManager;

    public function __construct(
        MessengerInterface $messenger,
        EntityTypeManagerInterface $entityTypeManager 
    ) {
        $this->messenger         = $messenger;
        $this->entityTypeManager = $entityTypeManager;
    }

    public function repetir( $palabra, $cantidad = 3 ) {

        # Alert
        /* $this->messenger->addStatus('La palabra se ha repetido con exito.');
        $this->messenger->addWarning('Advertencia no se pudieron repetir algunas letras.');
        $this->messenger->addError('Error al repetir la palabra.'); */

        return str_repeat( $palabra, $cantidad );
    }
}