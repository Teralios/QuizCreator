define(["require", "exports", "tslib", "WoltLabSuite/Core/Ajax", "WoltLabSuite/Core/Ui/Dialog", "WoltLabSuite/Core/Language"], function (require, exports, tslib_1, Ajax_1, UiDialog, Language) {
    "use strict";
    UiDialog = tslib_1.__importStar(UiDialog);
    Language = tslib_1.__importStar(Language);
    // local variables for player dialog.
    let _button;
    let gameID;
    let tpl;
    let dialogTitle = Language.get('wcf.quizCreator.user.play.details.dialog.title');
    // player dialog object.
    const PlayerDialog = {
        // init player dialog.
        init() {
            _button = document.getElementById('showUserResult');
            if (_button !== null) {
                gameID = _button.getAttribute('data-game-id');
            }
        },
        // return template for dialog.
        getHtml() {
            return tpl;
        },
        // shows dialog.
        showDialog() {
            UiDialog.open(this);
        },
        // builds button.
        _buildButton() {
            if (_button !== null) {
                _button.addEventListener('click', () => this.showDialog());
            }
        },
        // put return value to tpl variable.
        _parseData(data) {
            tpl = data.returnValues;
        },
        // load data.
        _loadData() {
            Ajax_1.apiOnce({
                data: {
                    actionName: "showResult",
                    className: 'wcf\\data\\quiz\\game\\GameAction',
                    objectIDs: [gameID]
                },
                success: (data) => this._parseData(data)
            });
        },
        // dialog information.
        _dialogSetup() {
            return {
                id: "playerResult",
                source: this.getHtml(),
                options: {
                    title: dialogTitle,
                }
            };
        },
    };
    return PlayerDialog;
});
