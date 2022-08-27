import React, { Component } from 'react';
import DeckComposer from "./deck/DeckComposer";
import ConditionPicker from "./condition/ConditionPicker";
import ScenarioSelector from "./scenario/ScenarioSelector";
import Header from "./Header";
import axios from "axios";
import {connect} from "react-redux";
import ConditionPicker2 from "./condition/ConditionPicker2";

class App extends Component {
    constructor(props) {
        super(props);

        this.state = {
            cards: [],
            conditions: [],
            scenarios: [],
            totalRuns: 0,
            successfulRuns: 0
        };

        this.scenarioSelector = React.createRef();

        this.runExperiment = this.runExperiment.bind(this);
    }

    runExperiment() {
        axios.post('/api/simulation', {
            scenario: this.scenarioSelector.current.getData(),
            conditions: this.props.conditionsList,
            deck: this.props.deck,
        })
            .then(response => {
                this.setState({
                    successfulRuns: response.data.success,
                    totalRuns: response.data.total
                });
            })
            .catch(function (error) {
            });
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
                    <ScenarioSelector
                        scenarios={this.state.scenarios}
                        ref={this.scenarioSelector}
                    />
                    <DeckComposer
                        cards={this.state.cards}
                    />
                    <ConditionPicker2
                        cards={this.state.cards}
                        conditions={this.state.conditions}
                    />
                    <div id="results-console" className="container">
                        Results: {this.state.successfulRuns} / {this.state.totalRuns}
                        <button onClick={this.runExperiment}>Evaluate</button>
                    </div>
                </div>
            </div>
        );
    }
}

const mapStateToProps = state => {
    return {
        deck: state.deckComposer.deck,
        conditionsList: state.conditionsReducer.conditions,
    };
}

export default connect(mapStateToProps, null)(App);
