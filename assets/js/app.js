// any CSS you require will output into a single css file (app.css in this case)

import React, { Component } from 'react';
import '../css/app.css';

class App extends Component {
    render() {
        console.log('Hello?');

        return (<h1>Ehlo</h1>);
    }
}

export default App;
