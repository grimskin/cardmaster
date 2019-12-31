import React, { Component } from 'react';
import AcList from "./AcList";

class DeckComposer extends Component {
    constructor(props) {
        super(props);

        this.state = {
            cardName: "",
            acItems: this.props.acItems
        };
        this.handleInputChange = this.handleInputChange.bind(this);
        this.setCardName = this.setCardName.bind(this);
    }

    setCardName(newCardName) {
        this.setState({
            cardName: newCardName,
            acItems: []
        });
    }

    handleInputChange(event) {
        const target = event.target;
        this.setState({
            [target.name]: target.value
        });
    }

    render() {
        return (
            <div>
                Deck Composer
                <div>
                    <div className="autocomplete">
                        <input type="text" id="card_name_input"
                               name="cardName"
                               value={this.state.cardName}
                               onChange={this.handleInputChange}
                        />
                        <button>add</button>
                        <AcList items={this.state.acItems} callback={this.setCardName} />
                    </div>
                </div>
            </div>
        );
    }
}

DeckComposer.defaultProps = {
    acItems: [
        "Angel of Grace",
        "Angelic Exaltation",
        "Archway Angel"
    ]
};

export default DeckComposer;
