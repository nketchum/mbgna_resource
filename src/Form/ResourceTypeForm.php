<?php

namespace Drupal\mbgna_resource\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form handler for resource type forms.
 */
class ResourceTypeForm extends BundleEntityFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $entity_type = $this->entity;
    if ($this->operation == 'edit') {
      $form['#title'] = $this->t('Edit %label resource type', ['%label' => $entity_type->label()]);
    }

    $form['label'] = [
      '#title' => $this->t('Label'),
      '#type' => 'textfield',
      '#default_value' => $entity_type->label(),
      '#description' => $this->t('The human-readable name of this resource type.'),
      '#required' => TRUE,
      '#size' => 30,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $entity_type->id(),
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
      '#machine_name' => [
        'exists' => ['Drupal\mbgna_resource\Entity\ResourceType', 'load'],
        'source' => ['label'],
      ],
      '#description' => $this->t('A unique machine-readable name for this resource type. It must only contain lowercase letters, numbers, and underscores.'),
    ];

    return $this->protectBundleIdElement($form);
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = $this->t('Save resource type');
    $actions['delete']['#value'] = $this->t('Delete resource type');
    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity_type = $this->entity;

    $entity_type->set('id', trim($entity_type->id()));
    $entity_type->set('label', trim($entity_type->label()));

    $status = $entity_type->save();

    if ($status == SAVED_UPDATED) {
      $message = $this->t('The resource type has been updated.');
    }
    elseif ($status == SAVED_NEW) {
      $message = $this->t('The resource type shas been added.');
    }
    $this->messenger()->addStatus($message);

    $form_state->setRedirectUrl($entity_type->toUrl('collection'));
  }

}
