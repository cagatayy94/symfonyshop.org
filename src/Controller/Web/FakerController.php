<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Faker\Factory as FakerFactory;

class FakerController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function hello(Request $request)
    {
        $ipAddress = $request->getClientIp();

        $fakerFactory = FakerFactory::create();

        try {

            $data['ip'] = $ipAddress;

            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;
            $data['uuid'] = $fakerFactory->uuid;

            dd();

            return new JsonResponse([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage()
                ]
            ]);
        }
    }
}
