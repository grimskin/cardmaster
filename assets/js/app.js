import React, { Component } from 'react';
import DeckComposer from "./deck/deckComposer";
import ConditionPicker from "./condition/conditionPicker";
import ScenarioSelector from "./scenario/scenarioSelector";
import Header from "./header";

class App extends Component {
    render() {
        return (
            <div id="App">
                <Header/>
                <div id="AppContainer">
                    <ScenarioSelector/>
                    <ConditionPicker/>
                    <DeckComposer/>
                </div>
            </div>
        );
    }
}

export default App;
