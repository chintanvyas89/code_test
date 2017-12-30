<?php

namespace Drupal\code_test\Controller;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class GetNode.
 *
 * @package Drupal\code_test\Controller
 */
class GetNode extends ControllerBase {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Constructs a new GetNode object.
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * index method.
   *
   * @var $apikey
   *   Api key stored in config.
   *
   * @var $nid
   *   nid of the node.
   *
   * @return string
   *   Return Json response with node data with 200 or 403 if do not satisfy the
   *   conditions.
   */
  public function index($apikey, $nid) {
    $access = false;

    // Load the node from nid passed in url. Check for node type and apikey. If
    // they match then grant access.
    if(is_numeric($nid)) {
      $node_obj = Node::load($nid);
      $stored_apikey = $this->configFactory->get('system.site')->get('siteapikey');

      if($node_obj->getType() == 'page' && $stored_apikey == $apikey) {
        $access = true;
      }
    }

    // If all conditions satisfy return appropriate json response.
    if($access == true) {
      // Load node and return JsonResponse.
      $node_obj = Node::load($nid);
      // Convert node object to array.
      $ret_data = $node_obj->toArray();

      // Return json response of the page node.
      return new JsonResponse($ret_data, 200, array('Content-Type', 'application/json'));
    }
    else {
      // Return access denied response with status code 403 Forbidden.
      return new JsonResponse('access denied', 403, array('Content-Type', 'application/json'));
    }

  }

}
