import React, { Component } from 'react';

class ScenarioSelector extends Component {
    constructor(props) {
        super(props);

        this.scenarioSelect = React.createRef();

        this.getData = this.getData.bind(this);
    }

    getData() {
        return { scenario: this.scenarioSelect.current.selectedOptions.item(0).value };
    }

    render() {
        return <div id="scenario-selector">
            Scenario Selector
            <br />
            <select ref={this.scenarioSelect}>
                <option value="">--</option>
                {this.props.scenarios.map((item, i) => {
                    return <option value={item.name} key={i}>{item.title}</option>
                })}
            </select>
        </div>;
    }
}

ScenarioSelector.defaultProps = {
    scenarios: []
};

export default ScenarioSelector;
