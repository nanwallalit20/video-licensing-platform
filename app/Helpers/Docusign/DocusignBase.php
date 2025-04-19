<?php

namespace App\Helpers\Docusign;

use GuzzleHttp\Client;
use App\Models\Docusign;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Session;

class DocusignBase {

    protected $accountId;

    private $integration_key;
    private $secret_key;

    private string|null $redirect_uri = null;

    public function __construct() {
        $this->redirect_uri = route('docusign-authorized-user');
        $this->accountId = env('DOCUSIGN_ACCOUNT_ID');
        $this->secret_key = env('DOCUSIGN_SECRET_KEY');
        $this->integration_key = env('DOCUSIGN_INTEGRATION_KEY');
    }

    public function authenticateDocusign() {
        $state = uniqid();
        Session::put('docusign_auth_state', $state);
        $scopes = [
            'dtr.documents.read', 'dtr.documents.write', 'signature', 'click.manage', 'click.send', 'content',
            'spring_read', 'spring_write', 'room_forms', 'webforms_read', 'webforms_instance_read',
            'webforms_instance_write'
        ];

        $auth_url = env('DOCUSIGN_AUTH_URL');
        $params = [
            "response_type" => "code",
            "scope" => implode(" ", $scopes),
            "client_id" => $this->integration_key,
            "state" => $state,
            "redirect_uri" => $this->redirect_uri,
        ];

        $url = $auth_url . "?" . http_build_query($params);
        return redirect($url);
    }

    public function authorizedUser() {

        $input_state = request()->input('state');
        $code = request()->input('code');

        $state = Session::get('docusign_auth_state');
        Session::remove('docusign_auth_state');
        if (!$state || $input_state != $state) {
            abort(403);
        }

        $token_url = env('DOCUSIGN_TOKEN_URL');
        $authToken = base64_encode($this->integration_key . ":" . $this->secret_key);

        $client = new Client();
        $response = $client->request('POST', $token_url, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . $authToken,
            ],
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
            ],
        ]);

        $content = json_decode($response->getBody()->getContents());
        $this->saveToken($content);
    }

    private function refreshToken() {
        $obj = $this->getSavedToken();

        $refresh_token = $obj->refresh_token;

        $refreshTokenUrl = env('DOCUSIGN_TOKEN_URL');
        $authToken = base64_encode($this->integration_key . ":" . $this->secret_key);
        $client = new Client();
        $response = $client->request('POST', $refreshTokenUrl, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . $authToken,
            ],
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
            ],
        ]);

        $content = json_decode($response->getBody()->getContents());
        $this->saveToken($content);
    }

    private function saveToken($obj) {
        Docusign::updateOrCreate(
            ['id' => 1], // Check if editing an existing title
            [
                ...(array)$obj,
            ]
        );
    }

    private function getSavedToken() {
        return Docusign::find(1);
    }

    protected function getToken() {
        $obj = $this->getSavedToken();
        if (!$obj) {
            return null;
        }
        $expires_in = $obj->expires_in;
        $updated_at = $obj->updated_at;
        $currentTime = date('Y-m-d H:i:s');
        $secondDifference = Helpers::differenceBetweenDateTime($updated_at, $currentTime);
        if ($secondDifference >= $expires_in) {
            $this->refreshToken();
            $obj = $this->getSavedToken();
        }
        return $obj;
    }

}
