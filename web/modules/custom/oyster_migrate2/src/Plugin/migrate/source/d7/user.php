<?php

namespace Drupal\oyster_migrate2\Plugin\migrate\source\d7;

use Drupal\user\Plugin\migrate\source\d7\User as D7User;

/**
 * Fetch only users that have authored a fish post.
 * 
 * Tutorial at https://drupalize.me/tutorial/customize-existing-source-plugin?p=2578
 *
 * Rather than start from scratch we extend the Drupal\user\Plugin\migrate\source\d7\User
 * class and override the query method. Then change the query that gets used to
 * select only users who have authored one or more fish posts.
 *
 * @MigrateSource(
 *   id = "custom_d7_user",
     source_module = "user"
 * )
 */
class User extends D7User {

  /**
   * Override the query() method, and provide a custom query that selects just
   * the users we're interested in.
   */
  public function query() {
    // Our custom query returns the set of fields as
    // Drupal\user\Plugin\migrate\source\d7\User::query(), but contains some
    // extra logic. Including a join to the node table, and a new condition that
    // effectively limits the rows returned to only those users who have
    // authored one-or-more fish posts.
    $query = $this->select('users', 'u');
    $query->join('node', 'n', 'n.uid = u.uid');
    $query->fields('u');
    $query->distinct()
      ->condition('n.type', 'fish', '=');
    return $query;
  }
}