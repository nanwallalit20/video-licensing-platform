<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Send return response when Ajax call on form submit
     *
     * @param boolean $status This will be true or false.
     * @param string $url This is the page Url where to redirect after form submit successfully.
     * @param string $message This is to show message on error.
     * @param array $data Just in case if you want to send some data in return.
     * @param array $function This function will call in javascript like function(param) (this is your custom function and param will be data you return).
     * @return array    This will return all param detail with array.
     *
     * */
    protected function sendResponse($status, $url = '', $message = '', $data = [], $function = '') {
        return [
            'status' => $status,
            'url' => $url,
            'message' => $message,
            'data' => $data,
            'function' => $function
        ];
    }
}
