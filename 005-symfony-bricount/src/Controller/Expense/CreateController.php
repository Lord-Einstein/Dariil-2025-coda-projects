<?php

namespace App\Controller\Expense;

use App\DTO\ExpenseDTO;
use App\Entity\User;
use App\Entity\Wallet;
use App\Form\ExpenseType;
use App\Service\ExpenseService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateController extends AbstractController
{
    #[Route('wallets/{uid}/expense/create', name: 'wallets_expense_create', methods: ['GET', 'POST'])]
    public function index(
        #[MapEntity(mapping: ['uid' => 'uid'])]
        Wallet         $wallet,
        Request        $request,
        ExpenseService $expenseService
    ): Response
    {

        $dto = new ExpenseDTO();
        $form = $this->createForm(ExpenseType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();

            $expenseService->createExpense($wallet, $dto, $user);

            $this->addFlash('success', 'Dépense ajoutée !');
            return $this->redirectToRoute('wallets_show', ['uid' => $wallet->getUid()]);
        }

        return $this->render('expense/create/index.html.twig', [
            'form' => $form->createView(),
            'wallet' => $wallet
        ]);
    }
}
