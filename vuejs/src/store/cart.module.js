import cartService from '../services/cart.service.js'

const user =localStorage.getItem('user');
const initialState = user
    ? {status: {loggedIn: true}, user}
    : {status: {loggedIn: false}, user: null};

const cartState = []
export const cart = {
    cartState: cartState,
    namespaced: true,
    state: initialState,
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
            console.log("Hello")
            console.log(product.id);
            console.log(product.quantity);
/*
            let data = _.find(this.cartState, ['_id', product.id])
            if(!data){
                this.cart.push({
                    details: product,
                    quantity: 1
                })
                cartService.sendElementInCart({
                    details: product,
                    quantity: 1
                }).then(() => {
                    return console.log('added to cart')
                })

            }else{
                data.quantity += 1
                cartService.updateElementInCart({
                    details: product,
                    quantity: 1
                }).then(() => {
                    return console.log('updated cart')
                })
            }*/
            commit('addToCart', product);
        },

        removeFromCart(sku) {
            const locationInCart = this.cart.findIndex(p => {
                return p.details.sku === sku
            })
            if (this.cart[locationInCart].quantity <= 1) {
                this.cart.splice(locationInCart, 1)
            } else {
                this.cart[locationInCart].quantity--
            }
            cartService.deleteElementInCart(locationInCart).then(() => {
                console.log('deleted from cart')
            })
        },

        setQuantityElement(sku) {
            const locationInCart = this.cart.findIndex(p => {
                return p.details.sku === sku
            })
            if (locationInCart) {
                this.cart[locationInCart].quantity++
                cartService.updateElementInCart(this.cart[locationInCart]).then(() => {
                    console.log('updated')
                })
            }
        },
    },
    mutations: {
        getCart(state) {
           return state.cartState
        },
        addToCart(state,product) {
            state.cartState.product = product;
        },
    }
};
