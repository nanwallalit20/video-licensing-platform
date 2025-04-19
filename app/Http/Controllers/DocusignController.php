<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Helpers\Docusign\DocusignHelper;
use Illuminate\Support\Facades\Session;

class DocusignController extends Controller {

    private $docusign;

    public function __construct() {
        $this->docusign = new DocusignHelper();
    }

    public function authenticateDocusign() {
        if (auth()->user()->getUserProfile->role_id->value != Roles::Superadmin->value) {
            abort(403);
        }
        return $this->docusign->authenticateDocusign();
    }

    public function authorizedUser() {
        $this->docusign->authorizedUser();
        Session::flash('success', 'Docusign Authorized');
        return redirect()->route('dashboard');
    }
}
