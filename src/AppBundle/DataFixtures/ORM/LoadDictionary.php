<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Dictionary;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDictionary implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $item = new Dictionary();
        $item->setOriginal('apple');
        $item->setTranslate('яблоко');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('peach');
        $item->setTranslate('персик');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('orange');
        $item->setTranslate('апельсин');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('grape');
        $item->setTranslate('виноград');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('lemon');
        $item->setTranslate('лимон');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('pineapple');
        $item->setTranslate('ананас');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('coconut');
        $item->setTranslate('кокос');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('banana');
        $item->setTranslate('банан');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('pomelo');
        $item->setTranslate('помело');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('strawberry');
        $item->setTranslate('клубника');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('raspberry');
        $item->setTranslate('малина');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('melon');
        $item->setTranslate('дыня');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('apricot');
        $item->setTranslate('абрикос');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('mango');
        $item->setTranslate('манго');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('pomegranate');
        $item->setTranslate('гранат');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('cherry');
        $item->setTranslate('вишня');
        $manager->persist($item);

        $item = new Dictionary();
        $item->setOriginal('pear');
        $item->setTranslate('груша');
        $manager->persist($item);

        $manager->flush();
    }
}