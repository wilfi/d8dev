<?php
/**
 * @file
 * Contains \Drupal\multiple_sitemap\Form\MultipleSitemapFormDelete.
 */

namespace Drupal\multiple_sitemap\Forms;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a confirmation form for deleting multisitemap record.
 */
class MultipleSitemapFormDelete extends ConfirmFormBase {

  /**
   * The ID of the item to delete.
   *
   * @var string
   */
  protected $id;

  /**
   * The sitemap db.
   *
   * @var \Drupal\multiple_sitemap\MultipleSitemapDB
   */
  protected $sitemapDb;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * MultipleSitemapFormDelete class constructor.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param string $sitemap_db
   *   The sitemap_db string.
   */
  public function __construct(MessengerInterface $messenger, $sitemap_db)
  {
    $this->messenger = $messenger;
    $this->sitemapDb = $sitemap_db;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('multiple_sitemap.db')
    );
  }

  /**
   * {@inheritdoc}.
   */
  public function getFormId()
  {
    return 'multiplesitemap_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    //the question to display to the user.
    return t('Do you want to delete %id?', array('%id' => $this->id));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    //this needs to be a valid route otherwise the cancel link won't appear
    return new Url('multiple_sitemap.dashboard');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    //a brief desccription
    return t('Only do this if you are sure!');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete it Now!');
  }


  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return $this->t('Cancel');
  }

  /**
   * {@inheritdoc}
   *
   * @param int $id
   *   (optional) The ID of the item to be deleted.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $ms_id = NULL) {

    $this->id = $ms_id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $conn = \Drupal::database();
    $ms_id = $this->id;
    $obj = $this->sitemapDb;

    // Delete xml file
    $obj->delete_multiple_sitemap_xml_file($ms_id);

    $deleted = $conn->delete('multiple_sitemap')
    ->condition('ms_id', $ms_id)
    ->execute();

    $record_types = array('content', 'menu', 'vocab');

    foreach ($record_types as $record_type) {
      $obj->multiple_sitemap_delete_sub_record($record_type, $ms_id);
    }

    $this->messenger->addMessage($this->t('Record deleted successfully'), $this->messenger::TYPE_STATUS);
    $form_state->setRedirect('multiple_sitemap.dashboard');
  }
}
