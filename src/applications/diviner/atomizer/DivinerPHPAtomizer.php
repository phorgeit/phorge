<?php

/**
 * @phutil-external-symbol class PhpParser\Node
 * @phutil-external-symbol class PhpParser\NodeTraverser
 * @phutil-external-symbol class PhpParser\Node\FunctionLike
 * @phutil-external-symbol class PhpParser\NodeVisitor\FindingVisitor
 * @phutil-external-symbol class PhpParser\NodeVisitor\NameResolver
 * @phutil-external-symbol class PhpParser\Node\Stmt\Class_
 * @phutil-external-symbol class PhpParser\Node\Stmt\ClassLike
 * @phutil-external-symbol class PhpParser\Node\Stmt\Enum_
 * @phutil-external-symbol class PhpParser\Node\Stmt\Function_
 * @phutil-external-symbol class PhpParser\Node\Stmt\Interface_
 * @phutil-external-symbol class PhpParser\Node\Stmt\Trait_
 * @phutil-external-symbol class PhpParser\PrettyPrinter\Standard
 */
final class DivinerPHPAtomizer extends DivinerAtomizer {

  protected function newAtom($type) {
    return parent::newAtom($type)->setLanguage('php');
  }

  protected function executeAtomize($file_name, $file_data) {
    $parser = PhutilPHPParserLibrary::getParser();
    $ast = $parser->parse($file_data);

    $classlike_finder = new PhpParser\NodeVisitor\FindingVisitor(
      function ($node) {
        return $node instanceof PhpParser\Node\Stmt\ClassLike;
      });
    $function_finder = new PhpParser\NodeVisitor\FindingVisitor(
      function ($node) {
        return $node instanceof PhpParser\Node\Stmt\Function_;
      });

    $namespace_resolver = new PhpParser\NodeVisitor\NameResolver();
    $traverser = new PhpParser\NodeTraverser();
    $traverser->addVisitor($namespace_resolver);
    $traverser->addVisitor($classlike_finder);
    $traverser->addVisitor($function_finder);
    $traverser->traverse($ast);

    $atoms = array();

    foreach ($function_finder->getFoundNodes() as $func) {
      $atom = $this->newAtom(DivinerAtom::TYPE_FUNCTION)
        ->setName($func->namespacedName->toString())
        ->setLine($func->getStartLine())
        ->setFile($file_name);

      $this->findAtomDocblock($atom, $func);
      $this->parseParams($atom, $func);
      $this->parseReturnType($atom, $func);

      $atoms[] = $atom;
    }

    $class_types = array(
      PhpParser\Node\Stmt\Class_::class => DivinerAtom::TYPE_CLASS,
      PhpParser\Node\Stmt\Interface_::class => DivinerAtom::TYPE_INTERFACE,
      PhpParser\Node\Stmt\Trait_::class => DivinerAtom::TYPE_TRAIT,
      PhpParser\Node\Stmt\Enum_::class => DivinerAtom::TYPE_ENUM,
    );

    foreach ($classlike_finder->getFoundNodes() as $class) {
      $atom_type = $class_types[get_class($class)];

      // Don't analyze anonymous classes.
      if (!$class->name) {
        continue;
      }

      $atom = $this->newAtom($atom_type)
        ->setName($class->namespacedName->toString())
        ->setFile($file_name)
        ->setLine($class->getStartLine());

      if ($class instanceof PhpParser\Node\Stmt\Class_) {
        if ($class->isAbstract()) {
          $atom->setProperty('abstract', true);
        } else if ($class->isFinal()) {
          $atom->setProperty('final', true);
        } else if ($class->isReadonly()) {
          $atom->setProperty('readonly', true);
        }

        if ($class->extends) {
          $atom->addExtends(
            $this->newRef(
              DivinerAtom::TYPE_CLASS,
              $class->extends->toString()));
        }

        foreach ($class->implements as $implement) {
          $atom->addExtends(
            $this->newRef(
              DivinerAtom::TYPE_INTERFACE,
              $implement->toString()));
        }
      } else if ($class instanceof PhpParser\Node\Stmt\Interface_) {
        foreach ($class->extends as $extend) {
          $atom->addExtends(
            $this->newRef(
              DivinerAtom::TYPE_INTERFACE,
              $extend->toString()));
        }
      } else if ($class instanceof PhpParser\Node\Stmt\Enum_) {
        foreach ($class->implements as $implement) {
          $atom->addExtends(
            $this->newRef(
              DivinerAtom::TYPE_INTERFACE,
              $implement->toString()));
        }
      }

      $this->findAtomDocblock($atom, $class);

      foreach ($class->getMethods() as $method) {
        $matom = $this->newAtom(DivinerAtom::TYPE_METHOD)
          ->setName($method->name->toString())
          ->setLine($method->getStartLine())
          ->setFile($file_name);

        $this->findAtomDocblock($matom, $method);

        if ($method->isFinal()) {
          $matom->setProperty('final', true);
        }

        if ($method->isAbstract()) {
          $matom->setProperty('abstract', true);
        }

        if ($method->isStatic()) {
          $matom->setProperty('static', true);
        }

        if ($method->isPrivate()) {
            $matom->setProperty('access', 'private');
        } else if ($method->isProtected()) {
            $matom->setProperty('access', 'protected');
        } else {
            $matom->setProperty('access', 'public');
        }

        $this->parseParams($matom, $method);

        $this->parseReturnType($matom, $method);
        $atom->addChild($matom);

        $atoms[] = $matom;
      }

      $atoms[] = $atom;
    }

    return $atoms;
  }

  private function parseParams(
    DivinerAtom $atom,
    PhpParser\Node\FunctionLike $func) {

    $params = $func->getParams();

    $param_spec = array();

    if ($atom->getDocblockRaw()) {
      $metadata = $atom->getDocblockMeta();
    } else {
      $metadata = array();
    }

    $docs = idx($metadata, 'param');
    if ($docs) {
      $docs = (array)$docs;
      $docs = array_filter($docs);
    } else {
      $docs = array();
    }

    if (count($docs)) {
      if (count($docs) < count($params)) {
        $atom->addWarning(
          pht(
            'This call takes %s parameter(s), but only %s are documented.',
            phutil_count($params),
            phutil_count($docs)));
      }
    }

    foreach ($params as $param) {
      $name = '$'.$param->var->name;
      $dict = array(
        'type'    => $this->stringify($param->type),
        'default' => $this->stringify($param->default),
      );

      if ($docs) {
        $doc = array_shift($docs);
        if ($doc) {
          $dict += $this->parseParamDoc($atom, $doc, $name);
        }
      }

      $param_spec[] = array(
        'name' => $name,
      ) + $dict;
    }

    if ($docs) {
      foreach ($docs as $doc) {
        if ($doc) {
          $param_spec[] = $this->parseParamDoc($atom, $doc, null);
        }
      }
    }

    // TODO: Find `assert_instances_of()` calls in the function body and
    // add their type information here. See T1089.

    $atom->setProperty('parameters', $param_spec);
  }

  private function findAtomDocblock(DivinerAtom $atom, PhpParser\Node $node) {
    $doc_comment = $node->getDocComment();

    if ($doc_comment) {
      $atom->setDocblockRaw($doc_comment->getText());
    } else {
      $comments = $node->getComments();

      foreach ($comments as $comment) {
        $value = $comment->getText();
        $matches = null;
        if (preg_match('/@(return|param|task|author)/', $value, $matches)) {
          $atom->addWarning(
            pht(
              'Atom "%s" is preceded by a comment containing `%s`, but '.
              'the comment is not a documentation comment. Documentation '.
              'comments must begin with `%s`, followed by a newline. Did '.
              'you mean to use a documentation comment? (As the comment is '.
              'not a documentation comment, it will be ignored.)',
              $atom->getName(),
              '@'.$matches[1],
              '/**'));
        }
      }

      $atom->setDocblockRaw('');
    }
  }

  protected function parseParamDoc(DivinerAtom $atom, $doc, $name) {
    $dict = array();
    $split = preg_split('/(?<!,)\s+/', trim($doc), 2);
    if (!empty($split[0])) {
      $dict['doctype'] = $split[0];
    }

    if (!empty($split[1])) {
      $docs = $split[1];

      // If the parameter is documented like `@param int $num Blah blah ..`,
      // get rid of the `$num` part (which Diviner considers optional). If it
      // is present and different from the declared name, raise a warning.
      $matches = null;
      if (preg_match('/^(\\$\S+)\s+/', $docs, $matches)) {
        if ($name !== null) {
          if ($matches[1] !== $name) {
            $atom->addWarning(
              pht(
                'Parameter "%s" is named "%s" in the documentation. '.
                'The documentation may be out of date.',
                $name,
                $matches[1]));
          }
        }
        $docs = substr($docs, strlen($matches[0]));
      }

      $dict['docs'] = $docs;
    }

    return $dict;
  }

  private function parseReturnType(
    DivinerAtom $atom,
    PhpParser\Node\FunctionLike $decl) {

    $return_spec = array();

    $metadata = $atom->getDocblockMeta();
    $return = idx($metadata, 'return');

    $type = null;
    $docs = null;

    if (!$return) {
      $return = idx($metadata, 'returns');
      if ($return) {
        $atom->addWarning(
          pht(
            'Documentation uses `%s`, but should use `%s`.',
            '@returns',
            '@return'));
      }
    }

    $return = (array)$return;
    if (count($return) > 1) {
        $atom->addWarning(
          pht(
            'Documentation specifies `%s` multiple times.',
            '@return'));
    }
    $return = head($return);

    if ($atom->getName() == '__construct' && $atom->getType() == 'method') {
      $return_spec = array(
        'doctype' => 'this',
        'docs' => '//Implicit.//',
      );

      if ($return) {
        $atom->addWarning(
          pht(
            'Method `%s` has explicitly documented `%s`. The `%s` method '.
            'always returns `%s`. Diviner documents this implicitly.',
            '__construct()',
            '@return',
            '__construct()',
            '$this'));
      }
    } else if ($return) {
      $split = preg_split('/(?<!,)\s+/', trim($return), 2);
      if (!empty($split[0])) {
        $type = $split[0];
      }

      if ($decl->returnsByRef()) {
        $type = $type.' &';
      }

      if (!empty($split[1])) {
        $docs = $split[1];
      }

      $return_spec = array(
        'doctype' => $type,
        'docs'    => $docs,
      );
    } else {
      $return_spec = array(
        'type' => 'wild',
      );
    }

    $atom->setProperty('return', $return_spec);
  }

  private function stringify(?PhpParser\Node $node) {
    if (!$node) {
      return '';
    }

    return id(new PhpParser\PrettyPrinter\Standard())
      ->prettyPrint(array($node));
  }

}
