<?php

namespace AppBundle\Service;


use AppBundle\Entity\Dictionary;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppService
{
    const SET_SIZE = 3;
    const PART_ORIGINAL = 'original';
    const PART_TRANSLATE = 'translate';
    const USED_WORDS_SESSION_KEY = 'used_words';

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
     * @return mixed
     */
    public function getOriginalOrTranslate()
    {
        $choices = [self::PART_ORIGINAL, self::PART_TRANSLATE];
        return $choices[array_rand($choices)];
    }

    /**
     * @return void
     */
    public function startSession()
    {
        $this->container->get('session')->set(self::USED_WORDS_SESSION_KEY, array());
    }

    /**
     * @param Dictionary $word
     */
    public function rememberWord(Dictionary $word)
    {
        $session = $this->container->get('session');
        $used = $session->get(self::USED_WORDS_SESSION_KEY, array());
        $used[] = $word->getId();
        $session->set(self::USED_WORDS_SESSION_KEY, $used);
    }

    /**
     * @return mixed
     */
    public function getUsedWords()
    {
        $session = $this->container->get('session');
        return $session->get(self::USED_WORDS_SESSION_KEY, array());
    }

    /**
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
        if (!$randomWord) return false;

        $this->rememberWord($randomWord);
        $otherWords = $dictionaryRepo->getOtherWords($randomWord, self::SET_SIZE);
        
        $dictionaryChoices = array_merge([$randomWord], $otherWords);
        shuffle($dictionaryChoices);

        $type = $this->getOriginalOrTranslate();
        $choices = [];

        if ($type == self::PART_ORIGINAL) {

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
        else if ($type == self::PART_TRANSLATE) {

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

        if ($type == self::PART_TRANSLATE) {
            $dictionary = $em->getRepository('AppBundle:Dictionary')->findOneBy(array(
                'translate' => $word, 'original' => $choice));

            return (bool) $dictionary;
        }
        else if ($type == self::PART_ORIGINAL) {
            $dictionary = $em->getRepository('AppBundle:Dictionary')->findOneBy(array(
                'translate' => $choice, 'original' => $word));

            return (bool) $dictionary;
        }
        else {
            throw new \Exception('Unknown type');
        }

    }
}