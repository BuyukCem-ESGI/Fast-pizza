import axios from 'axios';

class CartService {
    getCart(cartId) {
        return axios.get('https://localhost:443/carts/'+cartId);
    }
    sendElementInCart(cart) {
        return axios.post('https://localhost:443/carts', {
            "owner": cart.owner,
            "product": cart.product,
            "quantity": cart.quantity,
            "menus": [
                "string"
            ],
            "createdAt": "2022-01-30T14:29:59.014Z",
            "updatedAt": "2022-01-30T14:29:59.014Z"
        });
    }
    deleteElementInCart(product) {
        return axios.delete('https://localhost:443/carts/' + product);
    }
    updateElementInCart(product) {
        return axios.put('https://localhost:443/carts/' + product.id, {
            "quantity": product.quantity,
        });
    }
}

export default new CartService();
