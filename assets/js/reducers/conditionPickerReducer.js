const conditionsReducer = (state, action) => {
    if (state === undefined) {
        state = { conditions: [] };
    }

    const newState = Object.assign({}, state);
    const {name, title, param} = action.payload ?? {};

    switch (action.type) {
        case 'ADD_CONDITION':
            newState.conditions = newState.conditions.concat([{
                name: name,
                title: title,
                param: param,
            }]);
            return newState;
        case 'REMOVE_CONDITION':
            newState.conditions = newState.conditions.filter(item => {
                return (item.name !== name || item.param !== param);
            });

            return newState;
        default:
            return state;
    }
};

export default conditionsReducer;