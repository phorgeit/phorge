<?php

final class PhorgePHPParserExtractor extends PhpParser\NodeVisitorAbstract {
  private static $knownTypes = array(
    'PhabricatorEdgeType' => array(
      'getTransactionAddString' => array(null, 'number', null),
      'getTransactionRemoveString' => array(null, 'number', null),
      'getTransactionEditString' => array(
         null,
         'number',
         'number',
         null,
         'number',
         null,
      ),
      'getFeedAddString' => array(null, null, 'number', null),
      'getFeedRemoveString' => array(null, null, 'number', null),
      'getFeedEditString' => array(
        null,
        null,
        'number',
        'number',
        null,
        'number',
        null,
      ),
    ),
  );
  private $classDecl = null;
  private $classExtends = null;
  private $methodDecl = null;
  private $paramVars = [];
  private $filename = null;
  private $results = [];
  private $warnings = [];
  private $variableAssignments = [];

  public function getWarnings() {
    return $this->warnings;
  }

  public function getResults() {
    return $this->results;
  }

  public function __construct($filename) {
    $this->filename = $filename;
  }

  private function getName($node) {
    $name = $node->name;
    if (is_string($name)) {
      return $name;
    }
    if ($name instanceof PhpParser\Node\Name ||
      $name instanceof PhpParser\Node\Identifier
    ) {
      return $name->name;
    }
    return null;
  }

  private function typeNode(PhpParser\Node $node, array $seen = array()) {
    if ($node instanceof PhpParser\Node\Scalar\Int_) {
      return 'number';
    } else if ($node instanceof PhpParser\Node\Expr\BinaryOp) {
      // i.e count($foos) + count($bars)
      $type1 = $this->typeNode($node->left, $seen);
      $type2 = $this->typeNode($node->right, $seen);
      if ($type1 === $type2) {
        return $type1;
      }
    } else if ($node instanceof PhpParser\Node\Expr\FuncCall) {
      switch ($this->getName($node)) {
        case 'phutil_count':
        case 'count':
          return 'number';
        case 'phutil_person':
          return 'person';
      }
    } else if ($node instanceof PhpParser\Node\Expr\New_ ) {
      if ($this->getName($node->class) == 'PhutilNumber') {
        return 'number';
      }
    } else if ($node instanceof PhpParser\Node\Expr\Variable) {
      $name = $this->getName($node);
      if (isset($seen[$name])) {
        return null;
      }
      if (isset($this->variableAssignments[$name])) {
        return $this->typeNode(
          $this->variableAssignments[$name],
          $seen + [$name => true]);
      }
      $known = static::$knownTypes;
      if (isset($known[$this->classExtends])) {
        $class_data = $known[$this->classExtends];
      } else if (isset($known[$this->classDecl])) {
        $class_data = $known[$this->classDecl];
      }
      if (isset($class_data[$this->methodDecl])) {
        $types = $class_data[$this->methodDecl];
        if ($name && isset($this->paramVars[$name])) {
          if (isset($types[$this->paramVars[$name]])) {
            return $types[$this->paramVars[$name]];
          }
        }
      }
    }
    return null;
  }

  public function enterNode(PhpParser\Node $node) {
    if ($node instanceof PhpParser\Node\Stmt\Class_) {
      $this->classDecl = $this->getName($node);
      if ($node->extends) {
        $this->classExtends = $this->getName($node->extends);
      }
    } else if ($node instanceof PhpParser\Node\Stmt\ClassMethod) {
      $this->methodDecl = $this->getName($node);
      $this->paramVars = [];
      $i = 0;
      foreach ($node->params as $param) {
        $name = $this->getName($param->var);
        if ($name) {
          $this->paramVars[$name] = $i;
        }
        $i++;
      }
    } else if ($node instanceof PhpParser\Node\Expr\Assign) {
      if ($node->var instanceof PhpParser\Node\Expr\Variable) {
        $name = $this->getName($node->var);
        // $name could be null if this is a "variable variable"
        // (like $$foo = bar)
        if ($name) {
          $this->variableAssignments[$name] = $node->expr;
        }
      }
    } else if ($node instanceof PhpParser\Node\Expr\FuncCall) {
      if ($this->getName($node) == 'pht') {
        $types = [];
        $args = $node->args;
        $first = array_shift($args);
        try {
          $key = id(new PhpParser\ConstExprEvaluator())
            ->evaluateSilently($first->value);
        } catch (PhpParser\ConstExprEvaluationException $ex) {
          $this->warnings[] = pht(
            'Failed to evaluate pht() call on line %d in "%s": %s',
            $first->getStartLine(),
            $this->filename,
            $ex->getMessage());
          return;
        }
        foreach ($args as $child) {
          $types[] = $this->typeNode($child->value);
        }
        $this->results[] = array(
          'string' => $key,
          'line' => $first->getStartLine(),
          'types' => $types,
          'file' => $this->filename,
        );
      }
    }
  }

  public function leaveNode(PhpParser\Node $node) {
     if ($node instanceof PhpParser\Node\Stmt\Class_) {
      $this->classDecl = null;
      $this->classExtends = null;
     } else if ($node instanceof PhpParser\Node\Stmt\ClassMethod) {
      $this->methodDecl = null;
      $this->paramVars = [];
      $this->variableAssignments = [];
     }
  }
}
