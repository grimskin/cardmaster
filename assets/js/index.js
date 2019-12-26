// any CSS you require will output into a single css file (app.css in this case)

import React from 'react';
import ReactDOM from 'react-dom';
import App from "./app";
import '../css/app.css';

console.log('index');

const domContainer = document.querySelector('#root');
ReactDOM.render(<App/>, domContainer);