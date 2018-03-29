<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityRepository;

use App\Entity\Record;
use App\Repository\RecordRepository;


class FrontController extends Controller {

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request):Response
    {
        try {
            $repository = $this->container
                ->get('doctrine.orm.default_entity_manager')
                ->getRepository('App\Entity\Record');

            return $this->render('base.html.twig', ['records' => $repository->findAll() ]);
        } catch ( \Exception $e ) {
            return new Response($e->getMessage(), 500);
        }
        //return [200, 'Hello World'];
        
    }

    /**
     * @Route("/record/{id}", name="get_record")
     */
    public function record(Request $request, int $id):Response
    {
        try {
            $repository = $this->container
                ->get('doctrine.orm.default_entity_manager')
                ->getRepository('App\Entity\Record');

            return $this->render('record.html.twig', ['record' => $repository->find($id) ]);
        } catch ( \Exception $e ) {
            return new Response($e->getMessage(), 500);
        }
    }

    /**
     * @Route("/404", name="error_404")
     */
    public function error404(Request $request):Response
    {
        
        return new Response('No page found.', 404);
    }
}