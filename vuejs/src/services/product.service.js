import axios from 'axios';
import authHeader from './auth.header';

const API_URL = 'https://localhost:443';

class ProductService {

    addProduct(data) {
        console.log(data);
        axios.post(API_URL + "/products", {
            data
        },{
            headers: {'Authorization': "Bearer "+authHeader()}
        }).then(function (response) {
            //handle success
            console.log(response);
        }).catch(function (response) {
            //handle error
            console.log(response);
        });
    }
}

export default new ProductService();