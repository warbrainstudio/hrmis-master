<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');
require_once(FCPATH . 'vendor/autoload.php');

use GuzzleHttp\Client;

class ApiClient extends AppBackend
{
  private $apiUrl;
  private $apiUser;
  private $apiPassword;

  function __construct()
  {
    parent::__construct();

    $app = $this->app(); // Get config item from table setting

    $this->apiUrl = (isset($app->api_url)) ? $app->api_url : null;
    $this->apiUser = (isset($app->api_user)) ? $app->api_user : null;
    $this->apiPassword = (isset($app->api_password)) ? $app->api_password : null;
    $this->courseId = (isset($app->sinkronisasi_course_id)) ? $app->sinkronisasi_course_id : null;
  }

  private function _response($request)
  {
    $responseCode = $request->getStatusCode();
    $responseBody = $request->getBody()->getContents();

    if (in_array((int) $responseCode, [200, 201])) {
      $responseBodyDecode = json_decode($responseBody);
      $responseBody = (JSON_ERROR_NONE !== json_last_error()) ? $responseBody : $responseBodyDecode;

      $response = array(
        'status' => true,
        'data' => $responseBody
      );
    } else {
      $response = $this->_responseError($responseBody);
    };

    return $response;
  }

  private function _responseError($throw = null)
  {
    $response = array(
      'status' => false,
      'data' => 'Failed while requesting the data.',
      // 'error' => $throw // Uncomment this for development mode
    );

    return $response;
  }

  public function testAuth()
  {
    $endPoint = 'wp/v2/users/me';

    try {
      $client = new \GuzzleHttp\Client();
      $request = $client->request('GET', $this->apiUrl . $endPoint, [
        'auth' => [$this->apiUser, $this->apiPassword]
      ]);

      $responseCode = $request->getStatusCode();

      if (in_array((int) $responseCode, [200, 201])) {
        $response = 'OK, user is active.';
      };
    } catch (\Throwable $th) {
      $response = 'Failed to get user credential.';
    };

    return $response;
  }

  public function sample_delete($id)
  {
    $endPoint = 'api/v2/xxx';

    try {
      $client = new \GuzzleHttp\Client();
      $client->request('DELETE', $this->apiUrl . $endPoint, [
        'auth' => [$this->apiUser, $this->apiPassword]
      ]);

      $response = true;
    } catch (\Throwable $th) {
      $response = true;
    };

    return $response; // Just true when success or failed, we don't care about that
  }

  public function sample_post($id, $field1)
  {
    $endPoint = 'api/v2/xxx';

    try {
      $client = new \GuzzleHttp\Client();
      $request = $client->request('POST', $this->apiUrl . $endPoint, [
        'auth' => [$this->apiUser, $this->apiPassword],
        'form_params' => [
          'field1' => $field1,
        ]
      ]);

      $response = $this->_response($request);
    } catch (\Throwable $th) {
      $response = $this->_responseError($th);
    };

    return $response;
  }

  public function sample_get()
  {
    $endPoint = 'api/v2/xxx';

    try {
      $client = new \GuzzleHttp\Client();
      $request = $client->request('GET', $this->apiUrl . $endPoint, [
        'auth' => [$this->apiUser, $this->apiPassword]
      ]);

      $responseCode = $request->getStatusCode();

      if (in_array((int) $responseCode, [200, 201])) {
        $response = $this->_response($request);
      };
    } catch (\Throwable $th) {
      $response = $this->_responseError($th);
    };

    return $response;
  }
}
