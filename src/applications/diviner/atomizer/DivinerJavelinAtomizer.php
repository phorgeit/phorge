<?php

/**
 * @phutil-external-symbol class Peast\Peast
 * @phutil-external-symbol class Peast\Syntax\Node\Node
 * @phutil-external-symbol class Peast\Syntax\Node\AssignmentExpression
 * @phutil-external-symbol class Peast\Syntax\Node\CallExpression
 * @phutil-external-symbol class Peast\Syntax\Node\FunctionExpression
 * @phutil-external-symbol class Peast\Syntax\Node\Identifier
 * @phutil-external-symbol class Peast\Syntax\Node\LogicalExpression
 * @phutil-external-symbol class Peast\Syntax\Node\MemberExpression
 * @phutil-external-symbol class Peast\Syntax\Node\ObjectExpression
 * @phutil-external-symbol class Peast\Syntax\Node\StringLiteral
 */
final class DivinerJavelinAtomizer extends DivinerAtomizer {

  protected function newAtom($type) {
    return parent::newAtom($type)->setLanguage('js');
  }

  protected function executeAtomize($file_name, $file_data) {
    JavelinPeastLibrary::loadLibrary();

    $atoms = array();

    $ast = Peast\Peast::latest($file_data)->parse();
    $ast->traverse(function (Peast\Syntax\Node\Node $node) use (&$atoms) {
      if ($node instanceof Peast\Syntax\Node\CallExpression) {
        foreach ($this->parseCall($node) as $atom) {
          $atoms[] = $atom;
        }
      } else if ($node instanceof Peast\Syntax\Node\AssignmentExpression) {
        $atom = $this->parseAssignment($node);

        if ($atom) {
          $atoms[] = $atom;
        }
      }
    });

    $dparser = new PhutilDocblockParser();
    $blocks = $dparser->extractDocblocks($file_data);

    // Reject the first docblock as a header block.
    array_shift($blocks);

    $map = array();
    foreach ($blocks as $data) {
      list($block, $line) = $data;
      $map[$line] = $block;
    }

    $atoms = mpull($atoms, null, 'getLine');
    ksort($atoms);
    end($atoms);
    $last = key($atoms);

    $block_map = array();
    $pointer = null;
    for ($ii = 1; $ii <= $last; $ii++) {
      if (isset($map[$ii])) {
        $pointer = $ii;
      }
      $block_map[$ii] = $pointer;
    }

    /** @var DivinerAtom $atom */
    foreach ($atoms as $atom) {
      $block_id = $block_map[$atom->getLine()];
      if ($block_id !== null && isset($map[$block_id])) {
        $atom->setDocblockRaw($map[$block_id]);
        unset($map[$block_id]);
      } else {
        continue;
      }

      if (
        $atom->getType() === DivinerAtom::TYPE_METHOD ||
        $atom->getType() === DivinerAtom::TYPE_FUNCTION) {

        $this->parseReturnDoc($atom);
        $this->parseParametersDoc($atom);
      }
    }

    return $atoms;
  }

  private function parseCall(
    Peast\Syntax\Node\CallExpression $call): array {

    $callee = $call->getCallee();

    if (!($callee instanceof Peast\Syntax\Node\MemberExpression)) {
      return array();
    }

    $object = $callee->getObject();
    $property = $callee->getProperty();
    if (
      !($object instanceof Peast\Syntax\Node\Identifier) ||
      !($property instanceof Peast\Syntax\Node\Identifier)) {
      return array();
    }

    if ($object->getName() !== 'JX') {
      return array();
    }

    if ($property->getName() !== 'install') {
      return array();
    }

    $arguments = $call->getArguments();
    if (count($arguments) < 2) {
      return array();
    }

    list($install_name, $definition) = $arguments;
    if (
      !($install_name instanceof Peast\Syntax\Node\StringLiteral) ||
      !($definition instanceof Peast\Syntax\Node\ObjectExpression)) {

      return array();
    }

    list($class, $methods) = $this->parseClassDefinition($definition);
    $class->setLine($call->getLocation()->getStart()->getLine())
      ->setName('JX.'.$install_name->getValue());

    if (!$class->getExtends() && $class->getName() !== 'JX.Base') {
      $class->addExtends(
        $this->newRef(DivinerAtom::TYPE_CLASS, 'JX.Base'));
    }

    $atoms = $methods;
    $atoms[] = $class;

    return $atoms;
  }

  private function parseAssignment(
    Peast\Syntax\Node\AssignmentExpression $assignment): ?DivinerAtom {

    $left = $assignment->getLeft();
    $right = $assignment->getRight();

    if (!($left instanceof Peast\Syntax\Node\MemberExpression)) {
      return null;
    }

    $object = $left->getObject();
    if (
      !($object instanceof Peast\Syntax\Node\Identifier) ||
      $object->getName() !== 'JX') {

      return null;
    }

    // This supports constructions such as x || y || function () {}.
    if (
      $right instanceof Peast\Syntax\Node\LogicalExpression &&
      $right->getOperator() === '||') {

      // By associativity rules, this selects the rightmost expression.
      $right = $right->getRight();
    }

    if (!($right instanceof Peast\Syntax\Node\FunctionExpression)) {
      return null;
    }

    return $this->parseFunction($right, false)
      ->setName('JX.'.$left->getProperty()->getName())
      ->setLine($assignment->getLocation()->getStart()->getLine());
  }

  private function parseClassDefinition(
    Peast\Syntax\Node\ObjectExpression $definition): array {

    $methods = array();

    $class = $this->newAtom(DivinerAtom::TYPE_CLASS);

    foreach ($definition->getProperties() as $property) {
      $key = $property->getKey();
      $this->expectNode($key, Peast\Syntax\Node\Identifier::class);

      $name = $key->getName();
      $value = $property->getValue();
      $start_line = $key->getLocation()->getStart()->getLine();

      switch ($name) {
        case 'members':
        case 'statics':
          $this->expectNode($value, Peast\Syntax\Node\ObjectExpression::class);

          foreach ($this->parseInstallationEntries($value) as $atom) {
            $atom
              ->setProperty(
                'static',
                $name === 'statics')
              ->setLine($start_line);
            $class->addChild($atom);
            $methods[] = $atom;
          }
          break;
        case 'construct':
        case 'initialize':
          $this->expectNode(
            $value,
            Peast\Syntax\Node\FunctionExpression::class);

          $atom = $this->parseFunction($value, true)
            ->setName($name)
            ->setLine($start_line)
            ->setProperty(
              'static',
              $name === 'initialize');
          $class->addChild($atom);
          $methods[] = $atom;
          break;
        case 'extend':
          $this->expectNode($value, Peast\Syntax\Node\StringLiteral::class);
          $class->addExtends(
            $this->newRef(
              DivinerAtom::TYPE_CLASS,
              $value->getValue()));
          break;
        case 'properties':
          // Diviner doesn't document these yet.
        case 'events':
        case 'canCallAsFunction':
          // These have not been implemented yet.
          break;
        default:
          throw new Exception(
            pht(
              'Unexpected property "%s" in Javelin class definition!',
              $name));
      }
    }

    return array($class, $methods);
  }

  /**
   * @param Peast\Syntax\Node\ObjectExpression $object_expression
   * @return Generator<DivinerAtom>
   */
  private function parseInstallationEntries(
    Peast\Syntax\Node\ObjectExpression $object_expression): Generator {

    foreach ($object_expression->getProperties() as $property) {
      $key = $property->getKey();
      $this->expectNode($key, Peast\Syntax\Node\Identifier::class);
      $start_line = $key->getLocation()->getStart()->getLine();

      $value = $property->getValue();
      if ($value instanceof Peast\Syntax\Node\FunctionExpression) {
        $name = $key->getName();

        $method = $this->parseFunction($value, true)
          ->setName($name)
          ->setLine($start_line);

        if (!strncmp($name, '_', 1)) {
          $method->setProperty('access', 'private');
        }

        yield $method;
      }
    }
  }

  private function parseFunction(
    Peast\Syntax\Node\FunctionExpression $node,
    bool $class_function): DivinerAtom {

    $param_spec = array();

    foreach ($node->getParams() as $param) {
      $this->expectNode($param, Peast\Syntax\Node\Identifier::class);

      $param_spec[] = array(
        'name' => $param->getName(),
      );
    }

    if ($class_function) {
      $type = DivinerAtom::TYPE_METHOD;
    } else {
      $type = DivinerAtom::TYPE_FUNCTION;
    }

    return $this->newAtom($type)
      ->setProperty('parameters', $param_spec);
  }

  private function parseReturnDoc(DivinerAtom $atom) {
    $return = $atom->getDocblockMetaValue('return');

    if ($return) {
      $return = (array)$return;
      if (count($return) > 1) {
          $atom->addWarning(
            pht(
              'Documentation specifies `%s` multiple times.',
              '@return'));
      }
      $return = head($return);

      $split = preg_split('/\s+/', trim($return), $limit = 2);
      if (!empty($split[0])) {
        $type = $split[0];
      } else {
        $type = 'wild';
      }

      $docs = null;
      if (!empty($split[1])) {
        $docs = $split[1];
      }

      $return_spec = array(
        'doctype' => $type,
        'docs'    => $docs,
      );

      $atom->setProperty('return', $return_spec);
    }
  }

  private function parseParametersDoc(DivinerAtom $atom) {
    $docs = $atom->getDocblockMetaValue('param');

    if ($docs) {
      $docs = (array)$docs;
      $param_spec = array();

      foreach ($atom->getProperty('parameters') as $dict) {
        $doc = array_shift($docs);
        if ($doc) {
          $dict += $this->parseParamDoc($doc, $dict['name']);
        }
        $param_spec[] = $dict;
      }

      // Add extra parameters retrieved by arguments variable.
      foreach ($docs as $doc) {
        if ($doc) {
          $param_spec[] = array(
            'name' => '',
          ) + $this->parseParamDoc($doc, '');
        }
      }

      $atom->setProperty('parameters', $param_spec);
    }
  }

  private function parseParamDoc(string $doc, string $name): array {
    $dict = array();
    $split = preg_split('/(?<!,)\s+/', trim($doc), 2);
    if (!empty($split[0])) {
      $dict['doctype'] = $split[0];
    }

    if (!empty($split[1])) {
      $docs = $split[1];

      // If the parameter is documented like `@param int num Blah blah ..`,
      // get rid of the `num` part (which Diviner considers optional).
      // Unlike PHP, where the $ is a good identifier, for JavaScript we'll only
      // remove it if it matches the name of the parameter.
      // False positives should be unlikely, as these should be lowercase.
      if (!strncmp($docs, $name, strlen($name))) {
        $docs = trim(substr($docs, strlen($name)));
      }

      $dict['docs'] = $docs;
    }

    return $dict;
  }

  private function expectNode($node, string $class) {
    if (!($node instanceof $class)) {
      $position = $node->getLocation()->getStart();

      throw new Exception(
        pht(
          'Expected "%s" node but found "%s" (on line %d:%d).',
          id(new $class())->getType(),
          $node->getType(),
          $position->getLine(),
          $position->getColumn()));
    }
  }

}
