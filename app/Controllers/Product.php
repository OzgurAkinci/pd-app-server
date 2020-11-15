<?php namespace App\Controllers;

use App\Models\Product_model;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Psr\Log\LoggerInterface;

class Product extends ResourceController
{
	protected $format = 'json';
	/**
	 * @var Product_model
	 */
	private $m;

	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->m = new Product_model();
    }

	public function list()
	{
	    try{
            $data = $this->m->findAll($offset = 0, $limit = 10);
            return $this->response->setJSON($data);
        }catch (\Exception $e){
            die('Error: '.$e->getMessage());
        }
	}

}
