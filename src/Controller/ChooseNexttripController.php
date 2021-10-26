<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\NextTripService;


class ChooseNexttripController extends AbstractController
{

    private $nextTripService;

    public function __construct()
    {
        $this->nextTripService = new NextTripService();
    }


    /**
     * @Route("/chooseNextTrip", name="choose_nexttrip", methods={"POST"})
     */
    public function GetNextTrip(Request $request): Response {


        $town = $request->query->get('town1');

        $town2 = $request->query->get('town2');

        if(!empty($town) && !empty($town2)){
            
            try{
                $nextTrip = $this->nextTripService->getNextTrip($town,$town2);
            }
            catch (Exception $e){
                error_log($e->getMessage(),0);

                return $this->json([
                    'message' => 'Error : Can not get the weather datas',
                    'status' => '500',
                ],500);
            }
            
            return $this->json($nextTrip);

        }
        else{
            return $this->json([
                'message' => 'Error: Must be 2 town in input',
                'status' => '400',
            ],400);
        }
    }

        
}
