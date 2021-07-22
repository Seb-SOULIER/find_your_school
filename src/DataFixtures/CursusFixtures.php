<?php

namespace App\DataFixtures;

use App\Entity\Cursus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CursusFixtures extends Fixture
{
    private const CURSUS = [
        ['PS','Ecole'],
        ['MS','Ecole'],
        ['GS','Ecole'],
        ['CP','Ecole'],
        ['CE1','Ecole'],
        ['CE2','Ecole'],
        ['CM1','Ecole'],
        ['CM2','Ecole'],
        ['6EME','Collège'],
        ['5EME','Collège'],
        ['4EME','Collège'],
        ['3EME','Collège'],
        ['SECONDE','Lycée'],
        ['PREMIERE','Lycée'],
        ['TERMINALE','Lycée'],
    ];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < count(self::CURSUS); $i++) {
            $cursus = new Cursus();
            $cursus->setName(self::CURSUS[$i][0]);
            $cursus->setEtablissement(self::CURSUS[$i][1]);
            $manager->persist($cursus);
        }
        $manager->flush();
    }
}
