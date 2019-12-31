import React, { Component } from 'react';

class DeckComposer extends Component {
    constructor(props) {
        super(props);

        this.state = {};
    }

    render() {
        return (
            <div>
                Deck Composer
                <div>
                    <div className="autocomplete">
                        <input type="text" id="card_name_input"/>
                        <button>add</button>
                        <div className="ac_items">
                            <div className="ac_option">Angel of Grace</div>
                            <div className="ac_option">Angelic Exaltation</div>
                            <div className="ac_option">Archway Angel</div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default DeckComposer;
