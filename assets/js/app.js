// any CSS you require will output into a single css file (app.css in this case)

import React from 'react';
import ReactDOM from 'react-dom';
import '../css/app.css';

console.log('Hello?');

const domContainer = document.querySelector('#root');
ReactDOM.render(<h1>Ehlo</h1>, domContainer);

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
