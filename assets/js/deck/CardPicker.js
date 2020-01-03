import React, { Component } from 'react';
import AcList from "../common/AcList";
import AcInput from "../common/AcInput";

class CardPicker extends Component {
    constructor(props) {
        super(props);

        this.state = {
            cardName: "",
            cardAmount: 4
        };

        this.acInput = React.createRef();

        this.numberInputKeyDown = this.numberInputKeyDown.bind(this);
        this.addCard = this.addCard.bind(this);
        this.updateCardName = this.updateCardName.bind(this);
        this.handleInputChange = this.handleInputChange.bind(this);
    }

    addCard() {
        if (!this.state.cardName || !this.state.cardAmount) return;

        this.props.callBackAddCard(this.state.cardName, this.state.cardAmount);
        this.setState({ cardName: "", cardAmount: 4 });
        this.acInput.current.clearCardName();
    }

    numberInputKeyDown(e)
    {
        const keyCode = e.keyCode;
        if (keyCode === 13) {
            e.preventDefault();
            this.addCard();
        }
    }

    updateCardName(name) {
        this.setState({ cardName: name });
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
                <div className="autocomplete">
                    <AcInput id="deck-composer"
                             ref={this.acInput}
                             cards={this.props.cards}
                             updateCardCallback={this.updateCardName}
                             addCardCallback={this.addCard}
                    />
                    <input type="number"
                           id="card_amount_input"
                           name="cardAmount"
                           value={this.state.cardAmount}
                           onChange={this.handleInputChange}
                           onKeyDown={this.numberInputKeyDown}
                    />
                    <button onClick={this.addCard}>add</button>
                </div>
            </div>
        );
    }
}

CardPicker.defaultProps = {
    callBackAddCard: (name, amount) => {}
};

export default CardPicker;
