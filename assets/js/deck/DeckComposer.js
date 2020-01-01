import React, { Component } from 'react';
import axios from "axios";
import AcList from "./AcList";

class DeckComposer extends Component {
    constructor(props) {
        super(props);

        this.state = {
            cardName: "",
            cardAmount: 1,
            cards: [],
            acItems: this.props.acItems
        };
        this.handleInputChange = this.handleInputChange.bind(this);
        this.setCardName = this.setCardName.bind(this);
        this.hideAutocomplete = this.hideAutocomplete.bind(this);
        this.textInputBlur = this.textInputBlur.bind(this);
    }

    showAutocomplete(partialName) {
        if (partialName.length > 2) {
            const canonizedPartial = partialName.toUpperCase();
            let suggestions = this.state.cards.filter((item) => {
                return item.toUpperCase().includes(canonizedPartial) ? item : null;
            });
            this.setState({ acItems: suggestions.slice(0, 5) });
        }
    }

    componentDidMount() {
        axios.get('/api/cards')
            .then(response => {
                this.setState({ cards: response.data });
            })
            .catch(function (error) {
            });
    }

    setCardName(newCardName) {
        this.setState({ cardName: newCardName });
        this.hideAutocomplete();
    }

    hideAutocomplete() {
        this.setState({ acItems: [] });
    }

    textInputBlur() {
        setTimeout(this.hideAutocomplete, 500);
    }

    handleInputChange(event) {
        const target = event.target;
        this.setState({
            [target.name]: target.value
        });
        if (target.name === "cardName") {
            this.showAutocomplete(event.target.value);
        }
    }

    render() {
        return (
            <div id="deck_composer_container">
                Deck Composer
                <div>
                    <div className="autocomplete">
                        <input type="text" id="card_name_input"
                               name="cardName"
                               value={this.state.cardName}
                               onChange={this.handleInputChange}
                               onBlur={this.textInputBlur}
                        />
                        <input type="number"
                               id="card_amount_input"
                               name="cardAmount"
                               value={this.state.cardAmount}
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
    acItems: []
};

export default DeckComposer;
