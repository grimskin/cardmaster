import React, { Component } from 'react';
import CardPicker from "./CardPicker";

class DeckComposer extends Component {
    render() {
        return (
            <div id="deck_composer_container">
                Deck Composer
                <CardPicker/>
            </div>
        );
    }
}

DeckComposer.defaultProps = {
    acItems: []
};

export default DeckComposer;
