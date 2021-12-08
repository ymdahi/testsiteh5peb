<?php

namespace Drupal\h5peb;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\h5p\H5PDrupal\H5PDrupal;

/**
 * Class to handle the  Entity Browser Table Fields for H5P entities.
 */
class EntityBrowserTableFields {

  /**
   * Alters the fields in the Entity Browser Table.
   *
   *  The below fields are only used to create a correct header in the
   * "Entity Browser (Table)" part of the collection add/edit form.
   *
   * @param array $fields
   *   The fields.
   * @param array $context
   *   The context.
   */
  public function alterEntityBrowserTableFields(array &$fields, array $context): void {
    if ($context['entity_type'] === 'node') {
      $fields['title'] = [
        'type' => 'field',
        'label' => t('H5P title'),
        'weight' => 0,
      ];
      $fields['license'] = [
        'type' => 'field',
        'label' => t('License'),
        'weight' => 0,
      ];
      $fields['library'] = [
        'type' => 'field',
        'label' => t('Library'),
        'weight' => 0,
      ];
    }
  }

  /**
   * Turns a string into a render array for H5P entities.
   *
   * @param string $string
   *   The string to turn into a render array for H5P entities.
   *
   * @return array
   *   The render array for H5P entities.
   */
  public function stringToH5pRenderArray(string $string): array {
    $string = str_replace('H5P.', '', $string);
    $renderArray = [];

    $renderArray['#theme'] = 'field';
    $renderArray['#title'] = new TranslatableMarkup($string);
    $renderArray['#label_display'] = 'hidden';
    $renderArray['#view_mode'] = '_custom';
    $renderArray['#language'] = 'und';
    $renderArray['#field_name'] = $string;
    $renderArray['#field_type'] = "string";
    $renderArray['#field_translatable'] = FALSE;
    $renderArray['#entity_type'] = 'h5p_content';
    $renderArray['#bundle'] = 'h5p_content';
    $renderArray['#object'] = NULL;
    $renderArray['#formatter'] = "string";
    $renderArray['#is_multiple'] = FALSE;
    $renderArray[0] = [
      '#type' => 'inline_template',
      '#template' => '{{ value|nl2br }}',
      '#context' => [
        'value' => $string,
      ],
    ];

    return $renderArray;
  }

  /**
   * Returns the full license name given the short, abbreviated license name.
   *
   * @param string $shortLicenseString
   *   The short, abbreviated license name.
   *
   * @return string
   *   The full license name
   */
  public function getFullLicenseString(string $shortLicenseString): string {
    $h5p_integration = H5PDrupal::getGenericH5PIntegrationSettings();
    $licenseArrayKey = 'license' . str_replace(['-', ' '], '',
        $shortLicenseString);
    $licenseFullName = $h5p_integration['l10n']['H5P'][$licenseArrayKey] ?? '';

    return $licenseFullName . ' (' . $shortLicenseString . ')';
  }

}
