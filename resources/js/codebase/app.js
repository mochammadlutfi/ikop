// Import global dependencies
import './bootstrap';
// Import required modules
import Tools from './modules/tools';
import Helpers from './modules/helpers';
import Template from './modules/template';

// App extends Template
export default class App extends Template {
    constructor() {
        super();
    }
}

// Once everything is loaded
jQuery(() => {
    // Create a new instance of App
   window.Bumaba = new App();
});