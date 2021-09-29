import React from 'react';

const CardInDeck = ({setAmountCallback, name, amount}) => {
    const increaseAmount = () => setAmountCallback(name, amount+1);
    const decreaseAmount = () => setAmountCallback(name, amount-1);
    const removeCard = () => setAmountCallback(name, 0);

    return  <div className="card_in_deck">
        <button onClick={decreaseAmount}>-</button>
        {amount}
        <button onClick={increaseAmount}>+</button>
        {name}
        <button onClick={removeCard}>X</button>
    </div>;
}

CardInDeck.defaultProps = {
    amount: 0,
    name: "",
    setAmountCallback: (name, amount) => {}
};


export default CardInDeck;