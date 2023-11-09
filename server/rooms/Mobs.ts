///////////////////////////////////////////////////////////////////////////////
//
//
//////////////////////////////////////////////////////////////////////////////
import { ZombieState } from "./GameState";
var Bridge = require('../services/bridge.ts');
var p2 = require('p2');

export class Mob {
  pause: number;
  constructor() {
    this.pause = 0;
  }

  async load(self, nodeNumber: number, share) {
    Bridge.getMobs(nodeNumber).then((result: any) => {
      result.forEach(mobState => {
        const mobItem = Mob.updateZombieState(self,
          undefined, undefined, undefined, undefined, 100, 0, 0, mobState, undefined
        );
        self.state.mobResult.set(mobState.field_mob_name_value, mobItem);
      });
      return result;
    }).then((newResult: any) => {
      for (let i = 0; i < newResult.length; i++) {
        newResult[i].health = 100;
        var p2Mob = this.make(newResult[i], share);
        p2Mob.destinationX = 0;
        p2Mob.destinationY = 0;
        self.world.addBody(p2Mob);
        self.P2mobBodies.push(p2Mob);
      }
    }).catch(function () {
      console.log('Mob shit');
    });
  }

  make(mob: any, share: any) {
    // console.log('place');
    const circleShape = new p2.Circle({ radius: 10 });
    circleShape.collisionGroup = share.COL_ENEMY;
    circleShape.collisionMask = share.COL_PLAYER;
    // Create a typical dynamic body
    const circleBody = new p2.Body({
      mass: 20,
      position: [mob.field_x_value, mob.field_y_value],
      angle: 0,
      type: p2.Body.DYNAMIC,
      collisionResponse: true,
      velocity: [0, 0],
      angularVelocity: 0
    });
    circleBody.field_mobs_target_id = mob.field_mobs_target_id;
    circleBody.field_mob_name_value = mob.field_mob_name_value
    circleBody.health = mob.health;
    circleBody.isMob = true;
    circleBody.sensor = true;
    circleBody.addShape(circleShape);
    //this.circleBody.onBeginContact.add(this.checkHits(), this);
    // Add the body to the world
    return circleBody
  }

  updateMob(self) {
    if (self.P2mobBodies.length > 0) {
      // Update destination every 2 seconds for one of the mobs
      if (self.pause == 0) {
        self.pause = 1;
        const myPromise = new Promise((resolve, reject) => {
          resolve(Math.ceil(Math.random() * self.P2mobBodies.length - 1));
        });
        myPromise.then((mobNumber) => {
          this.updateMobForce(mobNumber, self.P2mobBodies);
        });
      }
      let i = 0;
      while (i < self.P2mobBodies.length) {
        self.P2mobBodies[i].setZeroForce();
        //    this.P2mobBodies[i].force[0] = this.P2mobBodies[i].destinationX;
        //    this.P2mobBodies[i].force[1] = this.P2mobBodies[i].destinationY;
        ////////////////////////////////////////////////////////////////////////////
        self.state.mobResult.forEach(mobState => {
          if (self.P2mobBodies[i].field_mob_name_value == mobState.field_mob_name_value) {

            //if  not following someone, do the test
            if (mobState.following == 0) {

              if (parseInt(mobState.field_x_value) != parseInt(self.P2mobBodies[i].position[0])
                || parseInt(mobState.field_y_value) != parseInt(self.P2mobBodies[i].position[1])) {
                //if state x,y is out of date
                Mob.updateZombieState(self,
                  mobState.field_mobs_target_id,
                  mobState.field_mob_name_value,
                  parseInt(self.P2mobBodies[i].position[0]),
                  parseInt(self.P2mobBodies[i].position[1]),
                  undefined,
                  undefined,
                  0,
                  mobState,
                  i
                )
              }
              self.state.players.forEach(player => {
                //var mobPlayerDist = Math.sqrt(Math.pow((player.x - parseInt(this.P2mobBodies[i].position[0])), 2) + Math.pow((player.y - parseInt(this.P2mobBodies[i].position[0])), 2));
                var mobPlayerDist = Math.hypot(player.x - parseInt(self.P2mobBodies[i].position[0]), player.y - parseInt(self.P2mobBodies[i].position[0]));
                //  console.log('player ' + player.playerId + ' dist: ' + mobPlayerDist + "from " + i);
                if (mobPlayerDist < 800) {
                  // this is to update the mobs follower
                  const mobItem = Mob.updateZombieState(self,
                    mobState.field_mobs_target_id,
                    mobState.field_mob_name_value,
                    parseInt(self.P2mobBodies[i].position[0]),
                    parseInt(self.P2mobBodies[i].position[1]),
                    undefined,
                    undefined,
                    player.playerId,
                    mobState,
                    i
                  );

                  self.state.mobResult.set(mobItem.field_mob_name_value, mobItem);
                }
              })
            }
            if (mobState.following > 0) {
              self.state.players.forEach(player => {
                if (player.playerId == mobState.following && mobState.dead != 1) {
                  if (mobState.dead == 0) {
                    if (parseInt(self.P2mobBodies[i].position[0]) > player.p2Player.Body.position[0]) {
                      self.P2mobBodies[i].force[0] = -130;
                    }
                    else {
                      self.P2mobBodies[i].force[0] = 130;
                    }
                    if (parseInt(self.P2mobBodies[i].position[1]) > player.p2Player.Body.position[1]) {
                      self.P2mobBodies[i].force[1] = -130;
                    }
                    else {
                      self.P2mobBodies[i].force[1] = 130;
                    }
                  }
                  const mobItem = Mob.updateZombieState(
                    self,
                    mobState.field_mobs_target_id,
                    mobState.field_mob_name_value,
                    parseInt(self.P2mobBodies[i].position[0]),
                    parseInt(self.P2mobBodies[i].position[1]),
                    undefined,
                    undefined,
                    player.playerId,
                    mobState,
                    i
                  )
                  self.state.mobResult.set(mobItem.field_mob_name_value, mobItem);
                }
              })
            };
            if (mobState.dead > 0) {
              self.P2mobBodies[i].destinationX = 0;
              self.P2mobBodies[i].destinationY = 0;
            }
          }
        });
        i++;
      };
    };
  }

  mobClicked(self, input, player) {
    if (input.mobClick != '') {
      self.state.mobResult.forEach(element => {
      });

      if (self.state.mobResult[input.mobClick] != undefined) {
        Mob.updateZombieState(self,
          self.state.mobResult[input.mobClick].field_mobs_target_id,
          self.state.mobResult[input.mobClick].field_mob_name_value,
          undefined,
          undefined,
          undefined,
          undefined,
          undefined,
          self.state.mobResult[input.mobClick],
          undefined
        )
        if (self.state.mobResult[input.mobClick].health > 0) {
          self.state.mobResult[input.mobClick].health -= 20;
          self.state.mobResult[input.mobClick].mass = 0;
          //    console.log(input.mobClick + "with " + self.state.mobResult[input.mobClick].health + "health, was attacked by " + player.playerId);

          if (self.state.mobResult[input.mobClick].health == 0) {
            //   console.log('zombie dead');
            // if mob is dead update health and dead and following
            const mobItem = Mob.updateZombieState(self, undefined, undefined, undefined,
              undefined, 0, 0, 1, self.state.mobResult[input.mobClick], undefined
            )
            self.state.mobResult.set(mobItem, mobItem);
            const promise1 = Promise.resolve(Bridge.updatePlayer(player.playerId, 'credits', 10, 0));
            promise1.then(() => {
            });
            return 1;
          }
          if (self.state.mobResult[input.mobClick].health < 0) {
            self.state.mobResult[input.mobClick].health = 0;
          }
          else {
            const promise1 = Promise.resolve(Bridge.updatePlayer(player.playerId, 'credits', 1, 0));
            promise1.then(() => {
            });
          }
          return 0;
        }
      }
    }
  };

  async updateMobForce(i, P2mobBodies) {
    await this.skip(2000);
    var forceX = (Math.ceil(Math.random() * 50) + 20) * (Math.round(Math.random()) ? 1 : -1);
    var forceY = (Math.ceil(Math.random() * 50) + 20) * (Math.round(Math.random()) ? 1 : -1);
    if (P2mobBodies[i].dead != 1) {
      P2mobBodies[i].destinationX = forceX;
      P2mobBodies[i].destinationY = forceY;
    }
    this.pause = 0;
  }

  skip(val) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve('resolved');
      }, val);
    });
  }

  static updateZombieState(self,
    target_id: number | undefined, name: string | undefined, x: number | undefined, y: number | undefined,
    health: number | undefined, dead: number | undefined, following: number | undefined, mobState, i: number | undefined
  ) {
    const mobItem = new ZombieState();
    if (mobState != undefined) {
      mobItem.field_mobs_target_id = mobState.field_mobs_target_id;
      mobItem.field_mob_name_value = mobState.field_mob_name_value;
      mobItem.field_x_value = mobState.field_x_value;
      mobItem.field_y_value = mobState.field_y_value;
      mobItem.following = mobState.following;
      mobItem.health = mobState.health;
      mobItem.dead = mobState.dead;
    }
    if (i != undefined) {
      mobItem.field_x_value = parseInt(self.P2mobBodies[i].position[0]);
      mobItem.field_y_value = parseInt(self.P2mobBodies[i].position[1]);
    }
    if (target_id != undefined) { mobItem.field_mobs_target_id = target_id; }
    if (name != undefined) { mobItem.field_mob_name_value = name; }
    if (x != undefined) { mobItem.field_x_value = x; }
    if (y != undefined) { mobItem.field_y_value = y; }
    if (following != undefined) { mobItem.following = following; }
    if (health != undefined) { mobItem.health = health; }
    if (dead != undefined) { mobItem.dead = dead; }

    //worldThing.state.mobResult.set(mobState.field_mob_name_value, mobItem);
    return mobItem;
  }
}