define(['Ajax', 'Ui/Dialog', 'Language'], function (Ajax, UiDialog, Language) {
    "use strict";

    // reduces warnings on scrutinizer
    var CLICK_EVENT = /** global: WCF_CLICK_EVENT */WCF_CLICK_EVENT

    return {
        init: function () {
            this._button = elById('showUserResult');
            this.matchID = elData(this._button, 'match-id');

            this._loadTPL();
        },

        getData: function (data) {
            this.tpl = data.returnValues;

            this._buildButton();
        },

        getHtml: function () {
            return this.tpl;
        },

        showDialog: function () {
            UiDialog.open(this);
        },

        _loadTPL: function () {
            Ajax.apiOnce(
                {
                    data: {
                        actionName: "showResult",
                        className: 'wcf\\data\\quiz\\match\\MatchAction',
                        objectIDs: [this.matchID]
                    },
                    success: this.getData.bind(this),
                }
            )
        },

        _buildButton: function() {
            this._button.addEventListener(CLICK_EVENT, this.showDialog.bind(this));
        },

        _dialogSetup: function() {
            var title = Language.get('wcf.quizCreator.user.play.details.dialog.title')
            return {
                id: "playerResult",
                source: this.getHtml(),
                options: {
                    title: title,
                }
            }
        },
    };
});
