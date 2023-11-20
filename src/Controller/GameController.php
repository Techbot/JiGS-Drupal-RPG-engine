<?php

namespace Drupal\jigs\Controller;

use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Drupal\jigs\Game\Loop;
use Drupal\jigs\Game\Dice;
use Drupal\jigs\Game\Npc;
use Drupal\jigs\Game\Player as old_player;
use Drupal\jigs\Game\Faction;
use Drupal\jigs\Game\Round;
use Drupal\jigs\Game\Weapon;
use Drupal\jigs\Entities\Player;
use Drupal\jigs\Entities\MapGrid;
use Drupal\jigs\Entities\Game;
use Drupal\jigs\Entities\City;
use Drupal\jigs\Game\FactionDecorator;
use Drupal\jigs\Game\WeaponDecorator;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides route responses for the JiGS module.
 */
class GameController extends ControllerBase
{
  public $round;
  public $dice1;
  public $dice2;
  public $dice3;
  public $dice4;
  public $simplePlayer;
  public $npc;
  public $user;
  public $player;
  public $text;
  public $faction;
  public $weapon;

  public function __construct()
  {
  }

  /**
   * Ajax callback event.
   *
   * @param array $form
   *  The triggering form render array.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *  Form state of current form.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *  The request object, holding current path and request uri.
   * @return mixed
   *  Must return AjaxResponse object or render array.
   *  Never return NULL or invalid render arrays. This
   *  could/will break your forms.
   */
  public function myData()
  {
    /** @var \Drupal\Core\Ajax\AjaxResponse $response */
    $response                 = new AjaxResponse();
    $loop                     = new Loop($this->round);
    $this->round->update();
    //$responseData = $loop->loop();
    $responseData = [];
    // $this->user->field_health = (int)$this->player->getHealth();
    $this->user->field_losses = (int)$this->player->getLosses();
    $this->user->field_wins   = (int)$this->player->getWins();
    $this->user->credits      = (int)$this->player->getCredits();
    $this->user->save();
    $response->addCommand(new \Drupal\Core\Ajax\DataCommand('#app', 'myKey', $responseData));
    return $response;
  }

  public function myState()
  {
    /** @var \Drupal\Core\Ajax\AjaxResponse $response */
    $response                   = new AjaxResponse();
    $player                     = new Player();
    $responseData['player']     =  $player->create();
    $gameConfig                 = new Game();
    $responseData['gameConfig'] = $gameConfig->create();
    $mapGrid                    = new MapGrid($responseData['player']['userMG']);
    $responseData['MapGrid']    = $mapGrid->create();
    $city                       = new City($responseData['MapGrid']['userCity']);
    $responseData['City']       = $city->create();
    $response->addCommand(new \Drupal\Core\Ajax\DataCommand('#app', 'myKey', $responseData));
    return $response;
  }

  public function myMissions()
  {
    /** @var \Drupal\Core\Ajax\AjaxResponse $response */
    $response           = new AjaxResponse();
    $player = new Player(\Drupal\user\Entity\User::load(\Drupal::currentUser()->id()));
    $responseData = $player->myMissions();
    $response->addCommand(new \Drupal\Core\Ajax\DataCommand('#app', 'myKey', $responseData));
    return $response;
  }

  public function myMission(Request $request)
  {
    /** @var \Drupal\Core\Ajax\AjaxResponse $response */
    $response           = new AjaxResponse();
    $player = new Player(\Drupal\user\Entity\User::load(\Drupal::currentUser()->id()));
    $responseData = $player->myMission($request->query->get('npc'));
    $response->addCommand(new \Drupal\Core\Ajax\DataCommand('#app', 'myKey', $responseData));
    return $response;
  }

  public function addMission(Request $request)
  {
    /** @var \Drupal\Core\Ajax\AjaxResponse $response */
    $response           = new AjaxResponse();
    $player = new Player(\Drupal\user\Entity\User::load(\Drupal::currentUser()->id()));
    $responseData = $player->addMission($request->query->get('id'));
    $response->addCommand(new \Drupal\Core\Ajax\DataCommand('#app', 'myKey', $responseData));
    return $response;
  }




  public function myInventory()
  {
    /** @var \Drupal\Core\Ajax\AjaxResponse $response */
    $response           = new AjaxResponse();
    $player = new Player(\Drupal\user\Entity\User::load(\Drupal::currentUser()->id()));
    $responseData = $player->myInventory();
    $response->addCommand(new \Drupal\Core\Ajax\DataCommand('#app', 'myKey', $responseData));
    return $response;
  }

  public function toBackpack(Request $request)
  {
    /** @var \Drupal\Core\Ajax\AjaxResponse $response */
    $response           = new AjaxResponse();
    $player = new Player(\Drupal\user\Entity\User::load(\Drupal::currentUser()->id()));
    $responseData = $player->toBackpack($request->query->get('id'));
    $response->addCommand(new \Drupal\Core\Ajax\DataCommand('#app', 'myKey', $responseData));
    return $response;
  }

  public function toStorage(Request $request)
  {
    /** @var \Drupal\Core\Ajax\AjaxResponse $response */
    $response           = new AjaxResponse();
    $player = new Player(\Drupal\user\Entity\User::load(\Drupal::currentUser()->id()));
    $responseData = $player->toStorage($request->query->get('id'));
    $response->addCommand(new \Drupal\Core\Ajax\DataCommand('#app', 'myKey', $responseData));
    return $response;
  }
}
