<?php

namespace App\Controller\Expense;

use App\Entity\Wallet;
use App\Service\ExpenseService;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateController extends AbstractController
{
    #[Route('wallets/{uid}/expense/create', name: 'wallets_expense_create', methods: ['GET', 'POST'])]
    public function index(
        Wallet         $wallet,
        Request        $request,
        ExpenseService $expenseService
    ): Response
    {
        return $this->render('expense/create/index.html.twig', [
            'controller_name' => 'CreateController',
        ]);
    }
}
