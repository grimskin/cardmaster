const rootReducer = (state, action) => {
    switch (action.type) {
        case 'ADD_CARD':
            const newState = Object.assign({}, state);
            newState.deck.push(action.payload);
            return newState;
        default:
            return state;
    }
};

export default rootReducer;