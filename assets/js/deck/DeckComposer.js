import React, { Component } from 'react';
import CardPicker from "./CardPicker";
import axios from "axios";
import CardInDeck from "./CardInDeck";
import { connect } from 'react-redux';

class DeckComposer extends Component {
    constructor(props) {
        super(props);

        this.state = {
            deckUrl: ""
        };

        this.fetchDeck = this.fetchDeck.bind(this);

        this.urlInput = React.createRef();
    }

    fetchDeck() {
        let url = this.urlInput.current.value;

        axios.get('/api/fetch/deck', { params: {deck_url: url} })
            .then(response => {
                Object.values(response.data).map((item) => {
                    this.props.handleAddCard(item.card_name, item.amount);
                });
            })
            .catch(function (error) {
            });
    }

    render() {
        return (
            <div id="deck-composer" className="container">
                Deck Composer
                <CardPicker callBackAddCard={this.props.handleAddCard} cards={this.props.cards} />
                <div id="cards-list-container">
                    {this.props.deck.map((item, i) => {
                        return <CardInDeck
                            name={item.name}
                            amount={item.amount}
                            setAmountCallback={this.props.handleChangeCardAmount}
                            key={i}
                        />;
                    })}
                </div>
                <div className="deck_importer">
                    <input
                        name={"deckUrl"}
                        id={"input_deck_url"}
                        placeholder={"Enter deck url"}
                        ref={this.urlInput}
                        defaultValue={"https://www.mtggoldfish.com/deck/3666706#paper"}
                    />
                    <button onClick={this.fetchDeck}>load</button>
                </div>
            </div>
        );
    }
}

const mapStateToProps = state => {
    return { deck: state.deckComposer.deck };
};

const mapDispatchToProps = dispatch => {
    return {
        handleAddCard: (name, amount) => dispatch({ type: 'ADD_CARD', payload: {name, amount} }),
        handleChangeCardAmount: (name, amount) => dispatch({type: 'CHANGE_CARD_AMOUNT', payload: {name, amount} }),
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(DeckComposer);
