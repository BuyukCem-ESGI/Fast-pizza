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

    updateProduct(data,id) {
        console.log(data);
        var config = {
            method: 'patch',
            url: API_URL+'/products/'+id,
            headers: { 
              'Content-Type': 'application/json'
            },
            data : data
          };
          
        return  axios(config)
    }

    getProductById(id) {
        return axios.get(API_URL + '/products/'+id)
    }

    getAllProducts() {
      return axios.get(API_URL + '/products');
    }

    deleteProduct(id) {
        var config = {
            method: 'delete',
            url:  API_URL+'/products/'+id,
            headers: { 
              'Content-Type': 'application/json'
            }
          };
          
        return axios(config)
    }
}

export default new ProductService();
