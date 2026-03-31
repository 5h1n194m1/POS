<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 * class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    
    // Properti session dibuka agar bisa diakses oleh controller anak
    protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do not edit this line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        
        // Mengaktifkan session secara global
        $this->session = \Config\Services::session();
        
        // Membuka helper agar fungsi-fungsi dasar seperti base_url() tersedia otomatis
        $this->helpers = ['form', 'url'];
    }
}