import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        mode: String,
    }

    initialize() {
        super.initialize();

        switch (localStorage.getItem('theme')) {
            case 'light':
                document.documentElement.classList.remove('dark')
                break
            case 'dark':
                document.documentElement.classList.add('dark')
                break
        }

        document.querySelector('#theme').innerText = localStorage.getItem('theme');
    }

    toggle() {
        switch (localStorage.getItem('theme')) {
            case 'light':
                document.documentElement.classList.add('dark')
                localStorage.setItem('theme', 'dark')
                break
            case 'dark':
                document.documentElement.classList.remove('dark')
                localStorage.setItem('theme', 'light')
                break
        }

        document.querySelector('#theme').innerText = localStorage.getItem('theme');
    }
}
