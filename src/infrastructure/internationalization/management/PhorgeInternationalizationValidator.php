<?php

final class PhorgeInternationalizationValidator extends Phobject {
  private function validateTranslation($locale, $proto, $transl, $types, $n) {
    $errors = array();
    if ($n >= count($types)) {
        if (is_array($transl)) {
          $errors[] = pht(
            'The locale `%s` defines a translation for the key `%s`, '.
            'which has at least %s level(s) of arrays, '.
            'however the source message has only %s parameter(s).',
            $locale,
            $proto,
            new PhutilNumber($n + 1),
            phutil_count($types));
        }
        $data = array();
        foreach ($types as $type) {
          if ($type === 'phutilnumber') {
            // Make a class that can be converted into a string
            // (to mimic the conversion pht() will do)
            // but not to a number (the double-conversion loses data)
            // See T16454
            $data[] = new PhorgeStringablePlaceholder();
          } else if ($type === 'number') {
            // no good way to check numbers being converted to strings
            // without parsing the format specifier ourself
            // (remember xsprintf can't work with translated strings since
            // they can use backreferences, format specifiers, etc)
            $data[] = 3;
          } else if ($type === null) {
            // This could either be a string or a number, let PHP type
            // conversions handle it
            $data[] = 'abc';
          } else {
            throw new Exception(pht('Bogus type "%s" for "%s"', $type, $proto));
          }
        }
        try {
          $parsed = vsprintf($transl, $data);
        } catch (ValueError $ex) {
          // In PHP 8 vsprintf throws a ValueError for bad data;
          // in PHP7 it returns false
          $parsed = false;
        } catch (RuntimeException $ex) {
          // The types of the args don't match (the RuntimeException comes
          // from PhutilErrorHandler.php throwing what was originally a PHP
          // warning)
          $msg = $ex->getMessage();
          if ($msg === 'Object of class PhorgeStringablePlaceholder '.
          'could not be converted to int') {
            $errors[] = pht(
              'The locale `%s` defines a translation for the key `%s` which '.
              'uses %%d to represent a PhutilNumber. This loses data if the  '.
              'number ends up being formatted with thousands specifiers. '.
              'See T16454',
              $locale,
              $proto);
            return $errors;
          }
          // This shouldn't happen, but if something else goes wrong fall
          // through to the generic `failed to interpolate properly` error
          $parsed = false;
        }
        if ($parsed === false) {
          $errors[] = pht(
            'The locale `%s` defines a translation for the key `%s` which '.
            'failed to interpolate properly. Probably it defines too many '.
            'parameters.',
            $locale,
            $proto);
        }
        return $errors;
    }
    if (!is_array($transl)) {
      $transl = array($transl);
    }
    $type = $types[$n];
    if ($type === null && count($transl) != 1) {
      $errors[] = pht(
        'The locale `%s` defines a translation for the key `%s`, which varies '.
        'on the plurality or gender of parameter %d, however that parameter '.
        'is not a number or person.',
        $locale,
        $proto,
        $n + 1);
    }
    foreach ($transl as $subarray) {
      $errors = array_merge(
       $errors,
       $this->validateTranslation($locale, $proto, $subarray, $types, $n + 1));
    }
    return $errors;
  }
  public function validateLibraries($loaded_json) {
    $errors = array();
    $all_translations = PhutilTranslation::getAllTranslations();
    $locales = PhutilLocale::loadAllLocales();
    $keyed_translations = array();
    $override_key = 'translation.override';
    try {
      $trans_override = PhabricatorEnv::getEnvConfig($override_key);
      $all_translations[$override_key] = $trans_override;
    } catch (Throwable $ex) {
      // If Phorge config is hosed then just don't check translation.override
    }
    foreach ($all_translations as $locale_code => $translations) {
      if (!isset($locales[$locale_code]) && $locale_code != $override_key) {
        $errors[] = pht(
          'Translations are defined for the locale `%s`, '.
          'which is not recognized.',
          $locale_code);
      }
      foreach ($translations as $proto => $transl) {
        if (!isset($keyed_translations[$proto])) {
          $keyed_translations[$proto] = array();
        }
        $keyed_translations[$proto][$locale_code] = $transl;
        // Check for unused translations
        if (!isset($loaded_json[$proto])) {
          $errors[] = pht(
            'The locale `%s` defines a translation for the string "%s", '.
            'however that string does not appear to be referenced '.
            'by the codebase.',
            $locale_code,
            $proto);
        }
      }
    }
    foreach ($loaded_json as $string => $spec) {
      // Check for wrong branches by parameter type
      $translations = idx($keyed_translations, $string, array());
      foreach ($translations as $locale => $translation) {
        $errors = array_merge($errors, $this->validateTranslation(
          $locale,
          $string,
          $translation,
          $spec['types'],
          0));
      }
      // Run it on the proto-English as a translation too
      // since this also does some parameter type checking
      // (some of which may belong better in a linter than here)
      $errors = array_merge($errors, $this->validateTranslation(
        id(new PhutilRawEnglishLocale())->getLocaleCode(),
        $string,
        $string,
        $spec['types'],
        0));
      // Check for missing branches in US english
      if (str_contains($string, '(s)')) {
        if (!isset($keyed_translations[$string]['en_US'])) {
          foreach ($spec['types'] as $type) {
            if ($type === 'number') {
              $errors[] = pht(
                'The string "%s" contains the placeholder "(s)" and  '.
                'a numeric parameter on which to vary the (s) by, '.
                'however the builtin US English translation does not do so.',
                $string);
            }
          }
        }
      }
    }
    return $errors;
  }
  public function loadExtractions($run_extractor, $quiet = false) {
    $libraries = PhutilBootloader::getInstance()->getAllLibraries();
    $phorge_root = phutil_get_library_root('phorge');
    $i18n_bin = Filesystem::resolvePath('../bin/i18n', $phorge_root);
    $all_json = array();
    foreach ($libraries as $lib) {
      $root = phutil_get_library_root($lib);
      $json = Filesystem::resolvePath('.cache/i18n_strings.json', $root);
      if ($run_extractor) {
        // The command needs to be stated twice to avoid the linter complaining
        // about the arg not being a scalar string
        if ($quiet) {
          execx(
            '%R extract %s',
            $i18n_bin,
            $root);
        } else {
          $err = phutil_passthru(
            '%R extract %s',
            $i18n_bin,
            $root);
          if ($err) {
            throw new Exception(pht(
              'Failed to run i18n extractor: %s',
              $err));
          }
        }
      } else if (!Filesystem::pathExists($json)) {
        throw new Exception(pht(
          'Strings have not yet been extracted for library %s. '.
          'Run `%s` for that library first to extract them or '.
          're-run with `%s` to automatically extract missing strngs.',
          $lib,
          'bin/i18n extract',
          '--extract'));
      }
      $all_json += phutil_json_decode(Filesystem::readFile($json));
    }
    // Add extra date elements from PhutilTranslator::translateDate
    // which aren't in a static pht() call
    $date = array('types' => array());
    $all_json['Jan'] = $date;
    $all_json['Feb'] = $date;
    $all_json['Mar'] = $date;
    $all_json['Apr'] = $date;
    $all_json['Jun'] = $date;
    $all_json['Jul'] = $date;
    $all_json['Aug'] = $date;
    $all_json['Sep'] = $date;
    $all_json['Oct'] = $date;
    $all_json['Nov'] = $date;
    $all_json['Dec'] = $date;
    return $all_json;
  }
}
