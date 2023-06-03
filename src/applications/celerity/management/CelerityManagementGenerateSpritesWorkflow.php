<?php

final class CelerityManagementGenerateSpritesWorkflow
  extends CelerityManagementWorkflow {

    protected function didConstruct() {
      $this
        ->setName('sprites')
        ->setExamples('**sprites** [options]')
        ->setSynopsis(pht('Rebuild CSS sprite sheets.'))
        ->setArguments(
          array(
            array(
              'name' => 'force',
              'help' => pht('Force regeneration even no sources have changed.'),
            ),
            array(
              'name' => 'no-map',
              'help' =>
                pht(
                  'Do not invoke `%s` after updating sprites',
                  'celerity map'),
            ),
          ));
    }

    public function execute(PhutilArgumentParser $args) {
      $resources_map = CelerityPhysicalResources::getAll();

      $console = PhutilConsole::getConsole();

      $root = dirname(phutil_get_library_root('phorge'));
      $webroot = $root.'/webroot/rsrc';
      $webroot = Filesystem::readablePath($webroot);

      $generator = new CeleritySpriteGenerator();

      $sheets = array(
        'tokens' => $generator->buildTokenSheet(),
        'login' => $generator->buildLoginSheet(),
      );

      list($err) = exec_manual('optipng');
      if ($err) {
        $have_optipng = false;
        $console->writeErr(
          "<bg:red> %s </bg> %s\n%s\n",
          pht('WARNING'),
          pht('`%s` not found in PATH.', 'optipng'),
          pht('Sprites will not be optimized! Install `%s`!', 'optipng'));
      } else {
        $have_optipng = true;
      }

      foreach ($sheets as $name => $sheet) {

        $sheet->setBasePath($root);

        $manifest_path = $root.'/resources/sprite/manifest/'.$name.'.json';
        if (!$args->getArg('force')) {
          if (Filesystem::pathExists($manifest_path)) {
            $data = Filesystem::readFile($manifest_path);
            $data = phutil_json_decode($data);
            if (!$sheet->needsRegeneration($data)) {
              continue;
            }
          }
        }

        $sheet
          ->generateCSS($webroot."/css/sprite-{$name}.css")
          ->generateManifest($root."/resources/sprite/manifest/{$name}.json");

        foreach ($sheet->getScales() as $scale) {
          if ($scale == 1) {
            $sheet_name = "sprite-{$name}.png";
          } else {
            $sheet_name = "sprite-{$name}-X{$scale}.png";
          }

          $full_path = "{$webroot}/image/{$sheet_name}";
          $sheet->generateImage($full_path, $scale);

          if ($have_optipng) {
            $console->writeOut("%s\n", pht('Optimizing...'));
            phutil_passthru('optipng -o7 -clobber %s', $full_path);
          }
        }
      }

      $run_map = !$args->getArg('no-map');

      if ($run_map) {
        $console->writeOut(
          "%s\n",
           pht('Done generating sprites - updating map...'));
        $map_flow = id($args->getWorkflows())['map'];
        // this is very hacky, but it works because `map` has no arguments.
        $map_flow->execute($args);
      } else {
        $console->writeOut("%s\n", pht('Done.'));
        return 0;
      }

    }


}
