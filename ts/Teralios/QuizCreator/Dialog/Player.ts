import {apiOnce} from 'WoltLabSuite/Core/Ajax';
import {ResponseData} from 'WoltLabSuite/Core/Ajax/Data';
import * as UiDialog from 'WoltLabSuite/Core/Ui/Dialog';
import * as Language from 'WoltLabSuite/Core/Language';
import {DialogCallbackObject} from "WoltLabSuite/Core/Ui/Dialog/Data";

// extend DialogCallbackObject
interface PlayerDialogObject extends DialogCallbackObject
{
    init: () => void;
    getHtml: () => string;
    showDialog: () => void;
    _buildButton: () => void;
    _parseData: (data: ResponseData) => void;
    _loadData: () => void;
}

// local variables for player dialog.
let _button: HTMLElement;
let gameID: string | null;
let tpl: string;
const dialogTitle: string = Language.get('wcf.quizCreator.user.play.details.dialog.title');

// player dialog object.
const PlayerDialog: PlayerDialogObject = {
    // init player dialog.
    init(): void
    {
        const tmpButton = document.getElementById('showUserResult');

        if (tmpButton !== null) {
            _button = tmpButton;
            gameID = _button.getAttribute('data-game-id');
            this._loadData();
        }
    },

    // return template for dialog.
    getHtml(): string
    {
        return tpl;
    },

    // shows dialog.
    showDialog(): void
    {
        UiDialog.open(this);
    },

    // builds button.
    _buildButton(): void
    {
        _button.addEventListener('click', () => { this.showDialog() })
    },

    // put return value to tpl variable.
    _parseData(data: ResponseData): void
    {
        tpl = data.returnValues;
        this._buildButton();
    },

    // load data.
    _loadData(): void
    {
        apiOnce(
            {
                data: {
                    actionName: "showResult",
                    className: 'wcf\\data\\quiz\\game\\GameAction',
                    objectIDs: [gameID]
                },
                success: (data) => { this._parseData(data) }
            }
        )
    },

    // dialog information.
    _dialogSetup() {
        return {
            id: "playerResult",
            source: this.getHtml(),
            options: {
                title: dialogTitle,
            }
        }
    },
}

export = PlayerDialog;