<?php

final class PhutilSearchStemmer
  extends Phobject {

  /**
   * Perform normalization and stemming on input token
   * @param  string $token Input token
   * @return string Either stemmed token, or original input if token is too
   *   short (<3 characters) or if token contains certain punctuation elements
   */
  public function stemToken($token) {
    $token = $this->normalizeToken($token);
    return $this->applyStemmer($token);
  }

  /**
   * Perform normalization and stemming on input corpus
   * @param  string $corpus Input corpus
   * @return string Stemmed corpus
   */
  public function stemCorpus($corpus) {
    $corpus = $this->normalizeCorpus($corpus);
    $tokens = preg_split('/[^a-zA-Z0-9\x7F-\xFF._]+/', $corpus);

    $words = array();
    foreach ($tokens as $key => $token) {
      $token = trim($token, '._');

      if (strlen($token) < 3) {
        continue;
      }

      $words[$token] = $token;
    }

    $stems = array();
    foreach ($words as $word) {
      $stems[] = $this->applyStemmer($word);
    }

    return implode(' ', $stems);
  }

  /**
   * Internally convert token to lower case in a UTF8-aware way.
   * @param   string  $token Input token.
   * @return  string  Input token, in some semblance of lower case.
   */
  private function normalizeToken($token) {
    return phutil_utf8_strtolower($token);
  }

  /**
   * Internally convert corpus to lower case in a UTF8-aware way.
   * @param   string  $corpus Input corpus.
   * @return  string  Input corpus, in some semblance of lower case.
   */
  private function normalizeCorpus($corpus) {
    return phutil_utf8_strtolower($corpus);
  }

  /**
   * Internally pass normalized tokens to Porter to perform stemming. Or not.
   * @param  string $normalized_token Lower case token
   * @return string Either stemmed token, or original input if token is too
   *   short (<3 characters) or if token contains certain punctuation elements
   * @phutil-external-symbol class Porter
   */
  private function applyStemmer($normalized_token) {
    // If the token has internal punctuation, handle it literally. This
    // deals with things like domain names, Conduit API methods, and other
    // sorts of informal tokens.
    if (preg_match('/[._]/', $normalized_token)) {
      return $normalized_token;
    }

    static $loaded;

    if ($loaded === null) {
      $root = dirname(phutil_get_library_root('phabricator'));
      require_once $root.'/externals/porter-stemmer/src/Porter.php';
      $loaded = true;
    }


    $stem = Porter::stem($normalized_token);

    // If the stem is too short, it won't be a candidate for indexing. These
    // tokens are also likely to be acronyms (like "DNS") rather than real
    // English words.
    if (strlen($stem) < 3) {
      return $normalized_token;
    }

    return $stem;
  }

}
