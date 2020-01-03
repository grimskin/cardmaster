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

        this.scenarioSelector = React.createRef();
        this.conditionPicker = React.createRef();
        this.deckComposer = React.createRef();

        this.runExperiment = this.runExperiment.bind(this);
    }

    runExperiment() {
        console.log(this.scenarioSelector.current.getData());
        console.log(this.conditionPicker.current.getData());
        console.log(this.deckComposer.current.getData());
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
                <div>
                    <button onClick={this.runExperiment}>Evaluate</button>
                </div>
                <div id="AppContainer">
                    <ScenarioSelector
                        scenarios={this.state.scenarios}
                        ref={this.scenarioSelector}
                    />
                    <ConditionPicker
                        cards={this.state.cards}
                        conditions={this.state.conditions}
                        ref={this.conditionPicker}
                    />
                    <DeckComposer
                        cards={this.state.cards}
                        ref={this.deckComposer}
                    />
                </div>
            </div>
        );
    }
}

export default App;
