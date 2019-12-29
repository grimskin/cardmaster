import React, { Component } from 'react';
import DeckComposer from "./deck/DeckComposer";
import ConditionPicker from "./condition/ConditionPicker";
import ScenarioSelector from "./scenario/ScenarioSelector";
import Header from "./Header";

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
