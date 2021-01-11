const removeCard = (deck, name) => {
    deck = deck.filter((item) => {
        if (item.name !== name) return item;
    });

    return deck;
};

const getCurrentAmount = (deck, name) => {
    return deck.reduce((total, item) => {
        return ((item.name === name) ? item.amount : 0) + total;
    }, 0);
};

const addCard = (deck, name, amount) => {
    if (getCurrentAmount(deck, name)) {
        deck = deck.map((item) => {
            if (item.name === name) {
                item.amount += Number(amount);
            }

            return item;
        });
    } else {
        deck = deck.concat([{name: name, amount: Number(amount)}]);
    }

    return deck;
};

const deckReducer = (state, action) => {
    if (state === undefined) {
    // if (!state) {
        state = { deck: [] };
    }

    const newState = Object.assign({}, state);
    const {name, amount} = action.payload ?? {};

    switch (action.type) {
        case 'ADD_CARD':
            newState.deck = addCard(newState.deck, name, amount);

            return newState;

        case 'CHANGE_CARD_AMOUNT':
            if (amount === 0) {
                newState.deck = removeCard(newState.deck, name);

                return newState;
            }

            newState.deck = addCard(newState.deck, name, amount-getCurrentAmount(newState.deck, name));

            return newState;

        default:
            return state;
    }
};

export default deckReducer;
