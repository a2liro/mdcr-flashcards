<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Card\CalcNoteService;
use App\Services\Deck\StoreDeckService;
use MDCR\core\File;
use MDCR\core\Request;
use MDCR\Models\Card;
use MDCR\Models\Deck;
use MDCR\Models\PlayedCard;
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

        $currentDate = date('Y-m-d H:i:s');
        foreach ($decks as $key => $deck) {

            $cards = (new Card)->select('id')
                ->where(
                    ['user_id', '=', $user->id],
                    ['deck_id', '=', $deck->id],
                    ['nextshow', '<=', "'$currentDate'"]
                )
                ->get();
            $decks[$key]->cardsToPlay = sizeof($cards);
            $cardsReverse = (new Card)->select('id')
                ->where(
                    ['user_id', '=', $user->id],
                    ['deck_id', '=', $deck->id],
                    ['nextshow_reverse', '<=', "'$currentDate'"]
                )
                ->get();
            $decks[$key]->cardsToPlayReverse = sizeof($cardsReverse);
        }
        return $this->view('deck/index.twig', ['decks' => $decks]);
    }


    public function create(Request $request, $categoryId)
    {
        return $this->view('deck/create.twig', ['categoryId' => $categoryId]);
    }

    public function store(Request $request, $categoryId)
    {
        $storeDeckService = new StoreDeckService();
        $data = $request->all();
        $data['category_id'] = intval($categoryId);
        $storeDeckService->run($data);
        return $this->redirect("/categorias/$categoryId/visualizar");
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
        // $user = new User();
        // $user = $user->getCurrentUser();
        // $deck = new Deck();
        // $deck = $deck->findOneByParams(['user_id' => $user->id]);
        // /* $deck->update($data); */
        // if ($deck->id == $id) {
        //     $deck->update($data);
        // }
        // return $this->view('deck/user/edit.twig', ['deck' => $deck]);

        $user = new User();
        $user = $user->getCurrentUser();
        $deck = new Deck();
        $deck = $deck->findOneByParams(['user_id' => $user->id, 'id' => $id]);
        /* $deck->update($data); */

        $data['isEnglish'] = isset($data['isEnglish']) ? $data['isEnglish'] : null;

        if ($deck->id == $id) {
            $deck->update($data);
        }
        return header("Location: /baralhos/$deck->id/visualizar");
    }


    public function show(Request $request, $id)
    {
        $user = (new User())->getCurrentUser();
        $deck = (new Deck())->findOneByParams(['id' => $id, 'user_id' => $user->id]);

        $cards = (new Card()) //->findAllByParams(['user_id' => $user->id, 'deck_id' => $id]);
        ->where(
            ['user_id', '=', $user->id],
            ['deck_id', '=', $id]
        )
            ->orderBy(['id' => 'desc'])
            ->get();


        $currentDate = date('Y-m-d H:i:s');
//        $cards = (new Card)->select('id')
//            ->where(
//                ['user_id', '=', $user->id],
//                ['deck_id', '=', $id],
//                ['nextshow', '<=', "'$currentDate'"]
//            )
//            ->get();
        $deck->cardsToPlay = sizeof($cards);

        $reverseCards = (new Card)->select('id')
            ->where(
                ['user_id', '=', $user->id],
                ['deck_id', '=', $id],
                ['nextshow_reverse', '<=', "'$currentDate'"]
            )
            ->get();
        $deck->cardsToPlayReverse = sizeof($reverseCards);


        return $this->view(
            'deck/show.twig',
            [
                'deck' => $deck,
                'cards' => $cards,
            ]
        );
    }

    public function playFront(Request $request, int $deckId, int $cardId)
    {
        $user = (new User())->getCurrentUser();
        $currentDate = date('Y-m-d H:i:s');
        $cards = (new Card)->where(
            ['id', '=', $cardId],
        )
            ->limit(1)
            ->get();
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

        $playedCardModel = new PlayedCard();
        $cardModel = new Card();
        $newCardsData = $cardModel->exec('SELECT card.* from card left join playedcard on card.id = playedcard.card_id where playedcard.id IS NULL and card.deck_id = ? and playedcard.user_id = ?', [$deckId,  $user->id]);
        $cardsToPlayAgainData = $cardModel->exec('SELECT * FROM  (SELECT card.*, playedcard.nextshow from card right join playedcard on card.id = playedcard.card_id where card.deck_id = ? and playedcard.user_id = ? order by playedcard.id desc) as a where a.nextshow < NOW()', [$deckId, $user->id]);

//        var_dump($cardsToPlayAgainData);
        var_dump($newCardsData);
        die();
        $cards = (new Card)->where(
            ['deck_id', '=', $deckId],
//            ['nextshow', '<=', "'$currentDate'"]
        )
//            ->orderBy(['nextshow' => 'asc'])
            ->limit(1)
            ->get();
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
        $card = end($cards);
        return $this->view('deck/playFrontAudio.twig', ['card' => $card, 'deck' => $deck]);
    }

    public function playBack(Request $request, int $deckId, int $cardId)
    {
        $user = (new User())->getCurrentUser();
        $card = (new Card())->findOneByParams(['user_id' => $user->id, 'deck_id' => $deckId, 'id' => $cardId]);
        $deck = (new Deck())->findOneByParams(['user_id' => $user->id, 'id' => $deckId]);

        $image64 = File::getBase64($card->image);
        $card->image64 = $image64;

        for ($count = 1; $count <= 5; $count++) {
            $interval = CalcNoteService::run($count, $card->difficulty); //CardController::calcNote($count, $card->difficulty);
            if ($interval < 60) {
                $card->intervals[$count] = $interval . ' minutos';
            } else if ($interval < 1440) {
                $card->intervals[$count] = intdiv($interval, 60) . ' hora(s)';
            } else {
                $card->intervals[$count] = intdiv($interval, 1440) . ' dia(s)';
            }

        }

        return $this->view('deck/playBack.twig', ['card' => $card, 'deck' => $deck]);
    }

    public function playAllAudios(Request $request, int $deckId)
    {
        $user = (new User())->getCurrentUser();
        $currentDate = date('Y-m-d H:i:s');
        $cards = (new Card)->where(
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


        if (strlen($fullAudio64) < 100) {
            return header("Location: /baralhos/$deck->id/ouvir-todos/gravados");
        }
        return $this->view('deck/playAllAudios.twig', ['deck' => $deck, 'fullAudio64' => $fullAudio64]);
    }

    public function playAllAudiosRecorded(Request $request, int $deckId)
    {
        $user = (new User())->getCurrentUser();
        $currentDate = date('Y-m-d H:i:s');
        $cards = (new Card)->where(
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


    public function playFrontReverse(Request $request, int $deckId)
    {
        $user = (new User())->getCurrentUser();

        $currentDate = date('Y-m-d H:i:s');
        $cards = (new Card)->where(
            ['user_id', '=', $user->id],
            ['deck_id', '=', $deckId],
            ['nextshow_reverse', '<=', "'$currentDate'"]
        )->orderBy(['nextshow_reverse', 'asc'])
            ->limit(1)
            ->get();
        $deck = (new Deck())->findOneByParams(['user_id' => $user->id, 'id' => $deckId]);

        return $this->view('deck/playFrontReverse.twig', ['card' => $cards[0], 'deck' => $deck]);
    }

    public function playBackReverse(Request $request, int $deckId, int $cardId)
    {
        $user = (new User())->getCurrentUser();
        $currentDate = date('Y-m-d H:i:s');
        $cards = (new Card)->where(
            ['user_id', '=', $user->id],
            ['id', '=', $cardId],
            ['nextshow_reverse', '<=', "'$currentDate'"]
        )->orderBy(['nextshow_reverse', 'asc'])
            ->limit(1)
            ->get();
        // $card = (new Card())->findOneByParams(['user_id' => $user->id, 'deck_id' => $deckId]);
        $deck = (new Deck())->findOneByParams(['user_id' => $user->id, 'id' => $deckId]);

        if (sizeof($cards)) {
            $image64 = File::getBase64($cards[0]->image);
            $audioFile64 = File::getBase64($cards[0]->audiofile);
            $audio64 = File::getBase64($cards[0]->audio);
            $cards[0]->image64 = $image64;
            $cards[0]->audioFile64 = $audioFile64;
            $cards[0]->audio64 = $audio64;
        }

        for ($count = 1; $count <= 5; $count++) {
            $interval = CardController::calcNote($count, $cards[0]->difficulty_reverse);
            if ($interval < 60) {
                $cards[0]->intervals[$count] = $interval . ' minutos';
            } else if ($interval < 1440) {
                $cards[0]->intervals[$count] = intdiv($interval, 60) . ' hora(s)';
            } else {
                $cards[0]->intervals[$count] = intdiv($interval, 1440) . ' dia(s)';
            }

        }
        return $this->view('deck/playBackReverse.twig', ['card' => end($cards), 'deck' => $deck]);
    }
}
