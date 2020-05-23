<?php

namespace Drupal\Tests\legacy_redirect\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests a request is redirected.
 *
 * @group legacy_redirect
 */
class LegacyRedirectTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['legacy_redirect'];

  /**
   * Get information about the test.
   */
  public static function getInfo() {
    return [
      'name' => 'Legacy Redirects',
      'description' => 'Test that q=path/to/page requests are properly redirected.',
      'group' => 'Legacy Redirect',
    ];
  }

  /**
   * Test that a request to a q=path/to/page properly redirects.
   */
  public function testLegacyRedirects() {
    global $base_url;

    // Test with q as sole query parameter.
    $this->drupalGet(NULL, ['query' => ['q' => 'user/register']]);
    $this->assertEqual($base_url . '/index.php/user/register', $this->getAbsoluteUrl($this->getUrl()));
    $this->assertText('Create new account');

    // Test with additional query parameters.
    $this->drupalGet(NULL, ['query' => ['q' => 'user/register', 'a' => 'b']]);
    $this->assertEqual($base_url . '/index.php/user/register?a=b', $this->getAbsoluteUrl($this->getUrl()));
    $this->assertText('Create new account');
  }

}
