import axios from 'axios';
// state
const state = {
    permissions: null,

}

// getters
const getters = {
    permissions: (state) => {
        return state.permissions
    },

}

// actions
const actions = {
    fetchPermissions(context, payload) {
        if (context.state.permissions === null) {
            axios.get(route('api.auth.users.hasPermissions' ))
                .then((response) => {
                    context.commit('setPermissions', response.data.data)
                });
        }
    },

}

// mutations
const mutations = {
    setPermissions(state, data) {
        state.permissions = data
    },

}

export default {
    state,
    getters,
    actions,
    mutations
}
