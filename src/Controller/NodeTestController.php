<?php
/**
 * @file
 * Contains \Drupal\site_api_key\Controller\NodeTestController
 * custom menu to check siteapikey and node id (/page_json/{site_api_key}/{nid})
 */
namespace Drupal\site_api_key\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for Node test.
 */
class NodeTestController extends ControllerBase {
  /**
   * @param $site_api_key - the API key parameter
   * @param $nid - node id parameter
   * @return JsonResponse
   */
  public function nodetest($site_api_key, $nid) {
    // Site API Key saved value
    $site_api_key_saved = \Drupal::config('site_api_key.settings')->get('siteapikey');
		
		// node load
		$node = \Drupal\node\Entity\Node::load($nid);

    // check whether node is of type page, the site api key is set and matches the supplied key
    if(!empty($node) && $node->bundle() == 'page' && $site_api_key_saved != ''
      && $site_api_key_saved == $site_api_key) {
      // Respond with the json representation of the node
      return new JsonResponse($node->toArray(), 200, ['Content-Type'=> 'application/json']);
    } else {
			// Respond with access denied
      return new JsonResponse(array("error" => "access denied"), 401,
        ['Content-Type'=> 'application/json']);
		}
  }
}