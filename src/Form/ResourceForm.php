<?php

namespace Drupal\mbgna_resource\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the resource entity edit forms.
 */
class ResourceForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New resource has been created.'));
        $this->logger('mbgna_resource')->notice('Created new resource.');
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The resource has been updated.'));
        $this->logger('mbgna_resource')->notice('Updated resource.');
        break;
    }

    $form_state->setRedirect('entity.resource.canonical', ['resource' => $entity->id()]);

    return $result;
  }

}
