<?php

namespace App\Repository;

use App\Entity\Balance;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function getUserBalances(?User $user): array
    {
        $queryBuilder = $this->createQueryBuilder('u');

        $queryBuilder->select('b.currency, SUM(b.amount) as total')
            ->join('u.balances', 'b')
            ->where('u = :user')
            ->groupBy('b.currency')
            ->setParameter('user', $user);

        $results = $queryBuilder->getQuery()->getArrayResult();

        $balances = [];
        foreach ($results as $result) {
            $balances[$result['currency']] = floatval($result['total']);
        }

        return $balances;
    }

}
