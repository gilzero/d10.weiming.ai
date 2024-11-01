<?php

namespace Drupal\Core\Updater;

use Drupal\Core\Url;

/**
 * Defines a class for updating themes.
 *
 * Uses Drupal\Core\FileTransfer\FileTransfer classes via authorize.php.
 */
class Theme extends Updater implements UpdaterInterface {

  /**
   * Returns the directory where a theme should be installed.
   *
   * If the theme is already installed,
   * \Drupal::service('extension.list.theme')->getPath() will return a valid
   * path and we should install it there. If we're installing a new theme, we
   * always want it to go into /themes, since that's where all the
   * documentation recommends users install their themes, and there's no way
   * that can conflict on a multi-site installation, since the Update manager
   * won't let you install a new theme if it's already found on your system,
   * and if there was a copy in the top-level we'd see it.
   *
   * @return string
   *   The absolute path of the directory.
   */
  public function getInstallDirectory() {
    if ($this->isInstalled() && ($relative_path = \Drupal::service('extension.list.theme')->getPath($this->name))) {
      // The return value of
      // \Drupal::service('extension.list.theme')->getPath() is always relative
      // to the site, so prepend DRUPAL_ROOT.
      return DRUPAL_ROOT . '/' . dirname($relative_path);
    }
    else {
      // When installing a new theme, prepend the requested root directory.
      return $this->root . '/' . $this->getRootDirectoryRelativePath();
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getRootDirectoryRelativePath() {
    return 'themes';
  }

  /**
   * {@inheritdoc}
   */
  public function isInstalled() {
    // Check if the theme exists in the file system, regardless of whether it
    // is enabled or not.
    $themes = \Drupal::state()->get('system.theme.files', []);
    return isset($themes[$this->name]);
  }

  /**
   * {@inheritdoc}
   */
  public static function canUpdateDirectory($directory) {
    $info = static::getExtensionInfo($directory);

    return (isset($info['type']) && $info['type'] == 'theme');
  }

  /**
   * Determines whether this class can update the specified project.
   *
   * @param string $project_name
   *   The project to check.
   *
   * @return bool
   */
  public static function canUpdate($project_name) {
    return (bool) \Drupal::service('extension.list.theme')->getPath($project_name);
  }

  /**
   * {@inheritdoc}
   */
  public function postInstall() {
    // Update the theme info.
    clearstatcache();
    \Drupal::service('extension.list.theme')->reset();
  }

  /**
   * {@inheritdoc}
   */
  public function postInstallTasks() {
    // Since this is being called outside of the primary front controller,
    // the base_url needs to be set explicitly to ensure that links are
    // relative to the site root.
    // @todo Simplify with https://www.drupal.org/node/2548095
    $default_options = [
      '#type' => 'link',
      '#options' => [
        'absolute' => TRUE,
        'base_url' => $GLOBALS['base_url'],
      ],
    ];
    return [
      $default_options + [
        '#url' => Url::fromRoute('system.themes_page'),
        '#title' => t('Install newly added themes'),
      ],
      $default_options + [
        '#url' => Url::fromRoute('system.admin'),
        '#title' => t('Administration pages'),
      ],
    ];
  }

}
