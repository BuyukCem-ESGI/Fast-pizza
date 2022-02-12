import cartService from '../services/cart.service.js'

const products =localStorage.getItem('products');
const cartState = products
    ? {products: JSON.parse(products)}
    : {products: []};

export const cart = {
    namespaced: true,
    state: cartState,
    actions: {
        getCart({commit}, cartId) {
            cartService.getCart(cartId).then(data => {
                commit('getCart', data);
                this.cartState = data;
                console.log(this.cartState);
                return this.cartState
            });
        },

        addToCart({commit}, product) {
            commit('addToCart', product);
        },
        removeFromCart({commit}, id) {
            commit('removeFromCart',id)
        },
        changeQuantity({commit}, data) {
            commit('changeQuantity',data)
        },
    },
    mutations: {
        getCart(state) {
           return state.products
        },
        addToCart(state,product) {
            if (state.products.length == 0) {
                const ar = state.products
                ar.push(product)
                state.products = ar
            }else {
                const found = state.products.find( p => p.data._id === product.data._id)
                if(found) {
                    state.products = state.products.map( p => {
                        if (found.data._id === p.data._id) {
                            p.quantity = p.quantity + 1
                        }
                        return p;
                    })
                }else {
                    state.products.push(product)

                }
            }
            localStorage.setItem('products',JSON.stringify(state.products))
        },
        removeFromCart(state,id) {
            state.products = state.products.filter( p => p.data._id !== id)
            localStorage.setItem('products',JSON.stringify(state.products))
        },
        changeQuantity(state,data) {
            switch(data.action) {
                case 'plus':
                    state.products = state.products.map( p => {
                        if (data.id === p.data._id) {
                            p.quantity = p.quantity + 1
                        }
                        return p;
                    })
                    break;
                case 'minus':
                    state.products = state.products.map( p => {
                        if (data.id === p.data._id) {
                            p.quantity = p.quantity - 1
                        }
                        return p;
                    })
                    break;
                default :
                console.log("R")
            }
            localStorage.setItem('products',JSON.stringify(state.products))
        }
    }
};
