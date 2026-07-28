<?php

final class DivinerLiveSymbol extends DivinerDAO
  implements
    PhabricatorPolicyInterface,
    PhabricatorMarkupInterface,
    PhabricatorDestructibleInterface,
    PhabricatorFulltextInterface {

  protected $bookPHID;
  protected $repositoryPHID;
  protected $context;
  protected $type;
  protected $name;
  protected $atomIndex;
  protected $graphHash;
  protected $identityHash;
  protected $nodeHash;

  protected $title;
  protected $titleSlugHash;
  protected $groupName;
  protected $summary;
  protected $isDocumentable = 0;

  private $book = self::ATTACHABLE;
  private $repository = self::ATTACHABLE;
  private $atom = self::ATTACHABLE;
  private $extends = self::ATTACHABLE;
  private $children = self::ATTACHABLE;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_TIMESTAMPS => false,
      self::CONFIG_COLUMN_SCHEMA => array(
        'context' => 'text255?',
        'type' => 'text32',
        'name' => 'text255',
        'atomIndex' => 'uint32',
        'identityHash' => 'bytes12',
        'graphHash' => 'text64?',
        'title' => 'text?',
        'titleSlugHash' => 'bytes12?',
        'groupName' => 'text255?',
        'summary' => 'text?',
        'isDocumentable' => 'bool',
        'nodeHash' => 'text64?',
        'repositoryPHID' => 'phid?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_phid' => null,
        'identityHash' => array(
          'columns' => array('identityHash'),
          'unique' => true,
        ),
        'phid' => array(
          'columns' => array('phid'),
          'unique' => true,
        ),
        'graphHash' => array(
          'columns' => array('graphHash'),
          'unique' => true,
        ),
        'nodeHash' => array(
          'columns' => array('nodeHash'),
          'unique' => true,
        ),
        'bookPHID' => array(
          'columns' => array(
            'bookPHID',
            'type',
            'name(64)',
            'context(64)',
            'atomIndex',
          ),
        ),
        'name' => array(
          'columns' => array('name(64)'),
        ),
        'key_slug' => array(
          'columns' => array('titleSlugHash'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhabricatorPHID::generateNewPHID(DivinerAtomPHIDType::TYPECONST);
  }

  public function getBook() {
    return $this->assertAttached($this->book);
  }

  public function attachBook(DivinerLiveBook $book) {
    $this->book = $book;
    return $this;
  }

  public function getRepository() {
    return $this->assertAttached($this->repository);
  }

  public function attachRepository(?PhabricatorRepository $repository = null) {
    $this->repository = $repository;
    return $this;
  }

  public function getAtom() {
    return $this->assertAttached($this->atom);
  }

  public function attachAtom(?DivinerLiveAtom $atom = null) {
    if ($atom === null) {
      $this->atom = null;
    } else {
      $this->atom = DivinerAtom::newFromDictionary($atom->getAtomData());
    }
    return $this;
  }

  /**
   * @return string|null
   */
  public function getURI() {
    $bookname = $this->getBook()->getName();
    $parts = array(
      'book',
      $bookname,
    );

    // Special handle methods which require the URI path to include their class
    $atom_type = $this->getType();
    if ($atom_type === DivinerAtom::TYPE_METHOD) {
      return $this->getMethodURI($parts, $bookname);
    }

    $parts[] = $atom_type;

    if ($this->getContext()) {
      $parts[] = $this->getContext();
    }

    $parts[] = $this->getName();

    if ($this->getAtomIndex()) {
      $parts[] = $this->getAtomIndex();
    }

    return '/'.implode('/', $parts).'/';
  }

  /**
   * @return string|null
   */
  private function getMethodURI(array $parts, string $bookname) {
    $atom = $this->getAtom();

    // Ghost items do not have an atom and thus should not link to anything
    if ($atom === null) {
      return null;
    }

    $method_class_name = $this->getMethodClassname();
    if (substr($method_class_name, -9) === 'Interface') {
      $parts[] = phutil_escape_uri_path_component(DivinerAtom::TYPE_INTERFACE);
    } else {
      $parts[] = phutil_escape_uri_path_component(DivinerAtom::TYPE_CLASS);
    }

    $parts[] = phutil_escape_uri_path_component($method_class_name);
    $parts[] = '#'.DivinerAtom::TYPE_METHOD;
    $parts[] = $this->getName();

    return '/'.implode('/', $parts);
  }

  /**
   * Get the name of the class in which the method is defined
   * @return string
   */
  public function getMethodClassname() {
    if (!$this->getType() === DivinerAtom::TYPE_METHOD) {
      throw new Exception(
        pht("Symbol '%s' is not a method!", $this->getName()));
    }
    $atom_file = $this->getAtom()->getFile();
    if ($this->getBook()->getName() === 'javelin') {
      return 'JX.'.basename($atom_file, '.js');
    }
    return basename($atom_file, '.php');
  }

  public function getSortKey() {
    // Sort articles before other types of content. Then, sort atoms in a
    // case-insensitive way.
    return sprintf(
      '%c:%s',
      ($this->getType() == DivinerAtom::TYPE_ARTICLE ? '0' : '1'),
      phutil_utf8_strtolower($this->getTitle()));
  }

  public function save() {
    // NOTE: The identity hash is just a sanity check because the unique tuple
    // on this table is way too long to fit into a normal `UNIQUE KEY`.
    // We don't use it directly, but its existence prevents duplicate records.

    if (!$this->identityHash) {
      $this->identityHash = PhabricatorHash::digestForIndex(
        serialize(
          array(
            'bookPHID' => $this->getBookPHID(),
            'context'  => $this->getContext(),
            'type'     => $this->getType(),
            'name'     => $this->getName(),
            'index'    => $this->getAtomIndex(),
          )));
    }

    return parent::save();
  }

  public function getTitle() {
    $title = parent::getTitle();

    if (!phutil_nonempty_string($title)) {
      $title = $this->getName();
    }

    return $title;
  }

  public function setTitle($value) {
    $this->writeField('title', $value);

    if (phutil_nonempty_string($value)) {
      $slug = DivinerAtomRef::normalizeTitleString($value);
      $hash = PhabricatorHash::digestForIndex($slug);
      $this->titleSlugHash = $hash;
    } else {
      $this->titleSlugHash = null;
    }

    return $this;
  }

  public function attachExtends(array $extends) {
    assert_instances_of($extends, self::class);
    $this->extends = $extends;
    return $this;
  }

  public function getExtends() {
    return $this->assertAttached($this->extends);
  }

  public function attachChildren(array $children) {
    assert_instances_of($children, self::class);
    $this->children = $children;
    return $this;
  }

  public function getChildren() {
    return $this->assertAttached($this->children);
  }


/* -(  PhabricatorPolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return $this->getBook()->getCapabilities();
  }

  public function getPolicy($capability) {
    return $this->getBook()->getPolicy($capability);
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $viewer) {
    return $this->getBook()->hasAutomaticCapability($capability, $viewer);
  }

  public function describeAutomaticCapability($capability) {
    return pht('Atoms inherit the policies of the books they are part of.');
  }


/* -( PhabricatorMarkupInterface  )------------------------------------------ */


  public function getMarkupFieldKey($field) {
    return $this->getPHID().':'.$field.':'.$this->getGraphHash();
  }

  public function newMarkupEngine($field) {
    return PhabricatorMarkupEngine::getEngine('diviner');
  }

  public function getMarkupText($field) {
    if (!$this->getAtom()) {
      return '';
    }

    return $this->getAtom()->getDocblockText();
  }

  public function didMarkupText($field, $output, PhutilMarkupEngine $engine) {
    return $output;
  }

  public function shouldUseMarkupCache($field) {
    return true;
  }


/* -(  PhabricatorDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhabricatorDestructionEngine $engine) {

    $this->openTransaction();
      $conn_w = $this->establishConnection('w');

      queryfx(
        $conn_w,
        'DELETE FROM %T WHERE symbolPHID = %s',
        id(new DivinerLiveAtom())->getTableName(),
        $this->getPHID());

      $this->delete();
    $this->saveTransaction();
  }


/* -(  PhabricatorFulltextInterface  )--------------------------------------- */


  public function newFulltextEngine() {
    if (!$this->getIsDocumentable()) {
      return null;
    }

    return new DivinerLiveSymbolFulltextEngine();
  }

}
