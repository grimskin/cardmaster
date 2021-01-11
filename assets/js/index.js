// any CSS you require will output into a single css file (app.css in this case)

import React from 'react';
import ReactDOM from 'react-dom';
import App from "./App";
import '../css/app.css';
import { createStore, applyMiddleware } from "redux";
import { Provider } from 'react-redux';
import thunk from "redux-thunk";
import rootReducer from "./reducers/rootReducer";
import { composeWithDevTools } from "redux-devtools-extension";

const store = createStore(
    rootReducer,
    composeWithDevTools(applyMiddleware(thunk))
);

const domContainer = document.querySelector('#root');

ReactDOM.render(
    <Provider store={store}>
        <App/>
    </Provider>
, domContainer);