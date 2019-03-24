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
            if (0 <= $result) {
                $this->updateBattleOutcome($soldier1, $battle, BattleOutcome::SURVIVED);
                $this->updateBattleOutcome($soldier2, $battle, BattleOutcome::DIED);
            } else {
                $this->updateBattleOutcome($soldier1, $battle, BattleOutcome::DIED);
                $this->updateBattleOutcome($soldier2, $battle, BattleOutcome::SURVIVED);
            }

            $war->addBattle($battle);
            $this->getDoctrine()->getManager()->persist($battle);
            $this->getDoctrine()->getManager()->persist($war);
            $this->getDoctrine()->getManager()->flush();
        }

        if ($this->findLiveSoldier($army1)) {
            $this->updateWarOutcome($army1, $war, WarOutcome::WON);
            $this->updateWarOutcome($army2, $war, WarOutcome::LOST);
        } elseif ($this->findLiveSoldier($army2)) {
            $this->updateWarOutcome($army1, $war, WarOutcome::LOST);
            $this->updateWarOutcome($army2, $war, WarOutcome::WON);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->render('default/index.html.twig', [
            'war' => $war,
        ]);
    }

    private function createArmy(string $tag, int $count)
    {
        $army = new Army();
        $army->setTag($tag);

        for($i = 0; $i < $count; $i++) {
            $soldier = new Soldier();
            $soldier
                ->setExperience(rand(0, 10) * (rand(1, 2) - rand(0, 1)))
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

    private function updateBattleOutcome(Soldier $soldier, Battle $battle, string $outcome)
    {
        $battleOutcome = new BattleOutcome();

        if ('survived' === $outcome) {
            $soldier->setExperience($soldier->getExperience() + 1);
        } elseif ('died' === $outcome) {
            $soldier->setAlive(false);
        }

        $battleOutcome
            ->setSoldier($soldier)
            ->setOutcome($outcome);

        $battle->addOutcome($battleOutcome);

        $this->getDoctrine()->getManager()->persist($soldier);
        $this->getDoctrine()->getManager()->persist($battleOutcome);
        $this->getDoctrine()->getManager()->persist($battle);
    }

    private function updateWarOutcome(Army $army, War $war, string $outcome)
    {
        $warOutcome = new WarOutcome();
        $warOutcome
            ->setArmy($army)
            ->setOutcome($outcome);

        $war->addOutcome($warOutcome);

        $this->getDoctrine()->getManager()->persist($warOutcome);
        $this->getDoctrine()->getManager()->persist($war);
    }
}