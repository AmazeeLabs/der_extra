<?php

namespace Drupal\der_extra\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\OptionsSelectWidget;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dynamic_entity_reference\Plugin\Field\FieldType\DynamicEntityReferenceItem;
use Drupal\dynamic_entity_reference\Plugin\Field\FieldWidget\DynamicEntityReferenceOptionsTrait;

/**
 * Plugin implementation of the 'options_select' widget.
 *
 * @FieldWidget(
 *   id = "der_extra_options_select",
 *   label = @Translation("Select list (Chosen)"),
 *   field_types = {
 *     "dynamic_entity_reference",
 *   },
 *   multiple_values = TRUE
 * )
 */
class DynamicEntityReferenceOptionsSelectWidget extends OptionsSelectWidget {
  use DynamicEntityReferenceOptionsTrait;

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $element['#chosen'] = TRUE;
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function supportsGroups() {
    $settings = $this->getFieldSettings();
    $target_types = DynamicEntityReferenceItem::getTargetTypes($settings);
    // We have more than one target type anyway so we need groups.
    if (count($target_types) > 1) {
      return TRUE;
    }
    $entity_type_id = current($settings['entity_type_ids']);

    // We only support groups when there is more than 1 target_bundle
    // available.
    return !empty($settings[$entity_type_id]['handler_settings']['target_bundles']) && count($settings[$entity_type_id]['handler_settings']['target_bundles']) > 1;
  }

}
