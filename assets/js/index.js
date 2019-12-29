// any CSS you require will output into a single css file (app.css in this case)

import React from 'react';
import ReactDOM from 'react-dom';
import App from "./App";
import '../css/app.css';

const domContainer = document.querySelector('#root');
ReactDOM.render(<App/>, domContainer);