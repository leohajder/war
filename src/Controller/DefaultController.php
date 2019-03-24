<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\War;
use App\Entity\Army;
use App\Entity\Soldier;
use App\Entity\Battle;
use App\Entity\BattleOutcome;
use App\Entity\WarOutcome;

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

            $soldier1 = $this->findLiveSoldier($army1, true);
            $soldier2 = $this->findLiveSoldier($army2, true);

            $result = $this->calculateFight($soldier1, $soldier2);
            $battleOutcome1 = new BattleOutcome();
            $battleOutcome2 = new BattleOutcome();

            if (0 <= $result) {
                $battleOutcome1
                    ->setSoldier($soldier1)
                    ->setOutcome('survived');
                $battle->addOutcome($battleOutcome1);
                $soldier1->setExperience($soldier1->getExperience() + 1);

                $battleOutcome2
                    ->setSoldier($soldier2)
                    ->setOutcome('killed');
                $battle->addOutcome($battleOutcome2);
                $soldier2->setAlive(false);
            } else {
                $battleOutcome1
                    ->setSoldier($soldier1)
                    ->setOutcome('killed');
                $soldier1->setAlive(false);

                $battleOutcome2
                    ->setSoldier($soldier2)
                    ->setOutcome('survived');
                $battle->addOutcome($battleOutcome2);
                $soldier2->setExperience($soldier2->getExperience() + 1);
            }

            $this->getDoctrine()->getManager()->persist($soldier1);
            $this->getDoctrine()->getManager()->persist($soldier2);
            $this->getDoctrine()->getManager()->persist($battleOutcome1);
            $this->getDoctrine()->getManager()->persist($battleOutcome2);

            $battle->addOutcome($battleOutcome1);
            $battle->addOutcome($battleOutcome2);

            $this->getDoctrine()->getManager()->persist($battle);

            $war->addBattle($battle);
            
            $this->getDoctrine()->getManager()->flush();
        }

        $warOutcome1 = new WarOutcome();
        $warOutcome2 = new WarOutcome();
        if ($this->findLiveSoldier($army1)) {
            $warOutcome1
                ->setArmy($army1)
                ->setWar($war)
                ->setOutcome('won');

            $warOutcome2
                ->setArmy($army2)
                ->setWar($war)
                ->setOutcome('lost');
        } elseif ($this->findLiveSoldier($army2)) {
            $warOutcome1
                ->setArmy($army1)
                ->setWar($war)
                ->setOutcome('lost');

            $warOutcome2
                ->setArmy($army2)
                ->setWar($war)
                ->setOutcome('won');
        }

        $this->getDoctrine()->getManager()->persist($warOutcome1);
        $this->getDoctrine()->getManager()->persist($warOutcome2);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('default/index.html.twig', [
            'warOutcome1' => $warOutcome1,
            'warOutcome2' => $warOutcome2,
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
            $liveSoldier = $this->findLiveSoldier($army);

            if (null === $liveSoldier) {
                return false;
            }
        }

        return true;
    }

    private function findLiveSoldier(Army $army, ?bool $random = false) 
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Soldier::class);

        $soldiers = $repository->findBy([
            'alive' => true,
            'army' => $army,
        ]);

        if ($random) {
            shuffle($soldiers);
        }

        if ($soldiers) {
            return $soldiers[0];
        }
        
        return null;
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