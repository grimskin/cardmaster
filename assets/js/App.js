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
            cards: []
        };
    }

    componentDidMount() {
        axios.get('/api/cards')
            .then(response => {
                this.setState({ cards: response.data });
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
