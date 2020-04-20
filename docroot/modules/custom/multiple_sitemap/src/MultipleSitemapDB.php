<?php

namespace Drupal\multiple_sitemap;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 *
 */
class MultipleSitemapDB {

  use StringTranslationTrait;
  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * MultipleSitemapForm class constructor.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * Save records into database.
   *
   * @param array $input
   *   Having input data.
   * @param int $update_ms_id
   *   Multiple sitemap id.
   *
   * @return int
   *   Ms id.
   */
  public function multiple_sitemap_save_record($input = [], $update_ms_id = NULL) {
    $ms_id = NULL;
    $conn = \Drupal::database();
    if (is_null($update_ms_id) && !empty($input)) {
      // Insert new record.
      try {
        $ms_id = $conn->insert('multiple_sitemap')
          ->fields([
            'file_name' => $input['file_name'],
            'custom_links' => $input['custom_links'],
          ])
          ->execute();
      }
      catch (Exception $e) {
        $this->messenger->addMessage($this->t(('File name already exist'), $this->messenger::TYPE_ERROR));
      }
    }
    else {
      // Update record.
      try {
        $ms_id = $conn->update('multiple_sitemap')
          ->fields([
            'file_name' => $input['file_name'],
            'custom_links' => $input['custom_links'],
          ])
          ->condition('ms_id', $update_ms_id, '=')
          ->execute();

        $ms_id = $update_ms_id;
      }
      catch (Exception $e) {
        $this->messenger->addMessage($this->t('Some thing is wrong here.'), $this->messenger::TYPE_ERROR);
      }
    }

    return $ms_id;
  }

  /**
   * Get records for give ms id.
   *
   * @param int $ms_id
   *   Having ms id.
   *
   * @return array
   *   Having existing records for given ms id.
   */
  public function multiple_sitemap_get_record($ms_id = NULL) {
    $conn = \Drupal::database();
    $records = [];
    if (!(is_null($ms_id))) {
      $query = $conn->select('multiple_sitemap', 'ms');
      $query->fields('ms');
      $query->condition('ms_id', $ms_id, '=');
      $result = $query->execute()->fetchAssoc();
      if (!empty($result)) {
        $records = $result;
      }

      $record_types = ['content', 'menu', 'vocab'];
      // $ms_id = db_insert('multiple_sitemap_' . $record_type)
      foreach ($record_types as $record_type) {
        $subrecords = [];
        $query = $conn->select('multiple_sitemap_' . $record_type, 'ms');
        $query->fields('ms');
        $query->condition('target_ms_id', $ms_id, '=');
        $results = $query->execute();
        if (!empty($results)) {
          foreach ($results as $result) {
            $subrecords[] = $result;
          }
        }

        $records[$record_type] = $subrecords;
      }
    }

    return $records;
  }

  /**
   * Insert new sub records for content type, menu type and vocab type.
   *
   * @param string $record_type
   *   Record type (content, menu, vocab).
   * @param int $ms_target_id
   *   Multiple sitemap id.
   * @param array $subrecords
   *   Having sub records.
   */
  public function multiple_sitemap_save_sub_record($record_type = NULL, $ms_target_id = NULL, $subrecords = []) {

    $conn = \Drupal::database();

    // Insert new records.
    if (!is_null($ms_target_id) && !is_null($record_type) && !empty($subrecords)) {

      // Record types.
      $record_types = ['content', 'menu', 'vocab'];

      // Check existence of record type.
      if (in_array($record_type, $record_types)) {
        foreach ($subrecords as $subrecord) {
          // Insert each record.
          try {
            $conn->insert('multiple_sitemap_' . $record_type)
              ->fields([
                'target_ms_id' => $ms_target_id,
                $record_type . '_type' => $subrecord['name'],
                'priority' => $subrecord['priority'],
                'changefreq' => $subrecord['changefreq'],
              ])
              ->execute();
          }
          catch (Exception $e) {
            $this->messenger->addMessage($this->t('Record already exist.'), $this->messenger::TYPE_ERROR);
          }
        }
      }
      else {
        $this->messenger->addMessage($this->t('Record type does not exist.'), $this->messenger::TYPE_ERROR);
      }
    }
  }

  /**
   * Delete the sub records.
   *
   * @param string $record_type
   *   Record type name.
   * @param int $ms_target_id
   *   Target ms id.
   */
  public function multiple_sitemap_delete_sub_record($record_type = NULL, $ms_target_id = NULL) {
    $conn = \Drupal::database();
    // Insert new records.
    if (!is_null($ms_target_id) && !is_null($record_type)) {

      // Record types.
      $record_types = ['content', 'menu', 'vocab'];

      // Check existence of record type.
      if (in_array($record_type, $record_types)) {
        try {

          $conn->delete('multiple_sitemap_' . $record_type)
            ->condition('target_ms_id', $ms_target_id, '=')
            ->execute();
        }
        catch (Exception $e) {
          $this->messenger->addMessage($this->t('Record is not deleted'), $this->messenger::TYPE_ERROR);
        }
      }
    }
  }

  /**
   * Get all files name.
   *
   * @return array
   *   Having all file name.
   */
  public function multiple_sitemap_get_files_name() {
    $conn = \Drupal::database();
    $records = [];
    $query = $conn->select('multiple_sitemap', 'ms');
    $query->fields('ms', ['ms_id', 'file_name']);
    $results = $query->execute();
    if (!empty($results)) {
      foreach ($results as $result) {
        $records[$result->ms_id] = $result->file_name;
      }
    }

    return $records;
  }

  /**
   * Get links from content types.
   *
   * @param array $contents
   *   Having content details.
   *
   * @return array
   *   Having content links.
   */
  public function multiple_sitemap_get_content_links($contents = []) {

    $content_links = [];
    $conn = \Drupal::database();

    $i = 0;

    if (!empty($contents)) {
      foreach ($contents as $content) {
        $type = $content->content_type;

        // Get the all links for given type.
        $query = $conn->select('node', 'n');
        $query->fields('n', ['nid']);
        $query->condition('type', $type);

        $results = $query->execute();
        if (!empty($results)) {
          foreach ($results as $result) {
            $nid = $result->nid;
            $path = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $nid);
            $path = Url::fromUserInput($path)->setAbsolute()->toString();
            $content_links[$i]['link'] = $path;
            $content_links[$i]['priority'] = $content->priority;
            $content_links[$i]['changefreq'] = $content->changefreq;

            $i++;
          }
        }
      }
    }

    return $content_links;
  }

  /**
   * Get links from menu types.
   *
   * @param array $menus
   *   Having menu details.
   *
   * @return array
   *   Having menus links.
   */
  public function multiple_sitemap_get_menu_links($menus = []) {
    $conn = \Drupal::database();

    $menu_links = [];
    $i = 0;

    if (!empty($menus)) {

      foreach ($menus as $menu) {
        $type = $menu->menu_type;

        $query = $conn->select('menu_link_content_data', 'ml');
        $query->fields('ml', ['link__uri']);
        $query->condition('menu_name', $type, '<>');
        $query->condition('enabled', '1', '=');
        $query->condition('link__uri', '%' . $conn->escapeLike('%') . '%', 'NOT LIKE');
        $query->condition('link__uri', '%' . $conn->escapeLike('<') . '%', 'NOT LIKE');
        $results = $query->execute();
        if (!empty($results)) {
          foreach ($results as $value) {
            $url = Url::fromUri($value->link__uri, ['absolute' => TRUE])->toString();
            $link = '/' . $url;
            $path = \Drupal::service('path.alias_manager')->getAliasByPath($link);
            // $path = Url::fromUserInput($path)->setAbsolute()->toString();
            $menu_links[$i]['link'] = $path;
            $menu_links[$i]['priority'] = $menu->priority;
            $menu_links[$i]['changefreq'] = $menu->changefreq;

            $i++;
          }
        }
      }
    }

    return $menu_links;
  }

  /**
   * Get links from vocab types.
   *
   * @param array $vocabs
   *   Having vocab details.
   *
   * @return array
   *   Having vocab links.
   */
  public function multiple_sitemap_get_vocab_links($vocabs = []) {

    $vocab_links = [];
    $conn = \Drupal::database();

    $i = 0;

    if (!empty($vocabs)) {
      foreach ($vocabs as $vocab) {
        $type = $vocab->vocab_type;

        // Get the all terms for given type.
        $query = $conn->select('taxonomy_term_data', 't');
        $query->fields('t', ['tid']);
        $query->condition('vid', $type);

        $results = $query->execute();

        if (!empty($results)) {
          foreach ($results as $term) {
            $path = \Drupal::service('path.alias_manager')->getAliasByPath('/taxonomy/term/' . $term->tid);
            $path = Url::fromUserInput($path)->setAbsolute()->toString();
            $vocab_links[$i]['link'] = $path;
            $vocab_links[$i]['priority'] = $vocab->priority;
            $vocab_links[$i]['changefreq'] = $vocab->changefreq;

            $i++;
          }
        }
      }
    }

    return $vocab_links;
  }

  /**
   * Delete xml file by it's id.
   *
   * @param int $ms_id
   *
   * @return bool
   *   TRUE or FALSE.
   */
  public function delete_multiple_sitemap_xml_file($ms_id) {

    if (empty($ms_id)) {
      return FALSE;
    }

    $conn = \Drupal::database();
    $records = [];
    $query = $conn->select('multiple_sitemap', 'ms');
    $query->fields('ms', ['file_name']);
    $query->condition('ms_id', $ms_id, '=');
    $results = $query->execute();
    $filename = $results->fetchCol();
    $filename = !empty($filename) ? $filename[0] : NULL;
    if (isset($filename)) {
      $dir_name = 'public://multiple_sitemap';
      $file_name = $dir_name . '/' . $filename . '.xml';
      unlink($file_name);

      return TRUE;
    }

    return FALSE;
  }

}
