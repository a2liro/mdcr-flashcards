<?php

namespace App\Controllers;

use App\Controllers\Controller;
use MDCR\core\File;
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
        $user = new User();
        $user = $user->getCurrentUser();
        $deck = (new Deck())->where(
          ['user_id', '=', $user->id],
          ['id', '=', $deckId]  
        )->get();
        return $this->view('flashCard/create.twig', ['deck' => $deck[0]]);
    }

    public function store(Request $request, int $deckId)
    {
        $imagePath = File::save($_FILES['image'], 'flashcards');
        $audioPath = File::save($_FILES['audiofile'], 'flashcards');
        $user = new User();
        $user->getCurrentUser();
        $data = $request->all();
        $data['user_id'] = $user->id;
        $data['deck_id'] = $deckId;
        $data['difficulty'] = 5;
        $data['lastshow'] = date('Y-m-d H:i:s');
        $data['nextshow'] = date('Y-m-d H:i:s');
        // $data['lastinterval'] = 0;
        $data['image'] = $imagePath ? $imagePath : null;
        $data['audiofile'] = $audioPath ? $audioPath : null;
        $data['audio'] = $data['audio'] ? File::saveBase64ToFile($data["audio"], 'flashcards', 'mp3') : null;
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
        $card = new FlashCard();
        $card = $card->findOneByParams(['id' => $id]);

        // $card->ownGiftList = $card->bean->ownGiftList;

        $image64 = File::getBase64($card->image);
        $audioFile64 = File::getBase64($card->audiofile);
        $audio64 = File::getBase64($card->audio);
        $card->image64 = $image64;
        $card->audioFile64 = $audioFile64;
        $card->audio64 = $audio64;

        $decks = (new Deck())->where(
            ['user_id', '=', $user->id],
          )->get();

          $currentDeck = (new Deck())->where(
            ['user_id', '=', $user->id],
            ['id', '=', $deckId]  
          )->get()[0];

        return $this->view('flashCard/edit.twig', ['card' => $card, 'currentDeck' => $currentDeck, 'decks' => $decks]);
    }
    public function update(Request $request, int $deckId, int $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        if ($_FILES['image']['size']) {
            $imagePath = File::save($_FILES['image'], 'flashcards');
            $data['image'] = $imagePath ? $imagePath : null;
        } else if (strlen($data['image64']) < 1) {
            // var_dump("entrou aqui");
            $data['image'] = '';
        }

        if ($_FILES['audiofile']['size']) {
            $imagePath = File::save($_FILES['audiofile'], 'flashcards');
            $data['audiofile'] = $imagePath ? $imagePath : null;
        } else if (strlen($data['audiofile64']) < 1) {
            $data['audiofile'] = '';
        }

        if(strlen($data['audio']) > 255) {
            $data['audio'] = $data['audio'] ? File::saveBase64ToFile($data["audio"], 'flashcards', 'mp3') : null;
        } else if(strlen($data['audio']) == 0) {
            $data['audio'] = null;
        }


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

    public function exclude(Request $request, int $deckId, int $cardId)
    {
        $user = (new User())->getCurrentUser();
        $cards = (new FlashCard())->where(
            ['user_id', '=', $user->id],
            ['id', '=', $cardId]
        )->get();
        return $this->view('flashCard/exclude.twig', ['card' => $cards[0], 'deckId' => $deckId]);
    }

    public function delete(Request $request, int $deckId, int $cardId)
    {

        $user = (new User())->getCurrentUser();
        $cards = (new FlashCard())->where(
            ['user_id', '=', $user->id],
            ['id', '=', $cardId]
        )->get();

        if($cards) {
            $delete = $cards[0]->delete();
            if($delete) {
                header("Location: /baralhos/{$deckId}/visualizar");
            }else {
                header("Location: /baralhos/{$deckId}/flashcard/$cardId/erro");
            }

        }
    }

    public function note(Request $request, int $deckId, int $cardId, $note)
    {
        $user = (new User())->getCurrentUser();
        $cards = (new FlashCard())->where(
            ['user_id', '=', $user->id],
            ['id', '=', $cardId]
        )->get();
        $timeToNextShow = $this->calcNote($note, $cards[0]->difficulty);
        $cards[0]->difficulty = $note;
        $cards[0]->lastshow = date('Y-m-d H:i:s');
        $cards[0]->nextshow = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " +$timeToNextShow day"));

        $cards[0]->update();

        header("Location: /baralhos/{$deckId}/jogar/frente/audio");
    }

    private function calcNote(int $note, $cardDifficulty)
    {
        if($note == 1) {
            switch($cardDifficulty) {
                case 1:
                    return 180;
                case 2:
                    return 60;
                case 3:
                    return 30;
                case 4:
                    return 15;
                case 5:
                    return 7;
                default:
                    return 0;
            }
        }
        if($note == 2) {
            switch($cardDifficulty) {
                case 1:
                    return 60;
                case 2:
                    return 30;
                case 3:
                    return 15;
                case 4:
                    return 7;
                case 5:
                    return 4;
                default:
                    return 0;
            }
        }

        if($note == 3) {
            switch($cardDifficulty) {
                case 1:
                    return 30;
                case 2:
                    return 15;
                case 3:
                    return 7;
                case 4:
                    return 4;
                case 5:
                    return 2;
                default:
                    return 0;
            }
        }

        if($note == 4) {
            switch($cardDifficulty) {
                case 1:
                    return 10;
                case 2:
                    return 6;
                case 3:
                    return 4;
                case 4:
                    return 2;
                case 5:
                    return 1;
                default:
                    return 0;
            }
        }

        if($note == 5) {
            switch($cardDifficulty) {
                case 1:
                    return 3;
                case 2:
                    return 2;
                case 3:
                    return 1;
                case 4:
                    return 0;
                case 5:
                    return 0;
                default:
                    return 0;
            }
        }
    }

    
    public function convert64ToFile()
    {
        $user = (new User())->getCurrentUser();

        $cards = (new FlashCard())->get();

        foreach($cards as $key => $card) {
            if(isset($card->audio) && strlen($card->audio) > 100) {
                $saveFile = File::saveBase64ToFile($card->audio, 'flashcards',);
                if($saveFile != false) {
                    $card->audio = $saveFile;
                    $card->update();
                }
            }
        }

        // var_dump($cards);
    }
}
