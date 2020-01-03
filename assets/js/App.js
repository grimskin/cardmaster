import React, { Component } from 'react';
import DeckComposer from "./deck/DeckComposer";
import ConditionPicker from "./condition/ConditionPicker";
import ScenarioSelector from "./scenario/ScenarioSelector";
import Header from "./Header";
import axios from "axios";

class App extends Component {
    constructor(props) {
        super(props);

        this.state = {
            cards: [],
            conditions: [],
            scenarios: []
        };
    }

    componentDidMount() {
        axios.get('/api/cards')
            .then(response => {
                this.setState({ cards: response.data });
            })
            .catch(function (error) {
            });
        axios.get('/api/conditions')
            .then(response => {
                this.setState({ conditions: Object.values(response.data) });
            })
            .catch(function (error) {
            });
        axios.get('/api/scenarios')
            .then(response => {
                this.setState({ scenarios: Object.values(response.data) });
            })
            .catch(function (error) {
            });
    }

    render() {
        return (
            <div id="App">
                <Header/>
                <div id="AppContainer">
                    <ScenarioSelector/>
                    <ConditionPicker/>
                    <DeckComposer cards={this.state.cards}/>
                </div>
            </div>
        );
    }
}

export default App;
