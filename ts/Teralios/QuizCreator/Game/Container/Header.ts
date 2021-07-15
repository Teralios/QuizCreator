/**
 * Provides helper functions to work with DOM nodes.
 *
 * @author      Karsten (Teralios) Achterrath
 * @copyright   2021 teralios.de
 * @license     GNU General Public License <https://opensource.org/licenses/GPL-3.0>
 * @module      Teralios/QuizCreator/Game/Container/Header
 */
export class Header {
    protected container: HTMLElement;
    public constructor(container: HTMLElement|null, ...views: HTMLElement[]) {
        this.container = container ?? this.initContainer();
        this.container.append(...views);
    }

    public getContainer(): HTMLElement {
        return this.container;
    }

    public show(): void {
        this.container.classList.add('show');
    }

    public hide(): void {
        this.container.classList.remove('show');
    }

    protected initContainer(): HTMLElement {
        const container = document.createElement('div');
        container.classList.add('header');

        return container;
    }
}
