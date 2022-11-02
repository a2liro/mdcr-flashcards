<?php

namespace App\Controllers;

use App\Controllers\Controller;
use MDCR\core\File;
use MDCR\core\Request;
use MDCR\Models\Expense;
use MDCR\Models\FlashCard;
use MDCR\Models\Deck;
use MDCR\Models\User;
use falahati\PHPMP3\MpegAudio;

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

    public function playFront(Request $request, int $deckId, int $cardId)
    {
        $user = (new User())->getCurrentUser();
        $currentDate = date('Y-m-d H:i:s');
        $cards = (new FlashCard)->where(
            ['user_id', '=', $user->id],
            ['id', '=', $cardId],
            ['nextshow', '<=', "'$currentDate'"]
        )->orderBy(['nextshow', 'asc'])
            ->limit(1)
            ->get();
        // $card = (new FlashCard())->findOneByParams(['user_id' => $user->id, 'deck_id' => $deckId]);
        $deck = (new Deck())->findOneByParams(['user_id' => $user->id, 'id' => $deckId]);

        if (sizeof($cards)) {
            $image64 = File::getBase64($cards[0]->image);
            $audioFile64 = File::getBase64($cards[0]->audiofile);
            $audio64 = File::getBase64($cards[0]->audio);
            $cards[0]->image64 = $image64;
            $cards[0]->audioFile64 = $audioFile64;
            $cards[0]->audio64 = $audio64;

        }



        return $this->view('deck/playFront.twig', ['card' => end($cards), 'deck' => $deck]);
    }

    public function playFrontAudio(Request $request, int $deckId)
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
        // $card = (new FlashCard())->findOneByParams(['user_id' => $user->id, 'deck_id' => $deckId]);
        $deck = (new Deck())->findOneByParams(['user_id' => $user->id, 'id' => $deckId]);

        if (sizeof($cards)) {
            $image64 = File::getBase64($cards[0]->image);
            $audioFile64 = File::getBase64($cards[0]->audiofile);
            if (!strlen($audioFile64) && !strlen($cards[0]->audio)) {
                $this->playFront($request, $deckId, $cards[0]->id);
                return 0;
            }
            $cards[0]->image64 = $image64;
            $cards[0]->audioFile64 = $audioFile64;
            
            $audio64 = File::getBase64($cards[0]->audio);
            $cards[0]->audio64 = $audio64;
        }



        return $this->view('deck/playFrontAudio.twig', ['card' => end($cards), 'deck' => $deck]);
    }

    public function playBack(Request $request, int $deckId, int $cardId)
    {
        $user = (new User())->getCurrentUser();
        $card = (new FlashCard())->findOneByParams(['user_id' => $user->id, 'deck_id' => $deckId, 'id' => $cardId]);
        $deck = (new Deck())->findOneByParams(['user_id' => $user->id, 'id' => $deckId]);

        $image64 = File::getBase64($card->image);
        $card->image64 = $image64;

        return $this->view('deck/playBack.twig', ['card' => $card, 'deck' => $deck]);
    }

    public function playAllAudios(Request $request, int $deckId)
    {
        $user = (new User())->getCurrentUser();
        $currentDate = date('Y-m-d H:i:s');
        $cards = (new FlashCard)->where(
            ['user_id', '=', $user->id],
            ['deck_id', '=', $deckId],
        )
            ->orderBy(['id', 'asc'])
            ->get();

        $deck = (new Deck())->findOneByParams(['user_id' => $user->id, 'id' => $deckId]);

        $fullAudio = null;

        foreach ($cards as $key => $card) {

            if ($fullAudio == null) {
                $fullAudio = file_get_contents(__DIR__ . '/../../storage/' . $card->audiofile);

            } else {
                $fullAudio = $fullAudio . file_get_contents(__DIR__ . '/../../storage/' . $card->audiofile);
            }

            // if($fullAudio == null) {
            //     $fullAudio = MpegAudio::fromFile(__DIR__ . '/../../storage/' . $card->audiofile);
            // }
            // else {
            //     $audio = MpegAudio::fromFile(__DIR__ . '/../../storage/' . $card->audiofile);
            //     var_dump($audio->getTotalDuration());
            //     $fullAudio->append($audio);
            // }
        }

        $fullAudio64 = base64_encode($fullAudio);



        return $this->view('deck/playAllAudios.twig', ['deck' => $deck, 'fullAudio64' => $fullAudio64]);
    }

    public function playAllAudiosRecorded(Request $request, int $deckId)
    {
        $user = (new User())->getCurrentUser();
        $currentDate = date('Y-m-d H:i:s');
        $cards = (new FlashCard)->where(
            ['user_id', '=', $user->id],
            ['deck_id', '=', $deckId],
        )
            ->orderBy(['id', 'asc'])
            ->get();

        $deck = (new Deck())->findOneByParams(['user_id' => $user->id, 'id' => $deckId]);

        $fullAudio = null;

        foreach ($cards as $key => $card) {

            if ($fullAudio == null) {
                $fullAudio = file_get_contents(__DIR__ . '/../../storage/' . $card->audio);

            } else {
                $fullAudio = $fullAudio . file_get_contents(__DIR__ . '/../../storage/' . $card->audio);
            }

            // if($fullAudio == null) {
            //     $fullAudio = MpegAudio::fromFile(__DIR__ . '/../../storage/' . $card->audiofile);
            // }
            // else {
            //     $audio = MpegAudio::fromFile(__DIR__ . '/../../storage/' . $card->audiofile);
            //     var_dump($audio->getTotalDuration());
            //     $fullAudio->append($audio);
            // }
        }

        $fullAudio64 = base64_encode($fullAudio);



        return $this->view('deck/playAllAudiosRecorded.twig', ['deck' => $deck, 'fullAudio64' => $fullAudio64]);
    }
}
