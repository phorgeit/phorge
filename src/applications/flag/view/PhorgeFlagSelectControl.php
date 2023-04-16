<?php

final class PhorgeFlagSelectControl extends AphrontFormControl {

  protected function getCustomControlClass() {
    return 'phorge-flag-select-control';
  }

  protected function renderInput() {
    require_celerity_resource('phorge-flag-css');

    $colors = PhorgeFlagColor::getColorNameMap();

    $value_map = array_fuse($this->getValue());

    $file_map = array(
      PhorgeFlagColor::COLOR_RED => 'red',
      PhorgeFlagColor::COLOR_ORANGE => 'orange',
      PhorgeFlagColor::COLOR_YELLOW => 'yellow',
      PhorgeFlagColor::COLOR_GREEN => 'green',
      PhorgeFlagColor::COLOR_BLUE => 'blue',
      PhorgeFlagColor::COLOR_PINK => 'pink',
      PhorgeFlagColor::COLOR_PURPLE => 'purple',
      PhorgeFlagColor::COLOR_CHECKERED => 'finish',
    );

    $out = array();
    foreach ($colors as $const => $name) {
      // TODO: This should probably be a sprite sheet.
      $partial = $file_map[$const];
      $uri = '/rsrc/image/icon/fatcow/flag_'.$partial.'.png';
      $uri = celerity_get_resource_uri($uri);

      $icon = id(new PHUIIconView())
        ->setImage($uri);

      $input = phutil_tag(
        'input',
        array(
          'type' => 'checkbox',
          'name' => $this->getName().'[]',
          'value' => $const,
          'checked' => isset($value_map[$const])
            ? 'checked'
            : null,
          'class' => 'phorge-flag-select-checkbox',
        ));

      $label = phutil_tag(
        'label',
        array(
          'class' => 'phorge-flag-select-label',
        ),
        array(
          $icon,
          $input,
        ));

      $out[] = $label;
    }

    return $out;
  }

}
