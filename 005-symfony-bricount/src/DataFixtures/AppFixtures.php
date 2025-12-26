<?php

namespace App\DataFixtures;

use App\Entity\Expense;
use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\XUserWallet;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    private const array EXPENSE_TYPES = [
        "hotel", "bar", "essence", "restau", "sortie", "souvenir", "sushi", "imprévu"
    ];

    private array $generatedUsers = [];
    private array $generatedWallets = [];

    private UserPasswordHasherInterface $hasher;
    private ObjectManager $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

// ...
    public function load(ObjectManager $manager): void
    {
        // initialisation des variables
        $this->manager = $manager;
        $this->generatedUsers = [];
        $this->generatedWallets = [];

        // generate 2 different users
        $this->generatedUsers[] = $this->generateUser("Alice", "alice@coda.fr", "alice");
        $this->generatedUsers[] = $this->generateUser("Bob", "bob@coda.fr", "bob");

        // generate 3 different wallets
        $this->generatedWallets[] = $this->generateWallet("Vacances");
        $this->generatedWallets[] = $this->generateWallet("Colloc");
        $this->generatedWallets[] = $this->generateWallet("Montagne");

        // now, generate 5 expenses per wallets
        foreach ($this->generatedWallets as $wallet) {
            for ($i = 0; $i < 25; $i++) {
                $this->generateExpense(
                    $wallet,
                    random_int(1, 150) * 100,
                    self::EXPENSE_TYPES[random_int(0, sizeof(self::EXPENSE_TYPES) - 1)],
                    $this->generatedUsers[0]
                );
                $this->generateExpense(
                    $wallet,
                    random_int(1, 150) * 100,
                    self::EXPENSE_TYPES[random_int(0, sizeof(self::EXPENSE_TYPES) - 1)],
                    $this->generatedUsers[0]
                );
                $this->generateExpense(
                    $wallet,
                    random_int(1, 150) * 100,
                    self::EXPENSE_TYPES[random_int(0, sizeof(self::EXPENSE_TYPES) - 1)],
                    $this->generatedUsers[1]
                );
                $this->generateExpense(
                    $wallet,
                    random_int(1, 150) * 100,
                    self::EXPENSE_TYPES[random_int(0, sizeof(self::EXPENSE_TYPES) - 1)],
                    $this->generatedUsers[1]
                );
                $this->generateExpense(
                    $wallet,
                    random_int(1, 150) * 100,
                    self::EXPENSE_TYPES[random_int(0, sizeof(self::EXPENSE_TYPES) - 1)],
                    $this->generatedUsers[1]
                );
            }
        }

        // generating links between users and wallets
        foreach ($this->generatedWallets as $wallet) {
            foreach ($this->generatedUsers as $user) {
                $role = $user->getName() == "Alice" ? "admin" : "user";

                $this->generateXUserWallet($user, $wallet, $role);
            }
        }
    }

    public function generateXUserWallet(User $user, Wallet $wallet, string $role): XUserWallet
    {
        $xUserWallet = new XUserWallet();

        $xUserWallet->setTargetUser($user);
        $xUserWallet->setWallet($wallet);
        $xUserWallet->setRole($role);

        $xUserWallet->setCreatedBy($this->generatedUsers[0]);
        $xUserWallet->setCreatedDate(new DateTime());

        $this->manager->persist($xUserWallet);
        $this->manager->flush();

        return $xUserWallet;
    }

    public function generateExpense(Wallet $wallet, int $amount, string $description, User $createdBy): Expense
    {
        $expense = new Expense();
        $expense->setUid(Uuid::v7()->toString());

        $expense->setWallet($wallet);
        $expense->setDescription($description);
        $expense->setAmount($amount);

        $totalAmount = $wallet->getTotalAmount();
        $wallet->setTotalAmount($totalAmount + $amount);

        $expense->setCreatedBy($createdBy);
        $expense->setCreatedDate(new DateTime());

        $this->manager->persist($expense);
        $this->manager->flush();

        return $expense;
    }

    public function generateWallet(string $label): Wallet
    {
        $wallet = new Wallet();

        $wallet->setUid(Uuid::v7()->toString());

        $wallet->setLabel($label);
        $wallet->setTotalAmount(0);
        $wallet->setPaymentsDue([]);

        $wallet->setCreatedBy($this->generatedUsers[0]);
        $wallet->setCreatedDate(new DateTime());

        $this->manager->persist($wallet);
        $this->manager->flush();

        return $wallet;
    }

    /**
     * Generates a User instance and store it in the databases.
     *
     * It also ensures that the password is hashed.
     *
     * @param string $name name of the user
     * @param string $email email of the user
     * @param string $password clear (not encrypted) password of the user
     * @return User the created user
     */
    private function generateUser(string $name, string $email, string $password): User
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}
