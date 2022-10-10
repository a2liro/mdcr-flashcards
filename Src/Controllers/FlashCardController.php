<?php

namespace App\Controllers;

use App\Controllers\Controller;
use MDCR\core\Request;
use MDCR\Models\Deck;
use MDCR\Models\Expense;
use MDCR\Models\ExpenseCategory;
use MDCR\Models\FlashCard;
use MDCR\Models\User;

class FlashCardController extends Controller
{
    public function index(Request $request)
    {
        $user = new User();
        $user = $user->getCurrentUser();
        $flashCard = new FlashCard();
        $flashCards = $flashCard->findAllByParams(['user_id' => $user->id]);
        return $this->view('flashCard/index.twig', ['flashCards' => $flashCards]);
    }


    public function create(Request $request, int $deckId)
    {
        return $this->view('flashCard/create.twig', ['deckId' => $deckId]);
    }

    public function store(Request $request, int $deckId)
    {
        $user = new User();
        $user->getCurrentUser();
        $data = $request->all();
        $data['user_id'] = $user->id;
        $data['deck_id'] = $deckId;
        $data['difficulty'] = 1;
        $data['lastshow'] = date('Y-m-d H:i:s');
        $data['nextshow'] = date('Y-m-d H:i:s');
        $data['lastinterval'] = 0;
        $flashCard = new FlashCard();
        $flashCard->create($data);
        $user->ownFlashCardList[] = $flashCard;
        $user->store();
        return $this->redirect("/baralhos/$deckId/flash-cards/criar");
    }

    public function edit(Request $request, int $deckId, int $id)
    {
        $user = new User();
        $user = $user->getCurrentUser();
        $flashCard = new FlashCard();
        $flashCard = $flashCard->findOneByParams(['id' => $id]);

        // $flashCard->ownGiftList = $flashCard->bean->ownGiftList;

        return $this->view('flashCard/edit.twig', ['flashCard' => $flashCard, 'deckId' => $deckId]);
    }
    public function update(Request $request, int $deckId, int $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        $user = new User();
        $user = $user->getCurrentUser();
        $flashCard = new FlashCard();
        $flashCard = $flashCard->findOneByParams(['user_id' => $user->id, 'id' => $id]);
        /* $flashCard->update($data); */
        if ($flashCard->id == $id) {
            $flashCard->update($data);
        }
        return header("Location: /baralhos/$deckId/visualizar");
        //return $this->view('flashCard/user/edit.twig', ['flashCard' => $flashCard]);
    }


    public function show(Request $request, $id)
    {
        $user = (new User())->getCurrentUser();
        $flashCard = new FlashCard();
        $flashCard = $flashCard->findOneByParams(['id' => $id, 'user_id' => $user->id]);
        $flashCard->ownGiftList = $flashCard->bean->ownGiftList;

        $expenseCategory = new ExpenseCategory();
        $expenseCategories = $expenseCategory->findAllByParams(['user_id' => $user->id, 'flashCard_id' => $id]);
        $expenses = (new Expense())->findAllByParams(['user_id' => $user->id, 'flashCard_id' => $id]);
        foreach ($expenses as $index => $expense) {
            // $expense->category = $expense->ownCategory;
            $category = $expense->category_id;
            $expenses[$index]->category = (function () use ($expense, $expenseCategories) {
                foreach ($expenseCategories as $category) {
                    if ($category->id == $expense->category_id) {
                        return $category;
                    }
                }
            })();
            // print_r($category);
        }
        return $this->view(
            'flashCard/show.twig',
            [
                'flashCard' => $flashCard,
                'expenseCategories' => $expenseCategories,
                'expenses' => $expenses
            ]
        );
    }

    public function note(Request $request, int $deckId, int $cardId, $note)
    {
        $user = (new User())->getCurrentUser();
        $cards = (new FlashCard())->where(
            ['user_id', '=', $user->id],
            ['id', '=', $cardId]
        )->get();
        $cards[0]->difficulty = $note;
        $cards[0]->lastshow = date('Y-m-d H:i:s');
        $cards[0]->nextshow = date('Y-m-d H:i:s', strtotime($cards[2]->lastshow . " +$note day"));
        
        $cards[0]->update();
        
        header("Location: /baralhos/1/jogar/frente");
    }
}
