<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Repositories\Organization\StoreOrganizationRepository;
use App\Services\Organization\GetAllOrganizationsService;
use App\Services\Course\GetCoursesByOrganizationIdService;
use MDCR\core\File;
use MDCR\core\Request;
use MDCR\Models\Deck;
use MDCR\Models\Card;
use MDCR\Models\Note;
use MDCR\Models\Organization;
use MDCR\Models\User;

class OrganizationController extends Controller
{

    public function index(Request $request)
    {
        $getAllOrganizationService = new GetAllOrganizationsService();
        $organizations = $getAllOrganizationService->run();
        return $this->view('organization/index.twig', ['organizations' => $organizations]);
    }


    public function create(Request $request)
    {
        $user = new User();
        $user = $user->getCurrentUser();
        return $this->view('organization/create.twig', []);
    }

    public function store(Request $request)
    {

        $data = $request->all();
        $storeOrganizationService = new StoreOrganizationRepository();
        $storeOrganizationService->run($data);
        return $this->redirect("/organizacoes");
    }

    public function edit(Request $request, int $deckId, int $id)
    {
        $user = new User();
        $user = $user->getCurrentUser();
        $card = new Card();
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

        return $this->view('organization/edit.twig', ['card' => $card, 'currentDeck' => $currentDeck, 'decks' => $decks]);
    }
    public function update(Request $request, int $deckId, int $id)
    {
        $data = $request->all();
        $data['id'] = $id;
        if ($_FILES['image']['size']) {
            $imagePath = File::save($_FILES['image'], 'cards');
            $data['image'] = $imagePath ? $imagePath : null;
        } else if (strlen($data['image64']) < 1) {
            $data['image'] = '';
        }

        if ($_FILES['audiofile']['size']) {
            $imagePath = File::save($_FILES['audiofile'], 'cards');
            $data['audiofile'] = $imagePath ? $imagePath : null;
        } else if (strlen($data['audiofile64']) < 1) {
            $data['audiofile'] = '';
        }

        if (strlen($data['audio']) > 255) {
            $data['audio'] = $data['audio'] ? File::saveBase64ToFile($data["audio"], 'cards', 'mp3') : null;
        } else if (strlen($data['audio']) == 0) {
            $data['audio'] = null;
        }


        $user = new User();
        $user = $user->getCurrentUser();
        $organization = new Card();
        $organization = $organization->findOneByParams(['user_id' => $user->id, 'id' => $id]);
        /* $organization->update($data); */
        if ($organization->id == $id) {
            $organization->update($data);
        }
        return header("Location: /baralhos/$deckId/visualizar");
        //return $this->view('organization/user/edit.twig', ['organization' => $organization]);
    }


    public function show(Request $request, int $id)
    {

        $getCoursesByOrganizationIdService = new GetCoursesByOrganizationIdService();
        $user = (new User())->getCurrentUser();
        $organization = new Organization();
        $organization = $organization->findOneByParams(['id' => $id]);
        $courses = $getCoursesByOrganizationIdService->run(organizationId: $id);
        return $this->view(
            'organization/show.twig',
            [
                'organization' => $organization,
                'courses' => $courses,
            ]
        );
    }

    public function exclude(Request $request, int $deckId, int $cardId)
    {
        $user = (new User())->getCurrentUser();
        $cards = (new Card())->where(
            ['user_id', '=', $user->id],
            ['id', '=', $cardId]
        )->get();
        return $this->view('organization/exclude.twig', ['card' => $cards[0], 'deckId' => $deckId]);
    }

    public function delete(Request $request, int $deckId, int $cardId)
    {

        $user = (new User())->getCurrentUser();
        $cards = (new Card())->where(
            ['user_id', '=', $user->id],
            ['id', '=', $cardId]
        )->get();

        if ($cards) {
            $delete = $cards[0]->delete();
            if ($delete) {
                header("Location: /baralhos/{$deckId}/visualizar");
            } else {
                header("Location: /baralhos/{$deckId}/card/$cardId/erro");
            }
        }
    }

    public function note(Request $request, int $deckId, int $cardId, $note)
    {
        $user = (new User())->getCurrentUser();
        $cards = (new Card())->where(
            ['user_id', '=', $user->id],
            ['id', '=', $cardId]
        )->get();
        $timeToNextShow = $this->calcNote($note, $cards[0]->difficulty);
        $cards[0]->difficulty = $note;
        $cards[0]->lastshow = date('Y-m-d H:i:s');
        $cards[0]->nextshow = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " +$timeToNextShow minutes"));

        $cards[0]->update();
        $noteData = [
            'user_id' => $user->id,
            'card_id' => $cardId,
            'note' => $note,
            'showdate' => $cards[0]->lastshow,
            'nextdate' => $cards[0]->nextshow,
            'interval' => $timeToNextShow,
            'isReverse' => false
        ];
        $note = (new Note())->create($noteData);

        header("Location: /baralhos/{$deckId}/jogar/frente/audio");
    }

    public function noteReverse(Request $request, int $deckId, int $cardId, $note)
    {
        $user = (new User())->getCurrentUser();
        $cards = (new Card())->where(
            ['user_id', '=', $user->id],
            ['id', '=', $cardId]
        )->get();
        $timeToNextShow = $this->calcNote($note, $cards[0]->difficulty_reverse);
        $cards[0]->difficultyReverse = $note;
        $cards[0]->lastshowReverse = date('Y-m-d H:i:s');
        $cards[0]->nextshowReverse = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " +$timeToNextShow minutes"));

        $cards[0]->update();
        $noteData = [
            'user_id' => $user->id,
            'card_id' => $cardId,
            'note' => $note,
            'showdate' => $cards[0]->lastshowReverse,
            'nextdate' => $cards[0]->nextshowReverse,
            'interval' => $timeToNextShow,
            'isReverse' => true
        ];
        $note = (new Note())->create($noteData);

        header("Location: /baralhos/{$deckId}/jogar/frente/reverso");
    }

    public static function calcNote(int $note, $cardDifficulty)
    {
        if ($note == 1) {
            switch ($cardDifficulty) {
                case 1:
                    return 365 * 1440;
                case 2:
                    return 180 * 1440;
                case 3:
                    return 60 * 1440;
                case 4:
                    return 30 * 1440;
                case 5:
                    return 15 * 1440;
                default:
                    return 0 * 1440;
            }
        }
        if ($note == 2) {
            switch ($cardDifficulty) {
                case 1:
                    return 180 * 1440;
                case 2:
                    return 60 * 1440;
                case 3:
                    return 30 * 1440;
                case 4:
                    return 15 * 1440;
                case 5:
                    return 8 * 1440;
                default:
                    return 0;
            }
        }

        if ($note == 3) {
            switch ($cardDifficulty) {
                case 1:
                    return 30 * 1440;
                case 2:
                    return 15 * 1440;
                case 3:
                    return 7 * 1440;
                case 4:
                    return 4 * 1440;
                case 5:
                    return 2 * 1440;
                default:
                    return 0 * 1440;
            }
        }

        if ($note == 4) {
            switch ($cardDifficulty) {
                case 1:
                    return 5 * 1440;
                case 2:
                    return 3 * 1440;
                case 3:
                    return 2 * 1440;
                case 4:
                    return 1 * 1440;
                case 5:
                    return 720;
                default:
                    return 0;
            }
        }

        if ($note == 5) {
            switch ($cardDifficulty) {
                case 1:
                    return 2 * 1440;
                case 2:
                    return 1 * 1440;
                case 3:
                    return 100;
                case 4:
                    return 20;
                case 5:
                    return 10;
                default:
                    return 0;
            }
        }
    }


    public function convert64ToFile()
    {
        $user = (new User())->getCurrentUser();

        $cards = (new Card())->get();

        foreach ($cards as $key => $card) {
            if (isset($card->audio) && strlen($card->audio) > 100) {
                $saveFile = File::saveBase64ToFile($card->audio, 'cards',);
                if ($saveFile != false) {
                    $card->audio = $saveFile;
                    $card->update();
                }
            }
        }
    }
}
