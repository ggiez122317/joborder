<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{

    protected $data;
    protected $module;
    protected $controller;
    protected $page;
    protected $view_path;

    public function __construct()
    {
        $this->module       = 'Login';
        $this->controller   = 'login';
        $this->page         = '';
        $this->view_path    = 'modules/'.strtolower(str_replace(" ", "_", $this->module));  
    }

    private function _setVariables()
    {
        $this->data['controller'] = $this->controller;
        $this->data['title'] = $this->module;
        $this->data['page'] = $this->page;
    }


    // ******************** Views ********************
    public function index()
    {

        // $encrypted = Crypt::encryptString('12345');
        // $decrypted = Crypt::decryptString($encrypted);

        // var_dump($encrypted);
        // var_dump($decrypted);
        // die(); 

        // initialize variables
        $this->page = '';
        $this->_setVariables();
        $data = $this->data;


        return view($this->view_path."/".($this->page?strtolower($this->page):'index'), $data);

    }

}
