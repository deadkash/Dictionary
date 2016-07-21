<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiController
 * @package AppBundle\Controller
 * @Route("/api/v1")
 */
class ApiController extends Controller
{

    /**
     * @Route("/start")
     * @Method("GET")
     * @return JsonResponse
     */
    public function startAction()
    {
        $this->get('app')->startSession();
        return new JsonResponse('ok');
    }

    /**
     * @Route("/word_set")
     * @Method("GET")
     */
    public function getWordSetAction()
    {
        return new JsonResponse($this->get('app')->randomSet());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @Route("/check_word")
     * @Method("POST")
     */
    public function checkWordAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        
        $word = (isset($data['word'])) ? $data['word'] : false;
        $choice = (isset($data['choice'])) ? $data['choice'] : false;
        $type = (isset($data['type'])) ? $data['type'] : false;
        
        if (!$word || !$choice || !$type) {
            throw new \Exception('Wrong data');
        }
        
        return new JsonResponse(array('result' => $this->get('app')->checkWord($word, $choice, $type)));
    }
}