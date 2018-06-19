<?php
/**
 * Class FMControllerFormmakerwdcaptcha
 */
class FMControllerFormmakerwdcaptcha {
  /**
   * @var view
   */
  private $view;

  /**
   * Execute.
   */
  public function execute() {
    $this->display();
  }

  /**
   * Display.
   */
  public function display() {
    // Load FMViewFormmakerwdcaptcha class.
    require_once WDFM()->plugin_dir . "/admin/views/FMCaptcha.php";
    $this->view = new FMViewFormmakerwdcaptcha();
    // Set params for view.
    $params = array();
    $this->view->display($params);
  }
}
