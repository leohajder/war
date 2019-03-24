<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\War;
use App\Entity\Army;
use App\Entity\Soldier;
use App\Entity\Battle;
use App\Entity\BattleOutcome;

class DefaultController extends AbstractController
{
    /**
     * Displays app index
     */
    public function index(Request $request)
    {
        /** @var int $army1count */
        $army1count = $request->query->getInt('army1');
        /** @var int $army2count */
        $army2count = $request->query->getInt('army2');

        if (0 >= $army1count || 0 >= $army2count) {
            return $this->render('default/instructions.html.twig');
        }

        $war = new War();
        $this->getDoctrine()->getManager()->persist($war);
        $this->getDoctrine()->getManager()->flush();

        $army1 = $this->createArmy('army1', $army1count);
        $army2 = $this->createArmy('army2', $army2count);
        $war->addArmy($army1);
        $war->addArmy($army2);
        $this->getDoctrine()->getManager()->persist($army1);
        $this->getDoctrine()->getManager()->persist($army2);
        $this->getDoctrine()->getManager()->flush();

        while ($this->warIsRaging($war)) {
            $battle = new Battle();

            $soldier1 = $this->getLiveSoldier($army1);
            $soldier2 = $this->getLiveSoldier($army2);

            $result = $this->calculateFight($soldier1, $soldier2);
            if (0 <= $result) {
                $outcome1 = new BattleOutcome();
                $outcome1
                    ->setSoldier($soldier1)
                    ->setOutcome('survived');
                $battle->addOutcome($outcome1);
                $soldier1->setExperience($soldier1->getExperience() + 1);

                $outcome2 = new BattleOutcome();
                $outcome2
                    ->setSoldier($soldier2)
                    ->setOutcome('killed by soldier');
                $battle->addOutcome($outcome2);
                $soldier2->setAlive(false);
            } else {
                $outcome1 = new BattleOutcome();
                $outcome1
                    ->setSoldier($soldier1)
                    ->setOutcome( 'killed by soldier');
                $soldier1->setAlive(false);

                $outcome2 = new BattleOutcome();
                $outcome2
                    ->setSoldier($soldier2)
                    ->setOutcome('survived');
                $battle->addOutcome($outcome2);
                $soldier2->setExperience($soldier2->getExperience() + 1);
            }

            $this->getDoctrine()->getManager()->persist($soldier1);
            $this->getDoctrine()->getManager()->persist($soldier2);
            $this->getDoctrine()->getManager()->persist($outcome1);
            $this->getDoctrine()->getManager()->persist($outcome2);

            $battle->addOutcome($outcome1);
            $battle->addOutcome($outcome2);

            $this->getDoctrine()->getManager()->persist($battle);

            $war->addBattle($battle);
            
            $this->getDoctrine()->getManager()->flush();
        }

        dump($this->getLiveSoldier($army1), $this->getLiveSoldier($army2)); exit;

        return $this->render('default/index.html.twig', [
            'army1' => $army1,
            'army2' => $army2,
        ]);
    }

    private function createArmy(string $tag, int $count)
    {
        $army = new Army();
        $army->setTag($tag);

        for($i = 0; $i < $count; $i++) {
            $soldier = new Soldier();
            $soldier
                ->setExperience(rand(0, 10))
                ->setAlive(true);

            $this->getDoctrine()->getManager()->persist($soldier);

            $army->addSoldier($soldier);
        }

        return $army;
    }

    private function warIsRaging(War $war)
    {
        foreach ($war->getArmies() as $army) {
            $liveSoldier = $this->getLiveSoldier($army);

            if (null === $liveSoldier) {
                return false;
            }
        }

        return true;
    }

    private function getLiveSoldier(Army $army) 
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Soldier::class);

        return $repository->findOneBy([
            'alive' => true,
            'army' => $army,
        ]);
    }

    private function calculateFight(Soldier $soldier1, Soldier $soldier2)
    {
        $exp1 = $soldier1->getExperience();
        $exp1 += $this->getAdrenalineBoost($exp1);
        $exp2 = $soldier2->getExperience();
        $exp2 += $this->getAdrenalineBoost($exp2);

        return $exp1 - $exp2;
    }

    private function getAdrenalineBoost(int $experience)
    {
        return rand(0, $experience / 4) + rand(-2, 2);
    }
}