/**
 * Provides helper functions to work with DOM nodes.
 *
 * @author      Karsten (Teralios) Achterrath
 * @copyright   2021 teralios.de
 * @license     GNU General Public License <https://opensource.org/licenses/GPL-3.0>
 * @module      Teralios/QuizCreator/Game/Container/Main
 */

import {hide, show} from 'WoltLabSuite/Core/Dom/Util';

export class Main {
    protected views: Map<string, HTMLElement>;
    protected container: HTMLElement;
    protected currentView = '';

    public constructor(container: HTMLElement|null = null) {
        this.container = container ?? this.initContainer();
    }

    protected initContainer(): HTMLElement {
        const container = document.createElement('div');
        container.classList.add('main');

        return container;
    }

    public addView(name: string, view: HTMLElement): void {
        hide(view);
        this.views[name] = view;
    }

    public showView(name: string): void {
        if (this.views[name] !== undefined) {
            if (this.currentView !== '') {
                hide(this.views[this.currentView]);
            }

            show(this.views[name]);
            this.currentView = name;
        }
    }

    public show(): void {
        this.container.classList.add('show');
    }

    public hide(): void {
        this.container.classList.remove('show');
    }
}