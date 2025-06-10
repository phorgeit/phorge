<?php

final class AphrontFormDateControl extends AphrontFormControl {

  private $initialTime;
  private $zone;

  private $valueDate;
  private $valueTime;
  private $allowNull;
  private $continueOnInvalidDate = false;
  private $isTimeDisabled;
  private $isDisabled;
  private $endDateID;

  public function setAllowNull($allow_null) {
    $this->allowNull = $allow_null;
    return $this;
  }

  public function setIsTimeDisabled($is_disabled) {
    $this->isTimeDisabled = $is_disabled;
    return $this;
  }

  public function setIsDisabled($is_datepicker_disabled) {
    $this->isDisabled = $is_datepicker_disabled;
    return $this;
  }

  public function setEndDateID($value) {
    $this->endDateID = $value;
    return $this;
  }

  const TIME_START_OF_DAY         = 'start-of-day';
  const TIME_END_OF_DAY           = 'end-of-day';
  const TIME_START_OF_BUSINESS    = 'start-of-business';
  const TIME_END_OF_BUSINESS      = 'end-of-business';

  public function setInitialTime($time) {
    $this->initialTime = $time;
    return $this;
  }

  public function readValueFromRequest(AphrontRequest $request) {
    $date = $request->getStr($this->getDateInputName());
    $time = $request->getStr($this->getTimeInputName());
    $enabled = $request->getBool($this->getCheckboxInputName());

    if ($this->allowNull && !$enabled) {
      $this->setError(null);
      $this->setValue(null);
      return;
    }

    $err = $this->getError();

    if ($date || $time) {
      $this->valueDate = $date;
      $this->valueTime = $time;

      // Assume invalid.
      $err = pht('Invalid');

      $zone = $this->getTimezone();

      try {
        $datetime = new DateTime("{$date} {$time}", $zone);
        $value = $datetime->format('U');
      } catch (Exception $ex) {
        $value = null;
      }

      if ($value) {
        $this->setValue($value);
        $err = null;
      } else {
        $this->setValue(null);
      }
    } else {
      $value = $this->getInitialValue();
      if ($value) {
        $this->setValue($value);
      } else {
        $this->setValue(null);
      }
    }

    $this->setError($err);

    return $this->getValue();
  }

  protected function getCustomControlClass() {
    return 'aphront-form-control-date';
  }

  public function setValue($epoch) {
    if ($epoch instanceof AphrontFormDateControlValue) {
      $this->continueOnInvalidDate = true;
      $this->valueDate = $epoch->getValueDate();
      $this->valueTime  = $epoch->getValueTime();
      $this->allowNull = $epoch->getOptional();
      $this->isDisabled = $epoch->isDisabled();

      return parent::setValue($epoch->getEpoch());
    }

    $result = parent::setValue($epoch);

    if ($epoch === null) {
      return $result;
    }

    $readable = $this->formatTime($epoch, 'Y!m!d!'.$this->getTimeFormat());
    $readable = explode('!', $readable, 4);

    $year  = $readable[0];
    $month = $readable[1];
    $day   = $readable[2];

    $this->valueDate = $month.'/'.$day.'/'.$year;
    $this->valueTime  = $readable[3];

    return $result;
  }

  private function getDateInputValue() {
    $date_format = $this->getDateFormat();
    $timezone = $this->getTimezone();

    try {
      $datetime = new DateTime($this->valueDate, $timezone);
    } catch (Exception $ex) {
      return $this->valueDate;
    }

    return $datetime->format($date_format);
  }

  private function getTimeFormat() {
    $viewer = $this->getViewer();
    $time_key = PhabricatorTimeFormatSetting::SETTINGKEY;
    return $viewer->getUserSetting($time_key);
  }

  private function getDateFormat() {
    $viewer = $this->getViewer();
    $date_key = PhabricatorDateFormatSetting::SETTINGKEY;
    return $viewer->getUserSetting($date_key);
  }

  private function getTimeInputValue() {
    return $this->valueTime;
  }

  private function formatTime($epoch, $fmt) {
    return vixon_format_local_time(
      $epoch,
      $this->getViewer(),
      $fmt);
  }

  private function getDateInputName() {
    return $this->getName().'_d';
  }

  private function getTimeInputName() {
    return $this->getName().'_t';
  }

  private function getCheckboxInputName() {
    return $this->getName().'_e';
  }

  protected function renderInput() {

    $disabled = null;
    if ($this->getValue() === null && !$this->continueOnInvalidDate) {
      $this->setValue($this->getInitialValue());
      if ($this->allowNull) {
        $disabled = 'disabled';
      }
    }

    if ($this->isDisabled) {
      $disabled = 'disabled';
    }

    $checkbox = null;
    if ($this->allowNull) {
      $checkbox = javelin_tag(
        'input',
        array(
          'type' => 'checkbox',
          'name' => $this->getCheckboxInputName(),
          'sigil' => 'calendar-enable',
          'class' => 'aphront-form-date-enabled-input',
          'value' => 1,
          'checked' => ($disabled === null ? 'checked' : null),
        ));
    }

    $date_sel = javelin_tag(
      'input',
      array(
        'autocomplete' => 'off',
        'name'  => $this->getDateInputName(),
        'sigil' => 'date-input',
        'value' => $this->getDateInputValue(),
        'type'  => 'text',
        'class' => 'aphront-form-date-input',
      ),
      '');

    $date_div = javelin_tag(
      'div',
      array(
        'class' => 'aphront-form-date-input-container',
      ),
      $date_sel);

    $cicon = id(new PHUIIconView())
      ->setIcon('fa-calendar');

    $cal_icon = javelin_tag(
      'a',
      array(
        'href'  => '#',
        'class' => 'calendar-button',
        'sigil' => 'calendar-button',
      ),
      $cicon);

    $values = $this->getTimeTypeaheadValues();

    $time_id = celerity_generate_unique_node_id();
    Javelin::initBehavior('time-typeahead', array(
      'startTimeID' => $time_id,
      'endTimeID' => $this->endDateID,
      'timeValues' => $values,
      'format' => $this->getTimeFormat(),
      ));

    $time_sel = javelin_tag(
      'input',
      array(
        'autocomplete' => 'off',
        'name'  => $this->getTimeInputName(),
        'sigil' => 'time-input',
        'value' => $this->getTimeInputValue(),
        'type'  => 'text',
        'class' => 'aphront-form-time-input',
      ),
      '');

    $time_div = javelin_tag(
      'div',
      array(
        'id' => $time_id,
        'class' => 'aphront-form-time-input-container',
      ),
      $time_sel);

    $viewer = $this->getViewer();
    $week_key = PhabricatorWeekStartDaySetting::SETTINGKEY;
    $week_start = $viewer->getUserSetting($week_key);

    $date_pht = array(
      'S|M|T|W|T|F|S' => pht('S|M|T|W|T|F|S'),
      'January' => pht('January'),
      'February' => pht('February'),
      'March' => pht('March'),
      'April' => pht('April'),
      'May' => pht('May'),
      'June' => pht('June'),
      'July' => pht('July'),
      'August' => pht('August'),
      'September' => pht('September'),
      'October' => pht('October'),
      'November' => pht('November'),
      'December' => pht('December'),
    );
    Javelin::initBehavior('fancy-datepicker', array(
      'format' => $this->getDateFormat(),
      'weekStart' => $week_start,
      'pht' => $date_pht,
      ));

    $classes = array();
    $classes[] = 'aphront-form-date-container';
    if ($disabled) {
      $classes[] = 'datepicker-disabled';
    }
    if ($this->isTimeDisabled) {
      $classes[] = 'no-time';
    }

    return javelin_tag(
      'div',
      array(
        'class' => implode(' ', $classes),
        'sigil' => 'phabricator-date-control',
        'meta'  => array(
          'disabled' => (bool)$disabled,
        ),
        'id' => $this->getID(),
      ),
      array(
        $checkbox,
        $date_div,
        $cal_icon,
        $time_div,
      ));
  }

  private function getTimezone() {
    if ($this->zone) {
      return $this->zone;
    }

    $viewer = $this->getViewer();

    $user_zone = $viewer->getTimezoneIdentifier();
    $this->zone = new DateTimeZone($user_zone);
    return $this->zone;
  }

  private function getInitialValue() {
    $zone = $this->getTimezone();

    // TODO: We could eventually allow these to be customized per install or
    // per user or both, but let's wait and see.
    switch ($this->initialTime) {
      case self::TIME_START_OF_DAY:
      default:
        $time = '12:00 AM';
        break;
      case self::TIME_START_OF_BUSINESS:
        $time = '9:00 AM';
        break;
      case self::TIME_END_OF_BUSINESS:
        $time = '5:00 PM';
        break;
      case self::TIME_END_OF_DAY:
        $time = '11:59 PM';
        break;
    }

    $today = $this->formatTime(time(), 'Y-m-d');
    try {
      $date = new DateTime("{$today} {$time}", $zone);
      $value = $date->format('U');
    } catch (Exception $ex) {
      $value = null;
    }

    return $value;
  }

  private function getTimeTypeaheadValues() {
    $time_format = $this->getTimeFormat();
    $times = array();

    if ($time_format == 'g:i A') {
      $am_pm_list = array('AM', 'PM');

      foreach ($am_pm_list as $am_pm) {
        for ($hour = 0; $hour < 12; $hour++) {
          $actual_hour = ($hour == 0) ? 12 : $hour;
          $times[] = $actual_hour.':00 '.$am_pm;
          $times[] = $actual_hour.':30 '.$am_pm;
        }
      }
    } else if ($time_format == 'H:i') {
      for ($hour = 0; $hour < 24; $hour++) {
        $written_hour = ($hour > 9) ? $hour : '0'.$hour;
        $times[] = $written_hour.':00';
        $times[] = $written_hour.':30';
      }
    }

    foreach ($times as $key => $time) {
      $times[$key] = array($key, $time);
    }

    return $times;
  }

}
