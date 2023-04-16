<?php

final class PhorgeFactHomeController
  extends PhorgeFactController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $chart = id(new PhorgeDemoChartEngine())
      ->setViewer($viewer)
      ->newStoredChart();

    return id(new AphrontRedirectResponse())->setURI($chart->getURI());
  }

}
