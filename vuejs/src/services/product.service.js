import axios from 'axios';
import authHeader from './auth.header';

const API_URL = 'https://localhost:443';

class ProductService {

    addProduct(data) {
        console.log(data);
        axios.post(API_URL + "/products",
            data
        ,{
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
        return axios({
            method: 'patch',
            url: API_URL+'/products/'+id,
            headers: {'Authorization': "Bearer "+authHeader()},
            data
        });
    }

    getProductById(id) {
      var config = {
        method: 'GET',
        url: API_URL + '/products/'+id,
        headers: {'Authorization': "Bearer "+authHeader()},
      };
        return axios(config)
    }

    getAllProducts() {
      return axios.get(API_URL + '/products',{headers: {'Authorization': "Bearer "+authHeader()}});
    }

    deleteProduct(id) {
        return axios( {
            method: 'delete',
            url:  API_URL+'/products/'+id,
            headers: {
                'Authorization': "Bearer "+authHeader()
            }
        })
    }
}

export default new ProductService();
