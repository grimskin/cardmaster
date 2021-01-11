// any CSS you require will output into a single css file (app.css in this case)

import React from 'react';
import ReactDOM from 'react-dom';
import App from "./App";
import '../css/app.css';
import { createStore, compose, applyMiddleware } from "redux";
import { Provider } from 'react-redux';
import thunk from "redux-thunk";
import rootReducer from "./reducers/rootReducer";

const composeEnhancers = (typeof window !== 'undefined' && window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__) || compose;
const store = createStore(
    rootReducer,
    composeEnhancers(applyMiddleware(thunk))
);

const domContainer = document.querySelector('#root');

ReactDOM.render(
    <Provider store={store}>
        <App/>
    </Provider>
, domContainer);