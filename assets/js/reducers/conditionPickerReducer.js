const initialState = { conditions: [] };

const conditionsReducer = (state = initialState, action) => {
    const newState = Object.assign({}, state);
    const {name, title, param, turn} = action.payload ?? {};

    switch (action.type) {
        case 'ADD_CONDITION':
            newState.conditions = newState.conditions.concat([{
                name, title, param,
            }]);
            return newState;
        case 'ADD_CONDITION_2':
            newState.conditions = newState.conditions.concat([{
                name, title, param, turn,
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
