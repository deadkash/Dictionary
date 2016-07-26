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
     * Start test session
     *
     * @Route("/start")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function startAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $username = (isset($data['username'])) ? $data['username'] : false;

        if (!$username) {
            throw new \Exception('User undefined');
        }

        $this->get('app')->startSession($username);
        return new JsonResponse('ok');
    }

    /**
     * Returns random word set
     *
     * @Route("/word_set")
     * @Method("GET")
     */
    public function getWordSetAction()
    {
        return new JsonResponse($this->get('app')->randomSet());
    }

    /**
     * Check if user choice is correct
     *
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

    /**
     * Save test results
     *
     * @Route("/save_result")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function saveResultAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $username = (isset($data['username'])) ? $data['username'] : '';
        $scores = (isset($data['score'])) ? $data['score'] : 0;
        $errors = (isset($data['errors'])) ? $data['errors'] : 0;

        $this->get('app')->saveScores($username, $scores, $errors);

        return new JsonResponse('ok');
    }
}