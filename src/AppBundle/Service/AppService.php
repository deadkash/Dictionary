<?php

namespace AppBundle\Service;


use AppBundle\Entity\Dictionary;
use AppBundle\Entity\Scores;
use AppBundle\Entity\WrongChoice;
use AppBundle\TestSession;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Tests\Templating\TemplateTest;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppService
{
    //Quantity of random words in set
    const SET_SIZE = 3;

    const TYPE_ORIGINAL = 'original';
    const TYPE_TRANSLATE = 'translate';
    const TEST_SESSION_KEY = 'test_session';

    /** @var ContainerInterface */
    private $container;

    /**
     * AppService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns random word set type (original or translate)
     * @return mixed
     */
    public function getOriginalOrTranslate()
    {
        $choices = [self::TYPE_ORIGINAL, self::TYPE_TRANSLATE];
        return $choices[array_rand($choices)];
    }

    /**
     * Start test session
     * @param $username
     */
    public function startSession($username)
    {
        $this->container->get('session')->set(self::TEST_SESSION_KEY, new TestSession($username));
    }

    /**
     * Returns test session
     * @return mixed|TestSession
     */
    private function getSession()
    {
        return $this->container->get('session')->get(self::TEST_SESSION_KEY);
    }

    /**
     * Save test session
     * @param TestSession $session
     */
    private function setSession($session)
    {
        $this->container->get('session')->set(self::TEST_SESSION_KEY, $session);
    }

    /**
     * Save used word in a session
     * @param Dictionary $word
     * @throws \Exception
     */
    public function rememberWord(Dictionary $word)
    {
        /** @var TestSession $session */
        $session = $this->getSession();
        if (!$session) {
            throw new \Exception('Session not found');
        }

        $session->addWord($word->getId());
        $this->setSession($session);
    }

    /**
     * Returns array of used word in current test session
     * @return mixed
     * @throws \Exception
     */
    public function getUsedWords()
    {
        $session = $this->getSession();
        if (!$session) {
            throw new \Exception('Session not found');
        }

        return $session->getWords();
    }

    /**
     * Returns random word set
     * @return array|bool
     * @throws \Exception
     */
    public function randomSet()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();
        $dictionaryRepo = $em->getRepository('AppBundle:Dictionary');

        $usedWords = $this->getUsedWords();
        $randomWord = $dictionaryRepo->getRandomWord($usedWords);

        //Test is over if random word is false (Unused words not found)
        if (!$randomWord) {
            return false;
        }

        $this->rememberWord($randomWord);
        $otherWords = $dictionaryRepo->getOtherWords($randomWord, self::SET_SIZE);
        
        $dictionaryChoices = array_merge([$randomWord], $otherWords);
        shuffle($dictionaryChoices);

        $type = $this->getOriginalOrTranslate();
        $choices = [];

        if ($type == self::TYPE_ORIGINAL) {

            /** @var Dictionary $dictionaryChoice */
            foreach ($dictionaryChoices as $dictionaryChoice) {
                $choices[] = $dictionaryChoice->getTranslate();
            }
            
            return [
                'type'      => $type,
                'word'      => $randomWord->getOriginal(),
                'choices'   => $choices
            ];
        }
        else if ($type == self::TYPE_TRANSLATE) {

            /** @var Dictionary $dictionaryChoice */
            foreach ($dictionaryChoices as $dictionaryChoice) {
                $choices[] = $dictionaryChoice->getOriginal();
            }

            return [
                'type'      => $type,
                'word'      => $randomWord->getTranslate(),
                'choices'   => $choices
            ];
        }
        else {
            throw new \Exception('Unknown type');
        }
    }

    /**
     * Calls if user choice is correct
     * @return void
     */
    private function onValidResult()
    {
        $session = $this->getSession();
        $session->addValid();
        $this->setSession($session);
    }

    /**
     * Calls if user choice is wrong
     * @param string $word
     * @param string $choice
     * @param string $type
     */
    private function onInvalidResult($word, $choice, $type)
    {
        $session = $this->getSession();
        $session->addError();
        $this->setSession($session);

        $this->saveWrongChoice($session->getUsername(), $word, $choice, $type);
    }

    /**
     * Check user choice
     *
     * @param string $word
     * @param string $choice
     * @param string $type
     * @return bool
     * @throws \Exception
     */
    public function checkWord($word, $choice, $type)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $result = false;

        if ($type == self::TYPE_TRANSLATE) {
            $dictionary = $em->getRepository('AppBundle:Dictionary')->findOneBy(array(
                'translate' => $word, 'original' => $choice));

            $result = (bool) $dictionary;
        }
        else if ($type == self::TYPE_ORIGINAL) {
            $dictionary = $em->getRepository('AppBundle:Dictionary')->findOneBy(array(
                'translate' => $choice, 'original' => $word));

            $result = (bool) $dictionary;
        }

        if ($result) {
            $this->onValidResult();
        }
        else {
            $this->onInvalidResult($word, $choice, $type);
        }

        return $result;
    }

    /**
     * Saving wrong choice in the Database
     *
     * @param $user
     * @param $word
     * @param $choice
     * @param $type
     * @return WrongChoice
     */
    public function saveWrongChoice($user, $word, $choice, $type)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $wrongChoice = new WrongChoice();
        $wrongChoice->setUsername($user);
        $wrongChoice->setWord($word);
        $wrongChoice->setChoice($choice);
        $wrongChoice->setType($type);
        $wrongChoice->setCreatedAt(new \DateTime());
        $em->persist($wrongChoice);
        $em->flush($wrongChoice);

        return $wrongChoice;
    }

    /**
     * Save user scores in the Database
     * @param string $username
     * @param int $score
     * @param int $errors
     * @return Scores
     */
    public function saveScores($username, $score, $errors)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $scores = new Scores();
        $scores->setCreatedAt(new \DateTime());
        $scores->setUsername($username);
        $scores->setScore($score);
        $scores->setErrors($errors);
        $em->persist($scores);
        $em->flush($scores);

        return $scores;
    }
}