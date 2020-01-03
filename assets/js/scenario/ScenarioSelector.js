import React, { Component } from 'react';

class ScenarioSelector extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return <div id="scenario-selector">
            Scenario Selector
            <br />
            <select>
                <option>--</option>
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
