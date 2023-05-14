
import { Scene } from 'phaser'
import { useCounterStore } from '../../stores/counter'

export class HudScene extends Scene {
    counter: any;
    score: number;
    x: string;
    y: string;

    constructor ()
    {
        super({ key: 'HudScene', active: true });
        this.counter = useCounterStore();
        this.score = 0;
    }

    create ()
    {
        var r1 = this.add.rectangle(80, 60, 260, 100, 0x6666ff).setBlendMode(Phaser.BlendModes.MULTIPLY);
        var r2 = this.add.rectangle(480, 60, 260, 100, 0x6666ff).setBlendMode(Phaser.BlendModes.MULTIPLY);

        //  Our Text object to display the Score
        let info = this.add.text(10, 10, 'Score: 0', { font: '12px Arial', fill: '#ffffff' });

        //  Grab a reference to the Game Scene
        let ourGame = this.scene.get('main');
        //  Listen for events from it

        ourGame.events.on('addScore', function () {
            this.score += 10;
            info.setText('Score: ' + this.score);
        }, this);

        ourGame.events.on('position', function (x : number, y: number) {
            this.x = x;
            this.y = y;
        }, this);

       // this.hud1 = this.add.text(10, 16, '', { fontSize: '24px', fill: '#111111' });
        this.hud2  = this.add.text(10, 28, '', { font: '12px Arial', fill: '#ffffff' });
        this.hud3  = this.add.text(10, 46, '', { font: '12px Arial', fill: '#ffffff' });
        this.hud4  = this.add.text(10, 66, '', { font: '12px Arial', fill: '#ffffff' });
        this.hud5  = this.add.text(10, 86, '', { font: '12px Arial', fill: '#ffffff' });

        this.hud6  = this.add.text(460,  28, '', { font: '12px Arial', fill: '#ffffff' });
        this.hud7  = this.add.text(460,  46, '', { font: '12px Arial', fill: '#ffffff' });
        this.hud8  = this.add.text(460,  66, '', { font: '12px Arial', fill: '#ffffff' });
        this.hud9  = this.add.text(460,  86, '', { font: '12px Arial', fill: '#ffffff' });
        this.hud10 = this.add.text(460, 106, '', { font: '12px Arial', fill: '#ffffff' });

    }
    update(){
       // this.hud1.setText('Blue: ' +  this.counter.Blobby);
        this.hud2.setText('State:'   + this.counter.gameState);
        this.hud3.setText('Node:'    + this.counter.userMapGrid);
        this.hud4.setText('TileMap:' + this.counter.tiled);
        this.hud5.setText('Title:'   + this.counter.nodeTitle);
        this.hud6.setText('X:' + this.x + 'Y:' + this.y);

        if (this.counter.portalsArray[0]){
        this.hud7.setText('P1 X:'  + this.counter.portalsArray[0].x + 'P1 Y:' + this.counter.portalsArray[0].y);
        }

        if (this.counter.portalsArray[1]) {
        this.hud8.setText('P2 X:'  + this.counter.portalsArray[1].x + 'P2 Y:' + this.counter.portalsArray[1].y);
        }

        if (this.counter.portalsArray[2]) {
        this.hud9.setText('P3 X:'  + this.counter.portalsArray[2].x + 'P3 Y:' + this.counter.portalsArray[2].y);
        }

        this.hud10.setText('City:' + this.counter.city);

    }
}