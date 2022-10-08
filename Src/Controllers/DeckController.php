<?php

namespace App\Controllers;

use App\Controllers\Controller;
use MDCR\core\Request;
use MDCR\Models\Expense;
use MDCR\Models\FlashCard;
use MDCR\Models\Deck;
use MDCR\Models\User;

class DeckController extends Controller
{
    public function index(Request $request)
    {
        $user = new User();
        $user = $user->getCurrentUser();
        $deck = new Deck();
        $decks = $deck->findAllByParams(['user_id' => $user->id]);
        return $this->view('deck/index.twig', ['decks' => $decks]);
    }


    public function create(Request $request)
    {
        return $this->view('deck/create.twig');
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->getCurrentUser();
        $data = $request->all();
        $data['user_id'] = $user->id;
        $deck = new Deck();
        $deck->create($data);
        $user->ownDeckList[] = $deck;
        $user->store();
        return $this->redirect('/baralhos');
    }

    public function edit(Request $request, int $id)
    {
        $user = new User();
        $user = $user->getCurrentUser();
        $deck = new Deck();
        $deck = $deck->findOneByParams(['id' => $id]);

        // $deck->ownGiftList = $deck->bean->ownGiftList;

        return $this->view('deck/edit.twig', ['deck' => $deck]);
    }
    public function update(Request $request, int $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        $user = new User();
        $user = $user->getCurrentUser();
        $deck = new Deck();
        $deck = $deck->findOneByParams(['user_id' => $user->id]);
        /* $deck->update($data); */
        if ($deck->id == $id) {
            $deck->update($data);
        }
        return $this->view('deck/user/edit.twig', ['deck' => $deck]);
    }


    public function show(Request $request, $id)
    {
        $user = (new User())->getCurrentUser();
        $deck = (new Deck())->findOneByParams(['id' => $id, 'user_id' => $user->id]);
        $deck->ownGiftList = $deck->bean->ownGiftList;

        $flashCards = (new FlashCard())->findAllByParams(['user_id' => $user->id, 'deck_id' => $id]);

        return $this->view(
            'deck/show.twig',
            [
                'deck' => $deck,
                'flashCards' => $flashCards,
            ]
        );
    }
    public function publicShowByLink(Request $request, $url)
    {

        $deck = new Deck();
        $deck = $deck->findOneByParams(['url' => $url]);
        $deck->ownGiftList = $deck->bean->ownGiftList;
        shuffle($deck->ownGiftList);
        return $this->view('deck/public/show.twig', ['deck' => $deck]);
    }

    public function playFront(Request $request, int $deckId)
    {
        $user = (new User())->getCurrentUser();
        $currentDate = date('Y-m-d H:i:s');
        $cards = (new FlashCard)->where(
            ['user_id', '=', $user->id],
            ['deck_id', '=', $deckId],
            ['nextshow', '<=', "'$currentDate'"]
        )->orderBy(['nextshow', 'asc'])
        ->limit(1)
        ->get();
        var_dump(sizeof($cards));
        // $card = (new FlashCard())->findOneByParams(['user_id' => $user->id, 'deck_id' => $deckId]);
        $deck = (new Deck())->findOneByParams(['user_id' => $user->id, 'id' => $deckId]);

        return $this->view('deck/playFront.twig', ['card' => end($cards), 'deck' => $deck]);
    }

    public function playBack(Request $request, int $deckId, int $cardId)
    {
        $user = (new User())->getCurrentUser();
        $card = (new FlashCard())->findOneByParams(['user_id' => $user->id, 'deck_id' => $deckId, 'id' => $cardId]);
        $deck = (new Deck())->findOneByParams(['user_id' => $user->id, 'id' => $deckId]);

        return $this->view('deck/playBack.twig', ['card' => $card, 'deck' => $deck]);
    }
}
